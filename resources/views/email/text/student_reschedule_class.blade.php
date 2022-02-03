@section('content')
    {{__('email.student_reschedule_class')}}

    @php
        $student = \App\Models\User\Student::find($content->student);
        $class_from = \App\Models\SchoolClass::find($content->class_old);
        $class_to = \App\Models\SchoolClass::find($content->class_new);
    @endphp
    {{__('email.student_reschedule_text1')}}
    @lang('general.Student'): {{ $student->name }}

    @lang('general.lecture_from')
    @lang('general.Date')
    : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class_from->class_date . ' ' . $class_from->hour->class_start)->format("d,M Y H:i") }}

    @lang('email.lecture_to')
    @lang('general.Date')
    : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class_to->class_date . ' ' . $class_to->hour->class_start)->format("d,M Y H:i") }}

    {{__('email.student_enroll_class_link')}}

@stop
