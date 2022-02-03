<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Message;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailMessage extends Model
{
    const EMAIL_SENT = 1;

    protected $table = "email_messages";
    protected $fillable = [
        "recipients",
        "subject",
        "status",
        "module",
        "data",
        "send_time",
    ];
    private $content;

    public static function allMessagesByDate()
    {
        return EmailMessage::orderByDesc("send_time")->get();
    }

    /**
     * @param array $recipients
     * @param string $sub
     * @param string $module
     * @param array $data
     */
    public static function addMailToQueue($recipients, $sub, $module, $data = [])
    {
        $em = new self();

        $em->recipients = json_encode($recipients);
        $em->subject = $sub;
        $em->status = 0;
        $em->module = $module;
        $em->data = json_encode($data);

        $em->save();
    }

    public static function sendOne($eid)
    {
        $em = EmailMessage::find($eid);

        if (!$em) return false;

        try {
            (new EmailMessage)->sendByModule($em);
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }

        return true;
    }

    private function sendByModule($mail)
    {
        $this->sendMail($mail, $this->getTemplate($mail));
    }

    private function sendMail($em, $template)
    {
        try {
            $emails = json_decode($em->recipients);
            if (!is_array($emails)) $emails = [$emails];

            $data_raw = json_decode($em->data);

            Log::info($em->data);

            $this->content = $data_raw;

            //get locale function (from recipients) and add it to content
            $lang = "sk";
            $u = User::where('email', $emails[0])->first();
            if($u){
                $lang = $u->profile->locale;
            }
            $this->content->lang = $lang;

            $this->send_to = array_unique($emails);
            $this->subject = $em->subject;
            try {
                Mail::send(["html" => "email.html." . $template, "text" => "email.text." . $template], ["content" => $this->content],
                    function (Message $message) {

                        $message
                            ->to($this->send_to)
                            ->subject($this->subject);

                    });
                $em->status = self::EMAIL_SENT;
                $em->updated_at = Carbon::now();
                $em->save();
            } catch (\Exception $e) {
                Log::critical($e->getMessage());
            }
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
        }
    }

    public function getTemplate($mail)
    {
        if (strcmp($mail->module, "test") == 0) {
            return 'test';
        } else if (strcmp($mail->module, "student_enroll") == 0) {
            return 'student_enroll';
        }else if (strcmp($mail->module, "student_enroll_smart") == 0) {
            return 'student_enroll_smart';
        } else if (strcmp($mail->module, "class_canceled") == 0) {
            return 'class_canceled';
        } else if (strcmp($mail->module, "new_hula_message") == 0) {
            return 'new_hula_message';
        }else if (strcmp($mail->module, "feedback_added") == 0) {
            return 'feedback_added';
        }else if (strcmp($mail->module, "student_reschedule_class") == 0) {
            return 'student_reschedule_class';
        }
        return $mail->module;
    }

    public function sendWaitingEmails()
    {
        try {
            $emails = $this->getUnsetMails();

            foreach ($emails as $mail) {
                $this->sendByModule($mail);
            }

        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    private function getUnsetMails()
    {
        return EmailMessage::where('send_time', "<=", now()->addHours(3)->format("Y-m-d G:i:s"))
            ->where('status', 0)
            ->limit(20)
            ->get();
    }

}
