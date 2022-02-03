<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    protected $table = 'user_packages';

    public function getNameByType($type_id)
    {
        return (new PackageOrder())->getNameByType($type_id);
    }

    public function getName()
    {
        return $this->getNameByType($this->type);
    }

    public function useLecture($lecture)
    {
        if ($this->classes_left == 0) return false;

        $is_ending_mail = $this->state;

        $this->classes_left -= 1;
        if ($this->classes_left == 0) {
            $this->last_class_id = $lecture;
        }
        if ($this->type == 2) {
            if ($this->classes_left / 10 <= 0.35) $this->state = 2; //change state to "ending soon" if only less than 35% classes are left -- PREMIUM
        } else if ($this->type == 3) {
            $this->state = 2; // change to "ending soon" status after enrolling for EXTRA package
        }

        //if was change to almost ending
        if ($is_ending_mail != 2 and $this->state == 2) {
            //and package do not have renewal set
            if (!$this->renewal_package_id) //notify user about package ending
                (new EmailMessageGenerator)->generatePackageNearEnd($this->user_id);
        }

        $this->save();

        return true;
    }

    public function unuseLecture()
    {
        $this->classes_left += 1;

        if ($this->type == 2) {
            if ($this->classes_left / 10 > 0.35) $this->state = 1;
        } else if ($this->type == 3) {
            $this->state = 2;
        }
        $this->save();

        return true;
    }

    public function lastLecture(){
        return $this->hasOne(SchoolClass::class,'id', 'last_class_id');
    }
}
