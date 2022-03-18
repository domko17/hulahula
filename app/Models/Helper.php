<?php

namespace App\Models;

use App\Models\User\TeacherHour;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

include(app_path().'/phpqrcode.php');

class Helper
{

    const PACKAGES = [
        0 => [
            'name' => '_EXTRA',
            'starter' => false,
            'teacher_salary' => 10,
            'classes_count' => 0,
        ],
        1 => [
            'name' => 'SMART',
            'starter' => false,
            'teacher_salary' => 10,
            'classes_count' => 20,
        ],
        2 => [
            'name' => 'PREMIUM INDIVIDUAL',
            'starter' => false,
            'teacher_salary' => 10,
            'classes_count' => 10,
        ],
        3 => [
            'name' => 'EXTRA',
            'starter' => false,
            'teacher_salary' => 10,
            'classes_count' => 1,
        ],
        99 => [
            'name' => 'START',
            'starter' => true,
            'teacher_salary' => 0,
            'classes_count' => 0,
        ],
    ];

    public static function fillLecturesByTeacherHour($thid)
    {
        $th = TeacherHour::where('active', 1)->where('id', $thid)->first();

        if (!$th->teacher->active) return;

        $cf = count($th->classes_future);

        if ($cf == 6) {
            return;
        }

        Log::info("Creating classes for teacher hour ID -> " . $thid . " | Created classes " . (6 - $cf));

        if ($cf == 0) {
            $last_day = Carbon::now();
            $last_day->startOfWeek()->addDays($th->day - 1);
            if ($last_day < Carbon::now()) {
                $last_day->addWeeks(1);
            }
        } else {
            $last_day = Carbon::createFromFormat('Y-m-d', $th->classes_future[$cf - 1]->class_date);
            $last_day = $last_day->startOfWeek()->addDays($th->day - 1);
            $last_day->addWeeks(1);
            $last_class = $th->classes_future[$cf - 1];
        }


        for ($i = 0; $i < 4 - $cf; $i++) {
            $sc = new SchoolClass();

            $sc->teacher_hour = $thid;
            $sc->class_date = $last_day;

            $sc->save();

            //Ak boli uz hodiny, najdem poslednu hodinu a jej studentov
            if (isset($last_class)) {
                $students = $last_class->students;
                foreach ($students as $student) {
                    //ak student chcel opakovat svhoje hodiny
                    if ($student->repeat) {
                        $profile = $student->user->profile;
                        //ak ma k dispozidii hviezdicky automaticky ho zahlasim na hodinu
                        if ($profile->stars_individual > 0) {
                            $cs = new ClassStudent();
                            $cs->class_id = $sc->id;
                            $cs->student_id = $student->student_id;
                            // ak ma viac hviezdicviek tak mu zapnem opakovanie
                            if ($profile->stars_individual > 1) {
                                $cs->repeat = 1;
                            }
                            $profile->stars_individual--;
                            $profile->save();
                            $cs->save();
                        }
                    }
                }
            }

            $last_day->addWeeks(1);
        }

        return;
    }

    public static function fillLecturesByTeacherHours()
    {
        $th = TeacherHour::where('active', 1)->where('one_time', 0)->get();

        foreach ($th as $i) {
            self::fillLecturesByTeacherHour($i->id);
        }

        return;
    }

    public static function signStudentsStudyTimeForDaysClasses()
    {
        $classes = SchoolClass::where('class_date', '=', Carbon::now()->subDay()->format('Y-m-d'))->get();


        dd($classes);
    }

    /**
     * @param int $orderId
     * @return bool
     */
    public static function getOrderQR(int $orderId = 1)
    {
        $QR_path = public_path() . "/Orders/QR/";

        //if order has qr generated, return it
        $presume_file = $QR_path . "po_" . $orderId . ".png";
        if (file_exists($presume_file)) return 'po_' . $orderId . ".png";

        // else generate and save new one
        /**
         * @var PackageOrder $order
         */
        $order = PackageOrder::find($orderId);
        if (!$order) return false;

        $vs = $order->variable_symbol;
        $price = intval($order->price);
        $date = Carbon::now()->format("Ymd");
        $user = $order->user;
        $comment = $user->name;

        $data = implode("\t", array(
            0 => '',                        //IDENTIFIKATOR PLATBY - AKYKOLVEK STRING
            1 => '1',                       // 1. PLATBA (moze byt viac platieb)
            2 => implode("\t", array(
                true,                               // PLATBA (vzdy true)
                $price,                             // SUMA
                'EUR',                              // MENA
                $date,                              // DATUM
                $vs,                                // VARIABILNY SYMBOL
                '0308',                             // KONSTANTNY SYMBOL
                '',                                 // SPECIFICKY SYMBOL
                '',                                 // PREDOSLE 3 UDAJE V SEPA FORMATE (ak su zadane tak toto netreba)
                $comment,                           // POZNAMKA
                '1',                                // 1. UCET (uctov moze byt viac)
                config('hulahula.bank.IBAN'),  // IBAN
                config('hulahula.bank.SWIFT'), // SWIFT
                '0',                                // Trvaly prikaz ? (0-nie, 1-ano)
                '0'                                 // Inkaso ? (0-nie, 1-ano)
            ))
        ));

        $new_code = $QR_path . 'po_' . $order->id . ".png";

        self::generatePayBySquare($data, $new_code);

        return 'po_' . $order->id . ".png";
    }

    /**
     * creates PayBySquare encoded string from provided data and in compliance with PayBySquare specification
     * and then generates PNG QR code
     *
     * @param string $data - data string containing all required parameters in compliance with PayBySquare specification
     * @param string $output_file - output file (path/to/file.png) for the generated PNG file
     * @author unknown
     *
     */
    private static function generatePayBySquare(string $data, string $output_file)
    {
        /*
         * prepend the data string with crc32 hash of the data string
         */
        $d = strrev(hash("crc32b", $data, TRUE)) . $data;

        /*
         * compress the string by LZMA1 with parameters specified in the PayBySquare specification
         */
        $x = proc_open("/usr/bin/xz '--format=raw' '--lzma1=lc=3,lp=0,pb=2,dict=128KiB' '-c' '-'", [0 => ["pipe", "r"], 1 => ["pipe", "w"]], $p);
        fwrite($p[0], $d);
        fclose($p[0]);
        $o = stream_get_contents($p[1]);
        fclose($p[1]);
        proc_close($x);

        /*
         * PayBySquare header
         */
        $d = bin2hex("\x00\x00" . pack("v", strlen($d)) . $o);
        $b = "";
        for ($i = 0; $i < strlen($d); $i++) {
            $b .= str_pad(base_convert($d[$i], 16, 2), 4, "0", STR_PAD_LEFT);
        }
        $l = strlen($b);
        $r = $l % 5;
        if ($r > 0) {
            $p = 5 - $r;
            $b .= str_repeat("0", $p);
            $l += $p;
        }
        $l = $l / 5;

        /*
         * Final encoded string ready for use
         */
        $d = str_repeat("_", $l);
        for ($i = 0; $i < $l; $i += 1) {
            $d[$i] = "0123456789ABCDEFGHIJKLMNOPQRSTUV"[bindec(substr($b, $i * 5, 5))];
        }

        /*
         * Generate the PNG file using the QRcode library
         */
        \QRcode::png($d, $output_file, QR_ECLEVEL_L, 10);
    }

    /**
     * check all user active/almost ending user packages
     * and set it used if it already used all its classes and last class is in the past
     */
    public static function checkUserPackages()
    {
        $user_packages = UserPackage::whereIn('state', [1, 2])->get();

        $changes_count = 0;
        foreach ($user_packages as $up) {
            if ($up->classes_left) continue;
            if ($lc_id = $up->last_class_id) {
                $lc = SchoolClass::find($lc_id);
                /**
                 * @var SchoolClass $lc
                 */
                if (!$lc) continue;
                if ($lc->is_past()) {
                    $up->state = 3;
                    $changes_count++;
                    $up->save();
                    Log::info("User package ID:" . $up->id . " has expired.");
                    if ($up->renewal_package_id) {
                        $rup = UserPackage::find($up->renewal_package_id);
                        $rup->state = 1;
                        $rup->save();
                        Log::info("User renewal package ID:" . $rup->id . " was automatically activated.");
                    } else {
                        (new EmailMessageGenerator)->generatePackageExpired($up->user_id);
                    }
                }
            } else {
                $up->state = 3;
                $changes_count++;
                $up->save();
                Log::info("User package ID:" . $up->id . " has expired.");
                if ($up->renewal_package_id) {
                    $rup = UserPackage::find($up->renewal_package_id);
                    $rup->state = 1;
                    $rup->save();
                    Log::info("User renewal package ID:" . $rup->id . " was automatically activated.");
                } else {
                    (new EmailMessageGenerator)->generatePackageExpired($up->user_id);
                }
            }
        }
    }
}
