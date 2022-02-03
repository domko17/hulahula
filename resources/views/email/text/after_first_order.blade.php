@section('content')
    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{ __('email.first_line_2', ['name' => $student->name], $content->lang) }}
    {{ __('email.after_first_order_text_1', [], $content->lang) }}
    Vojtech Paumer, {{ __('email.manager') }} HulaHula
@stop
