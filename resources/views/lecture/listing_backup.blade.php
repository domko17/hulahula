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
                    @lang('side_menu.Lections')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">@lang('lecture.lectures_future')
                        <button type="button" data-toggle="modal" data-target="#createCollectiveModal"
                                class="btn btn-gradient-success btn-sm pull-right">
                            <i class="fa fa-plus"></i> @lang('lecture.create_collective')
                        </button>
                    </h4>
                    <p class="card-description">@lang('lecture.lectures_future_help')</p>

                    <table class="table table-striped" id="lectures_future">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('lecture.lecture_title') </th>
                            <th> @lang('general.Date') </th>
                            <th> @lang('lecture.start') </th>
                            <th> @lang('lecture.end') </th>
                            <th> @lang('general.Type') </th>
                            <th> @lang('general.Teacher') </th>
                            <th> @lang('general.Student') </th>
                            <th> @lang('general.Status')</th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lectures_f as $l)
                            <tr>
                                <td class="py-1" style="font-size: 1.5em">
                                    <i class="flag-icon {{ $l->language->icon }}"></i>
                                </td>
                                <td>{{ $l->language->name_en }} ({{ $l->hour->class_difficulty }})
                                </td>
                                <td>
                                    <b>{{$l->class_date}}</b>{{-- \Carbon\Carbon::createFromFormat("Y-m-d", $l->class_date)->format("d,M Y") --}}
                                </td>
                                <td>{{ substr($l->hour->class_start, 0, 5) }}</td>
                                <td>{{ substr($l->hour->class_end, 0, 5) }}</td>
                                <td>{{ $l->teacherHour ? __('lecture.individual') : __("lecture.collective") }}</td>
                                <td>
                                    @if($l->teacher_hour)
                                        <a href="{{ route('user.profile', $l->hour->teacher->id) }}">
                                            {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}
                                        </a>
                                    @else
                                        @if($l->hour->teacher)
                                            <a href="{{ route('user.profile', $l->hour->teacher->id) }}">
                                                {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}
                                            </a>
                                        @endif
                                        /
                                        @if($l->hour->sub_teacher)
                                            <a href="{{ route('user.profile', $l->hour->sub_teacher->id) }}">
                                                {{ $l->hour->sub_teacher->profile->first_name }} {{ $l->hour->sub_teacher->profile->last_name }}
                                            </a>
                                        @endif

                                    @endif
                                </td>
                                <td>
                                    @if(count($l->students) == 0)
                                        -
                                    @elseif(count($l->students) == 1)
                                        {{ $l->students[0]->user->name }}
                                    @else
                                        {{ count($l->students) }}
                                    @endif
                                </td>
                                <td>
                                    @if(count($l->students) == 0 or (count($l->students) < $l->hour->class_limit))
                                        <span class="badge badge-gradient-success">@lang('lecture.free')</span>
                                    @else
                                        <span class="badge badge-gradient-danger">@lang('lecture.not_free')</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-inverse-danger btn-sm btn-icon pull-right">
                                        <i
                                            class="fa fa-times"></i></button>
                                    <button
                                        onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"
                                        class="btn btn-inverse-primary btn-sm btn-icon pull-right"><i
                                            class="fa fa-search"></i></button>
                                    {{--<button onclick="window.location.href='{{ route('admin.users.index') }}'"
                                            class="btn btn-inverse-warning btn-sm btn-icon pull-right"><i
                                            class="fa fa-user"></i></button>
                                    <button onclick="window.location.href='{{ route('admin.users.index') }}'"
                                            class="btn btn-inverse-info btn-sm btn-icon pull-right"><i
                                            class="fa fa-search"></i></button>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">@lang('lecture.lectures_past')</h4>
                    <p class="card-description">@lang('lecture.lectures_past_help')</p>

                    <table class="table table-striped sortable-table" id="lectures_past">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('lecture.lecture_title') </th>
                            <th> @lang('general.Date') </th>
                            <th> @lang('lecture.start') </th>
                            <th> @lang('lecture.end') </th>
                            <th> @lang('general.Type') </th>
                            <th> @lang('general.Teacher') </th>
                            <th> @lang('general.Student') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lectures_p as $l)
                            @if($l->canceled or count($l->students) > 0)
                                <tr>
                                    <td class="py-1" style="font-size: 1.5em">
                                        <i class="flag-icon {{ $l->language->icon }}"></i>
                                    </td>
                                    <td>{{ $l->language->name_en }} ({{ $l->hour->class_difficulty }})
                                    </td>
                                    <td>
                                        <b>{{$l->class_date}}</b>{{-- \Carbon\Carbon::createFromFormat("Y-m-d", $l->class_date)->format("d,M Y") --}}
                                    </td>
                                    <td>{{ substr($l->hour->class_start, 0, 5) }}</td>
                                    <td>{{ substr($l->hour->class_end, 0, 5) }}</td>
                                    <td>{{ $l->teacherHour ? __('lecture.individual') : __("lecture.collective") }}</td>
                                    <td>
                                        <a href="{{ route('user.profile', $l->hour->teacher->id) }}">
                                            {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if(count($l->students) == 0)
                                            -
                                        @elseif(count($l->students) == 1)
                                            {{ $l->students[0]->user->name }}
                                        @else
                                            {{ count($l->students) }}
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-inverse-danger btn-sm btn-icon pull-right">
                                            <i class="fa fa-times"></i></button>
                                        <button
                                            onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"
                                            class="btn btn-inverse-primary btn-sm btn-icon pull-right"><i
                                                class="fa fa-search"></i></button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createCollectiveModal" tabindex="-1" role="dialog"
         aria-labelledby="createCollectiveModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_add_collective_hour" method="POST"
                          action="{{ route('lectures.add_collective') }}">
                        @csrf

                        <div class="row form-group">
                            <label for="day" class="col-4 col-form-label text-right">@lang('general.day')</label>
                            <div class="col-4">

                                <select class="form-control form-control-sm chosen-select"
                                        name="day[]" id="day" multiple required>
                                    <option value="1">@lang('general.monday')</option>
                                    <option value="2">@lang('general.tuesday')</option>
                                    <option value="3">@lang('general.wednesday')</option>
                                    <option value="4">@lang('general.thursday')</option>
                                    <option value="5">@lang('general.friday')</option>
                                </select>

                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.start')</label>
                            <div class="col-6">
                                <input type="time" class="form-control" min="09:00" max="17:00" name="class_start"
                                       required>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.end')</label>
                            <div class="col-6">
                                <input type="time" class="form-control" min="10:00" max="18:00" name="class_end"
                                       required>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="" class="col-4 col-form-label text-right">@lang('general.language')</label>
                            <div class="col-4">
                                <select class="form-control" name="language" id="language" required>
                                    <option value="0" disabled selected>@lang('general.select_option')</option>
                                    @foreach($languages as $l)
                                        <option value="{{ $l->id }}">{{ $l->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for=""
                                   class="col-4 col-form-label text-right">@lang('lecture.language_level')</label>
                            <div class="col-4">
                                <select class="form-control" name="level" required>
                                    <option value="1">A1</option>
                                    <option value="2">A2</option>
                                    <option value="3">B1</option>
                                    <option value="4">B2</option>
                                    <option value="5">C1</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.class_limit')</label>
                            <div class="col-4">
                                <input type="number" class="form-control" min="1" max="99" name="limit" required>

                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.class_total')</label>
                            <div class="col-4">
                                <input type="number" class="form-control" min="6" max="99" name="classes_total"
                                       required>
                            </div>
                        </div>

                        <div class="row form-group teacher_select" style="display: none">
                            <label for=""
                                   class="col-4 col-form-label text-right">@lang('general.Teacher')</label>
                            <div class="col-4">
                                <select class="form-control" name="teacher" id="teacher_select">

                                </select>
                            </div>
                        </div>

                        <div class="row form-group teacher_select" style="display: none;">
                            <label for=""
                                   class="col-4 col-form-label text-right">sub-@lang('general.Teacher')</label>
                            <div class="col-4">
                                <select class="form-control" name="sub_teacher" id="sub_teacher_select">
                                </select>
                            </div>
                        </div>

                    </form>
                    <p>* - @lang('general.required_field')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="form_add_collective_hour"
                            class="btn btn-success">@lang('general.Create')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">
@stop

@section('page_scripts')
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>

    <script>

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
                "last": "Posledná",
                "next": "Nasledujúca",
                "previous": "Predchádzajúca"
            },
            "aria": {
                "sortAscending": ": aktivujte na zoradenie stĺpca vzostupne",
                "sortDescending": ": aktivujte na zoradenie stĺpca zostupne"
            }
        };

        $(document).ready(function () {

            $("#day").chosen({
                width: "100%"
            });

            /*$("#datepicker-popup").datepicker({
                enableOnReadonly: true,
                todayHighlight: true,
            });*/

            $('#lectures_past').DataTable({
                "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                "iDisplayLength": 5,
                "language": dt_language,
                "order": [[2, 'asc']],
                "columns": [
                    {"orderable": false},
                    {"orderable": false},
                    {"orderDataType": "dom-date"},
                    {"orderDataType": "dom-time"},
                    {"orderDataType": "dom-time"},
                    null,
                    null,
                    null,
                    {"orderable": false}
                ]
            });

            $('#lectures_future').DataTable({
                "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                "iDisplayLength": 5,
                "language": dt_language,
                "order": [[2, 'asc']],
                "columns": [
                    {"orderable": false},
                    {"orderable": false},
                    {"orderDataType": "dom-date"},
                    {"orderDataType": "dom-time"},
                    {"orderDataType": "dom-time"},
                    null,
                    null,
                    null,
                    null,
                    {"orderable": false}
                ]
            });

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
                            //console.log(response.data[data]);
                            $("#teacher_select").append($("<option></option>").attr("value", response.data[data].id).html(response.data[data].name));
                            $("#sub_teacher_select").append($("<option></option>").attr("value", response.data[data].id).html(response.data[data].name));
                        }
                        ;
                    }
                })
            })
        })

    </script>
@stop
