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
                    @lang('side_menu.gift_codes')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('giftcodes.unused')
                        <a href="{{ route("admin.gift_codes.create") }}"
                           class="btn btn-sm btn-gradient-success pull-right">
                            <i class="fa fa-plus"></i> @lang('giftcodes.add_new')
                        </a>
                    </h4>

                    <table class="table table-striped table-condensed">
                        <thead>
                        <tr>
                            <td>@lang('giftcodes.code')</td>
                            <td>{{ __('order.package') }}</td>
                            <td>Počet hodín v balíku</td>
                            <td>@lang('general.language')</td>
                            <td>@lang('giftcodes.created')</td>
                            <td>@lang('general.actions')</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($unused as $gc)
                            <tr>
                                <td><b>{{ $gc->code }}</b></td>
                                <td>{{ \App\Models\Helper::PACKAGES[$gc->package_id]['name'] }}</td>
                                <td>{{ $gc->package_class_count }}</td>
                                <td>{{ $gc->language_id ? $gc->language->name_en : "---" }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $gc->created_at)->format("Y-m-d") }}</td>
                                <td>
                                    <a href="#"
                                       class="text-danger pull-right delete-alert btn btn-sm btn-block btn-inverse-danger"
                                       data-item-id="{{ $gc->id }}"><i
                                            class="fa fa-times"></i></a>
                                    {{ Form::open(['method' => 'DELETE',
                                    'route' => ['admin.gift_codes.destroy', $gc->id],
                                    'id' => 'item-del-'. $gc->id  ]) }}
                                    {{ Form::hidden('hour_id', $gc->id) }}
                                    {{ Form::close() }}
                                    <a href="{{ route("admin.gift_codes.edit", $gc->id) }}"
                                       class="btn btn-inverse-info pull-right btn-sm btn-block">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('giftcodes.used')</h4>

                    <table class="table table-striped table-condensed" id="giftcodes_used_table">
                        <thead>
                        <tr>
                            <td>@lang('giftcodes.code')</td>
                            <td>{{ __('order.package') }}</td>
                            <td>Počet hodín v balíku</td>
                            <td>@lang('general.language')</td>
                            <td>@lang('giftcodes.used_by')</td>
                            <td>@lang('giftcodes.used_date')</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($used as $gc)
                            <tr>
                                <td><b>{{ $gc->code }}</b></td>
                                <td>{{ \App\Models\Helper::PACKAGES[$gc->package_id]['name'] }}</td>
                                <td>{{ $gc->package_class_count }}</td>
                                <td>{{ $gc->language_id ? $gc->language->name_en : "---" }}</td>
                                <td>
                                    <img src="{{ $gc->redeemer->profile->getProfileImage() }}" class="img-sm">
                                    <a href="{{ route("user.profile", $gc->redeemer->id) }}">{{ $gc->redeemer->name }}</a>
                                </td>
                                <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $gc->updated_at)->format("Y-m-d") }}</td>
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
            $('#giftcodes_used_table').DataTable({
                "aLengthMenu": [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                "iDisplayLength": 5,
                "language": dt_language,
                "order": [[5, 'desc']],
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {"orderDataType": "dom-date"},
                ]
            });

            $('.delete-alert').click(function (e) {
                var id = $(this).attr("data-item-id");
                console.log(id);
                swal({
                    title: "@lang('general.warning')",
                    text: "@lang('giftcodes.delete_warning')",
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
