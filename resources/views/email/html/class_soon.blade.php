@extends('email.html.layout')

@section('content')
    <h1>{{__('email.class_soon_title')}}</h1>
    <br>
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
/**
* @var $class \App\Models\SchoolClass
 */
            $class = \App\Models\SchoolClass::find($content->class);
        @endphp
        <h4>{{__('email.class_soon_text1')}}</h4>
        <p>
            {{ __('general.lecture',[], $content->lang) }}<br>
            {{ __('general.Date',[], $content->lang) }}
            : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . ' ' . $class->hour->class_start)->format("d.m.Y H:i") }}
            <br>
            {{ __('general.Teacher') }}: {{ $class->hour->teacher->name }}

        </p>
        <a href="{{ route('lectures.show', $class->id) }}">
            {{__('email.class_detail_link',[], $content->lang)}}
        </a>
    </section>
@stop
