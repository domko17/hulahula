@extends('email.html.layout')

@section('content')
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
        @endphp
        <h4>{{ __('email.first_line', ['name' => $student->name], $content->lang) }}</h4>
        <p>{{ __('email.no_order_package_after_first_class_text1', [], $content->lang) }}
        <a href="{{ $content->f_link }}"><b>{{ __('email.in_hulahula', [], $content->lang) }}</b></a> </p>
        <p>{{ __('email.thankyou') }}</p>
        <p>Vojtech Paumer, {{ __('email.manager') }} HulaHula</p>
    </section>
@stop
