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
                    @lang('side_menu.Users')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12 col-md-8 order-2 order-md-1">
                        <h4 class="card-title">@lang('users.system_users') ({{ count($users) }})</h4>
                    </div>
                    <div class="col-12 col-md-4 text-right order-1 order-md-2">
                        <a href="{{ route('admin.users.create') }}"
                           class="btn btn-success btn-sm mb-2 mb-md-0 btn-block"><i
                                class="fa fa-plus"></i> @lang('users.user_create')
                        </a>
                    </div>
                    <p class="card-description mb-2 mb-md-3">@lang('users.system_users_help')</p>

                    <table class="table table-striped table-condensed px-1 px-md-0" id="table_users_mobile"
                           style="display: none; width: 100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th> @lang('general.Name_surname') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>
                                    <small>{{ $u->id }}</small>
                                </td>
                                <td class="p-1 text-center" style="font-size: 1.5em">
                                    <img src="{{ $u->profile->getProfileImage() }}"
                                         class="{{ $u->is_online() ? ($u->is_online() == 1 ? 'profile_img_online':($u->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                                         alt="{{$u->profile->first_name}} {{$u->profile->last_name}}'s profile picture">
                                </td>
                                <td>
                                    @if($u->active)
                                        <span class="text-success"> <i
                                                class="mdi mdi-check-circle"></i></span>
                                    @else
                                        <span class="text-danger"> <i
                                                class="fa fa-times-circle"></i></span>
                                    @endif
                                    {{ $u->profile->first_name }} {{ $u->profile->last_name }} |
                                    @foreach ($u->roles as $role)
                                        <span
                                            class="text-{{ $role->name == 'teacher' ? "primary" : ($role->name == 'student' ? 'success' : ($role->name == 'admin' ? 'info' : ($role->name == 'developer' ? "danger" : "secondary")))  }}">
                                        {{ $role->display_name[0] }}
                                    </span>
                                    @endforeach
                                    <br>
                                    <small>{{ $u->email }}</small>
                                </td>
                                <td class="text-right">
                                    <button onclick="window.location.href='{{ route('user.profile', $u->id) }}'"
                                            class="btn btn-inverse-info btn-sm"><i
                                            class="fa fa-search"></i></button>
                                    <button
                                        onclick="window.location.href='{{ route('admin.users.edit', $u->id) }}'"
                                        class="btn btn-inverse-success btn-sm"><i
                                            class="fa fa-cog"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="table table-striped table-condensed" id="table_users_pc" style="display: none;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th> @lang('general.Name_surname') </th>
                            <th> @lang('general.Email')</th>
                            <th> @lang('general.role') </th>
                            <th><i class="mdi mdi-lock-open-outline"></i></th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td class="py-1 py-md-3">
                                    <small>{{ $u->id }}</small>
                                </td>
                                <td class="p-0 text-center" style="font-size: 1.5em">

                                    <img src="{{ $u->profile->getProfileImage() }}"
                                         class="{{ $u->is_online() ? ($u->is_online() == 1 ? 'profile_img_online':($u->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                                         alt="{{$u->profile->first_name}} {{$u->profile->last_name}}'s profile picture">
                                </td>
                                <td class="py-1 py-md-3">
                                    {{ $u->profile->first_name }} {{ $u->profile->last_name }}
                                </td>
                                <td>{{ $u->email }}</td>
                                <td class="py-1 py-md-3">
                                    @foreach ($u->roles as $role)
                                        <span
                                            class="badge badge-gradient-{{ $role->name == 'teacher' ? "primary" : ($role->name == 'student' ? 'success' : ($role->name == 'admin' ? 'info' : ($role->name == 'developer' ? "danger" : "secondary")))  }}">
                                        {{ $role->display_name }}
                                    </span>
                                    @endforeach
                                </td>
                                <td class="py-1 py-md-3">
                                    @if($u->active)
                                        <span class="badge badge-gradient-success"> <i
                                                class="mdi mdi-check-circle"></i></span>
                                    @else
                                        <span class="badge badge-gradient-danger"> <i
                                                class="fa fa-times-circle"></i></span>
                                    @endif
                                </td>
                                <td class="py-1 py-md-3 text-right">
                                    <button onclick="window.location.href='{{ route('user.profile', $u->id) }}'"
                                            class="btn btn-inverse-info btn-sm"><i
                                            class="fa fa-search"></i></button>
                                    <button
                                        onclick="window.location.href='{{ route('admin.users.edit', $u->id) }}'"
                                        class="btn btn-inverse-success btn-sm"><i
                                            class="fa fa-cog"></i></button>
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
                    "previous": "Pred."
                },
                "aria": {
                    "sortAscending": ": aktivujte na zoradenie stĺpca vzostupne",
                    "sortDescending": ": aktivujte na zoradenie stĺpca zostupne"
                }
            };

            if (window.mobilecheck()) {
                $('#table_users_mobile').show();
                $('#table_users_mobile').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 10,
                    "language": dt_language,
                    "order": [[0, 'asc']],
                    "columns": [
                        {"visible": false},
                        {"orderable": false},
                        null,
                        {"orderable": false}
                    ]
                });
            } else {
                $('#table_users_pc').show();
                $('#table_users_pc').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 10,
                    "language": dt_language,
                    "order": [[0, 'asc']],
                    "columns": [
                        null,
                        {"orderable": false},
                        null,
                        {"orderable": false},
                        {"orderable": false},
                        {"orderable": false},
                        {"orderable": false}
                    ]
                });
            }

        })

    </script>
@stop
