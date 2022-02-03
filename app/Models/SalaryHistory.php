<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    protected $table = "salary_history";

    public function user(){
        return $this->belongsTo(User::class, "teacher_id", "id");
    }
}
