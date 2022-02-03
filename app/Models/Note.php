<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = "notes";

    public function relatedToLecture(){
        return $this->lecture_id == null;
    }

    public function relatedToStudent(){
        return $this->student_id == null;
    }

    public function isPrivate(){
        return $this->type == 2;
    }


    //relations

    public function author(){
        return $this->belongsTo(User::class, "author_id", "id");
    }

    public function lecture(){
        return $this->belongsTo(SchoolClass::class, "lecture_id", "id");
    }

    public function student(){
        return $this->belongsTo(User::class, "stucent_id", "id");
    }
}
