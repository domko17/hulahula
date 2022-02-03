<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\SchoolClass;
use App\Models\User\Profile;
use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(["admin"])->except("teacherStudentListing");
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $users = User::all();

        return view('admin.users.listing')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $roles = Role::all();
        $languages = Language::all();

        return view('admin.users.create')
            ->with('roles', $roles)
            ->with('languages', $languages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $u = User::where('email', $request->email)->first();
        if ($u)
            return redirect()
                ->back()
                ->with(["message" => "Užívateľ zo zadaným emailom už existuje", "msg_type" => "danger"]);


        $user = new User();

        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        $profile = new Profile();

        $profile->user_id = $user->id;
        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;

        $profile->save();

        switch ($request->role) {
            case 1:
                $user->attachRole('admin');
                break;
            case 2:
                $user->attachRole('teacher');
                DB::insert('insert into teacher (user_id, language_id) values (?, ?)', [$user->id, $request->language]);
                break;
            case 3:
                $user->attachRole('student');
                DB::insert('insert into student (user_id, language_id) values (?, ?)', [$user->id, $request->language]);
                break;
            default:
                break;
        }

        return redirect()
            ->route('admin.users.index')
            ->with(["message" => __('alert.create_success'), "msg_type" => "success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile;
        $roles = Role::all();
        $languages = Language::all();

        return view('admin.users.edit')
            ->with('user', $user)
            ->with('profile', $profile)
            ->with('roles', $roles)
            ->with('languages', $languages);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        if (isset($request->roles)) {
            if (in_array("3", $request->roles) and !$request->languages_study)
                return redirect()
                    ->back()
                    ->with(["message" => __('messages.err_form_not_filled_correctly'), "msg_type" => "danger"]);

            if (in_array("2", $request->roles) and !$request->languages_teach)
                return redirect()
                    ->back()
                    ->with(["message" => __('messages.err_form_not_filled_correctly'), "msg_type" => "danger"]);
        }


        $user = User::findOrFail($id);

        //$user->name = $request->first_name . " " . $request->last_name;
        //$user->email = $request->email;

        if (isset($request->password)) {
            $user->password = bcrypt($request->password);
        }

        if (!isset($request->active)) {
            $user->active = 0;
        } else {
            $user->active = 1;
        }

        $user->save();

        //$prof = $user->profile;

        //$prof->first_name = $request->first_name;
        //$prof->last_name = $request->last_name;

        //$prof->save();

        $languages = Language::all();

        $languages = $languages->all();

        //todo skontorlovat role (eg. ak bol studentom ale uz nie je tak mu zrusit jazyky, podobne ucitela)
        if (isset($request->roles)) {
            foreach ($request->roles as $r) {
                switch ($r) {
                    case 2:
                        foreach ($languages as $l) {
                            if ($user->teaching()->where('languages.id', $l->id)->first()) {
                                if (!in_array($l->id, $request->languages_teach)) {
                                    DB::table("teacher")->where('user_id', $id)->where('language_id', $l->id)->delete();
                                }
                            } else {
                                if (in_array($l->id, $request->languages_teach)) {
                                    DB::insert('insert into teacher (user_id, language_id) values (?, ?)', [$id, $l->id]);
                                }
                            }
                        }
                        break;
                    case 3:
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
                        break;
                    default:
                        break;
                }
            }

            $user->syncRoles($request->roles);
        }

        return redirect()
            ->route('admin.users.index')
            ->with(["message" => __('alert.create_success'), "msg_type" => "success"]);
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

    public function guestsListing()
    {
        $users = User::join("role_user as ru", "ru.user_id", 'users.id')->where('role_id', 4)->get();

        return view('admin.guests.index')
            ->with('users', $users);
    }

    public function studentsListing()
    {
        $users = User::join("role_user as ru", "ru.user_id", 'users.id')->where('users.active', 1)->where('role_id', 3)->get();

        $filters = 0;
        if (isset($_GET['f_lang']) and intval($_GET['f_lang']) > 0) {
            $tmp = [];
            foreach ($users as $u) {
                if ($u->studying()->where('language_id', intval($_GET['f_lang']))->first()) $tmp[] = $u;
            }
            $users = $tmp;
            $filters++;
        }

        if (isset($_GET['f_hours']) and intval($_GET['f_hours']) > 0) {
            if (intval($_GET['f_hours']) == 1) {
                $tmp = $users->sortBy(function ($item) {
                    return $item->currentPackage ?
                        $item->currentPackage->classes_left + \App\Models\User\Student::stars_i_reserved($item->id)
                        : PHP_INT_MAX;
                });
            } else {
                $tmp = $users->sortByDesc(function ($item) {
                    return $item->currentPackage ?
                        $item->currentPackage->classes_left + \App\Models\User\Student::stars_i_reserved($item->id)
                        : - 1;
                });
            }
            $users = $tmp;
            $filters++;
        }

        if (isset($_GET['filtered']) and $filters == 0) {
            return redirect()->route('admin.users.students.index');
        }

        $date_last_week = Carbon::now()->subWeek();

        foreach ($users as $u) {
            /** @var Student $st */
            $st = Student::find($u->id);
            $u->new_user = Carbon::createFromFormat("Y-m-d H:i:s", $u->created_at) > $date_last_week;
            $u->is_active = $st->classes_past()->where('class_date', ">=", Carbon::now()->subWeeks(2)->format('Y-m-d'))->count();
            $u->has_future_cc = false;

            foreach ($st->classes_future as $i) {
                if ($i->collective_hour) {
                    $u->has_future_cc = true;
                    break;
                }
            }
        }

        return view('admin.students.index')
            ->with('users', $users);
    }

    public function teacherStudentListing()
    {
        if (Auth::user()->hasRole('teacher')) {

            $teacher = Teacher::find(Auth::id());
            $classes = $teacher->classes_all();

            $students = [];
            foreach ($classes as $c) {
                $class_students = $c->students;
                foreach ($class_students as $s) {
                    if (!in_array($s->student_id, array_keys($students))) {
                        $students[$s->student_id] = $s->user;
                    }
                }
            }
            sort($students);
            return view('admin.students.teachers_students')
                ->with('users', $students);
        } else {
            return redirect()
                ->back()
                ->with(["message" => "access denied", "msg_type" => "danger"]);
        }
    }

    public function teacherStudentListing_admin($tid)
    {
        $user = User::find($tid);
        if (!$user or !$user->hasRole('teacher')) {
            return redirect()
                ->back()
                ->with(["message" => "Zvolený užívateľ neexistuje alebo nie je učiteľom", "msg_type" => "danger"]);
        }

        $teacher = Teacher::find($tid);
        $classes = $teacher->classes_all();

        $students = [];
        foreach ($classes as $c) {
            $class_students = $c->students;
            foreach ($class_students as $s) {
                if (!in_array($s->student_id, array_keys($students))) {
                    $students[$s->student_id] = $s->user;
                }
            }
        }
        sort($students);
        return view('admin.students.teachers_students')
            ->with('users', $students)
            ->with('teacher', $teacher)
            ->with('admin', true);
    }
}
