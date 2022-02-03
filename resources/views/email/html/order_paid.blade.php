@extends('email.html.layout')

@section('content')
    <h1>{{ __('email.order_paid_title', [], $content->lang) }}</h1>
    <br>
    <br>
    <section>
        @php
            $package_name = \App\Models\Helper::PACKAGES[$content->package_type]['name'];
        @endphp
        <h4>{{ __('email.order_paid_text1', ['order_id' => $content->order_id], $content->lang) }}</h4>
        <p>{{ __('email.order_ordered_package', [], $content->lang) }}:<b>{{ $package_name }}</b></p>
        @if($content->was_renewal)
            <p>{{ __('email.order_was_renewal', [], $content->lang) }}</p>
        @endif
    </section>
@stop
