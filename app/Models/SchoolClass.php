<?php

namespace App\Models;

use App\Models\User\Student;
use App\Models\User\Teacher;
use App\Models\User\TeacherHour;
use App\User;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

/**
 * Class SchoolClass
 * @package App\Models
 */
class SchoolClass extends Model
{
    protected $table = "classes";

    /**
     * @return string
     */
    public function get_class_difficulty_code()
    {
        switch ($this->hour->class_difficulty) {
            case 1:
                return "A1";
            case 2:
                return "A2";
            case 3:
                return "B1";
            case 4:
                return "B2";
            case 5:
                return "C1";
            default:
                return "UNW";
        }
    }

    /**
     * @return bool
     */
    public function is_free()
    {
        $students = $this->students;
        if (count($students) == 0) return true;

        if (count($students) == 1) {
            if ($students[0]->user->currentPackage and $students[0]->user->currentPackage->type == 1 and $students[0]->user->id != Auth::id()) return true;
        }

        return false;
    }

    /**
     * @param int $student_id - student's ID
     * @param UserPackage|null $package - [optional] student's package, if null, student will be signed for class no matter of his package
     * @param int $language_id - [optional] language of class
     */
    public function enrollStudent(int $student_id, UserPackage $package = null, int $language_id = 0)
    {
        $class_student = new ClassStudent();
        $class_student->student_id = $student_id;
        $class_student->class_id = $this->id;

        if ($package)
            $class_student->package_used = $package->type;

        if ($language_id)
            $class_student->language_id = $language_id;

        $class_student->save();

        if ($package)
            $package->useLecture($this->id);
    }

    /**
     * @return HasMany
     */
    public function students()
    {
        return $this->hasMany(ClassStudent::class, "class_id", "id");
    }

    /**
     * @return BelongsTo
     */
    public function hour()
    {
        $class = $this->teacher_hour ? TeacherHour::class : CollectiveHour::class;
        $key = $this->teacher_hour ? "teacher_hour" : "collective_hour";

        return $this->belongsTo(
            $class,
            $key,
            "id"
        );
    }

    /**
     * @param $student_id
     * @return mixed
     */
    public function is_student_attending($student_id)
    {
        $student = Student::find($student_id);
        $res = $student->classes_all()->where('class_id', $this->id)->first();

        return $res;
    }

    /**
     * @param $student_id
     * @return bool
     */
    public function can_student_reschedule($student_id)
    {

        $count = 0;
        $tmp_class = $this;
        $visited = [];
        while ($tmp_res = $tmp_class->has_student_rescheduled($student_id, $visited)) {
            $tmp_class = $tmp_res[0];
            $count++;
            if ($tmp_res[1]) $visited[] = $tmp_res[1]->id;
        }
        if ($count >= 2) return false;
        return true;
    }

    /**
     * @param $student_id
     * @param array $visited_rows
     * @return array|bool
     */
    public function has_student_rescheduled($student_id, $visited_rows = [])
    {
        $tmp = ClassReschedule::where("class_to", $this->id)
            ->where('student_id', $student_id)
            ->whereNotIn('id', $visited_rows)
            ->orderByDesc('id')
            ->first();
        if ($tmp) {
            $res = SchoolClass::find($tmp->class_from);
            return [$res, $tmp];
        }
        return false;
    }

    /**
     * @return array
     */
    public function can_student_reschedule_to()
    {
        return [];
        //$lang = $this->language;
        /**
         * @var User $teacher
         */
        /*$teacher = $this->teacherHour->teacher;

        $tmp = $lang->classes_i_future()->where('classes.id', '!=', $this->id)->orderBy('classes.class_date')->get();
        $res = [];

        foreach ($tmp as $class) {
            if ($class->is_free()) $res[] = $class;
        }


        return $res;*/
    }

    /**
     * @param $student_id
     * @return bool
     */
    public function checkForStudentCollision($student_id)
    {
        $student = Student::find($student_id);

        //dump($student);

        $day_classes = $student->classes_future()->where('class_date', $this->class_date)->get();

        foreach ($day_classes as $class) {
            //dump("checking for intersection with:", $class);
            if ($this->intersect($class)) {
                //dd("intersects! ");
                return true;
            }
        }
        //dd($day_classes);
        return false;
    }

    /**
     * @param SchoolClass $other
     * @return bool
     */
    private function intersect(SchoolClass $other)
    {
        $time_start_this = Carbon::createFromFormat("Y-m-d H:i:s", $this->class_date . " " . $this->hour->class_start);
        $time_end_this = Carbon::createFromFormat("Y-m-d H:i:s", $this->class_date . " " . $this->hour->class_end);
        $time_start_other = Carbon::createFromFormat("Y-m-d H:i:s", $other->class_date . " " . $other->hour->class_start);
        $time_end_other = Carbon::createFromFormat("Y-m-d H:i:s", $other->class_date . " " . $other->hour->class_end);

        if ($time_start_other > $time_end_this or $time_end_other < $time_start_this) return false;

        return true;
    }

    public function checkForTeacherCollision($individual = 1, $collective = 0)
    {

        $teacher = Teacher::find($this->hour->teacher->id);

        if ($individual) {
            $classes = $teacher->classes_i_future()->where('canceled', 0)->get();

            foreach ($classes as $c) {
                if ($this->intersect($c)) {
                    //dd("NOK");
                    return true;
                }
            }
        }

        if ($collective) {
            $classes = $teacher->classes_c_future()->where('canceled', 0)->get();

            foreach ($classes as $c) {
                if ($this->intersect($c)) {
                    //dd("NOK");
                    return true;
                }
            }
        }

        //dd("OK");

        return false;
    }

    /**
     * @return bool
     */
    public function is_past()
    {

        $hour = $this->teacher_hour ? $this->teacherHour()->getResults() : $this->collectiveHour()->getResults();

        $date = Carbon::createFromFormat("Y-m-d H:i:s", $this->class_date . " " . $hour->class_end);
        return $date < Carbon::now();
    }

    /**
     * @return BelongsTo
     */
    public function teacherHour()
    {
        return $this->belongsTo("App\Models\User\TeacherHour", "teacher_hour", "id");
    }

    /**
     * @return BelongsTo
     */
    public function collectiveHour()
    {
        return $this->belongsTo("App\Models\CollectiveHour", "collective_hour", "id");
    }

    /**
     * @return HasOneThrough
     */
    public function language()
    {
        $class = $this->teacher_hour ? TeacherHour::class : CollectiveHour::class;
        $key = $key = $this->teacher_hour ? "teacher_hour" : "collective_hour";

        return $this->hasOneThrough(
            Language::class,
            $class,
            "id",
            "id",
            $key,
            "language_id"
        );
    }

    /**
     * @return HasManyThrough
     */
    public function material()
    {
        return $this->hasManyThrough(
            Material::class,
            ClassMaterial::class,
            'class_id',             //foreign key on middle table
            'id',                 //foreign key on final table
            'id',                   //local key on base table
            'material_id'     //local key on middle table
        );
    }

    public function packageUsed(): int
    {
        $students = $this->students;
        if (!count($students)) return -1;

        return $students[0]->getUsedPackage();
    }

    public function is_enroll_locked(): bool
    {
        $teacher_lock_time = $this->hour->teacher->profile->time_before_class;
        if(!$teacher_lock_time) return false;
        $class_start = Carbon::createFromFormat('Y-m-d H:i:s', $this->class_date . '' . $this->hour->class_start)->subHours($teacher_lock_time);
        $now = Carbon::now();
        return $now >= $class_start;
    }

    public function getClassDateTime(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->class_date." ".$this->hour->class_start);
    }

}
