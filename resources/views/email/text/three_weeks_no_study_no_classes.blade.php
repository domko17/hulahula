@section('content')

    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{ __('email.first_line', ['name' => $student->name], $content->lang) }}
    {{ __('email.three_weeks_no_study_no_classes_text_1', [], $content->lang) }}
    {{ __('email.three_weeks_no_study_no_classes_text_2', [], $content->lang) }}
    Vojtech Paumer, {{ __('email.manager') }} HulaHula
@stop
