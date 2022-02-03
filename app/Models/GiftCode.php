<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiftCode extends Model
{
    protected $table = "giftcodes";

    //relations


    public function language(){
        return $this->belongsTo(Language::class,"language_id","id");
    }

    public function redeemer(){
        return $this->belongsTo(User::class, "used_by", "id");
    }
}
