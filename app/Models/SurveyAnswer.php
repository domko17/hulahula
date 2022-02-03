<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table = "survey_question_answers";

    //rels

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
