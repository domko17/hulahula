<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LecturesController extends Controller
{

    /**
     * Get Lectures API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLectures(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $query = SchoolClass::orderBy("class_date");
        $filter_params = $body['filter'];
        $filters = 0;

        if (isset($filter_params['f_lang']) and intval($filter_params['f_lang']) > 0) {
            $filters++;
        }
        if (isset($filter_params['f_type']) and intval($filter_params['f_type']) > 0) {
            if (intval($filter_params['f_type']) == 1) {
                $query->whereNotNull('teacher_hour');
            } else if (intval($filter_params['f_type']) == 2) {
                $query->whereNotNull('collective_hour');
            }
            $filters++;
        }
        if (isset($filter_params['f_status']) and intval($filter_params['f_status']) > 0) {
            $filters++;
        }
        if (isset($filter_params['f_student']) and intval($filter_params['f_student']) != 0) {
            $filters++;
        }
        if (isset($filter_params['f_teacher']) and intval($filter_params['f_teacher']) != 0) {
            $filters++;
        }

        if (isset($filter_params['filtered']) and $filters == 0) {
//            return route('lectures.index');
        }

        $res = $query->get();
        $tmp = [];
        $classes_future = [];
        $classes_past = [];

        if (isset($filter_params['filtered'])) {
            foreach ($res as $c) {
                $ok = true;

                if ($ok and isset($filter_params['f_lang']) and intval($filter_params['f_lang']) > 0) {
                    if ($c->hour->language->id != intval($filter_params['f_lang'])) $ok = false;
                }

                if ($ok and
                    Carbon::createFromFormat("Y-m-d", $c->class_date) > now() and
                    isset($filter_params['f_status']) and
                    intval($filter_params['f_status']) > 0) {
                    if (intval($filter_params['f_status']) == 1 and !$c->is_free()) $ok = false;
                    if (intval($filter_params['f_status']) == 2 and $c->is_free()) $ok = false;
                }
                if ($ok and isset($filter_params['f_student']) and intval($filter_params['f_student']) != 0) {
                    if (intval($filter_params['f_student']) == -1 and count($c->students) > 0) $ok = false;
                    else if (intval($filter_params['f_student']) > 0 and !$c->is_student_attending(intval($filter_params['f_student']))) $ok = false;
                }
                if ($ok and isset($filter_params['f_teacher']) and intval($filter_params['f_teacher']) != 0) {
                    if (intval($filter_params['f_teacher']) == -1 and $c->hour->teacher) $ok = false;
                    else if (intval($filter_params['f_teacher']) > 0 and $c->hour->teacher->id != intval($filter_params['f_teacher'])) $ok = false;
                }

                if ($ok) {
                    $tmp[] = $c;
                }
            }
        } else $tmp = $res;

        foreach ($tmp as $c) {
            if (Carbon::createFromFormat("Y-m-d", $c->class_date) < now()) {
                $classes_past[] = $c;
            } else {
                $classes_future[] = $c;
            }
        }

        if ($body['type'] == 'past'){
            return json_encode($classes_past);
        }
        elseif($body['type'] == 'future'){
            return json_encode($classes_future);
        }
    }
}
