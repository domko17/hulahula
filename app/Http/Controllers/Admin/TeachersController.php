<?php

namespace App\Http\Controllers\Admin;

use App\Models\SalaryHistory;
use App\Models\User\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeachersController extends Controller
{

    public function __construct()
    {
        $this->middleware(["admin"])->except(['teacherFinishedClasses']);
    }

    public function index()
    {
        $teachers = Teacher::whereIn("id", DB::table('teacher')->pluck("user_id"))->where('users.active', 1)->get();
        $history = SalaryHistory::all();
        return view('admin.teachers.index')
            ->with([
                "teachers" => $teachers,
                "history" => $history
            ]);
    }

    public function makePayment(Request $request)
    {
        if(!isset($request->teacher_id) or !isset($request->confirm_password)){
            return redirect()->route("admin.teachers.index")
                ->with("message", "Cýbajúce údaje.")
                ->with('msg_type', "danger");
        }

        if (!Auth::attempt(['email' => Auth::user()->email, 'password' => $request->confirm_password])) {
            return redirect()->route("admin.teachers.index")
                ->with("message", "Nesprávne povrdzovacie heslo.")
                ->with('msg_type', "danger");
        }

        /**
         * @var Teacher $teacher
         */
        $teacher = Teacher::findOrFail($request->teacher_id);

        if ($teacher->pending_salary() == 0) {
            return redirect()->route("admin.teachers.index")
                ->with("message", "Učitelovi nie je čo zaplatiť.")
                ->with('msg_type', "danger");
        }

        $c_i = $teacher->classes_unpaid();
        //$c_c = $teacher->classes_c_unpaid();

        $c_i_ids = [];
        $c_c_ids = [];

        $to_pay = $teacher->pending_salary();

        foreach ($c_i as $c) {
            $c->teacher_paid = 1;
            $c->save();
            $c_i_ids[] = $c->id;
        }

        /*foreach ($c_c as $c) {
            $c->teacher_paid = 1;
            $c->save();
            $c_c_ids[] = $c->id;

        }*/

        $new_history = new SalaryHistory();

        $new_history->teacher_id = $teacher->id;
        $new_history->stars_i = count($c_i);
        $new_history->stars_c = 0;
        $new_history->classes_i = json_encode($c_i_ids);
        $new_history->classes_c = json_encode($c_c_ids);
        $new_history->paid = $to_pay;

        $new_history->save();

        return redirect()->route("admin.teachers.index")
            ->with("message", "Nevyplatene hodiny ucitela " . $teacher->name . " boli oznacene ako vyplatene.")
            ->with('msg_type', "success");
    }

    public function teacherFinishedClasses($tid){
        /**
         * @var Teacher $teacher
         */
        $teacher = Teacher::findOrFail($tid);

        $lui = $teacher->classes_unpaid();
        $lpi = $teacher->classes_paid();
        $export_dates = array();

        foreach ($lpi as $item){
            $tmp = substr($item->class_date,0,7);
            $tmp_ = explode("-",$tmp);
            $str = $tmp_[1]."/".$tmp_[0];
            if(!in_array($str, $export_dates))
                $export_dates[] = $str;
        }

        return view('lecture.listing_teachers_classes')
            ->with([
                "lectures_ui" => $lui,
                "lectures_pi" => $lpi,
                "teacher" => $teacher,
                "export_dates" => $export_dates
            ]);
    }
}
