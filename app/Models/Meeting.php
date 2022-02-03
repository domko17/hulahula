<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Meeting extends Model
{
    protected $table = 'meetings';

    /**
     * @return bool
     */
    public function is_past()
    {
        $day = Carbon::createFromFormat("Y-m-d H:i:m", $this->start);
        return $day < Carbon::now();
    }

    /**
     * @param $tid
     * @return MeetingMember|null
     */
    public static function teachersNearestMeeting($tid)
    {
        $fm = (new Meeting)->futureMeetings();
        foreach ($fm as $m){
            if(MeetingMember::where('user_id', $tid)->where('meeting_id', $m->id)->first()){
                return $m;
            }
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function futureMeetings()
    {
        return Meeting::where('start', '>=', Carbon::now()->format("Y-m-d H:i:s"))->orderByDesc('start')->get();
    }

    //----------

    /**
     * @return BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, "language_id", "id");
    }

    /**
     * @return HasManyThrough
     */
    public function members()
    {
        return $this->hasManyThrough(
            User::class,
            MeetingMember::class,
            "meeting_id", //foreign key on middle table
            "id", //foreign key on final table
            "id", //local key on base table
            "user_id"//local key on middle table
        );
    }
}
