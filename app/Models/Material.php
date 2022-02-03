<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = "materials";

    public function get_type_name(){
        switch ($this->type){
            case 1:
                return __('general.url_link');
            case 2:
                return "YouTube Video";
            case 3:
                return __('general.document');
            case 4:
                return "**audio";
            default:
                return "test";
        }
    }

    // relations

    public function language(){
        return $this->belongsTo(Language::class, "language_id", "id");
    }

    public function user(){
        return $this->belongsTo(User::class, "added_by", "id");
    }
}
