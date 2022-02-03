@section('content')
    @php
        $student = \App\Models\User\Student::find($content->student);
        $class = \App\Models\SchoolClass::find($content->class);
    @endphp
    {{__('email.student_enroll_text1')}}
    @lang('general.Student'): {{ $student->name }}
{{--
    @lang('general.lecture'): {{ $class->language->name_en }}
--}}
    @lang('general.Date'): {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . ' ' . $class->hour->class_start)->format("d,M Y H:i") }}
    @lang('general.Type'): {{ $class->teacher_hour ? __('lecture.individual') : __('lecture.collective') }}
@stop
