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
                    @lang('side_menu.teachers')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin mb-4 px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12 col-md-8 order-2 order-md-1">
                        <h4 class="card-title">@lang('language.teachers') ({{ count($teachers) }})</h4>
                    </div>
                    <div class="col-12 col-md-4 text-right order-1 order-md-2">
                        <a href="{{ route('admin.users.create') }}"
                           class="btn btn-success btn-sm mb-2 mb-md-0 btn-block"><i
                                class="fa fa-plus"></i> @lang('users.user_create')
                        </a>
                    </div>
                    <div class="col-12">
                        <p class="card-description">Aktuálny stav odučených hodín</p>
                    </div>

                    @include('admin.teachers.components.tables_teachers')
                </div>
            </div>
        </div>
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12 col-md-8 order-2 order-md-1">
                        <h4 class="card-title">História vyplatených hodín</h4>
                    </div>

                    @include('admin.teachers.components.tables_teacher_pay_history')
                </div>
            </div>
        </div>
    </div>

    @include('admin.teachers.components.modals')

@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {


            var dt_language = {
                "emptyTable": "Nie sú k dispozícii žiadne dáta",
                "info": "Záznamy _START_ až _END_ z celkom _TOTAL_",
                "infoEmpty": "Záznamy 0 až 0 z celkom 0 ",
                "infoFiltered": "(vyfiltrované spomedzi _MAX_ záznamov)",
                "infoPostFix": "",
                "infoThousands": ",",
                "lengthMenu": "Zobraz _MENU_ záznamov",
                "loadingRecords": "Načítavam...",
                "processing": "Spracúvam...",
                "search": "Hľadať:",
                "zeroRecords": "Nenašli sa žiadne vyhovujúce záznamy",
                "paginate": {
                    "first": "Prvá",
                    "last": "Posl.",
                    "next": "Nasl.",
                    "previous": "Predch."
                },
                "aria": {
                    "sortAscending": ": aktivujte na zoradenie stĺpca vzostupne",
                    "sortDescending": ": aktivujte na zoradenie stĺpca zostupne"
                }
            };

            if (window.mobilecheck()) {
                $('#table_teachers_mobile').show();
                $('#history_table_mobile').show();
                $('#history_table_mobile').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "order": [[4, 'desc']],
                    "columns": [
                        {"orderable": false},
                        null,
                        null,
                        {"orderDataType": "dom-date"},
                        {"visible": false},
                    ]
                });
            } else {
                $('#table_teachers_pc').show();
                $('#history_table_pc').show();
                $('#history_table_pc').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 5,
                    "language": dt_language,
                    "order": [[5, 'desc']],
                    "columns": [
                        {"orderable": false},
                        null,
                        null,
                        null,
                        {"orderDataType": "dom-date"},
                        {"visible": false},
                    ]
                });
            }

            $(".pay-alert").click(function () {
                var id = $(this).attr("data-item-id");
                $("#teacher_id").val(id);
                console.log($("#teacher_id").val())
            });

        })


    </script>
@stop
