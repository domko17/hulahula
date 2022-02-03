<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ClassRequest extends Model
{
    protected $table = 'class_request';

    public function student(){
       return $this->hasOne(User::class, 'id', 'student_id');
    }
}
