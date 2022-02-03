@section('content')

    @php
        $student = \App\Models\User\Student::find($content->student);
    @endphp
    {{ __('email.first_line', ['name' => $student->name], $content->lang) }}
    {{ __('email.no_order_package_after_first_class_text1', [], $content->lang) }}
    {{ __('email.in_hulahula', [], $content->lang) }}
    {{ __('email.thankyou') }}
    Vojtech Paumer, {{ __('email.manager') }} HulaHula

@stop
