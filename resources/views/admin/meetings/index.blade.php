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
                    @lang('side_menu.meetings')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        @lang('meeting.listing')
                        <a href="{{ route('admin.meetings.create') }}"
                           class="btn btn-gradient-success pull-right btn-sm">
                            @lang('meeting.create_new_btn')
                        </a>
                    </h4>

                    <table class="table table-striped" id="meetings_table">
                        <thead>
                        <tr>
                            <th>@lang('general.Date')</th>
                            <th>@lang('general.Type')</th>
                            <th>@lang('general.comment')</th>
                            <th>@lang('general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($meetings as $m)
                            <tr>
                                <td>
                                    <b>{{ \Carbon\Carbon::createFromFormat("Y-m-d",$m->day)->day.".".__('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d",$m->day)->month)." ".\Carbon\Carbon::createFromFormat("Y-m-d",$m->day)->year }}
                                        {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$m->start)->format("H:i")."-".\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$m->end)->format("H:i") }}</b>
                                </td>
                                <td>{{ $m->type == 1 ? __('meeting.all_school') : ($m->type == 2 ? __('language.teachers') .": ". $m->language->name_sk : __('meeting.custom')) }}</td>
                                <td>
                                    {{ $m->comment }}
                                    {{--@foreach($m->members as $mm)
                                        {{ $mm->profile->last_name.", " }}
                                    @endforeach--}}
                                </td>
                                <td>
                                    <a href="#"
                                       class="btn btn-sm btn-inverse-danger pull-right delete-alert"
                                       data-item-id="{{ $m->id }}"><i
                                            class="fa fa-times"></i></a>
                                    {{ Form::open(['method' => 'DELETE',
                                    'route' => ['admin.meetings.destroy', $m->id],
                                    'id' => 'item-del-'. $m->id  ]) }}
                                    {{ Form::hidden('meeting_id', $m->id) }}
                                    {{ Form::close() }}
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
                var id = $(this).attr("data-item-id");
                console.log(id);
                swal({
                    title: "Prosím podvtďte akciu",
                    text: "Akcia: zručenie porady.",
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
