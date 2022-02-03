@section('content')
    {{__('email.student_enroll_title')}}

    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{__('email.evaluate_student_title')}}
    {{ __('email.evaluate_student_text1') }}
    {{ __('email.evaluate_student_text2') }}
    @lang('general.Student'): {{ $student->name }}</p>

@stop
