@extends('email.html.layout')

@section('content')
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
        @endphp
        <h4>{{ __('email.first_line', ['name' => $student->name], $content->lang) }}</h4>
        <p>{{ __('email.one_week_no_study_text_1', [], $content->lang) }}</p>
        <p>Vojtech Paumer, {{ __('email.manager') }} HulaHula</p>
    </section>
@stop
