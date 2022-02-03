<?php

namespace App\Models;

use App\Models\User\Teacher;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TeacherVacation
 * @package App\Models
 *
 * @property integer id
 * @property integer user_id
 * @property string date_start
 * @property string date_end
 * @property string description
 *
 * @property Teacher teacher
 */
class TeacherVacation extends Model
{
    protected $table = "teacher_vacation";


    /**
     * @return BelongsTo
     */
    public function teacher(){
        return $this->belongsTo(Teacher::class, "user_id", "id");
    }
}
