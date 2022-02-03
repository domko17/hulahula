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
                    @lang("side_menu.materials")
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <h4 class="card-title">@lang('side_menu.materials')
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin') or \Illuminate\Support\Facades\Auth::user()->hasRole('teacher'))
                            <a href="{{ route("materials.create") }}"
                               class="btn btn-gradient-success btn-sm pull-right">
                                <i class="fa fa-plus"></i> @lang('general.add_material')
                            </a>
                        @endif
                    </h4>
                    <p class="card-description"></p>

                    @if( !empty($material))
                        <table class="table table-striped" id="materials_table_mobile" style="display: none">
                            <thead>
                            <tr>
                                <th></th>
                                <th>@lang('general.title')</th>
                                <th>@lang('general.actions')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($material as $m)
                                <tr>
                                    <td><i class="flag-icon {{ $m->language->icon }}"></i></td>
                                    <td>
                                        <b>{{ $m->name }}</b><br>
                                        @lang('general.inserted_by'): {{ $m->user->name }}<br>
                                        @lang('general.Type'): {{ $m->get_type_name() }}
                                    </td>
                                    <td>
                                        @if($m->type == 1)
                                            <a href="{{ $m->content }}" class="btn btn-gradient-primary btn-sm"
                                               target="_blank"><i class="fa fa-external-link"></i>
                                            </a>
                                        @endif
                                        @if($m->type == 2)
                                            <a href="{{ $m->content }}" class="btn btn-gradient-primary btn-sm"
                                               target="_blank"><i class="fa fa-youtube"></i></a>
                                        @endif
                                        @if($m->type == 3)
                                            <a href="{{ route('materials.download', $m->id) }}"
                                               class="btn btn-gradient-primary btn-sm"
                                            ><i class="fa fa-download"></i></a>
                                        @endif
                                        @if(Auth::user()->hasRole('admin') or Auth::id() == $m->added_by)
                                            <a href="#" class="btn btn-inverse-danger btn-sm delete-alert"
                                               data-item-id="{{ $m->id }}">
                                                <i class="fa fa-times"></i></a>
                                            {{ Form::open(['method' => 'DELETE',
                                              'route' => ['materials.destroy', $m->id],
                                              'id' => 'item-del-'. $m->id  ]) }}
                                            {{ Form::close() }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <table class="table table-striped" id="materials_table_pc" style="display: none">
                            <thead>
                            <tr>
                                <th></th>
                                <th>@lang('general.title')</th>
                                <th>@lang('general.inserted_by')</th>
                                <th>@lang('general.Type')</th>
                                <th>@lang('general.actions')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($material as $m)
                                <tr>
                                    <td><i class="flag-icon {{ $m->language->icon }}"></i></td>
                                    <td>{{ $m->name }}</td>
                                    <td>{{ $m->user->name }}</td>
                                    <td>{{ $m->get_type_name() }}</td>
                                    <td>

                                        @if(Auth::user()->hasRole('admin') or Auth::id() == $m->added_by)
                                            <a href="#" class="btn btn-inverse-danger btn-sm pull-right delete-alert"
                                               data-item-id="{{ $m->id }}">
                                                <i class="fa fa-times"></i></a>
                                            {{ Form::open(['method' => 'DELETE',
                                              'route' => ['materials.destroy', $m->id],
                                              'id' => 'item-del-'. $m->id  ]) }}
                                            {{ Form::close() }}
                                        @endif
                                        @if($m->type == 1)
                                            <a href="{{ $m->content }}"
                                               class="btn btn-gradient-primary btn-sm pull-right"
                                               target="_blank"><i
                                                        class="fa fa-external-link"></i> @lang('general.url_link')
                                            </a>
                                        @endif
                                        @if($m->type == 2)
                                            <a href="{{ $m->content }}"
                                               class="btn btn-gradient-primary btn-sm pull-right"
                                               target="_blank"><i class="fa fa-youtube"></i> YouTube</a>
                                        @endif
                                        @if($m->type == 3)
                                            <a href="{{ route('materials.download', $m->id) }}"
                                               class="btn btn-gradient-primary btn-sm pull-right"
                                            ><i class="fa fa-download"></i> @lang('general.download')</a>
                                        @endif
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

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {
            @if( !empty($material))
            if (window.mobilecheck()) {
                $('#materials_table_mobile').show();
                $('#materials_table_mobile').DataTable({
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
                        {"orderable": false}
                    ]
                });
            } else {
                $('#materials_table_pc').show();
                $('#materials_table_pc').DataTable({
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
                        null,
                        {"orderable": false}
                    ]
                });
            }
            @endif

            $('.delete-alert').click(function (e) {
                var id = $(this).attr("data-item-id");
                console.log(id);
                swal({
                    title: "Pozor!",
                    text: "Potvrďte mazanie materiálu",
                    icon: "error",
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
