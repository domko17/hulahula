@section('content')
    {{ __('email.order_add_admin_title', [], $content->lang) }}

    @php
        $package_name = \App\Models\Helper::PACKAGES[$content->package_type]['name'];
        $student = \App\Models\User\Student::find($content->student_id);
    @endphp
    {{ __('email.order_add_admin_text1', ['order_id' => $content->order_id], $content->lang) }}
    {{ __('email.order_ordered_package', [], $content->lang) }}:{{ $package_name }}
    {{ __('email.order_ordered_by', [], $content->lang) }}:{{ $student->name }}
@stop
