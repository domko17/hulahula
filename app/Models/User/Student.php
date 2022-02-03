<?php

namespace App\Models\User;

use App\Models\ClassFeedback;
use App\Models\ClassStudent;
use App\Models\Note;
use App\Models\SchoolClass;
use App\Models\StudentStudyDay;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Student
 * @package App\Models\User
 *
 *
 */
class Student extends User
{
    protected $table = "users";

    /**
     * @param $sid - student id
     * @return int
     */
    public static function stars_i_reserved($sid)
    {
        $student = self::findOrFail($sid);

        $res = DB::table('class_student as cs')
            ->join('classes as c', 'cs.class_id', "=", "c.id")
            ->where('class_date', ">", Carbon::now()->format("Y-m-d"))
            ->where('cs.student_id', $sid)
            ->whereNotNull('c.teacher_hour')
            ->where('cs.canceled', 0)
            ->get();

        return count($res);
    }

    /**
     * @param $sid - student id
     * @return int
     */
    public static function stars_c_reserved($sid)
    {
        $student = self::findOrFail($sid);

        $res = DB::table('class_student as cs')
            ->join('classes as c', 'cs.class_id', "=", "c.id")
            ->where('class_date', ">", Carbon::now()->format("Y-m-d"))
            ->where('cs.student_id', $sid)
            ->whereNotNull('c.collective_hour')
            ->where('cs.canceled', 0)
            ->get();

        return count($res);
    }

    // relations

    public function classes_all()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            ClassStudent::class,
            "student_id",
            "id",
            "id",
            "class_id");
    }

    public function classes_future()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            ClassStudent::class,
            "student_id",
            "id",
            "id",
            "class_id")
            ->where('class_date', '>=', Carbon::now()->format("Y-m-d"))
            ->where('classes.canceled', 0)
            ->orderByDesc("class_date");
    }

    public function teachers_notes()
    {
        return $this->hasMany(Note::class, "student_id", "id")
            ->orderByDesc("created_at");
    }

    /**
     * @return Collection
     */
    public function getCalendarData()
    {
        $data = collect();
        $data->inst = $this;
        $data->classes_past = $this->classes_past;
        $data->classes_future = $this->classes_future;
        $data->languages = $this->studying;
        $data->package = $this->currentPackage;

        return $data;
    }

    /**
     * @return HasMany
     */
    public function feedbacks()
    {
        return $this->hasMany(ClassFeedback::class, "student_id", "id");
    }

    /**
     * @return bool|Teacher
     */
    public function canDoFeedback()
    {
        $done_feedbacks = $this->feedbacks;
        $done_classes = $this->classes_past;

        $teachers_feedbacks = [];
        foreach ($done_feedbacks as $df) {
            $t = $df->teacher_id;
            if (!in_array($t, $teachers_feedbacks)) $teachers_feedbacks[] = $t;
        }

        foreach ($done_classes as $dc) {
            $t = $dc->hour->user_id;
            if (!in_array($t, $teachers_feedbacks)) return $dc->hour->teacher;
        }


        return false;
    }

    public function canDoFeedbackGetAll()
    {
        $done_feedbacks = $this->feedbacks;
        $done_classes = $this->classes_past;

        $res = array();

        $teachers_feedbacks = [];
        foreach ($done_feedbacks as $df) {
            $t = $df->teacher_id;
            if (!in_array($t, $teachers_feedbacks)) $teachers_feedbacks[] = $t;
        }

        $can_already = array();
        foreach ($done_classes as $dc) {
            $t = $dc->hour->user_id;
            if (!in_array($t, $teachers_feedbacks) and !in_array($t, $can_already)) {
                $res[] = $dc->hour->teacher;
                $can_already[] = $t;
            }
        }

        return $res;
    }

    /**
     * @return array
     */
    public function getChartData()
    {
        $result = array(
            'bar_data' => array(),
            'pie_data' => array(),
            'days_all' => "",
            'classes_all' => ""
        );

        $today = Carbon::now();
        $month = $today->month;
        $year = $today->year;
        for ($i = 0; $i < 6; $i++) {
            $d1_i = Carbon::now()->startOfMonth()->month($month)->year($year);
            $d1 = $d1_i->format('Y-m-d');
            $d2_i = Carbon::now()->startOfMonth()->month($month)->year($year)->startOfMonth()->addMonth()->startOfMonth()->subDay();
            $d2 = $d2_i->format('Y-m-d');
            $date_str = $month . "/" . substr($year, 2, 2);
            $classes = $this->classes_past()->where('class_date', '>=', $d1)->where('class_date', '<=', $d2)->count();
            $self_study = StudentStudyDay::userStudyHoursBetweenDays($this->id, $d1_i, $d2_i);
            $result['bar_data'][$date_str] = $classes + ($self_study->hours < 5 ? $self_study->hours : round($self_study->hours / 60, 1));

            if ($month == 1) {
                $month = 12;
                $year -= 1;
            } else {
                $month -= 1;
            }
        }

        $self_study_totals = StudentStudyDay::userTotalStudy($this->id);

        $classes_all = $this->classes_past;

        $result['classes_all'] = count($classes_all) + $self_study_totals->hours;

        $days = [];
        $teachers = [];
        foreach ($classes_all as $c) {
            if (!in_array($c->class_date, $days)) $days[] = $c->class_date;
            if (!in_array($c->hour->user_id, $teachers)) $teachers[$c->hour->teacher->name] = 1;
            else $teachers[$c->hour->teacher->name] += 1;
        }

        $result['pie_data'] = $teachers;
        $result['days_all'] = count($days) + $self_study_totals->days_count;

        return $result;
    }

    public function classes_past()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            ClassStudent::class,
            "student_id",
            "id",
            "id",
            "class_id")
            ->where('classes.canceled', 0)
            ->where('class_date', '<', Carbon::now()->format("Y-m-d"));
    }

}
