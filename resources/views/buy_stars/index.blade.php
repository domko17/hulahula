@extends('layouts.app')

@section('title')

@stop

@section('content')
    <div class="page-header mt-2 mb-2 mb-mt-4 mt-md-0">
        <h3 class="page-title">
            <button onclick="window.location.href='{{ route('dashboard') }}'"
                    class="page-title-icon btn btn-gradient-primary btn-icon btn-rounded btn-sm">
                <i class="mdi mdi-home"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="text-dark"></a>
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb px-1 px-md-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-primary">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('dashboard.buy_stars')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        {{-- TODO Zobraziť informácie o aktuálnom balíčku --}}
        {{-- TODO Ak má balíček ďalši kúpený bude jeho predĺžením ? --}}
        {{-- TODO Ak má aj predĺženie nepustiť objednávať ďalšie ? --}}
        <div class="col-12">
            <h2 class="text-primary px-3">
                @lang('order.how_to_pay')
            </h2>
        </div>
        <div class="col-lg-12 grid-margin stretch-card order-1 order-md-1">
            <div class="card">
                <div class="card-body p-2 p-md-4">

                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-2 mb-md-0 stretch-card">
                            <div class="card bg-primary border-round-10">
                                <div class="card-body p-2 p-md-4 text-center text-light d-flex flex-wrap">
                                    <div class="w-100">
                                        <h3 class="w-100">{{ (new \App\Models\PackageOrder)->getNameByType(2) }}</h3>
                                        <small><a href="#package2Info" data-toggle="modal" class="text-light">
                                                <i class="fa fa-question-circle"></i> @lang('order.package_whatisit')
                                            </a></small>
                                    </div>
                                    <div class="w-100 align-self-end">
                                        <hr>
                                        <button type="button" class="btn btn-light buy_package"
                                                data-package="2"
                                                data-package-price="150">@lang('order.buy_package')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2 mb-md-0 stretch-card">
                            <div class="card bg-primary border-round-10">
                                <div class="card-body p-2 p-md-4 text-center text-light d-flex flex-wrap">
                                    <div class="w-100">
                                        <h3 class="w-100">{{ (new \App\Models\PackageOrder)->getNameByType(1) }}</h3>
                                        <small><a href="#package1Info" data-toggle="modal" class="text-light">
                                                <i class="fa fa-question-circle"></i> @lang('order.package_whatisit')
                                            </a></small>
                                    </div>
                                    <div class="w-100 align-self-end">
                                        <hr>
                                        <button type="button" class="btn btn-light buy_package"
                                                data-package="1"
                                                data-package-price="200">@lang('order.buy_package')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 stretch-card">
                            <div class="card bg-primary border-round-10">
                                <div class="card-body p-2 p-md-4 text-center text-light d-flex flex-wrap">
                                    <div class="w-100">
                                        <h3 class="w-100">{{ (new \App\Models\PackageOrder)->getNameByType(3) }}</h3>
                                        <small><a href="#package3Info" data-toggle="modal" class="text-light">
                                                <i class="fa fa-question-circle"></i> @lang('order.package_whatisit')
                                            </a></small>
                                    </div>
                                    <div class="w-100 align-self-end">
                                        <hr>
                                        <button type="button" class="btn btn-light buy_package"
                                                data-package="3"
                                                data-package-price="23">@lang('order.buy_package')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 grid-margin order-4 order-md-4">
            <div class="card">
                <div class="card-body px-2 px-md-4">
                    <h4 class="card-title">@lang('order.your_orders')</h4>
                    <table class="table table-striped">
                        {{--<thead>
                        <tr>
                            <th>VS</th>
                            <th></th>
                            <th>@lang('order.created')</th>
                            <th></th>
                            <th>@lang('general.Status')</th>
                            <th></th>
                        </tr>
                        </thead>--}}
                        <tbody>
                        @if($orders)
                            @foreach($orders as $i)
                                @php
                                    /**
                                    * @var AppModelsPackageOrder $i
                                    */
                                @endphp
                                <tr>
                                    <td><b>{{ $i->variable_symbol }}</b></td>
                                    <td>{{ $i->getName() }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$i->created_at)->format("d.m.Y") }}</td>
                                    <td><b>{{ $i->price }} €</b></td>
                                    <td>
                                    <span
                                        class="badge badge-gradient-{{ $i->paid ? "success": ($i->canceled ? "secondary" : "danger") }}">
                                        {{ $i->paid ? __('order.paid') : ($i->canceled ? __('order.canceled') : __('order.unpaid')) }}
                                    </span>
                                    </td>
                                    <td>
                                        @if( !$i->paid and !$i->canceled )
                                            <button type="button" data-item-id="{{ $i->id }}"
                                                    class="btn btn-inverse-danger pull-right btn-sm delete-alert"><i
                                                    class="fa fa-times"></i></button>
                                            {{ Form::open(['method' => 'DELETE',
                                            'route' => ['admin.package-orders.destroy', $i->id],
                                            'id' => 'item-del-'. $i->id  ]) }}
                                            {{ Form::hidden('order_id', $i->id) }}
                                            {{ Form::close() }}
                                            <a class="btn btn-primary pull-right btn-sm text-light get_qr"
                                               data-oid="{{ $i->id }}"><i class="fa fa-qrcode"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    @if($orders_old and count($orders_old))
                        <br><br><br>
                        <h4 class="card-title">@lang('order.your_orders_old')
                            <a class="toggle_old_orders text-primary" style="cursor: pointer"><i class="fa fa-eye"></i></a>
                        </h4>
                        <div class="old_orders" style="display: none">
                            <table class="table table-striped">
                                {{--<thead>
                                <tr>
                                    --}}{{--                            <th>ID</th>--}}{{--
                                    <th>VS</th>
                                    <th>@lang('order.lessons_ic')</th>
                                    <th>@lang('order.created')</th>
                                    <th>@lang('order.price')</th>
                                    <th>@lang('general.Status')</th>
                                </tr>
                                </thead>--}}
                                <tbody>
                                @foreach($orders_old as $i)
                                    <tr>
                                        {{--                                <td>#{{ $i->id }}</td>--}}
                                        <td><b>{{ $i->variable_symbol }}</b></td>
                                        <td>{{ $i->stars_i }} / {{ $i->stars_c }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$i->created_at)->format("d.m.Y") }}</td>
                                        <td><b>{{ $i->price }} €</b></td>
                                        <td>
                                    <span
                                        class="badge badge-gradient-{{ $i->paid ? "success": ($i->canceled ? "secondary" : "danger") }}">
                                        {{ $i->paid ? __('order.paid') : ($i->canceled ? __('order.canceled') : __('order.unpaid')) }}
                                    </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 grid-margin stretch-card order-2 order-md-3">
            <div class="card">
                <div class="card-body px-2 px-md-4">
                    <h4 class="card-title">@lang('order.how_to_pay')</h4>
                    <p class="card-description">@lang('order.how_to_pay_text1')</p>

                    <p><b>IBAN:</b><br> {{ config('hulahula.bank.IBAN') }}</p>
                    <p class="text-danger"><b>@lang('order.variable_symbol_text')
                            :</b> @lang('order.variable_symbol_hint')</p>
                    <hr>
                    <p><b>@lang('order.account_number'):</b> {{ config('hulahula.bank.account') }}<br>
                        <b>@lang('order.bank_code'):</b> {{ config('hulahula.bank.code') }}<br>
                        <b>@lang('order.bank_name'):</b> {{ config('hulahula.bank.name') }}</p>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="payInfoModal" tabindex="-1" role="dialog"
         aria-labelledby="payInfoModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6">
                            <h4 class="text-success">@lang("order.created_info")</h4>
                            <hr>
                            <h4 class="card-title">@lang('order.how_to_pay')
                                <small style="font-size: .6em; font-weight: 200;">(@lang("order.created_info_2"))
                                </small>
                            </h4>
                            <p class="card-description">@lang('order.how_to_pay_text1')</p>
                            <p style="font-size: 1.3em"><b>IBAN:</b><br> {{ config('hulahula.bank.IBAN') }}</p>
                            <p class="text-danger"><b>@lang('order.variable_symbol_text'):</b><span id="vs_place"></span></p>
                            <hr>
                            <p>
                                <b>@lang('order.account_number'):</b> {{ config('hulahula.bank.account') }}
                                &nbsp;&nbsp;&nbsp;<b>@lang('order.bank_code'):</b> {{ config('hulahula.bank.code') }}<br>
                                <b>@lang('order.bank_name'):</b> {{ config('hulahula.bank.name') }}
                            </p>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <p class="text-center">
                                @lang('order.pay_by_square_text')
                                <img src="" id="qr_code_image" alt="QR Code" style="width: 75%;">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="location.href='{{route('buy_stars.index')}}'"
                            class="btn btn-success">OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payQRModal" tabindex="-1" role="dialog"
         aria-labelledby="payQRModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <h4 class="text-success">@lang("order.pay_by_square")</h4>
                    <hr>
                    <p class="text-center">
                        @lang('order.pay_by_square_text')
                        <img src="" id="qr_code_image" alt="QR Code" style="width: 75%;">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-toggle="modal" data-target="#payQRModal"
                            class="btn btn-success">OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="package1Info" tabindex="-1" role="dialog"
         aria-labelledby="package1InfoModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <h3 class="text-primary">@lang("order.package")
                        : {{ (new \App\Models\PackageOrder)->getNameByType(1) }}</h3>
                    <hr>
                    <p><strong>@lang('order.package1_text1')</strong></p>
                    <p>@lang('order.package1_text2')</p>
                    <hr>
                    <h5>{{ __('order.package1_text3', ['price' => 200]) }}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary buy_package"
                            data-package="1" data-package-price="200" data-toggle="modal"
                            data-target="#package1Info">@lang('order.buy_package')</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#package1Info">
                        @lang('general.Cancel')
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="package2Info" tabindex="-1" role="dialog"
         aria-labelledby="package1InfoModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <h3 class="text-primary">@lang("order.package")
                        : {{ (new \App\Models\PackageOrder)->getNameByType(2) }}</h3>
                    <hr>
                    <p><strong>@lang('order.package2_text1')</strong></p>
                    <p>@lang('order.package2_text2')</p>
                    <hr>
                    <h5>{{ __('order.package2_text3', ['price' => 150]) }}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary buy_package"
                            data-package="2" data-package-price="150" data-toggle="modal"
                            data-target="#package2Info">@lang('order.buy_package')</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#package2Info">
                        @lang('general.Cancel')
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="package3Info" tabindex="-1" role="dialog"
         aria-labelledby="package1InfoModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <h3 class="text-primary">@lang("order.package")
                        : {{ (new \App\Models\PackageOrder)->getNameByType(3) }}</h3>
                    <hr>
                    <p><strong>@lang('order.package3_text1')</strong></p>
                    <p>@lang('order.package3_text2')</p>
                    <hr>
                    <h5>{{ __('order.package3_text3', ['price' => 23]) }}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary buy_package"
                            data-package="3" data-package-price="23" data-toggle="modal"
                            data-target="#package3Info">@lang('order.buy_package')</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#package3Info">
                        @lang('general.Cancel')
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        function place_order(id, price) {
            let package_name = "";
            if (id == 1) package_name = "SMART";
            if (id == 2) package_name = "PREMIUM INDIVIDUAL";
            if (id == 3) package_name = "EXTRA";
            swal({
                title: '@lang('order.checkout_text1')',
                text: "@lang('order.package'): " + package_name + "\n @lang('order.price'): " + price + "€\n\n @lang('order.checkout_text2') \"@lang('order.checkout_confirm')\"",
                showCancelButton: true,
                buttons: {
                    cancel: {
                        text: "@lang('order.checkout_cancel')",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    },
                    confirm: {
                        text: "@lang('order.checkout_confirm')",
                        value: true,
                        visible: true,
                        className: "btn btn-success",
                        closeModal: true
                    }
                }
            }).then((result) => {
                if (result) {
                    $.ajax({
                        url: "{{ route("ajax_int") }}",
                        method: "POST",
                        data: {
                            action: "create_package_order",
                            p: price,
                            package_id: id
                        },
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (response) {
                            let modal = $('#payInfoModal');
                            modal.find('#vs_place').html(response.order_vs);
                            modal.find('#price_place').html(price);
                            modal.find('#qr_code_image').attr('src', '/zona/Orders/QR/' + response.order_qr);
                            modal.modal('show');
                        },
                        error: function (response) {
                            swal({
                                title: 'ERROR',
                                text: "---",
                                icon: 'error'
                            })
                        }
                    })
                }
            })
        }

        $(document).ready(function () {

            $('.toggle_old_orders').on('click', function () {
                $('.old_orders').fadeToggle('slow');
            });

            $('.get_qr').on('click', function () {
                let oid = $(this).data('oid');
                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "get_order_qr",
                        oid: oid,
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        let modal = $('#payQRModal');
                        modal.find('#qr_code_image').attr('src', '/zona/Orders/QR/' + response.order_qr);
                        modal.modal('show');
                    },
                    error: function (response) {
                        $.toast({
                            text: "There was an error requesting your QR code.", // Text that is to be shown in the toast
                            heading: 'Error', // Optional heading to be shown on the toast
                            icon: 'danger', // Type of toast icon
                            showHideTransition: 'fade', // fade, slide or plain
                            allowToastClose: false, // Boolean value true or false
                            hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                            stack: 1, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                            position: 'bottom-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
                            textAlign: 'right',  // Text alignment i.e. left, right or center
                            loader: true,  // Whether to show loader or not. True by default
                            loaderBg: '#b00d1d',  // Background color of the toast loader
                            afterHidden: function () {
                                //location.reload();
                            }  // will be triggered after the toast has been hidden
                        })
                    }
                })
            });

            $('.delete-alert').click(function (e) {
                var id = $(this).attr("data-item-id");
                swal({
                    title: "Pozor",
                    text: "Tímto označíte objednávku ako zrušenú. Pokračovať?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            document.getElementById('item-del-' + id).submit();
                        }
                    });
            });

            $(".buy_package").click(function () {

                let package_id = $(this).data('package');
                let package_price = $(this).data('package-price');

                place_order(package_id, package_price);
            });

        })

    </script>
@stop
