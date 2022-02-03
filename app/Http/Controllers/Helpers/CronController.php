<?php

namespace App\Http\Controllers\Helpers;

use App\Models\EmailMessage;
use App\Models\EmailMessageGenerator;
use App\Models\Helper;
use App\Models\SchoolClass;
use App\Models\User\TeacherHour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    /**
     *
     * Cron call - interval 5 minutes
     *
     * @return false|string
     */
    public function cronMail()
    {
        Log::info("Cron Mail call");

        //Auto mails creation
        $emg = new EmailMessageGenerator();
        $emg->autoGenerateEmails(EmailMessageGenerator::EVERY_FIVE_MINUTES);

        (new EmailMessage())->sendWaitingEmails();

        Helper::checkUserPackages();

        return json_encode(["Status" => "OK"]);
    }

    /**
     * Cron call - interval 1 day at 01:00AM
     *
     * @return false|string
     */
    public function cronDaily()
    {
        Log::info("Cron Daily call");

        //Auto mails creation
        $emg = new EmailMessageGenerator();
        $emg->autoGenerateEmails(EmailMessageGenerator::DAILY);


        return json_encode(["Status" => "OK"]);
    }


    //---------

}

