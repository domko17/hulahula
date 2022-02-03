@extends('email.html.layout')

@section('content')
    <h1>{{__('email.student_reschedule_class')}}</h1>
    <br>
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
            $class_from = \App\Models\SchoolClass::find($content->class_old);
            $class_to = \App\Models\SchoolClass::find($content->class_new);
        @endphp
        <h4>{{__('email.student_reschedule_text1')}}</h4>
        <p>@lang('general.Student'): {{ $student->name }}</p>
        <p>
            @lang('email.lecture_from')<br>
            @lang('general.Date')
            : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class_from->class_date . ' ' . $class_from->hour->class_start)->format("d,M Y H:i") }}
        </p>
        <p>
            @lang('email.lecture_to')<br>
            @lang('general.Date')
            : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class_to->class_date . ' ' . $class_to->hour->class_start)->format("d,M Y H:i") }}
        </p>
        <a href="{{ route('lectures.show', $class_to->id) }}">
            {{__('email.student_enroll_class_link')}}
        </a>
    </section>
@stop
