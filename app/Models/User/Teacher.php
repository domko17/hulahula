<?php

namespace App\Models\User;

use App\Models\ClassRequest;
use App\Models\CollectiveHour;
use App\Models\Helper;
use App\Models\Meeting;
use App\Models\SchoolClass;
use App\Models\TeacherVacation;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class Teacher
 * @package App\Models\User
 *
 * @property Collection teacher_hours_all
 * @property Collection classes_all
 * @property Collection vacations
 * @property Collection vacationsPast
 * @property Collection vacationsFuture
 */
class Teacher extends User
{
    protected $table = "users";

    /**
     * @return int
     */
    public function stars_i()
    {
        $past_classes_i = $this->classes_i_past()->where('canceled', 0)->get();
        $counter = 0;

        foreach ($past_classes_i as $i) {
            if (!$i->is_free()) {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_i_past()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            TeacherHour::class,
            "user_id", //foreign key on middle table
            "teacher_hour", //foreign key on final table
            "id", //local key on base table
            "id")//local key on middle table
        ->where('class_date', '<', Carbon::now()->format("Y-m-d"));
    }

    /**
     * @return int
     */
    public function stars_c()
    {

        $tmp = DB::table('classes as c')
            ->join('collective_hours as ch', "ch.id", "=", "c.collective_hour")
            ->where('ch.user_id', $this->id)
            ->where('c.canceled', 0)
            ->where('c.class_date', '<', Carbon::now())
            ->get();

        $counter = 0;

        return count($tmp);
    }

    /**
     * @return array
     */
    public function classes_i_unpaid()
    {
        $tmp = $this->classes_i_past()->where('canceled', 0)->where('teacher_paid', 0)->get();

        $res = [];

        foreach ($tmp as $class) {
            if (count($class->students) > 0) {
                $res[] = $class;
            }
        }

        return $res;
    }

    /**
     * @return array
     */
    public function classes_c_unpaid()
    {
        $tmp = $this->classes_c_past()->where('canceled', 0)->where('teacher_paid', 0)->get();

        $res = [];

        foreach ($tmp as $class) {
            if (count($class->students) > 0) {
                $res[] = $class;
            }
        }

      return $res;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_c_past()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            CollectiveHour::class,
            "user_id",
            "collective_hour",
            "id",
            "id"
        )->where('class_date', '<', Carbon::now()->format("Y-m-d"))
            ->where('collective_hours.user_id', $this->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_c_paid()
    {
        return $this->classes_c_past()->where('teacher_paid', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_all()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            TeacherHour::class,
            "user_id", //foreign key on middle table
            "teacher_hour", //foreign key on final table
            "id", //local key on base table
            "id"); //local key on middle table;
    }

    /**
     * @return array
     */
    public function classes_paid()
    {
        $tmp = $this->classes_i_past()
            ->where('canceled', 0)
            ->where('teacher_paid', 1)
            ->orderBy('class_date')->get();

        $res = [];

        foreach ($tmp as $class) {
            if (count($class->students) > 0) {
                $res[] = $class;
            }
        }

        return $res;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_c_future()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            CollectiveHour::class,
            "user_id",
            "collective_hour",
            "id",
            "id"
        )->where('class_date', '>=', Carbon::now()->format("Y-m-d"))
            ->where('collective_hours.user_id', $this->id)
            ->orderBy("class_date", 'asc')->orderBy('class_start');
    }

    /**
     * @return float|int
     */
    public function pending_salary()
    {
        $ci = $this->classes_unpaid();

        $sbi = floatval($this->profile->teacher_salary_i);

        $salary = 0;

        foreach ($ci as $c) {
            /**
             * @var SchoolClass $c ;
             */
            $type = $c->packageUsed();
            if ($type == 99) continue;
            $salary += $sbi;
        }

        return $salary;

    }

    /**
     * @return array
     */
    public function classes_unpaid()
    {
        $tmp = $this->classes_i_past()->where('canceled', 0)
            ->where('teacher_paid', 0)
            ->orderByDesc('class_date')
            ->get();

        $res = [];

        foreach ($tmp as $class) {
            if (count($class->students) > 0) {
                $res[] = $class;
            }
        }

        return $res;
    }

    /**
     * @return array
     */
    public function classes_all_past()
    {
        $res = $this->classes_i_all()
            ->where('class_date', '<', Carbon::now()->format("Y-m-d"))
            ->get();
        $res_2 = $this->classes_c_all()->where('class_date', '<', Carbon::now()->format("Y-m-d"))->get();
        $res = $res->merge($res_2);

        $rres = [];

        foreach ($res as $class) {
            if (count($class->students) > 0) {
                $rres[] = $class;
            }
        }

        return $rres;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_i_all()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            TeacherHour::class,
            "user_id", //foreign key on middle table
            "teacher_hour", //foreign key on final table
            "id", //local key on base table
            "id"); //local key on middle table;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_c_all()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            CollectiveHour::class,
            "user_id",
            "collective_hour",
            "id",
            "id"
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    /*public function classes_all()
    {
        $res = $this->classes_i_all()->get();
        $res_2 = $this->classes_c_all()->get();
        $res = $res->merge($res_2);
        return $res;
    }*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacher_hours_all()
    {
        return $this
            ->hasMany(TeacherHour::class, "user_id", "id")
            ->where('active', 1);
    }

    /**
     * @param $date
     * @return bool
     */
    public function has_class_on_date($date)
    {
        $classes = $this->classes_i_future()->where('class_date', $date)->get();
        foreach ($classes as $class) {
            if (!$class->is_free()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function classes_i_future()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            TeacherHour::class,
            "user_id", //foreign key on middle table
            "teacher_hour", //foreign key on final table
            "id", //local key on base table
            "id")//local key on middle table
        ->where('class_date', '>=', Carbon::now()->format("Y-m-d"))
            ->orderBy("class_date", 'asc')->orderBy('class_start');
    }

    /**
     * @param $date
     * @return bool | int
     */
    public function is_free($date)
    {
        $date_carbon = Carbon::createFromFormat("Y-m-d", $date);
        $day_of_week = $date_carbon->dayOfWeekIso;

        //Check for any reserved classes for the specified day
        //if ($this->has_class_on_date($date)) return false;

        // Check for canceled classes (those are assumed to be not available to be opened again)
        if (!$this->has_free_classes_exclude_canceled($date)) return false;

        //Check for unavailability due to vacation for specified day
        if ($this->checkDateIntersectsWithVacations($date)) return false;

        // set time to actual time if day in check is today else accept any time
        $now = Carbon::now();
        if ($now->day == $date_carbon->day and $now->month == $date_carbon->month) $now_time = $now->format('H:m:s');
        else $now_time = "00:00:00";

        //Check for availability for the specified day
        $ths = $this->teacher_hours()->where('day', $day_of_week)->where('class_start', '>', $now_time)->get();
        $filtered = array();
        foreach ($ths as $th){
            if(!$th->is_enroll_locked($date))
                $filtered[] = $th;
        }
        if (count($filtered)) return 1;

        //Check for availability of one-time hours for specified day //TODO: disable this for SMART student
        $ot_ths = $this->teacher_hours_ot()->where('day', $date)->where('class_start', '>', $now_time)->get();
        $ot_filtered = array();
        foreach ($ot_ths as $th){
            if(!$th->is_enroll_locked($date))
                $ot_filtered[] = $th;
        }
        if (count($ot_filtered)) return 2;

        return false;
    }

    /**
     * @param string $date
     * @return bool
     */
    public function has_free_classes_exclude_canceled(string $date)
    {
        $date_carbon = Carbon::createFromFormat("Y-m-d", $date);
        $day_of_week = $date_carbon->dayOfWeekIso;

        $classes = $this->classes_i_future()->where('class_date', $date)->get();

        $canceled_th_ids = array();
        foreach ($classes as $class) {
            if ($class->canceled or !$class->is_free() or $class->is_enroll_locked()) {
                $canceled_th_ids[] = $class->teacher_hour;
            }
        }

        $th = $this->teacher_hours()->where('day', $day_of_week)->whereNotIn('id', $canceled_th_ids)->count();
        $th_ot = $this->teacher_hours_ot()->where('day', $date)->whereNotIn('id', $canceled_th_ids)->count();

        return ($th > 0 or $th_ot > 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacher_hours()
    {
        return $this
            ->hasMany(TeacherHour::class, "user_id", "id")
            ->where('active', 1)
            ->where('one_time', 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacher_hours_ot()
    {
        return $this
            ->hasMany(TeacherHour::class, "user_id", "id")
            ->where('active', 1)
            ->where('one_time', 1);
    }

    private function checkDateIntersectsWithVacations($date)
    {
        foreach ($this->vacationsFuture as $vac) {
            $d_s = Carbon::createFromFormat("Y-m-d", $vac->date_start);
            $d_e = Carbon::createFromFormat("Y-m-d", $vac->date_end);
            $d = Carbon::createFromFormat("Y-m-d", $date);
            if ($d >= $d_s and $d <= $d_e) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $date
     * @return array
     */
    public function get_free_classes_on_date($date)
    {
        $date_carbon = Carbon::createFromFormat('Y-m-d', $date);
        $day_of_week = $date_carbon->dayOfWeekIso;

        if ($this->checkDateIntersectsWithVacations($date)) return array();

        // set time to actual time if day in check is today else accept any time
        $now = Carbon::now();
        if ($now->day == $date_carbon->day and $now->month == $date_carbon->month) $now_time = $now->format('H:m:s');
        else $now_time = "00:00:00";

        $tmp = $this->teacher_hours()
            ->where('day', $day_of_week)
            ->where('class_start', '>', $now_time)
            ->orderBy('class_start')
            ->get();
        $tmp_ot = $this->teacher_hours_ot()
            ->where('day', $date)
            ->where('class_start', '>', $now_time)
            ->orderBy('class_start')
            ->get();
        $tmp = $tmp->merge($tmp_ot);
        $teacher_hours = array();

        foreach ($tmp as $i) {
            /**
             * @var TeacherHour $i
             */
            $count = $i->classes_future()->where('class_date', $date)->count();
            if (!$count) {
                $teacher_hours[] = collect(['th' => $i, 'time_locked' => $i->is_enroll_locked($date)]);
            } else {
                $classes = $i->classes_future()->where('class_date', $date)->get();
                foreach ($classes as $c)
                    /**
                     * @var SchoolClass $c
                     */
                    if ($c->is_free() && !$c->canceled) {
                        $tmp = collect(['th' => $c->hour, 'class_instance' => $c, 'time_locked' => $c->is_enroll_locked()]);
                        $teacher_hours[] = $tmp;
                    }
            }
        }

        return $teacher_hours;
    }

    public function get_classes_for_export($month, $year)
    {
        $date = Carbon::createFromFormat("Y-m-d", $year . "-" . $month . "-01");
        $date_s = $date->format("Y-m-d");
        $date->endOfMonth();
        $date_e = $date->format("Y-m-d");

        $tmp = $this->classes_i_past()
            ->where('canceled', 0)
            ->where('teacher_paid', 1)
            ->whereBetween('class_date', [$date_s, $date_e])
            ->orderBy('class_date')->get();

        $res = [];

        foreach ($tmp as $class) {
            if (count($class->students) > 0) {
                $res[] = $class;
            }
        }

        return $res;
    }

    public function vacationsAll()
    {
        return $this->hasMany(TeacherVacation::class, "user_id", "id");
    }

    public function vacationsFuture()
    {
        $date = Carbon::now()->format("Y-m-d");
        return $this->hasMany(TeacherVacation::class, "user_id", "id")
            ->where('date_start', "<=", $date)->orWhere('date_end', '>=', $date);
    }

    public function vacationsPast()
    {
        $date = Carbon::now()->format("Y-m-d");
        return $this->hasMany(TeacherVacation::class, "user_id", "id")
            ->where('date_end', '<', $date);
    }

    public function getCalendarData()
    {

        $data = collect();
        $data->inst = $this;
        $data->languages = $this->teaching;
        $data->classes_all = $this->classes_all;
        $data->classes_future = $this->classes_i_future();
        $data->nearest_meeting = Meeting::teachersNearestMeeting($this->id);
        $data->teacher_hours = $this->teacher_hours_all;
        $data->vacations_future = $this->vacationsFuture;

        return $data;
    }

    public function getAvailableClassRequests()
    {
        $requests = ClassRequest::all();

        $res = array();

        foreach ($requests as $r) {
            $student = $r->student;

            if ($r->language) {
                if (Auth::user()->teaching()->where('languages.id', $r->language)->first())
                    $res[] = $r;
            } else {
                $my_langs = Auth::user()->teaching()->pluck('languages.id');
                $student_langs = $student->studying()->pluck('languages.id');

                if ($student_langs->merge($my_langs))
                    $res[] = $r;
            }
        }

        return $res;
    }

}
