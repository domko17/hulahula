<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = "user_profile";

    protected $fillable =[
        "user_id",
        "first_name",
        "last_name"
    ];

    public function getFullName()
    {
        $res = "";

        if ($this->title_before){
            $res .= $this->title_before;
            $res .= " ";
        }

        $res .= $this->first_name . " " . $this->last_name;

        if ($this->title_after){
            $res .= ", ";
            $res .= $this->title_after;
        }

        return $res;

    }

    public function getProfileImage(){
        if ($this->image){
            return asset('images/profiles/'.$this->user_id.'/'.$this->image);
        }
        return asset('images/app/Placeholders/profile_male.png');
    }

    //relations
    //-----------------------------------

    public function user()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }
}
