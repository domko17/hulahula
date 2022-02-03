<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageOrder extends Model
{
    protected $table = "package_order";

    public function getNameByType($type_id){
        switch ($type_id){
            case 1: return "SMART";
            case 2: return "PREMIUM INDIVIDUAL";
            case 3: return "EXTRA";
            case 99: return "START";
            default: return "UNKNOWN";
        }
    }

    public function getName(){
        return $this->getNameByType($this->package_id);
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
