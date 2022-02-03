<?php

namespace App\Models;

use App\Models\User\Student;
use App\Models\User\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ClassFeedback
 * @package App\Models
 *
 * @property Teacher teacher
 * @property Student student
 */
class ClassFeedback extends Model
{
    protected $table = "class_feedback";

    /**
     * @return BelongsTo
     */
    function teacher(){
        return $this->belongsTo(Teacher::class, "teacher_id", "id");
    }

    /**
     * @return BelongsTo
     */
    function student(){
        return $this->belongsTo(Student::class, "student_id", "id");
    }
}
