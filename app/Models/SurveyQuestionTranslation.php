<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestionTranslation extends Model
{
    protected $table = "survey_question_translations";

    //rels

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
