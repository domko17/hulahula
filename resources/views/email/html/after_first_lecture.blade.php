@extends('email.html.layout')

@section('content')
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
        @endphp
        <h4>{{ __('email.after_first_lecture_text1', ['name' => $student->name], $content->lang) }}</h4>
        <p>{{ __('email.after_first_lecture_text2', [], $content->lang) }}</p>
        <p>{{ __('email.thankyou') }}</p>
        <p>Vojtech Paumer, {{ __('email.manager') }} HulaHula</p>
    </section>
@stop
