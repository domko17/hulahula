<?php

namespace App\Models\User;

use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TeacherHour extends Model {
	protected $table = "teacher_hours";

	public static function deactivate($id) {
		$item = self::find($id);
		$item->active = 0;
		$item->save();

		return true;
	}

	public function getDayName() {
		switch ($this->day) {
		case 1:
			return __('general.monday');
		case 2:
			return __('general.tuesday');
		case 3:
			return __('general.wednesday');
		case 4:
			return __('general.thursday');
		case 5:
			return __('general.friday');
		case 6:
			return __('general.saturday');
		case 7:
			return __('general.sunday');
		default:
			return "ERROR";
		}
	}

	public function checkForConflict() {
		$teacher = Teacher::find($this->user_id);

		$teacher_hours = $teacher->teacher_hours()->where('id', "!=", $this->id)->where('active', 1)->get();

		foreach ($teacher_hours as $th) {
			if ($this->intersects($th)) {
				//dump($th);
				//dump($this);
				//dd("conflict");
				return true;
			}
		}
		//dd("OK");
		return false;
	}

	private function intersects(TeacherHour $other) {
		if ($this->day != $other->day) {
			return false;
		}

		$this_start = Carbon::createFromFormat("H:i", $this->class_start);
		$this_end = Carbon::createFromFormat("H:i", $this->class_end);
		$other_start = Carbon::createFromFormat("H:i:s", $other->class_start);
		$other_end = Carbon::createFromFormat("H:i:s", $other->class_end);

		if ($other_start > $this_end or $other_end < $this_start) {
			return false;
		}

		return true;
	}

	// Relations

	public function language() {
		return $this->belongsTo('App\Models\Language', 'language_id', 'id');
	}

	public function teacher() {
		return $this->belongsTo('App\User', "user_id", "id");
	}

	public function classes_all() {
		return $this->hasMany(SchoolClass::class, 'teacher_hour', 'id');
	}

	public function classes_past() {
		return $this->hasMany(SchoolClass::class, 'teacher_hour', 'id')
			->where('class_date', '<', Carbon::now()->format('Y-m-d'))
			->orderBy('class_date');

	}

	public function classes_future() {
		return $this->hasMany(SchoolClass::class, 'teacher_hour', 'id')
			->where('class_date', '>=', Carbon::now()->format('Y-m-d'))
			->orderBy('class_date');
	}

    public function is_enroll_locked($date): bool
    {
        $teacher_lock_time = $this->teacher->profile->time_before_class;
        if(!$teacher_lock_time) return false;
        $class_start = Carbon::createFromFormat('Y-m-d H:i:s', $date . '' . $this->class_start)->subHours($teacher_lock_time);
        $now = Carbon::now();
        return $now >= $class_start;
    }

}
