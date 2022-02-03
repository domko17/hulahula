<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = "class_student";

    //relations

    public function user(){
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function getUsedPackage(){
        return $this->package_used;
    }
}
