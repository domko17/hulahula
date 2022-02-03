@section('content')
    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{ __('email.first_line', ['name' => $student->name], $content->lang) }}
    {{ __('email.after_third_class_text_1', [], $content->lang) }}
    {{ __('email.after_third_class_text_2', [], $content->lang) }}
    {{ __('email.in_hulahula', [], $content->lang) }}
    Vojtech Paumer, {{ __('email.manager') }} HulaHula
@stop
