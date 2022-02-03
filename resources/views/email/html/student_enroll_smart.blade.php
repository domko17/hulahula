@extends('email.html.layout')

@section('content')
    <h1>{{__('email.student_enroll_smart_title')}}</h1>
    <br>
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
            $count = $content->class_count;
            $ths = \App\Models\User\TeacherHour::whereIn('id', $content->teacher_hours)->get();
            $day = $content->day;
        @endphp
        <h4>{{__('email.student_enroll_smart_text1')}}</h4>
        <p>@lang('general.Student'): {{ $student->name }}</p>
        <p>{{ __('email.student_enroll_smart_text2', ['count'=>$count]) }}</p>

        @foreach($ths as $th)
            <p>{{ $th->one_time ? $th->day : __('general.day_'.$th->day) }} {{ $th->one_time ?: (substr($th->class_start,0,5)." - ".substr($th->class_end,0,5) ) }}</p>
        @endforeach

        {{__('email.student_enroll_smart_text3')}}

    </section>
@stop
