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
        <div class="col-sm-12 grid-margin px-0 py-0 mb-2 mt-4 stretch-card">
            <a href="{{ route('admin.package-orders.index') }}" class="btn btn-sm btn-outline-primary btn-block">
                @lang('order.order_list_new')
            </a>
        </div>

        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <h4 class="card-title">@lang('order.order_list_old_admin')</h4>

                    @include('admin.star_order.components.tables_finished')
                </div>
            </div>
        </div>
    </div>
@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {
            if (window.mobilecheck()) {
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
                        {"visible": false},
                        null,
                        null,
                        null,
                        null,
                    ]
                });
            } else {
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
                        {"orderable": false},
                        null,
                        null,
                        {"orderable": false},
                        {"orderDataType": "dom-date"},
                        null,
                        {"orderable": false}
                    ]
                });
            }

        })

    </script>
@stop
