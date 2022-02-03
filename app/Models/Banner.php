<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner_posts';

    public function getBgColorClass(){
        switch($this->bckg_colour){
            case 1:return "bg-gradient-primary";
            case 2:return "bg-gradient-info";
            case 3:return "bg-gradient-warning";
            case 4:return "bg-gradient-danger";
            case 5:return "bg-gradient-success";
            case 6:return "bg-gradient-silverish";
            case 7:return "bg-gradient-dark";
            default: return "bg-gradient-light";
        }
    }

    public function visibility(){
        return $this->hasOne(BannerVisibility::class, 'banner_id','id');
    }
}
