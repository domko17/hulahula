<?php


namespace App\Models;

use App\Models\User\TeacherHour;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class TeacherVacation
 * @package App\Models
 *
 * @property integer id
 * @property integer user_id
 * @property integer teacher_hours_id
 * @property integer zoom_meeting_id
 * @property dateTime start_datetime
 * @property integer duration
 * @property string password
 * @property string h323_password
 * @property string pstn_password
 * @property string encrypted_password
 * @property integer type
 * @property string join_url
 * @property string start_url
 * @property integer end_of_meeting
 * @property ZoomMeeting teacher
 */
class ZoomMeeting extends Model
{
    protected $table = 'zoom_meetings';

    public function getUserZoomMeeting()
    {
        return $this->where('user_id', 150)->whereDate('start_datetime', now())->whereTime('start_datetime', '>', now())->orderBy('start_datetime','ASC')->first();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
