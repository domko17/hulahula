<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ClassMaterial;
use App\Models\ClassReschedule;
use App\Models\ClassStudent;
use App\Models\CollectiveHour;
use App\Models\EmailMessage;
use App\Models\Language;
use App\Models\SchoolClass;
use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Models\User\TeacherHour;
use App\Models\UserPackage;
use App\User;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LecturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse|Response|View
     */
    public function index()
    {
        $languages = Language::all();
        $teachers = User::all();

        return view('lecture.listing')
            ->with('languages', $languages)
            ->with('teachers', $teachers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response|View
     */
    public function show($id)
    {
        /**
         * @var SchoolClass $lecture
         */
        $lecture = SchoolClass::findOrFail($id);
        $is_individual = $lecture->teacherHour;
        $is_collective = $lecture->collectiveHour;
        $detail = $is_collective ? $is_collective : $is_individual;
        $language = $detail->language;
        $teacher = $detail->teacher ? $detail->teacher->profile : null;
        $sub_teacher = ($is_collective and $detail->sub_teacher) ? $detail->sub_teacher->profile : null;
        $students = $lecture->students;
        $is_past = $lecture->is_past();
        $language_material = []; // TODO fix
        $lecture_material = [];// TODO fix
        $lecture_material_ids = [];// TODO fix

        $user = Auth::user();
        $can_reschedule = false;
        $has_conflict = false;
        if ($user->hasRole('admin') or ($user->hasRole('student') and $lecture->can_student_reschedule($user->id))) {
            $can_reschedule = true;
        }

        if ($user->hasRole('student') and
            !$lecture->is_student_attending($user->id)) {
            $has_conflict = $lecture->checkForStudentCollision($user->id);
        }

        return view('lecture.show')
            ->with([
                'lecture' => $lecture,
                'detail' => $detail,
                'language' => $language,
                'teacher' => $teacher,
                'sub_teacher' => $sub_teacher,
                'students' => $students,
                'is_individual' => $is_individual,
                'is_collective' => $is_collective,
                'is_past' => $is_past,
                'current_user' => $user,
                'language_material' => $language_material,
                'lecture_material' => $lecture_material,
                'lecture_material_ids' => $lecture_material_ids,
                'can_reschedule' => $can_reschedule,
                'has_conflict' => $has_conflict
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $th_id
     * @param string $date
     * @return Response|View
     */
    public function show_preview($th_id, $date)
    {
        $lecture = new SchoolClass();
        $teacher_hour = TeacherHour::find($th_id);
        $date_carbon = Carbon::createFromFormat('Y-m-d', $date);

        $lecture->teacher_hour = $th_id;
        $lecture->class_date = $date;

        $detail = $teacher_hour;
        $language = $detail->language;
        $teacher = $teacher_hour->teacher;
        $language_material = [];
        $lecture_material = [];
        $lecture_material_ids = [];

        return view('lecture.preview')
            ->with([
                'lecture' => $lecture,
                'detail' => $detail,
                'language' => $language,
                'teacher' => $teacher,
                'students' => [],
                'current_user' => Auth::user(),
                'language_material' => $language_material,
                'lecture_material' => $lecture_material,
                'lecture_material_ids' => $lecture_material_ids,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $cid - Class ID
     * @return RedirectResponse
     */
    public function signStudentForClass(Request $request, $cid)
    {
        if (!isset($request->student_id)) {
            dd("ERR1");
        }

        /**
         * @var User $student
         */
        $student = User::find($request->student_id);

        if (!$student) {
            dd("ERR2");
        }

        /**
         * @var SchoolClass $lecture
         */
        $lecture = SchoolClass::findOrFail($cid);

        if (Carbon::createFromFormat("Y-m-d", $lecture->class_date)->startOfDay() < now()->startOfDay()) {
            dd("ERR3");
        }

        /**
         * @var UserPackage $student_package
         */
        $student_package = $student->currentPackage;

        //TODO - add choice for student with multiple languages to choose language for this class(es)
        $language_chosen = 0;
        if (isset($request->language_id))
            $language_chosen = $request->language_id;

        // Mám inštanciu hodiny
        // TODO: Rezervacia SMART studakov
        if ($student_package->type == 1) {
            $control_date = Carbon::now();
            $now = Carbon::now();
            $ths = TeacherHour::whereIn('id', $request->smart_th)->get();
            $count = $student_package->classes_left;
            while ($student_package->classes_left) {
                foreach ($ths as $th) {
                    if (!$student_package->classes_left) break;

                    $tmp_date = "" . $control_date->year . "-" . $control_date->month . "-" . $control_date->startOfWeek()->addDays($th->day - 1)->day . " " . $th->class_start;
                    $th_date = Carbon::createFromFormat('Y-m-d H:i:s', $tmp_date);
                    if ($th_date > $now) {
                        $existing_class = SchoolClass::where('teacher_hour', $th->id)->where('class_date', $th_date->format('Y-m-d'))->get();

                        $was_existing = false;

                        foreach ($existing_class as $ec) {
                            /**
                             * @var SchoolClass $ec
                             */
                            if (!$was_existing and $ec->is_free()) {
                                $was_existing = true;
                                if (!$ec->canceled) {

                                    //vytvaram zaznam o prihlasenom studentovi
                                    $ec->enrollStudent($request->student_id, $student_package, $language_chosen);

                                }
                            }
                        }
                        if (!$was_existing) {
                            $tmp_class = new SchoolClass();
                            $tmp_class->teacher_hour = $th->id;
                            $tmp_class->class_date = $th_date->format('Y-m-d');
                            $tmp_class->save();

                            //vytvaram zaznam o prihlasenom studentovi
                            $tmp_class->enrollStudent($request->student_id, $student_package, $language_chosen);

                        }
                    }
                }
                $control_date->addWeek();
            }

            {   // Notifikacia o zapisani studenta
                // declare
                $recipients = [];
                $subject = __('email.student_enroll_subject');
                $module = "student_enroll_smart";
                $data = [];

                //fill
                $teacher = $lecture->hour->teacher;

                if ($teacher) { //posleme ucitelovi
                    $recipients[] = $teacher->email;
                } else { //ak nie je ucitel tak adminovi
                    $recipients[] = User::find(1)->email;
                    $recipients[] = User::find(4)->email;
                }

                $data["day"] = $lecture->class_date;
                $data["teacher_hours"] = $request->smart_th;
                $data["class_count"] = $count;
                $data["student"] = $student->id;

                // vytvorenie mailu
                EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
            }

        } else {
            //vytvaram zaznam o prihlasenom studentovi
            $lecture->enrollStudent($request->student_id, $student_package, $language_chosen);

            {   // Notifikacia o zapisani studenta
                // declare
                $recipients = [];
                $subject = __('email.student_enroll_subject');
                $module = "student_enroll";
                $data = [];

                //fill
                $hour = $lecture->hour;
                $teacher = $hour->teacher;

                if ($teacher) { //posleme ucitelovi
                    $recipients[] = $teacher->email;
                } else { //ak nie je ucitel tak adminovi
                    $recipients[] = User::find(1)->email;
                    $recipients[] = User::find(4)->email;
                }

                $data["class"] = $lecture->id;
                $data["repeat"] = isset($request->repeat) ? 1 : 0;
                $data["student"] = $student->id;

                // vytvorenie mailu
                EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
            }
        }


        return redirect()
            ->route('dashboard')
            ->with('package_type', $student_package->type)
            ->with('message','1:'. __('messages.ok_student_signed_up'))
            ->with('msg_type', "success");
    }

    /**
     * @param Request $request
     * @param $id - Class ID
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editInfo(Request $request, $id)
    {
        $rules = [
            "info" => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_lecture_info_not_edited'))
                ->with('msg_type', 'danger');
        }

        $lecture = SchoolClass::findOrFail($id);
        $is_individual = $lecture->teacherHour;
        $is_collective = $lecture->collectiveHour;
        $detail = $is_collective ? $is_collective : $is_individual;
        $teacher = $detail->teacher;
        $current_user = User::find(Auth::id());

        if (Carbon::createFromFormat("Y-m-d", $lecture->class_date) < Carbon::now()) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_lecture_info_not_edited_past_lesson'))
                ->with('msg_type', 'danger');
        }
        if ($teacher->id != $current_user->id and !$current_user->hasRole('admin')) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_lecture_info_not_edited_unauthorized'))
                ->with('msg_type', 'danger');
        }

        $lecture->info = $request->info;
        $lecture->save();

        return redirect()
            ->back()
            ->with('message', __('messages.ok_lecture_info_changed'))
            ->with('msg_type', 'success');
    }

    /**
     * @param $cid - Class ID
     * @param $sid - User(student) ID
     * @return RedirectResponse
     */
    public function unAssignStudent($cid, $sid)
    {
        $class = SchoolClass::findOrFail($cid);

        if (!Auth::user()->hasRole('admin')) {
            if (Carbon::now() > Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . " " . $class->hour->class_start)->subDay()) {
                return redirect()
                    ->back()
                    ->with('message', __('messages.lecture_un_assign_student_late'))
                    ->with('msg_type', 'danger');
            }
        }

        $student = User::findOrFail($sid);

        $sc = ClassStudent::where('student_id', $sid)->where('class_id', $cid)->first();

        if (!$sc) {
            return redirect()
                ->back()
                ->with('message', __('messages.lecture_un_assign_student_not_attending', [$student->id . "-" . $student->name]))
                ->with('msg_type', 'danger');
        }

        ClassStudent::destroy([$sc->id]);

        /**
         * @var UserPackage $student_package
         */
        $student_package = $student->currentPackage;

        $student_package->unuseLecture();

        return redirect()
            ->back()
            ->with('message', __('messages.lecture_un_assign_student_star_returned'))
            ->with('msg_type', 'success');
    }

    /**
     * @param Request $request
     * @param $cid - Class ID
     * @return RedirectResponse
     */
    public function cancelClass(Request $request, $cid)
    {
        $class = SchoolClass::findOrFail($cid);
        $user = Auth::user();

        if ($class->is_past() or
            !(($class->teacherHour and $user->id == $class->hour->user_id) or $user->hasRole('admin'))) {
            return redirect()
                ->back()
                ->with('message', "Unauthorized action | Nemáte prístup k tejto akcii")
                ->with('msg_type', 'danger');
        }

        try {
            $this->validate($request, ["reason" => "required"]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', 'danger');
        }

        $class->canceled = 1;
        $class->cancel_reason = $request->reason;

        //return unused class to students
        $students = $class->students;
        $emails = [];
        foreach ($students as $s) {
            /**
             * @var ClassStudent $s
             */
            $s->canceled = 1;
            $s->save();

            /**
             * @var UserPackage $student_package
             */
            $student_package = $s->user->currentPackage;

            $student_package->unuseLecture();

            $emails[] = $s->user->email;
        }

        //notify students
        {
            // declare
            $recipients = $emails;
            $subject = __('email.class_canceled_subject');
            $module = "class_canceled";
            $data = [];

            //fill
            $hour = $class->hour;
            $teacher = $hour->teacher;

            if ($teacher) { //posleme ucitelovi
                $recipients[] = $teacher->email;
            } else { //ak nie je ucitel tak adminovi
                $recipients[] = User::find(1)->email;
                $recipients[] = User::find(4)->email;
            }

            $data["class"] = $class->id;

            // vytvorenie mailu
            EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
        }

        $class->save();

        return redirect()
            ->back()
            ->with('message', __('messages.lecture_canceled', ["reason" => $request->reason]))
            ->with('msg_type', 'success');
    }

    /**
     * @param Request $request
     * @param $cid - Class ID
     * @return RedirectResponse
     */
    public function saveRecording(Request $request, $cid)
    {
        $class = SchoolClass::findOrFail($cid);
        $user = Auth::user();

        try {
            $this->validate($request, ["link" => "required"]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', 'danger');
        }

        if ($class->is_past() and !$class->canceled) {
            $class->recording_url = $request->link;
            $class->save();

            return redirect()
                ->back()
                ->with('message', "OK")
                ->with('msg_type', 'success');

        } else {
            return redirect()
                ->back()
                ->with('message', "Invalid action | Nesprávna akcia")
                ->with('msg_type', 'danger');
        }
    }

    /**
     * @param Request $request
     * @param $cid - Class ID
     * @return RedirectResponse
     */
    public function addStudentsAdmin(Request $request, $cid)
    {
        $user = Auth::user();

        /** @var SchoolClass $class */
        $class = SchoolClass::findOrFail($cid);

        if ($user->hasRole('admin') or ($user->hasRole('teacher') and $class->hour->teacher and $class->hour->teacher->id == $user->id)) {
            //---
        } else {
            return redirect()
                ->route('lectures.show', $cid)
                ->with([
                    "message" => "ERR: 403 - Na túto akciu nemáte povolenie",
                    "msg_type" => "danger"
                ]);
        }

        $students_before = $class->students()->pluck('student_id');

        $limit = $class->hour->class_limit;
        $i = 0;

        if (isset($request->students)) {
            foreach ($request->students as $rs) {
                if (in_array($rs, $students_before->toArray())) {
                    //nothing
                } else {
                    //Tu by sa nemali dostat studenti ktory nemaju volne hodiny
                    // -- o to sa ma postarat funkcia ktora generuje zoznam studentov pre frontend vid Ajax.php
                    $student = User::find($rs);
                    /** @var UserPackage $student_package */
                    $student_package = $student->currentPackage;

                    $class->enrollStudent($rs, $student_package, 0);

                    // TODO: ako v tomto pripade zapisovat SMART studakov - zamysliet sa
                    /*if ($class->collective_hour) {
                        $classes_future = SchoolClass::where('collective_hour', $class->collective_hour)
                            ->where('class_date', ">=", Carbon::now()
                                ->format("Y-m-d"))
                            ->where('canceled', 0)
                            ->get();

                        foreach ($classes_future as $item) {
                            $is_signed_next = ClassStudent::where('class_id', $item->id)->where('student_id', $student->user_id)->first();
                            if ($student->stars_collective > 0 and !$is_signed_next) {
                                $cs = new ClassStudent();

                                $cs->class_id = $item->id;
                                $cs->student_id = $student->user_id;

                                $cs->save();
                                $student->stars_collective--;
                            }
                        }

                        $student->save();
                    }*/
                }
                $i++;
                if ($i >= $limit) break;
            }
            foreach ($students_before as $sb) {//odhlasit tych ktory niesu v zozname
                if (!in_array($sb, $request->students)) {
                    $tmp = ClassStudent::where('class_id', $cid)->where('student_id', $sb)->first();
                    ClassStudent::destroy([$tmp->id]);
                    $student = User::find($sb);

                    /** @var UserPackage $student_package */
                    $student_package = $student->currentPackage;

                    $student_package->unuseLecture();
                }
            }
        } else { //ak bol prazdny zoznam studakov vsetkych odhlasit
            foreach ($students_before as $sb) {
                $tmp = ClassStudent::where('class_id', $cid)->where('student_id', $sb)->first();
                ClassStudent::destroy([$tmp->id]);
                $student = User::find($sb);

                /** @var UserPackage $student_package */
                $student_package = $student->currentPackage;

                $student_package->unuseLecture();
            }
        }

        return redirect()->back()
            ->with("message", "OK")->with("msg_type", "success");

    }

    /**
     * @param Request $request
     * @param $cid - Class ID
     * @return RedirectResponse
     */
    public function editMaterial(Request $request, $cid)
    {

        $user = Auth::user();
        $class = SchoolClass::find($cid);

        if (!$user->hasRole('admin') and !($user->hasRole('teacher') and $class->teacher->id == $user->id)) {
            return redirect()->back()->with(['message' => __('messages.access_denied'), 'msg_type' => "danger"]);
        }

        $class_material = $class->material()->pluck('materials.id');

        $tmp = array_diff($class_material->toArray(), $request->l_material);
        $tmp2 = array_diff($request->l_material, $class_material->toArray());

        foreach ($tmp as $k => $v) {
            $i = ClassMaterial::where('class_id', $cid)->where('material_id', $v)->first();
            ClassMaterial::destroy($i->id);
        }

        foreach ($tmp2 as $k => $v) {
            $i = new ClassMaterial();
            $i->class_id = $cid;
            $i->material_id = $v;
            $i->save();
        }

        return redirect()->back()->with(["message" => "OK", "msg_type" => "success"]);
    }

    /**
     * @param Request $request
     * @param int $cid - Class ID
     * @return RedirectResponse
     */
    public function rescheduleClass(Request $request, int $cid)
    {
        $rules = [
            'student_id' => "required",
            'is_preview' => "required",
            'reschedule_id' => "required",
            'reschedule_date' => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', 'danger');
        }

        //student
        $user = User::find($request->student_id);

        $class_from = SchoolClass::find($cid);

        if (!Auth::user()->hasRole('admin')) {
            $can = $class_from->can_student_reschedule($user->id);
            if (!$can) {
                return redirect()
                    ->back()
                    ->with(["message" => "Reschedule limit count already reached", "msg_type" => "danger"]);
            }
        }

        $old_enroll = ClassStudent::where('class_id', $class_from->id)->where('student_id', $user->id)->first();

        if (!$old_enroll) {
            return redirect()
                ->back()
                ->with(["message" => "ERROR: invalid old enroll id", "msg_type" => "danger"]);
        }

        ClassStudent::destroy([$old_enroll->id]);

        $class_to = null;
        if ($request->is_preview) {
            $class_to = new SchoolClass();
            $class_to->teacher_hour = $request->reschedule_id;
            $class_to->class_date = $request->reschedule_date;
            $class_to->save();
        } else {
            $class_to = SchoolClass::findOrFail($request->reschedule_id);
        }

        $class_to->enrollStudent($user->id, null, $class_from->language_id ? $class_from->language_id : 0);

        $log = new ClassReschedule();
        $log->student_id = $user->id;
        $log->class_from = $class_from->id;
        $log->class_to = $class_to->id;
        $log->save();

        //todo inform teacher/s about change by mail
        {
            // declate
            $recipients = [];
            $subject = __('email.student_reschedule_class_subject');
            $module = "student_reschedule_class";
            $data = [];

            //fill
            $teacher = $class_from->hour->teacher;

            //posleme ucitelovi
            $recipients[] = $teacher->email;

            $data["class_old"] = $class_from->id;
            $data["class_new"] = $class_to->id;
            $data["student"] = $user->id;

            // vytvorenie mailu
            EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
        }

        return redirect()->route('dashboard')->with(["message" => "OK", "msg_type" => "success"]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function enrollPromPreview(Request $request)
    {
        $rules = ["student_id" => "required", "teacher_hour_id" => "required", 'date' => 'required'];
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with(["message" => "Required data missing", "msg_type" => "danger"]);
        }


        $teacher_hour = TeacherHour::find($request->teacher_hour_id);
        $date_carbon = Carbon::createFromFormat('Y-m-d', $request->date);
        $student = User::findOrFail($request->student_id);

        /**
         * @var UserPackage $student_package
         */
        $student_package = $student->currentPackage;

        //TODO - add choice for student with multiple languages to choose language for this class(es)
        $language_chosen = 0;
        if (isset($request->language_id))
            $language_chosen = $request->language_id;

        // Mám inštanciu hodiny
        if ($student_package->type == 1) {
            $rules = ["smart_th" => "required", "student_id" => "required", "teacher_hour_id" => "required", 'date' => 'required'];
            try {
                $this->validate($request, $rules);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()->with(["message" => "Required data missing", "msg_type" => "danger"]);
            }
            $control_date = Carbon::now();
            $now = Carbon::now();
            $ths = TeacherHour::whereIn('id', $request->smart_th)->get();
            $count = $student_package->classes_left;
            while ($student_package->classes_left) {
                foreach ($ths as $th) {
                    if (!$student_package->classes_left) break;

                    $tmp_date = "" . $control_date->year . "-" . $control_date->month . "-" . $control_date->startOfWeek()->addDays($th->day - 1)->day . " " . $th->class_start;
                    $th_date = Carbon::createFromFormat('Y-m-d H:i:s', $tmp_date);
                    if ($th_date > $now) {
                        $existing_class = SchoolClass::where('teacher_hour', $th->id)->where('class_date', $th_date->format('Y-m-d'))->get();

                        $was_existing = false;

                        foreach ($existing_class as $ec) {
                            /** @var SchoolClass $ec */
                            if (!$was_existing and $ec->is_free()) {
                                $was_existing = true;
                                if (!$ec->canceled) {

                                    //vytvaram zaznam o prihlasenom studentovi
                                    $ec->enrollStudent($request->student_id, $student_package, $language_chosen);
                                }
                            }
                        }
                        if (!$was_existing) {
                            $tmp_class = new SchoolClass();
                            $tmp_class->teacher_hour = $th->id;
                            $tmp_class->class_date = $th_date->format('Y-m-d');
                            $tmp_class->save();

                            //vytvaram zaznam o prihlasenom studentovi
                            $tmp_class->enrollStudent($request->student_id, $student_package, $language_chosen);
                        }
                    }
                }
                $control_date->addWeek();
            }

            {   // Notifikacia o zapisani studenta
                // declare
                $recipients = [];
                $subject = __('email.student_enroll_subject');
                $module = "student_enroll_smart";
                $data = [];

                //fill
                $teacher = $teacher_hour->teacher;

                if ($teacher) { //posleme ucitelovi
                    $recipients[] = $teacher->email;
                } else { //ak nie je ucitel tak adminovi
                    $recipients[] = User::find(1)->email;
                    $recipients[] = User::find(4)->email;
                }

                $data["day"] = $date_carbon->format('Y-m-d');
                $data["teacher_hours"] = $request->smart_th;
                $data["class_count"] = $count;
                $data["student"] = $student->id;

                // vytvorenie mailu
                EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
            }

            return redirect()->route('dashboard')->with([
                'package_type' => $student_package->type,
                'message' => 'Akcia úspešná',
                'msg_type' => 'success'
            ]);

        } else {
            $lecture = new SchoolClass();

            $lecture->teacher_hour = $teacher_hour->id;
            $lecture->class_date = $request->date;

            //ukladam hodinu
            $lecture->save();

            //vytvaram zaznam o prihlasenom studentovi
            $lecture->enrollStudent($request->student_id, $student_package, $language_chosen);

            {   // Notifikacia o zapisani studenta
                // declare
                $recipients = [];
                $subject = __('email.student_enroll_subject');
                $module = "student_enroll";
                $data = [];

                //fill
                $hour = $lecture->hour;
                $teacher = $hour->teacher;

                if ($teacher) { //posleme ucitelovi
                    $recipients[] = $teacher->email;
                } else { //ak nie je ucitel tak adminovi
                    $recipients[] = User::find(1)->email;
                    $recipients[] = User::find(4)->email;
                }

                $data["class"] = $lecture->id;
                $data["repeat"] = isset($request->repeat) ? 1 : 0;
                $data["student"] = $student->id;

                // vytvorenie mailu
                EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
            }

            return redirect()->route('dashboard')->with([
                'package_type' => $student_package->type,
                'message' => 'Akcia úspešná',
                'msg_type' => 'success'
            ]);
        }

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function createLectureFromPreview(Request $request)
    {
        $rules = ["teacher_hour_id" => "required", 'date' => 'required', 'lecture_data' => 'required'];
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with(["message" => "Required data missing", "msg_type" => "danger"]);
        }

        $ld = json_decode($request->lecture_data);

        $lecture = new SchoolClass();
        $lecture->teacher_hour = $ld->teacher_hour;
        $lecture->class_date = $ld->class_date;
        $lecture->save();

        return redirect()->route('lectures.show', ['id' => $lecture->id])->with([
            'message' => 'Akcia úspešná',
            'msg_type' => 'success'
        ]);

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancelLectureFromPreview(Request $request)
    {
        $rules = ["teacher_hour_id" => "required", 'date' => 'required'];
        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with(["message" => "Required data missing", "msg_type" => "danger"]);
        }

        $teacher_hour = TeacherHour::find($request->teacher_hour_id);

        $lecture = new SchoolClass();

        $lecture->teacher_hour = $request->teacher_hour_id;
        $lecture->class_date = $request->date;
        $lecture->canceled = 1;

        //ukladam hodinu
        $lecture->save();

        return redirect()->route('user.profile', $teacher_hour->user_id)->with([
            'message' => "Akcia úspešná",
            'msg_type' => 'success',
        ]);
    }

    //NOT IN USE FUCTIONS

    public function collectiveCoursesListing()
    {
        dd("NOT IN USE");
    }

    public function collectiveCourseDestroy($id)
    {
        dd("NOT IN USE");
    }

    public function collectiveCourseProlong(Request $request)
    {
        dd("NOT IN USE");
    }

    public function addCollective(Request $request)
    {
        dd("NOT IN USE");
    }

    public function assignAsTeacher($lid)
    {
        dd("NOT IN USE");
    }

    public function assignAsSubTeacher($lid)
    {
        dd("NOT IN USE");
    }

    public function unassignAsTeacher($lid)
    {
        dd("NOT IN USE");
    }

    public function unassignAsSubTeacher($lid)
    {
        dd("NOT IN USE");
    }

    public function changeClassLimit(Request $request, $id)
    {

        dd("NOT IN USE");
    }

}
