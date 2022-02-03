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
                <li class="breadcrumb-item ">
                    <a href="{{ route("admin.teachers.index") }}">
                        @lang('side_menu.teachers')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('general.teachers_hours', ['name' => $teacher->name])
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 mb-2">
            <div class="card">
                <div class="card-body py-2">
                    <h4 class="mb-0 text-center">@lang('general.teachers_hours', ['name' => $teacher->name])</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-6 grid-margin mb-2 mb-md-4">
            <div class="card border border-danger">
                <div class="card-body py-2 px-2 px-md-4">
                    <p class="card-description text-danger mb-2 text-uppercase">@lang('lecture.teachers_lecture_unpaid_individual')
                        ({{count($lectures_ui)}})</p>
                    @if(count($lectures_ui) > 0)
                        <table class="table table-striped table-responsive" id="lectures_ui">
                            <thead>
                            <tr>
                                <th> @lang('general.Date') </th>
                                <th> @lang('general.Student') </th>
                                <th> Balíček </th>
                                <th> @lang('general.actions') </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lectures_ui as $l)
                                <tr>
                                    <td>
                                        <b>{{$l->class_date}}</b>
                                    </td>
                                    <td>
                                        @if(count($l->students) == 0)
                                            -
                                        @elseif(count($l->students) == 1)
                                            {{ $l->students[0]->user->name }}
                                        @else
                                            @lang('general.students'): {{ count($l->students) }}
                                        @endif
                                    </td>
                                    <td>{{ \App\Models\Helper::PACKAGES[$l->students[0]->getUsedPackage()]['name'] }}</td>
                                    <td>
                                        <button
                                            onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"
                                            class="btn btn-inverse-primary btn-sm pull-right"><i
                                                class="fa fa-search"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin mb-2 mb-md-4">
            <div class="card border border-success">
                <div class="card-body py-2 px-2 px-md-4">
                    <p class="card-description text-success mb-2">@lang('lecture.teachers_lecture_paid_individual')
                        ({{count($lectures_pi)}})
                        <input type="hidden" id="export_teacher_id" value="{{ $teacher->id }}">
                        <select id="export_date" class="">
                            <option value="all" selected>All</option>
                            @foreach($export_dates as $ed)
                                <option value="{{ $ed }}">{{ $ed }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-sm btn-success" id="export_button">Export</button>
                    </p>
                    @if(count($lectures_pi) > 0)
                        <table class="table table-striped table-responsive" id="lectures_pi">
                            <thead>
                            <tr>
                                <th> @lang('general.Date') </th>
                                <th> @lang('general.Student') </th>
                                <th> Balíček </th>
                                <th> @lang('general.actions') </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lectures_pi as $l)
                                <tr>
                                    <td>
                                        <b>{{$l->class_date}}</b>
                                    </td>
                                    <td>
                                        @if(count($l->students) == 0)
                                            -
                                        @elseif(count($l->students) == 1)
                                            {{ $l->students[0]->user->name }}
                                        @else
                                            @lang('general.students'): {{ count($l->students) }}
                                        @endif
                                    </td>
                                    <td>{{ \App\Models\Helper::PACKAGES[$l->students[0]->getUsedPackage()]['name'] }}</td>
                                    <td>
                                        <button
                                            onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"
                                            class="btn btn-inverse-primary btn-sm pull-right"><i
                                                class="fa fa-search"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
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
        $(document).ready(function () {

            $("#day").chosen({
                width: "100%"
            });

            $('#lectures_ui').DataTable({
                "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                "iDisplayLength": 10,
                "language": dt_language,
                "ordering": true,
                "searching": false,
                "lengthChange": false,
                "order": [[0, "desc"]],
                "columns": [
                    {"orderDataType": "dom-date"},
                    null,
                    null,
                    {"orderable": false}
                ]
            });
            $('#lectures_pi').DataTable({
                "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                "iDisplayLength": 10,
                "language": dt_language,
                "ordering": !window.mobilecheck(),
                "searching": true,
                "lengthChange": false,
                "order": [[0, "desc"]],
                "columns": [
                    {"orderDataType": "dom-date"},
                    null,
                    null,
                    {"orderable": false}
                ]
            });


            $("#export_button").click(function () {
                let val = $("#export_date option:selected").val();
                let tid = $("#export_teacher_id").val();

                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "export_teachers_paid_classes",
                        date: val,
                        teacher_id: tid,
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        console.log(response.csv_string);
                        const blob = new Blob([response.csv_string], {type:"application/csv"});
                        blob.name = "export.csv";
                        const reader = new FileReader();
                        reader.onload = e => {
                            const anchor = document.createElement('a');
                            anchor.style.display = 'none';
                            anchor.href = e.target.result;
                            anchor.download = blob.name;
                            anchor.click();
                        };
                        reader.readAsDataURL(blob);
                    },
                    error: function (response) {
                        $.toast({
                            heading: 'Error',
                            text: 'AJAX-Error',
                            position: 'bottom-right',
                            icon: 'error',
                            stack: false,
                            loaderBg: '#ed3939',
                            bgColor: '#f0aaaa',
                            textColor: 'black'
                        })
                    }
                });
                return true;
            })

        })

    </script>
@stop
