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
                    @lang('side_menu.banners')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('side_menu.banners')
                        <a href="{{ route('admin.banners.create') }}"
                           class="btn btn-sm pull-right btn-gradient-success">
                            @lang('banners.create_new')
                        </a>
                    </h4>
                    <p class="card-description"></p>

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>@lang('banners.title')</th>
                            <th>@lang('banners.description')</th>
                            <th>@lang('general.Type')</th>
                            <th>@lang('banners.active')?</th>
                            <th style="width: 15%">@lang('general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banners as $b)
                            <tr>
                                <td>{{ strlen($b->title) == 0 ? __('general.unset') : $b->title }}</td>
                                <td>{{ strlen($b->description) == 0 ? __('general.unset') : $b->description }}</td>
                                <td>{{ $b->type == 1 ? "Text" : ($b->type == 2 ? __('general.image') : __('general.video')) }}</td>
                                <td class="text-center"><span
                                        class="badge badge-gradient-{{ $b->active ? "success" : "danger" }}">
                                         <i class="fa fa-{{ $b->active ? "check" : "times-circle" }}"></i>
                                    </span></td>
                                <td style="font-size: 1.25em" class="text-right px-0">
                                    <a href="#!" data-item-id="{{ $b->id }}"
                                       class="text-danger pull-right delete-alert px-1"
                                       data-custom-class="tooltip-danger"
                                       data-toggle="tooltip"
                                       data-placement="top" title=""
                                       data-original-title="@lang('general.delete')"><i
                                            class="fa fa-times fa-fw"></i></a>
                                    {{ Form::open(['method' => 'DELETE', 'route' => ['admin.banners.destroy', $b->id ],
                                            'id' => 'item-del-'. $b->id  ])
                                        }}
                                    {{ Form::hidden('banner_id', $b->id) }}
                                    {{ Form::close() }}
                                    <a href="{{ route('admin.banners.edit', $b->id) }}" class="pull-right text-info px-1"
                                       data-custom-class="tooltip-info"
                                       data-toggle="tooltip"
                                       data-placement="top" title=""
                                       data-original-title="@lang('general.edit')">
                                        <i class="fa fa-edit fa-fw"></i>
                                    </a>
                                    <a href="{{ route('admin.banners.toggle_active', $b->id) }}"
                                       class="px-1 pull-right text-{{ $b->active ? "danger" : "success" }}"
                                       data-custom-class="tooltip-silverish"
                                       data-toggle="tooltip"
                                       data-placement="top" title=""
                                       data-original-title="@lang('banners.toggle_active')">
                                        <i class="fa fa-fw fa-{{ $b->active ? "times-circle" : "check" }}"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
            $('.delete-alert').click(function (e) {
                var id = $(e.currentTarget).attr("data-item-id");
                swal({
                    title: "DANGER ZONE! Are you sure you want to proceed?",
                    text: "Banner will be deleted.",
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
