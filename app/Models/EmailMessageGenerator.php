<?php

namespace App\Models;

use App\Models\User\Student;
use App\Models\User\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailMessageGenerator extends Model
{
    public const DAILY = 1;
    public const HOURLY = 2;
    public const EVERY_FIVE_MINUTES = 3;

    public static function generateEmail($emailType, $data)
    {
        if (strcmp($emailType, "order_paid") == 0) self::generateOrderPaid($data);
        if (strcmp($emailType, "new_order_admin") == 0) self::generateOrderNewAdmin($data);
    }

    private static function generateOrderPaid($data)
    {
        $recipients = $data['recipients'];
        $subject = __('email.order_paid_subject');
        $module = "order_paid";

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    private static function generateOrderNewAdmin($data)
    {
        $recipients = $data['recipients'];
        $subject = __('email.order_add_admin_subject');
        $module = "new_order_admin";

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    // ----------------------------------------------------------------------

    public function autoGenerateEmails($mode)
    {
        if ($mode == self::DAILY) {
            $this->checkNoOrderPackageAfterFirstLecture();
            $this->checkOneWeekWithoutStudy();
            $this->checkTwoWeeksWithoutStudy();
            $this->checkThreeWeeksWithoutStudyNoClasses();
            $this->checkThreeWeeksWithoutStudyHasClasses();
            $this->checkTeacherEvaluateStudent();
        } else if ($mode == self::EVERY_FIVE_MINUTES) {
            $this->checkFirstLectureFinished();
            $this->checkAfterThirdClass();
            $this->checkStartingClassesSoon();
        }
    }

    /**
     * V prípade že si študent neobjedná balík hodín po absolvovaní prvej(ukazkovej) hodiny,
     * nech mu po piatom dni príde informácia, že môže ohodnotiť lektora.
     * Ak si objedná balík hodín, nechodí mu notifikácia.
     */
    private function checkNoOrderPackageAfterFirstLecture()
    {
        $students = User::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var Student $student */
            if (!$student->is_student()) continue;
            if ($this->checkDoubleSendProtection('no_order_package_after_fc', $student->id)) continue;

            //if already has some orders, skip user
            if ($student->packageOrders()->count()) continue;

            $classes = ClassStudent::where('student_id', $student->id)->get();
            if (count($classes) != 1) continue;

            $sc = SchoolClass::find($classes[0]->class_id);
            if (!$sc) continue;
            $now = Carbon::now();
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $sc->class_date . " " . $sc->hour->class_end);
            // if class is in the past and it is 5th day since the class
            if ($now > $date and strcmp($now->addDays(5)->format('Y-m-d'), $date->format('Y-m-d')) == 0) {
                $this->generateNoOrderPackageAfterFirstLecture($student->id);
                $this->setDoubleSendProtection('no_order_package_after_fc', $student->id);
            }
        }
    }

    private function checkDoubleSendProtection($module, $user_id)
    {
        $data = (array)Option::getOption(Option::DOUBLE_EMAIL_PROTECTION);
        if (isset($data[$module])) {
            if (in_array($user_id, $data[$module]))
                return true;
        }
        return false;
    }

    /**
     * @param $student_id
     */
    public function generateNoOrderPackageAfterFirstLecture($student_id)
    {
        /** @var Student $student */
        $student = Student::find($student_id);
        $can_feedback = $student->canDoFeedback();
        $feedback_link = route('dashboard');
        if ($can_feedback) {
            $feedback_link = route('feedback.createFeedback', $can_feedback->id);
        }

        $recipients = array($student->email);
        $subject = __('email.no_order_package_after_first_class_subject');
        $module = "no_order_package_after_fc";
        $data = [];
        $data['student'] = $student_id;
        $data['f_link'] = $feedback_link;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    private function setDoubleSendProtection($module, $user_id)
    {
        $data = (array)Option::getOption(Option::DOUBLE_EMAIL_PROTECTION);

        if (isset($data[$module])) {
            if (!in_array($user_id, $data[$module]))
                $data[$module][] = $user_id;
        } else {
            $data[$module] = array();
            $data[$module][] = $user_id;
        }

        Option::setOption(Option::DOUBLE_EMAIL_PROTECTION, $data);
    }

    /**
     * Týždeň po poslednej absolvovanej hodine a je jedno či ich má zaplatené alebo nie
     */
    private function checkOneWeekWithoutStudy()
    {
        $students = Student::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var Student $student */
            if (!$student->is_student()) continue;

            $classes_future = $student->classes_future;
            if (count($classes_future)) continue;

            $last_class = $student->classes_past()->orderByDesc('class_date')->first();
            if (!$last_class) continue;
            $now = Carbon::now();
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $last_class->class_date . " " . $last_class->hour->class_end);
            $tmp = Carbon::createFromTimestamp($now->timestamp - $date->timestamp);
            if ($tmp->day == 7 and $tmp->month == 1 and $tmp->yearIso == 1970) {
                $this->generateOneWeekWithoutStudy($student->id);
            }
        }
    }

    public function generateOneWeekWithoutStudy($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.one_week_no_study_subject');
        $module = "one_week_no_study";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * Dva týždne nemal hodinu
     */
    private function checkTwoWeeksWithoutStudy()
    {
        $students = Student::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var Student $student */
            if (!$student->is_student()) continue;

            $classes_future = $student->classes_future;
            if (count($classes_future)) continue;

            $last_class = $student->classes_past()->orderByDesc('class_date')->first();
            if (!$last_class) continue;
            $now = Carbon::now();
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $last_class->class_date . " " . $last_class->hour->class_end);
            $tmp = Carbon::createFromTimestamp($now->timestamp - $date->timestamp);
            if ($tmp->day == 14 and $tmp->month == 1 and $tmp->yearIso == 1970) {
                $this->generateTwoWeeksWithoutStudy($student->id);
            }
        }
    }

    public function generateTwoWeeksWithoutStudy($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.two_weeks_no_study_subject');
        $module = "two_weeks_no_study";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * Už tri týždne nemal hodinu, nemá predplatené hodiny
     */
    private function checkThreeWeeksWithoutStudyNoClasses()
    {
        $students = Student::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var Student $student */
            if (!$student->is_student()) continue;

            $cp = $student->currentPackage;
            if ($cp and (in_array($cp->state, [1, 2]) or $cp->renewal_package_id)) continue;

            $classes_future = $student->classes_future;
            if (count($classes_future)) continue;

            $last_class = $student->classes_past()->orderByDesc('class_date')->first();
            if (!$last_class) continue;
            $now = Carbon::now();
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $last_class->class_date . " " . $last_class->hour->class_end);
            $tmp = Carbon::createFromTimestamp($now->timestamp - $date->timestamp);
            if ($tmp->day == 21 and $tmp->month == 1 and $tmp->yearIso == 1970) {
                $this->generateThreeWeeksWithoutStudyNoClasses($student->id);
            }
        }
    }

    // ----------------------------------------------------------------------

    public function generateThreeWeeksWithoutStudyNoClasses($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.three_weeks_no_study_subject');
        $module = "three_weeks_no_study_no_classes";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * Tri týždne nemal hodinu, ale má nejaké predplatené
     */
    private function checkThreeWeeksWithoutStudyHasClasses()
    {
        $students = Student::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var Student $student */
            if (!$student->is_student()) continue;

            $cp = $student->currentPackage;
            if (!($cp and (in_array($cp->state, [1, 2]) or $cp->renewal_package_id))) continue;

            $classes_future = $student->classes_future;
            if (count($classes_future)) continue;

            $last_class = $student->classes_past()->orderByDesc('class_date')->first();
            if (!$last_class) continue;
            $now = Carbon::now();
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $last_class->class_date . " " . $last_class->hour->class_end);
            $tmp = Carbon::createFromTimestamp($now->timestamp - $date->timestamp);
            if ($tmp->day == 21 and $tmp->month == 1 and $tmp->yearIso == 1970) {
                $this->generateThreeWeeksWithoutStudyHasClasses($student->id);
            }
        }
    }


    // ----------------------------------------------------------------------

    public function generateThreeWeeksWithoutStudyHasClasses($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.three_weeks_no_study_subject');
        $module = "three_weeks_no_study_has_classes";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * Po absolvovaní prvej hodiny v ten istý deň
     */
    private function checkFirstLectureFinished()
    {
        $students = User::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var User $student */
            if (!$student->is_student()) continue;
            if ($this->checkDoubleSendProtection('first_lecture_finished', $student->id)) continue;

            $classes = ClassStudent::where('student_id', $student->id)->get();
            if (count($classes) != 1) continue;

            $sc = SchoolClass::find($classes[0]->class_id);
            if (!$sc) continue;
            $now = Carbon::now();
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $sc->class_date . " " . $sc->hour->class_end);
            if (strcmp($now->format('Y-m-d'), $date->format('Y-m-d')) == 0 and $now > $date) {
                $this->generateFirstLectureFinished($student->id);
                $this->setDoubleSendProtection('first_lecture_finished', $student->id);
            }
        }
    }

    /**
     * @param $student_id
     */
    public function generateFirstLectureFinished($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.after_first_lecture_subject');
        $module = "after_first_lecture";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * V deň po absolvovanej tretej hodine
     */
    private function checkAfterThirdClass()
    {
        $students = User::where('active', 1)->get();
        foreach ($students as $student) {
            /** @var Student $student */
            if (!$student->is_student()) continue;
            if ($this->checkDoubleSendProtection('after_third_class', $student->id)) continue;

            $classes = ClassStudent::where('student_id', $student->id)->get();
            $past_classes = 0;
            foreach ($classes as $class) {
                $sc = SchoolClass::find($class->class_id);
                if (!$sc) continue;
                $now = Carbon::now();
                $date = Carbon::createFromFormat("Y-m-d H:i:s", $sc->class_date . " " . $sc->hour->class_end);
                if ($now > $date) $past_classes++;
            }

            if ($past_classes == 3) {
                $this->generateAfterThirdClass($student->id);
                $this->setDoubleSendProtection('after_third_class', $student->id);
            }
        }
    }

    /**
     * @param $student_id
     */
    public function generateAfterThirdClass($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.after_third_class_subject');
        $module = "after_third_class";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * check for classes that are starting soon and generate a notification for students
     */
    private function checkStartingClassesSoon()
    {
        $classes = SchoolClass::where('class_date', Carbon::now()->format('Y-m-d'))->where('canceled', 0)->get();
        $now = Carbon::now();
        foreach ($classes as $c) {
            /**
             * @var $c SchoolClass
             */
            $students = $c->students;
            if (!count($students)) continue;

            $c_time = $c->getClassDateTime();
            $time_diff = $c_time->getTimestamp() - $now->getTimestamp();

            if ($time_diff > 0 and $time_diff < 3600000) {
                foreach ($students as $s) {
                    $actual_student_id = $s->student_id;
                    if ($this->checkDoubleSendProtection('class_soon_' . $c->id, $actual_student_id)) continue;
                    $this->generateStartingClassesSoon($actual_student_id, $c->id);
                    $this->setDoubleSendProtection('class_soon_' . $c->id, $actual_student_id);
                }
            }
        }
    }

    public function generateStartingClassesSoon($student_id, $class_id)
    {
        $student = Student::findOrFail($student_id);

        $recipients = array($student->email);
        $subject = __('email.class_soon_subject');
        $module = "class_soon";
        $data = [];
        $data['student'] = $student_id;
        $data['class'] = $class_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * @param $student_id
     */
    public function generateAfterFirstOrder($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.after_first_order_subject');
        $module = "after_first_order";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * @param $student_id
     */
    public function generateAfterThirdOrder($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.after_third_order_subject');
        $module = "after_third_order";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * @param $student_id
     */
    public function generatePackageNearEnd($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.package_ending_subject');
        $module = "package_ending";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * @param $student_id
     */
    public function generatePackageExpired($student_id)
    {
        $student = Student::find($student_id);

        $recipients = array($student->email);
        $subject = __('email.package_expired_subject');
        $module = "package_expired";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    private function checkTeacherEvaluateStudent()
    {
        $students = User::where('active', 1)->get();
        foreach ($students as $s) {
            /**
             * @var User $s
             */
            if (!$s->is_student()) continue;

            $students_classes = Student::find($s->id)->classes_past;
            $classes_teachers = array();
            foreach ($students_classes as $sc) {
                if (!in_array($sc->hour->user_id, array_keys($classes_teachers))) {
                    $classes_teachers[$sc->hour->user_id] = 1;
                } else {
                    $classes_teachers[$sc->hour->user_id] = $classes_teachers[$sc->hour->user_id] + 1;
                }

            }
            foreach ($classes_teachers as $ct => $class_count) {
                if ($class_count >= 3 and $class_count <= 6 and !$this->checkDoubleSendProtection('evaluate_notif_' . $ct . '_' . $s->id, $ct)){
                    $this->generateTeacherEvaluateStudent($ct, $s->id);
                    $this->setDoubleSendProtection('evaluate_notif_' . $ct . '_' . $s->id, $ct);
                }
            }
        }
    }

    public function generateTeacherEvaluateStudent($teacher_id, $student_id){
        $teacher = Teacher::find($teacher_id);

        $recipients = array($teacher->email);
        $subject = __('email.evaluate_student_subject');
        $module = "evaluate_student";
        $data = [];
        $data['student'] = $student_id;

        EmailMessage::addMailToQueue($recipients, $subject, $module, $data);
    }

    /**
     * TODO Keď hodina má nejakú pravidelnosť ale človek zabudne že by sa mal učiť
     */
    private function checkRegularClasses()
    {
        // todo
    }
}
