@extends('email.html.layout')

@section('content')
    <h1>{{__('email.student_enroll_title')}}</h1>
    <br>
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
        @endphp
        <h4>{{__('email.evaluate_student_title')}}</h4>
        <p>{{ __('email.evaluate_student_text1') }}</p>
        <p>{{ __('email.evaluate_student_text2') }}</p>
        <p>@lang('general.Student'): {{ $student->name }}</p>

        <a href="{{ route('user.profile', $student->id) }}">
            {{__('email.student_detail_link')}}
        </a>
    </section>
@stop
