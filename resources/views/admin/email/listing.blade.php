@extends('layouts.app')

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
                    @lang('side_menu.Emails')
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">{{ __('email.recipients') }}</th>
                            <th scope="col">{{ __('email.module') }}</th>
                            <th scope="col">{{ __('email.send_time') }}</th>
                            <th scope="col">@lang('general.Status')</th>
                            <th scope="col">{{ __('general.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($emails as $em)
                            <tr class="text-center">
                                <th scope="row">
                                    <small>(#{{ $em->id }})</small>
                                </th>
                                <th scope="row">
                                    <small>{{ $em->recipients }}</small>
                                </th>
                                <th scope="row">
                                    <small>{{ $em->module }}</small>
                                </th>
                                <th scope="row">
                                    <small>{{ $em->send_time }}</small>
                                </th>
                                <td>
                                    @if($em->status == 0)
                                        <span class="badge badge-danger"><i class="fa fa-minus fa-fw"></i></span>
                                    @else
                                        <span class="badge badge-success"><i class="fa fa-check fa-fw"></i></span>
                                    @endif
                                </td>
                                <td>

                                    <a href="#!" data-item-id="{{ $em->id }}"
                                       class="btn btn-danger btn-sm listing_controls pull-right delete-alert"><i
                                            class="fa fa-times fa-fw"></i></a>
                                    {{ Form::open(['method' => 'DELETE', 'route' => ['admin.email-queue.destroy', $em->id ],
                                            'id' => 'item-del-'. $em->id  ])
                                        }}
                                    {{ Form::hidden('email_id', $em->id) }}
                                    {{ Form::close() }}

                                    <a href="{{ route('admin.email-queue.send_one', $em->id) }}"
                                       class="btn btn-sm pull-right btn-primary">
                                        <i class="fa fa-send"></i>
                                    </a>
                                    <a href="{{ route('admin.email-queue.preview', $em->id) }}"
                                       class="btn btn-sm pull-right btn-warning" target="_blank">
                                        <i class="fa fa-search"></i>
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

@section('scripts')
    <script>

        $(document).ready(function () {

            $('.delete-alert').click(function (e) {
                var id = $(e.currentTarget).attr("data-item-id");
                swal({
                    title: "DANGER ZONE! Are you sure you want to proceed?",
                    text: "Email will be deleted from queue.",
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
