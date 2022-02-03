<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Note;
use App\Models\SchoolClass;
use App\Models\TeacherVacation;
use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Models\User\TeacherHour;
use App\Models\UserPackage;
use App\Models\ZoomMeeting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use MacsiDigital\Zoomy;
use MacsiDigital\Zoom\Facades\Zoom;

class ProfileController extends Controller
{
    /** TODO: toto necham
     * Show the form for editing the users profile
     *
     * @param $id - User ID
     * @return RedirectResponse|View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if (!Auth::user()->hasRole('admin') and $user->id != Auth::id()) {
            return redirect()
                ->route('dashboard')
                ->with("message", "Access denied")
                ->with("msg_type", "danger");
        }

        $profile = $user->profile;

        return view("user.profile.edit")
            ->with("user", $user)
            ->with("profile", $profile);
    }

    /** TODO: zamysliet sa
     * Updates users profile
     *
     * @param Request $request - POST form data
     * @param int $id - User ID
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!Auth::user()->hasRole('admin') and $user->id != Auth::id()) {
            return redirect()
                ->route('dashboard')
                ->with(["message" => "Access denied", "msg_type" => "danger"]);
        }

        if ($user->id == Auth::id()) {
            $rules = [
                "first_name" => "required",
                "last_name" => "required",
                "gender" => "required",
                "birthday" => "required",
                "street" => "required",
                "street_number" => "required",
                "zip" => "required",
                "phone" => "required",
                "city" => "required",
                "nationality" => "required",
            ];

            if ($user->hasRole('teacher')) $rules["iban"] = "required";

            try {
                $this->validate($request, $rules);
            } catch (ValidationException $e) {
                if ($e->validator->failed()["password_confirm"]) {
                    return redirect()->route('user.profile.edit', $id)
                        ->with(['message' => __('messages.err_passwords_not_equal'), 'msg_type' => "danger"]);
                }
                return redirect()->route('user.profile.edit', $id)
                    ->with(['message' => __('messages.err_validation_error'), 'msg_type' => "danger"]);
            }
        }

        $profile = $user->profile;

        if (!empty($request->title_before)) $profile->title_before = $request->title_before;
        if (!empty($request->title_after)) $profile->title_after = $request->title_after;
        if (!empty($request->first_name)) $profile->first_name = $request->first_name;
        if (!empty($request->last_name)) $profile->last_name = $request->last_name;
        if (!empty($request->gender)) $profile->gender = $request->gender;
        if (!empty($request->birthday)) $profile->birthday = Carbon::createFromFormat("d/m/Y", $request->birthday);
        if (!empty($request->street)) $profile->street = $request->street;
        if (!empty($request->street_number)) $profile->street_number = $request->street_number;
        if (!empty($request->city)) $profile->city = $request->city;
        if (!empty($request->zip)) $profile->zip = $request->zip;
        if (!empty($request->phone)) $profile->phone = $request->phone;
        if (!empty($request->bio)) $profile->bio = $request->bio;
        if (!empty($request->nationality)) $profile->nationality = $request->nationality;


        if ($user->hasRole('teacher')) {
            if (!empty($request->iban)) $profile->iban = $request->iban;
            if (!empty($request->zune)) $profile->zune_link = $request->zune;
            if (isset($request->time_before_class)) $profile->time_before_class = $request->time_before_class;
        }

        if ($user->id == Auth::id()) $profile->set = 1;

        if (isset($request->adminStudentSetting)) {
            if ($pckg = $user->currentPackage) {
                if (isset($request->package_user)) {
                    $pckg->type = $request->package_user;
                    $pckg->save();
                }
                if (isset($request->package_classes_left) and $request->package_classes_left >= 0) {
                    $pckg->classes_left = $request->package_classes_left;
                    $pckg->save();
                }
            }
        }

        if (isset($request->adminTeacherSetting)) {
            $profile->teacher_salary_i = 0.0 + floatval($request->salary_i);
            $profile->teacher_salary_c = 0.0 + floatval($request->salary_c);
        }

        $profile->save();

        $user->name = $profile->first_name . " " . $profile->last_name;
        $user->save();

        if (isset($request->adminStudentSetting)) {
            foreach ($user->studying as $item)
                DB::update('update student set level = ? where user_id = ? and language_id = ?', [$request["level_lang_" . $item->id], $user->id, $item->id]);
        }

        if (isset($request->old_pass) and isset($request->password)) {
            if (!Hash::check($request->old_pass, $user->password)) {
                return redirect()->route('user.profile.edit', $id)
                    ->with('message', __('messages.err_wrong_old_password'))
                    ->with('msg_type', "warning");
            }
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect()->route('user.profile', $id)
            ->with('message', __('messages.ok_profile_updated'))
            ->with('msg_type', "success");
    }

    /** TODO: toto necham
     * Shows profile detail view
     *
     * @param $id - User ID
     * @return View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile;
        $teacher = null;

        if ($user->hasRole('teacher')) {
            /** @var Teacher $t */
            $t = Teacher::find($user->id);
            $teacher = $t->getCalendarData();
        }
        $student = $user->hasRole('student') ? $user->studying : false;
        $s_inst = $user->hasRole('student') ? Student::find($id) : null;

        return view("user.profile.show")
            ->with([
                'student_instance' => $s_inst,
                'student' => $student,
                'teacher' => $teacher,
                "user" => $user,
                "profile" => $profile,
            ]); 
    }

    /** TODO: toto necham
     * Saves teacher hour preference
     *
     * @param Request $request - POST form data
     * @param $id - Teacher(user) ID
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function saveTeacherHours(Request $request, $id)
    {
        $rules = [
            'teacher_id' => "required",
            'day' => "required",
            'class_start' => "required",
            'class_end' => "required",
        ];

        $this->validate($request, $rules);

        $hour = new TeacherHour();

        $hour->user_id = $id;
        $hour->day = $request->day;
        $hour->class_start = $request->class_start;
        $hour->class_end = $request->class_end;
        $hour->language_id = 0;
        $hour->class_difficulty = 5;

        if ($hour->checkForConflict()) {
            return redirect()->route('user.profile', $id)
                ->with('message', "Nie je možné vytvoriť hodinu(#1): V zadaný čas a deň už máte naplánovanú inú hodinu")
                ->with('msg_type', "danger");
        } else {
            $hour->save();
        }

        // Zoom API
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $loggedUser = \auth()->user();
        $zoomUser = Zoom::user();

        if ($dayOfTheWeek <= $request->day){
            $meeting_date = Carbon::now()->add($request->day - $dayOfTheWeek, 'day');
        }
        else if($dayOfTheWeek > $request->day){
            $meeting_date = Carbon::now()->add(7 - ($dayOfTheWeek - $request->day), 'day');
        }

        $name = explode(" ", $loggedUser->name);
        if (!array_key_exists(1, $name)){
            $name[1] = null;
        }

        $existZoomUser = $zoomUser->find($loggedUser->email);
        if(!$existZoomUser){
            $existZoomUser = $zoomUser->create([
                "action" => "custCreate",
                "user_info" => [
                    "email" => $loggedUser->email,
                    "type" => 1,
                    "first_name" => $name[0],
                    "last_name" => $name[1]
                ]
            ]);
        }

        if (!$hour->checkForConflict()) {
          $duration = (strtotime($request->class_end) - strtotime($request->class_start)) / 60;

          $meeting = Zoom::meeting()->make([
              "topic" => "Meeting ".$existZoomUser->first_name." ".$existZoomUser->last_name ,
              "type"=> 2,
              "start_time"=> $meeting_date->format("Y-m-d\T".$request->class_start.":00\Z"),
              "duration"=> $duration,
              "password"=> Str::random(8)
          ]);

          $zoomMeeting = new ZoomMeeting();
          $meeting_info = $existZoomUser->meetings()->save($meeting);

          $zoomMeeting->user_id = $id;
          $zoomMeeting->teacher_hours_id = $hour->id;
          $zoomMeeting->zoom_meeting_id =  (string)$meeting_info->id;
          $zoomMeeting->start_datetime = $meeting_date->format("Y-m-d ".$request->class_start.":00");
          $zoomMeeting->password = $meeting_info->password;
          $zoomMeeting->h323_password = $meeting_info->h323_password;
          $zoomMeeting->pstn_password = $meeting_info->pstn_password;
          $zoomMeeting->encrypted_password = $meeting_info->encrypted_password;
          $zoomMeeting->type = $meeting_info->type;
          $zoomMeeting->join_url = $meeting_info->join_url;
          $zoomMeeting->start_url = $meeting_info->start_url;
          $zoomMeeting->duration = $duration;

          $zoomMeeting->save();
        }
        //TODO: toto pojde pravdepodobne preč
        //Helper::fillLecturesByTeacherHour($hour->id);

        return redirect()->route('user.profile', $id)
            ->with('message', __('messages.ok_teaching_hour_added'))
            ->with('msg_type', "success");
    }

    /** TODO: zamysliet sa nad tym
     * Deletes teacher hour preference
     *
     * @param Request $request - POST data with hour_id
     * @param $id - Teacher(user) ID
     * @return RedirectResponse
     */
    public function deleteTeacherHours(Request $request, $id)
    {
        $delete_classes = SchoolClass::where('teacher_hour', $request->hour_id)
            ->where('class_date', ">", Carbon::now())
            ->get();

        $counter = 0;
        foreach ($delete_classes as $dc) {
            //delete class if no student was signed up, else leave it be
            if ($dc->is_free()) {
                SchoolClass::destroy($dc->id);
                $counter++;
            }
        }

        TeacherHour::deactivate($request->hour_id);

        return redirect()->back()
            ->with('message', __('messages.ok_teaching_hour_removed', ["counter" => $counter, "counter2" => (count($delete_classes) - $counter)]))
            ->with('msg_type', "success");
    }

    /** //TODO: toto necham
     * Adds teachers note for specific student
     *
     * @param Request $request - Form POST data
     * @param $sid - Student(user) ID
     * @return RedirectResponse
     */
    public function addTeachersNote(Request $request, $sid)
    {
        $student = Student::findOrFail($sid);

        $user = Auth::user();

        if (!($user->hasRole('admin') or $user->hasRole('teacher'))) {
            return redirect()->back()
                ->with('message', "unauthorized action")
                ->with('msg_type', "danger");
        }

        $rules = [
            "note" => "required",
            "teacher_id" => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', "danger");
        }

        $note = new Note();

        $note->author_id = $request->teacher_id;
        $note->student_id = $student->id;
        $note->text = $request->note;

        $note->save();

        return redirect()->back()
            ->with('message', __('messages.teachers_note_saved'))
            ->with('msg_type', "success");
    }

    /** TODO: toto necham
     * Adds one-time teacher hour for specific day
     *
     * @param Request $request
     * @param $user_id
     * @return RedirectResponse
     */
    public function saveTeacherOneTimeHour(Request $request, $user_id)
    {
        $rules = [
            'teacher_id' => "required",
            'day' => "required",
            'class_start' => "required",
            'class_end' => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->route('user.profile', $user_id)
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', "danger");
        }
        $hour = new TeacherHour();

        $date = Carbon::createFromFormat("Y-m-d", $request->day);


        $hour->day = $date->format("Y-m-d");
        $hour->user_id = $user_id;
        $hour->class_start = $request->class_start;
        $hour->class_end = $request->class_end;
        $hour->language_id = 1;
        $hour->class_difficulty = 0;
        $hour->one_time = 1;

        $hour->save();

        $class = new SchoolClass();
        $class->teacher_hour = $hour->id;
        $class->class_date = $date->format("Y-m-d");

        if ($class->checkForTeacherCollision()) {
            TeacherHour::destroy($hour->id);
            return redirect()->route('user.profile', $user_id)
                ->with('message', "Nie je možné vytvoriť hodinu(#2): V zadaný čas a deň už máte naplánovanú inú hodinu")
                ->with('msg_type', "danger");
        }

        // Zoom API
        $loggedUser = \auth()->user();
        $zoomUser = Zoom::user();
        $meeting_date = $date;

        $name = explode(" ", $loggedUser->name);
        if (!array_key_exists(1, $name)){
            $name[1] = null;
        }

        $existZoomUser = $zoomUser->find($loggedUser->email);
        if(!$existZoomUser){
            $existZoomUser = $zoomUser->create([
                "action" => "custCreate",
                "user_info" => [
                    "email" => $loggedUser->email,
                    "type" => 1,
                    "first_name" => $name[0],
                    "last_name" => $name[1]
                ]
            ]);
        }
        if (!$hour->checkForConflict()) {
          $duration = (strtotime($request->class_end) - strtotime($request->class_start)) / 60;

          $meeting = Zoom::meeting()->make([
              "topic" => "Meeting ".$existZoomUser->first_name." ".$existZoomUser->last_name ,
              "type"=> 2,
              "start_time"=> $meeting_date->format("Y-m-d\T".$request->class_start.":00\Z"),
              "duration"=> $duration,
              "password"=> Str::random(8)
          ]);

          $zoomMeeting = new ZoomMeeting();
          $meeting_info = $existZoomUser->meetings()->save($meeting);

          $zoomMeeting->user_id = $user_id;
          $zoomMeeting->teacher_hours_id = $hour->id;
          $zoomMeeting->zoom_meeting_id = (string)$meeting_info->id;
          $zoomMeeting->start_datetime = $meeting_date->format("Y-m-d ".$request->class_start.":00");
          $zoomMeeting->password = $meeting_info->password;
          $zoomMeeting->h323_password = $meeting_info->h323_password;
          $zoomMeeting->pstn_password = $meeting_info->pstn_password;
          $zoomMeeting->encrypted_password = $meeting_info->encrypted_password;
          $zoomMeeting->type = $meeting_info->type;
          $zoomMeeting->join_url = $meeting_info->join_url;
          $zoomMeeting->start_url = $meeting_info->start_url;
          $zoomMeeting->duration = $duration;

          $zoomMeeting->save();
        }

        return redirect()->route('user.profile', $user_id)
            ->with('message', __('messages.ok_one_time_hour_added'))
            ->with('msg_type', "success");
    }

    public function setPackageForStudent(Request $request, $sid)
    {
        $student = Student::findOrFail($sid);

        $user = Auth::user();

        if (!($user->hasRole('admin'))) {
            return redirect()->back()
                ->with('message', "unauthorized action")
                ->with('msg_type', "danger");
        }

        $rules = [
            "package_id" => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', "danger");
        }

        $class_count = 0;
        if ($request->package_id == 1) $class_count = 20;
        else if ($request->package_id == 2) $class_count = 10;
        else if ($request->package_id == 3) $class_count = 1;

        $up = new UserPackage();
        $up->user_id = $sid;
        $up->type = $request->package_id;
        $up->state = 1;
        $up->classes_left = $class_count;
        $up->save();

        return redirect()->route('user.profile.edit', $sid)->with([
            'message' => 'Balicek aktivovany',
            'msg_type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return RedirectResponse
     */
    public function addVacation(Request $request, $user_id)
    {
        $rules = [
            'day_start' => "required",
            'day_end' => "required",
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return redirect()->route('user.profile', $user_id)
                ->with('message', __('messages.err_form_not_filled_correctly'))
                ->with('msg_type', "danger");
        }

        $vacation = new TeacherVacation();
        $vacation->user_id = $user_id;
        $vacation->date_start = $request->day_start;
        $vacation->date_end = $request->day_end;
        $vacation->description = $request->description;
        $vacation->save();

        return redirect()->back()
            ->with('message', __('messages.vacation_saved'))
            ->with('msg_type', "success");
    }

    public function setStudentStudyLanguages(Request $request, $id)
    {
        if (!$request->languages_study or !count($request->languages_study))
            return redirect()
                ->back()
                ->with(["message" => __('messages.err_form_not_filled_correctly'), "msg_type" => "danger"]);

        $user = User::findOrFail($id);

        $languages = Language::all();

        foreach ($languages as $l) {
            if ($user->studying()->where('languages.id', $l->id)->first()) {
                if (!in_array($l->id, $request->languages_study)) {
                    DB::table("student")->where('user_id', $id)->where('language_id', $l->id)->delete();
                }
            } else {
                if (in_array($l->id, $request->languages_study)) {
                    DB::insert('insert into student (user_id, language_id) values (?, ?)', [$id, $l->id]);
                }
            }
        }

        return redirect()->back()->with([
            'message' => 'OK',
            'msg_type' => 'success',
        ]);

    }

    public function deleteVacation(Request $request, $user_id)
    {
        TeacherVacation::destroy([$request->vacation_id]);

        return redirect()->back()->with([
            'message' => 'OK',
            'msg_type' => 'success',
        ]);
    }

    public function evaluateLanguage(Request $request, $user_id){
        DB::update('update student set level = ? where user_id = ? and language_id = ?', [$request->level_lang, $user_id, $request->language_id]);
        return redirect()->back()->with([
            'message' => 'OK',
            'msg_type' => 'success',
        ]);
    }
}
