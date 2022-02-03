@extends('email.html.layout')

@section('content')
    <h1>{{__('email.student_enroll_title')}}</h1>
    <br>
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
            $class = \App\Models\SchoolClass::find($content->class);
        @endphp
        <h4>{{__('email.student_enroll_text1')}}</h4>
        <p>@lang('general.Student'): {{ $student->name }}</p>
        <p>
            @lang('general.lecture')<br>
            @lang('general.Date')
            : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . ' ' . $class->hour->class_start)->format("d.m.Y H:i") }}
            <br>
            @lang('general.Type'): {{ $class->teacher_hour ? __('lecture.individual') : __('lecture.collective') }}
        </p>
        <a href="{{ route('lectures.show', $class->id) }}">
            {{__('email.student_enroll_class_link')}}
        </a>
    </section>
@stop
