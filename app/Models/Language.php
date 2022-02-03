<?php

namespace App\Models;

use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Models\User\TeacherHour;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = "languages";

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, "teacher", "language_id", 'user_id')->where('active', 1);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, "student", "language_id",'user_id')->where('active', 1);
    }

    public function classes_i_future()
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            TeacherHour::class,
            'language_id',
            "teacher_hour",
            "id",
            "id"
        )->where('class_date', '>=', Carbon::now()->format('Y-m-d'));
    }

    public function classes_c_future(){
        return $this->hasManyThrough(
            SchoolClass::class,
            CollectiveHour::class,
            'language_id',
            "collective_hour",
            "id",
            "id"
        )->where('class_date', '>=', Carbon::now()->format('Y-m-d'));
    }

    public function word_cards(){
        return $this->hasMany(WordCard::class,"language_id","id");
    }

    public function material(){
        return $this->hasMany(Material::class,'language_id','id');
    }
}
