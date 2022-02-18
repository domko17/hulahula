@extends('layouts.app')

@section('title')

@stop

@section('content')
    <div class="page-header mt-2 mb-2 mb-mt-4 mt-md-0 mb-1">
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
                    @lang('side_menu.Lections')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        @include('lecture.components.tables_filter')

        @include('lecture.components.tables_past_classes')

        @include('lecture.components.tables_future_classes')
    </div>

    @include('lecture.components.modals')
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">

    <style>
        .filter select {
            border: gray 1px solid;
        }
    </style>
@stop

@section('page_scripts')
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#day").chosen({
                width: "100%"
            });
            if (window.mobilecheck()) {
                $('#lectures_past_mobile').show();
                $('#lectures_past_mobile').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "order": [[0, 'desc'], [1, 'asc']],
                    "pagingType": "numbers",
                    "columns": [
                        {"visible": false, "orderDataType": "dom-date"},
                        {"visible": false, "orderDataType": "dom-time"},
                        {"orderable": false},
                        {"orderable": false},
                        {"orderable": false},
                        {"orderable": false}
                    ]
                });
                $('#lectures_future_mobile').show();
                $('#lectures_future_mobile').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "order": [[0, "asc"], [1, 'asc']],
                    "pagingType": "numbers",
                    "columns": [
                        {"visible": false, "orderDataType": "dom-date"},
                        {"visible": false, "orderDataType": "dom-time"},
                        {"orderable": false},
                        {"orderable": false},
                        {"orderable": false},
                        {"orderable": false}
                    ]
                });
            } else {
                $('#lectures_past_pc').show();
                $('#lectures_past_pc').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "order": [[3, 'desc']],
                    "columns": [
                        {"orderable": false},
                        null,
                        null,
                        {"orderDataType": "dom-date"},
                        null,
                        null,
                        {"orderable": false}
                    ]
                });
                $('#lectures_future_pc').show();
                $('#lectures_future_pc').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "order": [[0, "asc"], [1, 'asc']],
                    "columns": [
                        {"orderDataType": "dom-date"},
                        {"orderDataType": "dom-time"},
                        null,
                        null,
                        null,
                        null,
                        {"orderable": false}
                    ]
                });
            }

            $("#language").change(function () {
                let val = $("#language option:selected").val();
                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "get_language_teachers",
                        language_id: val
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        $(".teacher_select").each(function () {
                            $(this).show(function () {
                                $(this).animate()
                            })
                        });
                        $("#teacher_select").empty();
                        $("#sub_teacher_select").empty();
                        $("#teacher_select").append($("<option></option>").attr("value", 0).html("..."));
                        $("#sub_teacher_select").append($("<option></option>").attr("value", 0).html("..."));
                        for (var data in response.data) {
                            $("#teacher_select").append($("<option></option>").attr("value", response.data[data].id).html(response.data[data].name));
                            $("#sub_teacher_select").append($("<option></option>").attr("value", response.data[data].id).html(response.data[data].name));
                        }
                    }
                })
            })
        })
    </script>
@stop
