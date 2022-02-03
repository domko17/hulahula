{{__('email.new_teacher_feedback_added')}}

@php
    $student = \App\Models\User\Student::find($content->student);
    $teacher = \App\Models\User\Teacher::find($content->teacher);
@endphp
@lang('general.Student'): {{ $student->name }} Pridal/upravil hodnotenie učiteľa: {{ $teacher->name }}


