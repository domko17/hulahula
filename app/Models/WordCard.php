<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordCard extends Model
{
    protected $table = "word_cards";

    public function getImage(){
        if ($this->image){
            return asset("images/word_cards/".$this->language->id."/".$this->image);
        }
        return asset("images/app/Placeholders/profile_male.png");
    }

    public function language(){
        return $this->belongsTo(Language::class, "language_id", "id");
    }

    public function language_level_text(){
        switch ($this->language_level){
            case 1: return "A1";
            case 2: return "A2";
            case 3: return "B1";
            case 4: return "B2";
            case 5: return "C1";
            default: return "ERR";
        }
    }
}
