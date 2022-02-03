<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Option
 * @package App\Models
 */
class Option extends Model
{
    protected $table = "options";

    public const DOUBLE_EMAIL_PROTECTION = "double_email_protection";

    /**
     * @param $name
     * @return array|mixed
     */
    public static function getOption($name)
    {
        $opt = Option::where('option_name', $name)->first();
        if ($opt) {
            return json_decode($opt->data);
        }
        return [];
    }

    /**
     * @param $name
     * @param $data
     */
    public static function setOption($name, $data)
    {
        $opt = Option::where('option_name', $name)->first();
        if ($opt) {
            $opt->data = json_encode($data);
            $opt->save();
        } else {
            $no = new Option();
            $no->option_name = $name;
            $no->data = json_encode($data);
            $no->save();
        }
    }
}
