<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $table = "survey_questions";


    //rels

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id', 'id');
    }

    public function responders(){
        return $this->hasManyThrough(
            User::class,
            SurveyAnswer::class,
            'question_id',      //foreign key on middle table
            'id',             //foreign key on final table
            'id',               //local key on base table
            'user_id'     //local key on middle table
        );
    }

    public function translations()
    {
        return $this->hasMany(SurveyQuestionTranslation::class, 'question_id', 'id');
    }

}
