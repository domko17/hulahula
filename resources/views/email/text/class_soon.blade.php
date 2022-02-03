@section('content')
    {{__('email.class_soon_title')}}

    @php
        $student = \App\Models\User\Student::find($content->student);
        $class = \App\Models\SchoolClass::find($content->class);
    @endphp
    {{__('email.class_soon_text1')}}
    {{ __('general.lecture',[], $content->lang) }}
    {{ __('general.Date',[], $content->lang) }}
    : {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . ' ' . $class->hour->class_start)->format("d.m.Y H:i") }}

@stop
