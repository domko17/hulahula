<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassFeedback;
use App\Models\ClassRequest;
use App\Models\EmailMessageGenerator;
use App\Models\Helper;
use App\Models\Language;
use App\Models\Meeting;
use App\Models\SchoolClass;
use App\Models\StudentStudyDay;
use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Models\User\TeacherHour;
use App\Models\WordCard;
use App\Models\ZoomMeeting;
use App\Notifications\TestNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Mockery\Exception;
use Whoops\Exception\ErrorException;

class DashboardController extends Controller
{

    private $debug = false;

    /**
     * DashboardController constructor.
     *
     * Applies auth middleware and inline middleware to set application locale
     */
    public function __construct()
    {
        $this->middleware(["auth"]);

        $this->middleware(function ($request, $next) {
            $user = User::find(Auth::id());
            $profile = $user->profile;

            \app()->setLocale($profile->locale);
            return $next($request);
        });
    }

    public function test()
    {
        $user = Student::find(Auth::id());

        //Helper::checkUserPackages();

        $emg = new EmailMessageGenerator();
        //$emg->generateNoOrderPackageAfterFirstLecture(1);
        //$emg->autoGenerateEmails(EmailMessageGenerator::EVERY_FIVE_MINUTES);
        //$emg->autoGenerateEmails(EmailMessageGenerator::DAILY);
        //$emg->checkTeacherEvaluateStudent();

        return view("test");
    }

    public function testEventFire()
    {
        $user = Auth::user();
        $user->notify(new TestNotification());
        //event(new TestEvent(Auth::user()));
    }


    /**
     * Renders main dashboard view
     *
     * @return View
     */
    public function index()
    {

        $user = User::find(Auth::id());

        $profile = $user->profile;

        $guest = null;
        $admin = null;
        $teacher = null;
        $student = null;

        $messages = $user->getNewMessages();
        $banners = $user->getMyBanners();

        if ($user->hasRole('admin')) {
            $admin = collect();
        }

        if ($user->hasRole('teacher')) {
            /** @var Teacher $t */
            $t = Teacher::find($user->id);

            $teacher = $t->getCalendarData();

            $teacher->classRequests = $t->getAvailableClassRequests();
        }

        if ($user->hasRole('student')) {
            /** @var Student $st */
            $st = Student::findOrFail($user->id);

            $student = $st->getCalendarData();

            $student->chart_data = $st->getChartData();

            $student->can_feedback = $st->canDoFeedback();
        }
        if ($user->hasRole('guest')) {
            $guest = true;
        }

        $quick_survey = $user->availableQuickSurvey();
        $zoom_meeting = new ZoomMeeting();

        if ($zoom_meeting->getUserZoomMeeting()){
            Cache::put('zoom_meeting_duration', $zoom_meeting->getUserZoomMeeting(), Carbon::createFromFormat('Y-m-d H:i:s', $zoom_meeting->getUserZoomMeeting()['start_datetime'])->addMinutes( $zoom_meeting->getUserZoomMeeting()['duration']));
        }

        return view('dashboard.index')
            ->with(
                [
                    'user' => $user,
                    'profile' => $profile,
                    'admin' => $admin,
                    'teacher' => $teacher,
                    'student' => $student,
                    'guest' => $guest,
                    'messages' => $messages,
                    'banners' => $banners,
                    'quick_survey' => $quick_survey,
                ]
            );
    }

    public function contactPage()
    {
        $users = User::all();
        $admins = [];
        foreach ($users as $user) {
            if ($user->hasRole('admin')) {
                $admins[] = $user;
            }
        }

        $teachers = [];

        if (Auth::user()->hasRole('student')) {
            foreach (Auth::user()->studying as $l) {
                foreach ($l->teachers()->where('active', 1)->get() as $t) {
                    $teachers[$t->id] = $t;
                }
            }
        }

        $teachers = array_unique($teachers);

        return view('dashboard.contact')
            ->with([
                "admins" => $admins,
                "teachers" => $teachers,
            ]);
    }

    public function signStudentStudyDay(Request $request)
    {

        $rules = [
            "hours" => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()->with("message", "ERROR")->with("msg_type", "danger");
        }

        $ssd = new StudentStudyDay();

        $ssd->student_id = Auth::id();
        $ssd->hours = $request->hours;

        $ssd->save();

        return redirect()->back()->with("message", "OK")->with("msg_type", "success");
    }

    public function selectFirstLanguage($lid)
    {
        $user = Auth::user();

        if (!$user->hasRole("guest")) {
            return redirect()->route('dashboard')
                ->with("message", "Funkcia dostupná len ak ste hosť.")
                ->with("mdg_type", "warning");
        }

        $language = Language::findOrFail($lid);

        DB::insert('insert into student (user_id, language_id) values (?, ?)', [$user->id, $lid, 1]);

        $user->syncRoles([3]);

        return redirect()->route('dashboard')
            ->with("message", "Gratulujeme. Stali ste sa študentom našej školy :) Môžete začať naplno študovať.")
            ->with("msg_type", "success");
    }

    public function birthdays()
    {
        $birthday_users = User::birthdayWeek();

        return view('dashboard.birthdays')
            ->with([
                "users" => $birthday_users,
            ]);
    }

    public function themeChange()
    {
        $user = Auth::user();

        if ($user->theme == 1) {
            $user->theme = 2;
        } else {
            $user->theme = 1;
        }
        $user->save();

        return redirect()->back();
    }

    public function translations()
    {
        $sk = File::files(base_path() . "/resources/lang/sk");
        $langs = ['en', 'de', 'ru'];
        $translation_status = array();
        foreach ($sk as $f) {
            if (!in_array($f->getFileNameWithoutExtension(), ['validation'])) {
                $translations_sk = Lang::get($f->getFileNameWithoutExtension(), [], 'sk', false);
                $count_sk = 0;
                foreach ($translations_sk as $k => $v) {
                    if ($v) {
                        $count_sk++;
                    }

                    foreach ($langs as $l) {
                        $translations_l = Lang::get($f->getFileNameWithoutExtension(), [], $l, false);
                        try {
                            if (is_countable($translations_l) and count($translations_l) < $count_sk) {
                                $translation_status[$f->getFileNameWithoutExtension()][$l] = $count_sk - count($translations_l);
                            } else if (!is_countable($translations_l)) {
                                $translation_status[$f->getFileNameWithoutExtension()][$l] = $count_sk;
                            } else {
                                $translation_status[$f->getFileNameWithoutExtension()][$l] = false;
                            }
                        } catch (\ErrorException $e) {
                            Log::warning("Error Exception during translation count check. File: " . $f->getFileNameWithoutExtension() . " | Language: " . $l);
                        }
                    }
                }
            }
        }

        return view('translations.index')
            ->with('files', $sk)
            ->with('langs', $langs)
            ->with('statuses', $translation_status);
    }

    public function translationsFile($file_name, $langcode)
    {
        $translations = Lang::get($file_name, [], 'sk', false);
        $langs = [$langcode];
        if ($langcode == "sk") $langs = ['sk', $langcode];

        return view('translations.edit')
            ->with([
                'translations' => $translations,
                'langs' => $langs,
                'language' => $langcode,
                'file' => $file_name
            ]);
    }

    public function translationsFileSave(Request $request, $file_name, $langcode)
    {
        //dump($request);
        $langs = [$langcode];
        if ($langcode == "sk") $langs[] = 'sk';

        foreach ($langs as $lang) {
            //dump($lang);
            $path = base_path() . "/resources/lang";
            if (!File::exists($path . "/" . $lang . "/" . $file_name . ".php"))
                File::put($path . "/" . $lang . "/" . $file_name . ".php", '');

            $f = File::get($path . "/" . $lang . "/" . $file_name . ".php");
            $lang_code = substr($lang, -2);
            $output = "<?php\r\n\r\nreturn [\r\n";
            $translations = Lang::get($file_name, [], $lang_code, false);
            if (!is_array($translations)) $translations = array();
            foreach ($request->request as $k => $v) {
                //dump($k);
                //dump($v);
                $exp = explode("_", $k);
                if (count($exp) < 3) continue;
                $tmp = substr($k, 12);
                $r_l = substr($tmp, -2);
                $r_k = substr($tmp, 0, strlen($tmp) - 3);
                if ($r_l == $lang_code) {
                    //dump(isset($translations[$r_k]));
                    //dump($v);
                    //dump(!isset($translations[$r_k]) and (!$v));
                    if (!isset($translations[$r_k]) and (!$v)) continue;
                    //dump('will save');
                    $translations[$r_k] = $v ? $v : "";
                }
            }

            foreach ($translations as $k => $v) {
                $v = str_replace('"', "'", $v);
                $output .= "\t'" . $k . "' => \"" . $v . "\",\r\n";
            }
            $output .= "];";
            File::put($path . "/" . $lang . "/" . $file_name . ".php", $output);
        }
        //dd('---');

        return redirect()->back()->with([
            "message" => "Preklady uložené",
            "msg_type" => "success",
        ]);
    }

    public function saveClassRequest(Request $request)
    {
        $rules = [
            'day' => 'required',
            'class_start' => 'required',
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()->with([
                'message' => __('messages.err_form_not_filled_correctly'),
                'msg_type' => 'danger'
            ]);
        }

        $req = new ClassRequest();
        $req->student_id = Auth::id();
        $req->date = $request->day;
        $req->start_time = $request->class_start;
        $req->language = ($request->class_language ? $request->class_language : 0);
        $req->save();

        return redirect()->back()->with([
            'message' => __('dashboard.class_request_made'),
            'msg_type' => 'success'
        ]);
    }

    public function takeClassRequest($req_id)
    {
        $req = ClassRequest::findOrFail($req_id);

        $time_e = Carbon::createFromFormat("Y-m-d H:i:s", $req->date . " " . $req->start_time)->addMinutes(50);

        $hour = new TeacherHour();
        $hour->day = $req->date;
        $hour->user_id = Auth::id();
        $hour->class_start = $req->start_time;
        $hour->class_end = $time_e->format('H:i:s');
        $hour->language_id = 1;
        $hour->class_difficulty = 0;
        $hour->one_time = 1;

        $hour->save();

        $class = new SchoolClass();
        $class->teacher_hour = $hour->id;
        $class->class_date = $req->date;

        if ($class->checkForTeacherCollision()) {
            TeacherHour::destroy($hour->id);
            return redirect()->back()
                ->with('message', "Nie je možné vytvoriť hodinu(#2): V zadaný čas a deň už máte naplánovanú inú hodinu")
                ->with('msg_type', "danger");
        }

        $class->save();

        ClassRequest::destroy($req_id);

        return redirect()->route('lectures.show', $class->id)
            ->with('message', "Požiadavka bola vzatá, dajte vedieť študentovi, prípadne ho prihláste na hodinu.")
            ->with('msg_type', "success");

    }

    public function thankYou()
    {
        return view('auth.thank_you');
    }
}
