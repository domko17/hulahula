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
                    @lang('side_menu.star_orders')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <h4 class="card-title">@lang('order.order_list_unpaid_admin')</h4>

                    @include('admin.package_order.components.tables_unpaid')
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <h4 class="card-title">@lang('order.order_list_finished_admin')
                        <a class="toggle_paid_orders text-primary" style="cursor: pointer"><i class="fa fa-eye"></i></a>
                    </h4>

                    <div id="paid_orders_wrap" style="display: none">
                    @include('admin.package_order.components.tables_finished')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 grid-margin px-0 stretch-card">
            <a href="{{ route('admin.star-orders.index') }}" class="btn btn-sm btn-outline-primary btn-block">
                @lang('order.order_list_old')
            </a>
        </div>
    </div>
@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {

            $('.toggle_paid_orders').on('click', function () {
                $('#paid_orders_wrap').fadeToggle('slow');
            });

            if (window.mobilecheck()) {
                $('#table_unpaid_mobile').show();

                $('#table_paid_mobile').show();
                $('#table_paid_mobile').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "searching": false,
                    "lengthChange": false,
                    "order": [[0, 'desc']],
                    "columns": [
                        {"visible": false, "visible": false},
                        null,
                        null,
                        null,
                        null,
                    ]
                });
            } else {
                $('#table_unpaid_pc').show();

                $('#table_paid_pc').show();
                $('#table_paid_pc').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "searching": false,
                    "lengthChange": false,
                    "order": [[2, 'asc']],
                    "columns": [
                        {"orderable": false, "visible": false},
                        null,
                        null,
                        {"orderable": false},
                        {"orderDataType": "dom-date"},
                        null,
                        {"orderable": false}
                    ]
                });
            }

            $('.confirm-alert').click(function (e) {
                var id = $(this).attr("data-item-id");
                swal({
                    title: "Pozor",
                    text: "Tímto označíte objednávku ako zaplatenú. Pokračovať?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            document.getElementById('item-conf-' + id).submit();
                        }
                    });
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

        })

    </script>
@stop
