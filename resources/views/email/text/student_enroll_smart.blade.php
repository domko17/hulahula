@section('content')
    {{__('email.student_enroll_smart_title')}}

    @php
        $student = \App\Models\User\Student::find($content->student);
        $count = $content->class_count;
        $ths = \App\Models\User\TeacherHour::whereIn('id', $content->teacher_hours)->get();
        $day = $content->day;
    @endphp
    {{__('email.student_enroll_smart_text1')}}
    @lang('general.Student'): {{ $student->name }}
    {{ __('email.student_enroll_smart_text2', ['count'=>$count]) }}

    @foreach($ths as $th)
        {{ $th->one_time ? $th->day : __('general.day_'.$th->day) }} {{ $th->one_time ?: (substr($th->class_start,0,5)." - ".substr($th->class_end,0,5) ) }}
    @endforeach

    {{__('email.student_enroll_smart_text3')}}


@stop
