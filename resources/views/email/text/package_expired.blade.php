@section('content')
    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{ __('email.first_line', ['name' => $student->name], $content->lang) }}
    {{ __('email.package_expired_text1', [], $content->lang) }}
    Vojtech Paumer, {{ __('email.manager') }} HulaHula
@stop
