@extends('email.html.layout')

@section('content')
    <h1>{{ __('email.order_add_admin_title', [], $content->lang) }}</h1>
    <br>
    <section>
        @php
            $package_name = \App\Models\Helper::PACKAGES[$content->package_type]['name'];
            $student = \App\Models\User\Student::find($content->student_id);
        @endphp
        <h4>{{ __('email.order_add_admin_text1', ['order_id' => $content->order_id], $content->lang) }}</h4>
        <p>{{ __('email.order_ordered_package', [], $content->lang) }}: <b>{{ $package_name }}</b></p>
        <p>{{ __('email.order_ordered_by', [], $content->lang) }}: <b>{{ $student->name }}</b></p>
        <p><a href="{{ route('admin.package-orders.index') }}">OBJEDNÁVKY</a> </p>
    </section>
@stop
