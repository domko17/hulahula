@section('content')
    {{ __('email.order_paid_title', [], $content->lang) }}

    @php
        $package_name = \App\Models\Helper::PACKAGES[$content->package_type]['name'];
    @endphp
    {{ __('email.order_paid_text1', ['order_id' => $content->order_id], $content->lang) }}
    {{ __('email.order_ordered_package', [], $content->lang) }}:{{ $package_name }}
    @if($content->was_renewal)
        {{ __('email.order_was_renewal', [], $content->lang) }}
    @endif
@stop
