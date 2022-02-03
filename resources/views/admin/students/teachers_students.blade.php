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
                    @lang('side_menu.my_students')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <h4 class="card-title">
                        @if(isset($admin))
                            @lang('general.teachers_students', ['name'=>$teacher->name])
                        @else
                            @lang('side_menu.my_students')
                        @endif

                        ({{ count($users) }})
                    </h4>
                    <hr>
                    <table class="table table-striped pl-1 px-md-0" id="table_users_mobile"
                           style="display: none; width: 100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('general.Name_surname') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td class="p-1 text-center" style="font-size: 1.5em">
                                    <img src="{{ $u->profile->getProfileImage() }}"
                                         class="{{ $u->is_online() ? ($u->is_online() == 1 ? 'profile_img_online':($u->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                                         alt="{{$u->profile->first_name}} {{$u->profile->last_name}}'s profile picture">
                                </td>
                                <td>
                                    <a href="{{ route('user.profile', $u->id) }}" class="text-primary">
                                        {{ $u->profile->first_name }} {{ $u->profile->last_name }}</a><br>
                                    <small>{{ $u->email }}</small>
                                </td>
                                <td>
                                    <button onclick="window.location.href='{{ route('user.profile', $u->id) }}'"
                                            class="btn btn-inverse-info btn-sm mx-1 pull-right"><i
                                            class="fa fa-search"></i> @lang('general.profile')</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="table table-striped" id="table_users_pc" style="display: none;">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('general.Name_surname') </th>
                            <th> @lang('general.Email') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td class="py-1">
                                    <img src="{{ $u->profile->getProfileImage() }}"
                                         class="{{ $u->is_online() ? ($u->is_online() == 1 ? 'profile_img_online':($u->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                                         alt="{{$u->profile->first_name}} {{$u->profile->last_name}}'s profile picture">
                                </td>
                                <td><a href="{{ route('user.profile', $u->id) }}" class="text-primary">
                                        {{ $u->profile->first_name }} {{ $u->profile->last_name }}</a></td>
                                <td> {{ $u->email }}</td>
                                <td>
                                    <button onclick="window.location.href='{{ route('user.profile', $u->id) }}'"
                                            class="btn btn-inverse-info btn-sm mx-1 pull-right"><i
                                            class="fa fa-search"></i> @lang('general.profile')</button>
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
                    "previous": "Predch."
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
                    "order": [[1, 'asc']],
                    "columns": [
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
                    "order": [[1, 'asc'], [2, 'asc']],
                    "columns": [
                        {"orderable": false},
                        null,
                        null,
                        {"orderable": false}
                    ]
                });
            }

        })

    </script>
@stop
