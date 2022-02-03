@section('content')
    @php
        $class = \App\Models\SchoolClass::find($content->class);
    @endphp
    {{__('email.class_canceled_text1')}}
{{--
    @lang('general.lecture'): {{ $class->language->name_en }}
--}}
    @lang('general.Date'): {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $class->class_date . ' ' . $class->hour->class_start)->format("d,M Y H:i") }}
    @lang('general.Type'): {{ $class->teacher_hour ? __('lecture.individual') : __('lecture.collective') }}
    @lang('lecture.cancel_lecture_reason'): {{ $class->cancel_reason }}
@stop
