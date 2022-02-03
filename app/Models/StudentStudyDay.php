<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StudentStudyDay extends Model
{
    protected $table = "student_study_days";


    /**
     * @param $id
     * @return Collection
     */
    public static function userTotalStudy($id)
    {
        $tmp = self::where("student_id", $id)->get();
        $res = collect();
        $res->days_count = count($tmp);
        $res->hours = 0;

        foreach ($tmp as $item) {
            $res->hours += ($item->hours < 5 ? $item->hours : round($item->hours / 60, 1));
        }

        return $res;
    }

    public static function userStudyHoursBetweenDays($id, Carbon $day_start, Carbon $day_end)
    {
        $tmp = self::userStudyBetweenDays($id, $day_start, $day_end);
        $res = collect();
        $res->days_count = count($tmp);
        $res->hours = 0;

        foreach ($tmp as $item) {
            $res->hours += intval($item->hours);
        }

        return $res;
    }

    /**
     * @param $id
     * @return int
     */
    public static function userConsecutiveStudyDays($id)
    {
        $date = Carbon::now()->startOfDay();
        $res = 0;

        if (self::where('student_id', $id)
            ->where('created_at', ">", $date->format("Y-m-d H:i:s"))
            ->first()) {
            $res++;
        }

        $date = Carbon::now()->subDay();

        while (self::where('student_id', $id)
            ->where('created_at', ">", $date->format("Y-m-d H:i:s"))
            ->where('created_at', "<", $date->subDay()->format("Y-m-d H:i:s"))
            ->first()) {
            $res++;
        }

        return $res;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function userConfirmedToday($id)
    {
        $date = Carbon::now()->startOfDay();
        return self::where('student_id', $id)
            ->where('created_at', ">", $date->format("Y-m-d H:i:s"))
            ->first();
    }

    /**
     * @param $id
     * @param Carbon $day
     * @return mixed
     */
    public static function userStudyByDay($id, Carbon $day)
    {

        return self::where('student_id', $id)
            ->where('created_at', ">", $day->startOfDay()->format("Y-m-d H:i:s"))
            ->where('created_at', '<', $day->endOfDay()->format("Y-m-d H:i:s"))
            ->first();
    }

    public static function userStudyBetweenDays($id, Carbon $day_start, Carbon $day_end)
    {

        return self::where('student_id', $id)
            ->where('created_at', ">", $day_start->startOfDay()->format("Y-m-d H:i:s"))
            ->where('created_at', '<', $day_end->endOfDay()->format("Y-m-d H:i:s"))
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function userAllStudyDay($id)
    {
        return self::where('student_id', $id)->get();
    }
}
