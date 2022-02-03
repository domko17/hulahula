@section('content')
    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{ __('email.after_first_lecture_text1', ['name' => $student->name], $content->lang) }}
    {{ __('email.after_first_lecture_text2', [], $content->lang) }}
    {{ __('email.thankyou') }}
    Vojtech Paumer, {{ __('email.manager') }} HulaHula
@stop
