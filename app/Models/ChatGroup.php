<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $table = 'message_group';

    public function lastMessage()
    {
        return $this->messages()->orderByDesc('id')->first();
    }

    //----

    public function messages()
    {
        return $this->hasMany(Message::class, "group_id", "id")
            ->orderByDesc('created_at');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, "admin_id", "id");
    }

    public function members()
    {
        return $this->hasManyThrough(
            User::class,
            ChatGroupMember::class,
            "group_id", //foreign key on middle table
            "id", //foreign key on final table
            "id", //local key on base table
            "user_id")//local key on middle table
            ->where('user_message_group.active', 1);
    }
}
