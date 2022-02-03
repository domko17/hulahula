@extends('email.html.layout')

@section('content')
    <h1>{{__('email.class_canceled_title', [], $content->lang)}}</h1>
    <br>
    <br>
    <section>
        @php
            $class = \App\Models\SchoolClass::find($content->class);
        @endphp
        @if( $class )
            <h4>{{__('email.class_canceled_text1', [], $content->lang)}}</h4>
            <p>
                {{ __('general.lecture', [], $content->lang) }}<br>
                {{ __('general.Date', [], $content->lang) }}
                : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . ' ' . $class->hour->class_start)->format("d,M Y H:i") }}
                <br>
                {{--{{ __('general.Type'): {{ $class->teacher_hour ? __('lecture.individual') : __('lecture.collective') }} }}
                <br>--}}
            </p>
            <hr>
            <p>
                <b>
                    {{ __('lecture.cancel_lecture_reason', [], $content->lang) }}: {{ $class->cancel_reason }}
                </b>
            </p>
            <a href="{{ route('lectures.show', $class->id) }}">
                {{__('email.student_enroll_class_link', [], $content->lang)}}
            </a>
        @else
            <p style="color: #f00;">Chyba: Hodina nenájdená</p>
        @endif
    </section>
@stop
