@extends('email.html.layout')

@section('content')
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
        @endphp
        <h4>{{ __('email.first_line', ['name' => $student->name], $content->lang) }}</h4>
        <p>{{ __('email.three_weeks_no_study_has_classes_text_1', [], $content->lang) }}</p>
        <p>{{ __('email.three_weeks_no_study_has_classes_text_2', [], $content->lang) }}</p>
        <p>{{ __('email.three_weeks_no_study_has_classes_text_3', [], $content->lang) }}
            <a href="{{ route('buy_stars.index') }}"><b>{{ __('email.in_hulahula', [], $content->lang) }}</b></a> </p>
        <p>Vojtech Paumer, {{ __('email.manager') }} HulaHula</p>
    </section>
@stop
