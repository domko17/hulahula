<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "internal_message";

    public function is_read(){
        return $this->read;
    }

    public function set_read(){
        $this->read = 1;
        $this->save();
    }

    //relations

    public function sender(){
        return $this->belongsTo(User::class, "sender_id", "id");
    }

    public function reciever(){
        return $this->belongsTo(User::class, "reciever_id", "id");
    }
}
