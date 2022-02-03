@extends('email.html.layout')

@section('content')
    <h1>{{__('email.new_teacher_feedback_added')}}</h1>
    <br>
    <br>
    <section>
        @php
            $student = \App\Models\User\Student::find($content->student);
            $teacher = \App\Models\User\Teacher::find($content->teacher);
        @endphp
        <p>@lang('general.Student'): {{ $student->name }} Pridal/upravil hodnotenie učiteľa: {{ $teacher->name }}</p>

        Hodnotenie si môžete prezrieť na <a href="{{ route('feedback.show', $content->feedback) }}">
            tomto odkaze.
        </a>
    </section>
@stop
