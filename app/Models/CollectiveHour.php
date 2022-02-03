<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CollectiveHour extends Model
{
    protected $table = "collective_hours";

    public static function deactivate($id)
    {
        $item = self::find($id);
        $item->active = 0;
        $item->save();

        return true;
    }

    public function getDayName()
    {
        switch ($this->day) {
            case 1:
                return __('general.monday');
            case 2:
                return __('general.tuesday');
            case 3:
                return __('general.wednesday');
            case 4:
                return __('general.thursday');
            case 5:
                return __('general.friday');
            case 6:
                return __('general.saturday');
            case 7:
                return __('general.sunday');
            default:
                return "ERROR";
        }
    }

    // Relations

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id', 'id');
    }

    public function teacher(){
        return $this->belongsTo('App\User', "user_id", "id");
    }

    public function sub_teacher(){
        return $this->belongsTo('App\User', "sub_user_id", "id");
    }

    public function classes_future()
    {
        return $this->hasMany(SchoolClass::class, 'collective_hour', 'id')
            ->where('class_date', '>=', Carbon::now())
            ->orderBy('class_date');
    }
}
