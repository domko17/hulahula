<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StarOrder
 * @package App\Models
 *
 * @property User user;
 */
class StarOrder extends Model
{
    protected $table = "star_order";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
