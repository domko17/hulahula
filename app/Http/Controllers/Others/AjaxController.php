<?php

namespace App\Http\Controllers\Others;

use App\Http\Controllers\Controller;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use App\Models\EmailMessage;
use App\Models\EmailMessageGenerator;
use App\Models\GiftCode;
use App\Models\Helper;
use App\Models\Language;
use App\Models\Meeting;
use App\Models\Message;
use App\Models\PackageOrder;
use App\Models\SchoolClass;
use App\Models\StarOrder;
use App\Models\SurveyAnswer;
use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Models\User\TeacherHour;
use App\Models\UserPackage;
use App\Notifications\NewHulaChatMessageNotification;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

/**
 * Class AjaxController
 * @package App\Http\Controllers\Others
 */
class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * This function handles every ajax calls.
     *
     * Define custom ajax actions here
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function handle(Request $request)
    {

        /**
         * Request have to contain 'action'
         */
        if (!isset($request->action)) {
            return response()
                ->json(["status" => "ERR",
                    "code" => "a-1",
                    "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "action"]),
                ]);
        }

        $action = $request->action;

        switch ($action) {

            /**
             * Save profile's image
             */
            case "save_profile_image_croppie":
            {
                if (!isset($request->imageBase64)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "imageBase64"]),
                        ]);
                }
                if (!isset($request->user_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user_id"]),
                        ]);
                }

                $data = $request->imageBase64;
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $imageName = time() . '.png';
                $filePath = public_path('/images/profiles/' . $request->user_id);

                if (File::isDirectory($filePath) or File::makeDirectory($filePath, 0777, true, true)) ;
                file_put_contents($filePath . '/' . $imageName, $data);

                $user = User::findOrFail($request->user_id);

                $profile = $user->profile;

                if ($profile->image) {
                    if (file_exists($filePath . "/" . $profile->image)) {
                        unlink($filePath . "/" . $profile->image);
                    }
                }

                $profile->image = $imageName;
                $profile->save();

                return response()->json(["status" => "OK", "message" => __('ajax.profile_image_set')]);
            }

            /**
             * Get private chat messages
             */
            case 'get_messages':
            {
                if (!isset($request->reciever_id) and !isset($request->group_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "reciever_id or group_id"]),
                        ]);
                }
                if (!isset($request->user_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user_id"]),
                        ]);
                }

                if (!isset($request->group_id)) {
                    $user = User::findOrFail($request->user_id);
                    $messages = $user->allMessagesWith($request->reciever_id);

                    return response()->json(["status" => "OK",
                        "messages" => json_encode($messages)]);
                }

                $group = ChatGroup::find($request->group_id);
                $messages = $group->messages;
                foreach ($messages as $m) {
                    $m->sender_img = $m->sender->profile->getProfileImage();
                    $m->sender_name = $m->sender->name;
                }

                return response()->json(["status" => "OK",
                    "messages" => json_encode($messages)]);

            }

            /**
             * Send private chat message
             */
            case 'send_message':
            {
                if (!isset($request->reciever_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "reciever_id"]),
                        ]);
                }
                if (!isset($request->user_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user_id"]),
                        ]);
                }
                if (!isset($request->message)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "message"]),
                        ]);
                }

                $m = new Message();
                $m->sender_id = $request->user_id;
                if (isset($request->is_group) and intval($request->is_group) == 1) {
                    $m->group_id = $request->reciever_id;
                } else {
                    $m->reciever_id = $request->reciever_id;
                    Notification::send(User::where("id", $request->reciever_id)->get(), new NewHulaChatMessageNotification());
                }
                $m->message = $request->message;
                $m->save();

                {
                    // declate
                    $recipients = [];
                    $subject = __('email.new_hula_message_subject');
                    $module = "new_hula_message";
                    $data = [];

                    $data["name"] = $m->sender->name;
                    if ($m->group_id) {
                        $group = ChatGroup::find($m->group_id);
                        $recipients = $group->members()->where('users.id', "!=", $m->sender_id)->pluck('email');

                        $data["group"] = true;
                        $data["name"] = $group->name;
                    } else {
                        $reciever = $m->reciever;
                        $sender = $m->sender;
                        $recipients[] = $reciever->email;

                        $data["name"] = $sender->name;
                    }

                    $data['sender_email'] = $sender->email;
                    $data['message'] = $m->message;

                    // vytvorenie mailu
                    EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
                }

                return response()->json(["status" => "OK"]);
            }

            /**
             * NOT IN USE
             */
            case 'create_stars_order':
            {
                return response()->json([
                    "status" => "NOK",
                    'message' => "NOT IN USE",
                    'msg_type' => "danger"
                ]);
            }

            /**
             * Creates package order
             */
            case 'create_package_order':
            {
                $user = Auth::user();
                if (!isset($request->p)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "p"]),
                        ]);
                }
                if (!isset($request->package_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "package_id"]),
                        ]);
                }

                $po = new PackageOrder();

                $po->user_id = $user->id;
                $po->price = $request->p;
                $po->package_id = $request->package_id;

                $po->save();

                //VS = ID pouzivatela v systeme + Rok + Mesiac + ID objednavky v systeme
                $po->variable_symbol = $po->user_id . "" . (substr(Carbon::now()->year, -2)) . "" . Carbon::now()->month . "" . $po->id;

                $po->save();

                $qr_code_file = Helper::getOrderQR($po->id);

                //odoslat email adminovi ze bola vytvorena objednavka
                $admin = User::find(4);
                EmailMessageGenerator::generateEmail(
                    'new_order_admin',
                    array(
                        'recipients' => $admin->email,
                        'package_type' => $po->package_id,
                        'order_id' => $po->id,
                        'student_id' => $user->id,
                    )
                );

                $emg = new EmailMessageGenerator();
                /** @var User $user */
                if ($user->packageOrders()->count() == 1) {
                    $emg->generateAfterFirstOrder($user->id);
                }
                if ($user->packageOrders()->count() == 3) {
                    $emg->generateAfterThirdOrder($user->id);
                }

                return response()->json([
                    "status" => "OK",
                    'order_id' => $po->id,
                    'order_vs' => $po->variable_symbol,
                    'order_qr' => $qr_code_file
                ]);
            }

            /**
             * Get QR code for users order
             */
            case 'get_order_qr':
            {
                if (!isset($request->oid)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "oid"]),
                        ]);
                }

                $qr_code_file = Helper::getOrderQR($request->oid);

                return response()->json([
                    "status" => "OK",
                    'order_qr' => $qr_code_file
                ]);
            }

            /**
             * Redeems a coupon
             */
            case "redeem_coupon":
            {
                if (!isset($request->code)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "code"]),
                        ]);
                }
                if (!isset($request->user)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user"]),
                        ]);
                }

                $gc = GiftCode::where("code", strtoupper($request->code))->first();

                if (!$gc) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "gc-1",
                            "message" => "Nesprávny kód poukážky",
                        ]);
                }

                if ($gc->used) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "gc-2",
                            "message" => "Poukážka už bola použitá.",
                        ]);
                }

                $user = User::find($request->user);

                if ($user->hasRole('guest') and $gc->language_id) {
                    $user->syncRoles([Role::where('name', 'student')->first()->id]);
                    DB::insert('insert into student (user_id, language_id) values (?, ?)', [$user->id, $gc->language_id, 1]);
                } else if ($user->hasRole('student') and $gc->language_id and !$user->studying()->where("language_id", $gc->language_id)->first()) {
                    DB::insert('insert into student (user_id, language_id) values (?, ?)', [$user->id, $gc->language_id, 1]);
                }

                {// log users new package
                    $user_package = $user->currentPackage;
                    if (!$user_package) {
                        $new_up = new UserPackage();
                        $new_up->user_id = $user->id;
                        $new_up->type = $gc->package_id;
                        $new_up->state = 1;
                        $new_up->classes_left = $gc->package_class_count;
                        $new_up->save();
                    } else {
                        //Ak ma balicek a nema nasledujuci balicek, vytvorit nasledujuci balicek
                        if ($user_package->type == 99) { //if has STARTER, cancel it and set new package
                            $user_package->state = 3;
                            $user_package->save();

                            $new_up = new UserPackage();
                            $new_up->user_id = $user->id;
                            $new_up->type = $gc->package_id;
                            $new_up->state = 1;
                            $new_up->classes_left = $gc->package_class_count;
                            $new_up->save();
                        } else { // else set renewal package
                            $renew_up = new UserPackage();
                            $renew_up->user_id = $user->id;
                            $renew_up->type = $gc->package_id;
                            $renew_up->state = 4;
                            $renew_up->classes_left = $gc->package_class_count;
                            $renew_up->save();

                            $user_package->renewal_package_id = $renew_up->id;
                            $user_package->save();
                        }
                    }
                }

                $gc->used = 1;
                $gc->used_by = $request->user;

                $gc->save();

                return response()
                    ->json(["status" => "OK", "comment" => $gc->comment]);
            }

            /**
             * Get teachers for language
             */
            case "get_language_teachers":
            {
                if (!isset($request->language_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "language_id"]),
                        ]);
                }

                $language = Language::findOrFail($request->language_id);

                $teachers = $language->teachers;

                return response()->json(["status" => "OK", "data" => $teachers]);
            }

            /**
             * Sends message to the chat group
             */
            case 'send_group_message':
            {
                if (!isset($request->recievers)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "recievers"]),
                        ]);
                }
                if (!isset($request->user_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user_id"]),
                        ]);
                }
                if (!isset($request->message)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "message"]),
                        ]);
                }

                foreach ($request->recievers as $r) {
                    //todo notify reciever about message
                    $m = new Message();
                    $m->sender_id = $request->user_id;
                    $m->reciever_id = $r;
                    $m->message = $request->message;
                    $m->save();
                }

                return response()->json(["status" => "OK"]);
            }

            /**
             * Creates message group
             */
            case 'create_message_group':
            {
                if (!isset($request->members)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "recievers"]),
                        ]);
                }
                if (!isset($request->title)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "recievers"]),
                        ]);
                }
                if (!isset($request->admin_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user_id"]),
                        ]);
                }
                if (!isset($request->message)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "message"]),
                        ]);
                }

                $group = new ChatGroup();
                $group->name = $request->title;
                $group->admin_id = $request->admin_id;
                $group->save();

                $gm = new ChatGroupMember();
                $gm->group_id = $group->id;
                $gm->user_id = $request->admin_id;
                $gm->save();

                foreach ($request->members as $m) {
                    $gm = new ChatGroupMember();
                    $gm->group_id = $group->id;
                    $gm->user_id = $m;
                    $gm->save();
                }

                //todo send message
                $m = new Message();
                $m->sender_id = $request->admin_id;
                $m->group_id = $group->id;
                $m->message = $request->message;
                $m->save();

                return response()->json(["status" => "OK"]);
            }

            /**
             * Get messeges in group chat
             */
            case 'get_chat_group_members':
            {
                if (!isset($request->group_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "group_id"]),
                        ]);
                }

                $group = ChatGroup::find($request->group_id);
                $members = $group->members;
                foreach ($members as $m) {
                    if ($m->id == $group->admin_id) {
                        $m->admin = true;
                    } else {
                        $m->admin = false;
                    }
                    $m->img = $m->profile->getProfileImage();
                }

                return response()->json(["status" => "OK", "members" => $members]);
            }

            /**
             * NOT IN USE
             */
            case 'get_students_available_future_classes':
            {
                return response()->json([
                    "status" => "NOK",
                    'message' => "NOT IN USE",
                    'msg_type' => "danger"
                ]);
            }

            /**
             * Get events for student's personal calendar
             */
            case 'profile-student-events':
            {
                if (!isset($request->student_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "student_id"]),
                        ]);
                }
                if (!isset($request->date)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "date"]),
                        ]);
                }

                $d = Carbon::createFromFormat("Y-m-d", $request->date);
                $rd = __('general.day_' . intval($d->dayOfWeekIso)) . " - " . $d->format("d.m.Y");
                $enrolled = [];
                $is_past = Carbon::now() > $d->endOfDay();
                $student = Student::find($request->student_id);
                $student_langs = $student->studying;

                if ($is_past) {
                    $f = $student->classes_past()->where('class_date', $request->date)->get();
                    foreach ($f as $l) {
                        $l->start = $l->hour->class_start;
                        $l->end = $l->hour->class_end;
                        $l->teacher_name = $l->hour->teacher->name;
                        $enrolled[] = $l;
                    }

                    return response()->json([
                        "status" => "OK",
                        "available" => [],
                        "enrolled" => json_encode($enrolled),
                        "title_date" => $rd,
                        "is_past" => $is_past,
                    ]);
                }


                $f = $student->classes_future()->where('class_date', $request->date)->get();
                foreach ($f as $l) {
                    $l->start = $l->hour->class_start;
                    $l->end = $l->hour->class_end;
                    $l->teacher_name = $l->hour->teacher->name;
                    $enrolled[] = $l;
                }


                $teachers = array();
                foreach ($student->studying as $l) {
                    $_teachers = $l->teachers;
                    foreach ($_teachers as $t) if (!in_array($t, $teachers)) $teachers[] = $t;
                }
                $tmp = array();
                foreach ($teachers as $t) {
                    /**
                     * @var Teacher $t
                     */
                    $teacher_langs = $t->teaching;
                    $langs_available = $student_langs->intersect($teacher_langs);
                    $tmp[$t->id]['teacher']['id'] = $t->id;
                    $tmp[$t->id]['teacher']['name'] = $t->name;
                    $tmp[$t->id]['teacher']['image'] = asset("images/profiles/" . $t->id . "/" . $t->profile->image);
                    $tmp[$t->id]['teacher']['profile_url'] = route('user.profile', $t->id);
                    $tmp[$t->id]['classes'] = $t->get_free_classes_on_date($request->date);
                    $tmp[$t->id]['languages'] = $langs_available;
                }

                return response()->json([
                    "status" => "OK",
                    "available" => $tmp,
                    "enrolled" => json_encode($enrolled),
                    "title_date" => $rd,
                    "is_past" => $is_past,
                ]);
            }

            /**
             * Get available days for student's class reschedule
             */
            case "get_classes_days_for_reschedule":
            {
                if (!isset($request->month)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "month",
                        ]);
                }
                if (!isset($request->year)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "year",
                        ]);
                }
                if (!isset($request->class_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "class_id",
                        ]);
                }

                $student_id = Auth::id();
                $student = Student::findOrFail($student_id);
                $class = SchoolClass::findOrFail($request->class_id);

                $dates = array();
                $now = Carbon::now();

                if ($now->month <= $request->month) {
                    if ($now->month == $request->month) {
                        $month_s = Carbon::now();
                        $day_i = Carbon::now();
                        $days_in_month = $month_s->daysInMonth - $month_s->day + 1;
                    } else {
                        $month_s = Carbon::create($request->year, $request->month);
                        $day_i = Carbon::create($request->year, $request->month);
                        $days_in_month = $month_s->daysInMonth;
                    }
                    for ($i = 1; $i <= $days_in_month; $i++) {
                        $str = $day_i->year . "-" . ($day_i->month < 10 ? "0" : "") . $day_i->month . "-" . ($day_i->day < 10 ? "0" : "") . $day_i->day;
                        $dates[$str] = false;
                        $day_i = $day_i->addDays(1);
                    }

                    /**
                     * @var Teacher $t
                     */
                    $t = Teacher::find($class->hour->user_id);

                    foreach ($dates as $key => $value) {
                        if ($t->is_free($key)) {
                            $dates[$key] = true;
                            continue;
                        }
                    }

                }

                return response()
                    ->json([
                        'status' => "OK",
                        'days' => $dates,
                    ]);
            }

            /**
             * Get available lectures for student's class reschedule on chosen day
             */
            case 'reschedule-student-events':
            {
                if (!isset($request->student_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "student_id"]),
                        ]);
                }
                if (!isset($request->date)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "date"]),
                        ]);
                }
                if (!isset($request->class_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "class_id"]),
                        ]);
                }

                $class = SchoolClass::findOrFail($request->class_id);

                /**
                 * @var Teacher $t
                 */
                $t = Teacher::find($class->hour->user_id);

                $tmp[$t->id]['teacher']['id'] = $t->id;
                $tmp[$t->id]['teacher']['name'] = $t->name;
                $tmp[$t->id]['teacher']['image'] = asset("images/profiles/" . $t->id . "/" . $t->profile->image);
                $tmp[$t->id]['classes'] = $t->get_free_classes_on_date($request->date);

                $d = Carbon::createFromFormat("Y-m-d", $request->date);
                $rd = __('general.day_' . intval($d->dayOfWeekIso)) . ", " . $d->format("d.m.Y");

                return response()->json([
                    "status" => "OK",
                    "available" => $tmp,
                    "title_date" => $rd,
                ]);
            }

            /**
             * Submit user's quick survey answer
             */
            case 'send_answer_quick_survey':
            {
                if (!isset($request->qid)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "qid"]),
                        ]);
                }
                if (!isset($request->user_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "user_id"]),
                        ]);
                }
                if (!isset($request->anon)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "anon"]),
                        ]);
                }
                if (!isset($request->answer)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "answer"]),
                        ]);
                }

                $a = new SurveyAnswer();

                $a->question_id = $request->qid;
                $a->user_id = $request->user_id;
                $a->answer = $request->answer;
                $a->anonymous = $request->anon;

                $a->save();

                return response()
                    ->json(["status" => "OK"]);

            }

            /**
             * Lead teacher calendar events
             */
            case 'load_teacher_calendar':
            {
                if (!isset($request->month)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "month",
                        ]);
                }
                if (!isset($request->teacher_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "teacher_id",
                        ]);
                }
                if (!isset($request->year)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "year",
                        ]);
                }

                $student_id = Auth::id();
                $student = Student::findOrFail($student_id);

                $dates = array();
                $now = Carbon::now();

                if ($now->month <= $request->month && $now->year <= $request->year) {
                    if ($now->month == $request->month) {
                        $month_s = Carbon::now();
                        $day_i = Carbon::now();
                        $days_in_month = $month_s->daysInMonth - $month_s->day + 1;
                    } else {
                        $month_s = Carbon::create($request->year, $request->month);
                        $day_i = Carbon::create($request->year, $request->month);
                        $days_in_month = $month_s->daysInMonth;
                    }
                    for ($i = 1; $i <= $days_in_month; $i++) {
                        $str = $day_i->year . "-" . ($day_i->month < 10 ? "0" : "") . $day_i->month . "-" . ($day_i->day < 10 ? "0" : "") . $day_i->day;
                        $dates[$str] = false;
                        $day_i = $day_i->addDays(1);
                    }

                    /**
                     * @var Teacher $t
                     */
                    $t = Teacher::findOrFail($request->teacher_id);
                    foreach ($dates as $key => $value) {
                        if ($res = $t->is_free($key)) {
                            $dates[$key] = $res;
                            continue;
                        }
                    }

                }

                return response()
                    ->json([
                        'status' => "OK",
                        'days' => $dates,
                    ]);
            }

            /**
             * Get events for teacher's personal calendar
             */
            case "profile-teacher-events":
            {
                if (!isset($request->teacher_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "teacher_id"]),
                        ]);
                }
                if (!isset($request->date)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => __("ajax.msg_required_attribute_missing", ["attribute" => "date"]),
                        ]);
                }

                $d = Carbon::createFromFormat("Y-m-d", $request->date);
                $rd = __('general.day_' . intval($d->dayOfWeekIso)) . ", " . $d->format("d.m.Y");
                $enrolled = [];
                $is_past = Carbon::now() > $d->endOfDay();

                /**
                 * @var Teacher $teacher
                 */
                $teacher = Teacher::find($request->teacher_id);

                if ($is_past) {
                    $f = $teacher->classes_i_past()->where('class_date', $request->date)->get();
                    foreach ($f as $l) {
                        $students = $l->students;
                        if (!count($l->students)) continue;

                        if (count($students) > 1) {
                            $l->teacher_img = "";
                            $l->teacher_name = "";
                            foreach ($students as $student) {
                                $l->teacher_name .= $student->user->name . ", ";
                            }
                        } else {
                            $l->teacher_name = $students[0]->user->name;
                            $l->teacher_img = $students[0]->user->profile->getProfileImage();
                        }

                        $l->start = $l->hour->class_start;
                        $l->end = $l->hour->class_end;
                        $enrolled[] = $l;
                    }

                    return response()->json([
                        "status" => "OK",
                        "available" => [],
                        "enrolled" => json_encode($enrolled),
                        "title_date" => $rd,
                        "is_past" => $is_past,
                    ]);
                }


                $f = $teacher->classes_i_future()->where('class_date', $request->date)->get();
                foreach ($f as $l) {
                    if (count($l->students) > 0 and !$l->canceled) {
                        $students = $l->students;
                        if (count($students) > 1) {
                            $l->teacher_img = "";
                            $l->teacher_name = "";
                            foreach ($students as $student) {
                                $l->teacher_name .= $student->user->name . ", ";
                            }
                        } else {
                            $l->teacher_name = $students[0]->user->name;
                            $l->teacher_img = $students[0]->user->profile->getProfileImage();
                        }
                        $l->start = $l->hour->class_start;
                        $l->end = $l->hour->class_end;
                        $enrolled[] = $l;
                    }
                }

                $tmp = array();

                /**
                 * @var Teacher $t
                 */
                $t = $teacher;

                $tmp[$t->id]['teacher']['id'] = $t->id;
                $tmp[$t->id]['teacher']['name'] = $t->name;
                $tmp[$t->id]['teacher']['image'] = asset("images/profiles/" . $t->id . "/" . $t->profile->image);
                $tmp[$t->id]['classes'] = $t->get_free_classes_on_date($request->date);


                return response()->json([
                    "status" => "OK",
                    "available" => $tmp,
                    "enrolled" => json_encode($enrolled),
                    "title_date" => $rd,
                    "is_past" => $is_past,
                ]);
            }

            /**
             * Get days with available lectures for student's personal calendar
             */
            case "load_days_free_classes":
            {
                if (!isset($request->month)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "month",
                        ]);
                }
                if (!isset($request->year)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "year",
                        ]);
                }

                $student_id = Auth::id();
                $student = Student::findOrFail($student_id);

                $dates = array();
                $now = Carbon::now();

                if ($now->month <= $request->month && $now->year <= $request->year) {
                    if ($now->month == $request->month) {
                        $month_s = Carbon::now();
                        $day_i = Carbon::now();
                        $days_in_month = $month_s->daysInMonth - $month_s->day + 1;
                    } else {
                        $month_s = Carbon::create($request->year, $request->month);
                        $day_i = Carbon::create($request->year, $request->month);
                        $days_in_month = $month_s->daysInMonth;
                    }
                    for ($i = 1; $i <= $days_in_month; $i++) {
                        $str = $day_i->year . "-" . ($day_i->month < 10 ? "0" : "") . $day_i->month . "-" . ($day_i->day < 10 ? "0" : "") . $day_i->day;
                        $dates[$str] = false;
                        $day_i = $day_i->addDays(1);
                    }

                    $teachers = array();
                    foreach ($student->studying as $l) {
                        $_teachers = $l->teachers;
                        foreach ($_teachers as $t) if (!in_array($t, $teachers)) $teachers[] = $t;
                    }

                    foreach ($teachers as $t) {
                        /**
                         * @var Teacher $t
                         */
                        foreach ($dates as $key => $value) {
                            if ($t->is_free($key)) {
                                $dates[$key] = true;
                                continue;
                            }
                        }
                    }
                }

                return response()
                    ->json([
                        'status' => "OK",
                        'days' => $dates,
                    ]);
            }

            case 'student_smart_days_for_study':
            {
                if (!isset($request->student_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "student_id",
                        ]);
                }
                if (!isset($request->th_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "th_id",
                        ]);
                }

                /**
                 * @var TeacherHour $teacher_hour
                 */
                $teacher_hour = TeacherHour::findOrFail($request->th_id);

                /**
                 * @var Teacher $teacher
                 */
                $teacher = Teacher::findOrFail($teacher_hour->user_id);

                /**
                 * @var TeacherHour[] $teacher_hours
                 */
                $teacher_hours = $teacher->teacher_hours()->orderBy('day')->orderBy('class_start')->get();
                foreach ($teacher_hours as $th) {
                    $th->day_name = $th->getDayName();
                }

                return response()
                    ->json([
                        'status' => "OK",
                        'th' => $teacher_hours,
                    ]);
            }

            case 'get_zoom_signature':
            {
                $api_key = "nIF0zcxtQc-p4yqdxnppLw";
                $api_secret = "2qAwnml4nmKDeLxcQ3qxZcyYGLI5OpOxZIR7";
                $meeting_number = 384152005;
                $role = 0;

                $time = time() * 1000 - 30000;//time in milliseconds (or close enough)

                $data = base64_encode($api_key . $meeting_number . $time . $role);

                $hash = hash_hmac('sha256', $data, $api_secret, true);

                $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);

                //return signature, url safe base64 encoded
                return response()->json([
                    'status' => 'OK',
                    'signature' => rtrim(strtr(base64_encode($_sig), '+/', '-_'), '='),
                ]);

            }

            case "export_teachers_paid_classes":
            {
                if (!isset($request->teacher_id)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "teacher_id",
                        ]);
                }
                if (!isset($request->date)) {
                    return response()
                        ->json(["status" => "ERR",
                            "code" => "a-1",
                            "message" => "date",
                        ]);
                }


                /** @var Teacher $teacher */
                $teacher = Teacher::findOrFail($request->teacher_id);

                $classes = array();

                if ($request->date == "all") {
                    $classes = $teacher->classes_paid();
                } else {
                    $date = explode("/", $request->date);
                    $classes = $teacher->get_classes_for_export($date[0], $date[1]);
                }

                $output_csv = "";
                // loop over the input array
                foreach ($classes as $class) {
                    // generate csv lines from the inner arrays
                    $students = $class->students;
                    $students_str = "[";
                    foreach ($students as $s) {
                        $students_str .= $s->user->name . " / ";
                    }
                    $students_str .= "]";
                    $output_csv .= $class->class_date . ";";
                    $output_csv .= substr($class->hour->class_start, 0, 5) . "-" . substr($class->hour->class_end, 0, 5) . ";";
                    $output_csv .= $students_str . ";";
                    $output_csv .= "\n";
                }
                return response()
                    ->json([
                        'status' => "OK",
                        'csv_string' => $output_csv,
                    ]);
            }
        }

    }
}
