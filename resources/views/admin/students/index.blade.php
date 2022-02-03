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
                        <h4 class="card-title">@lang('users.system_students') ({{ count($users) }})</h4>
                    </div>
                    <div class="col-12 col-md-4 text-right order-1 order-md-2">
                        <a href="{{ route('admin.users.create') }}"
                           class="btn btn-success btn-block btn-sm"><i
                                    class="fa fa-plus"></i> @lang('users.user_create')
                        </a>
                    </div>
                    <p class="card-description"></p>

                    <form method="get" class="filter">
                        @csrf
                        <input type="hidden" name="filtered" value="1">

                        <div class="row form-group mb-3 ml-2 ml-md-0">
                            <div class="col-6 col-md-2">
                                <label class="col-form-label py-0 m-0" for="f_lang">@lang('general.language')</label>
                                <select
                                        class="form-control @if(isset($_GET['f_lang']) and $_GET['f_lang'] != 0) text-primary @endif"
                                        name="f_lang" id="f_lang">
                                    <option value="0"
                                            @if(!isset($_GET['f_lang']) or (isset($_GET['f_lang']) and $_GET['f_lang'] == 0)) selected @endif>@lang('general.select_option')</option>
                                    @foreach(\App\Models\Language::all() as $l)
                                        <option value="{{ $l->id }}"
                                                @if(isset($_GET['f_lang']) and $_GET['f_lang'] == $l->id) selected @endif>{{ $l->name_sk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="col-form-label py-0 m-0" for="f_hours">Počet hodín</label>
                                <select
                                    class="form-control @if(isset($_GET['f_hours']) and $_GET['f_hours'] != 0) text-primary @endif"
                                    name="f_hours" id="f_hours">
                                    <option value="0"
                                            @if(!isset($_GET['f_hours']) or (isset($_GET['f_hours']) and $_GET['f_hours'] == 0)) selected @endif>@lang('general.select_option')</option>
                                    <option value="1"
                                            @if(isset($_GET['f_hours']) and $_GET['f_hours'] == 1) selected @endif>Vzostupne</option>
                                    <option value="2"
                                            @if(isset($_GET['f_hours']) and $_GET['f_hours'] == 2) selected @endif>Zostupne</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter
                                </button>
                                @if(isset($_GET['filtered']))
                                    <a href="{{ route('admin.users.students.index') }}" class="btn btn-danger btn-sm"><i
                                                class="fa fa-times"></i></a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <hr>
                    <table class="table table-striped px-1 px-md-0 table-condensed" id="table_users_mobile"
                           style="display: none; width: 100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
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
                                <td><a href="{{ route('user.profile', $u->id) }}" class="text-primary">
                                        {{ $u->profile->first_name }} {{ $u->profile->last_name }}</a>
                                    @if($u->profile->stars_collective + \App\Models\User\Student::stars_c_reserved($u->id) > 0 and !$u->has_future_cc)
                                        <i class="mdi mdi-account-multiple-minus text-danger"
                                           data-custom-class="tooltip-danger"
                                           data-toggle="tooltip"
                                           data-placement="top" title=""
                                           data-original-title="Nie je zapísaný v žiadnej skupinovej hodine"></i>
                                    @endif
                                    @if($u->new_user) <span class="badge badge-gradient-golden"> Nový </span> @endif<br>
                                    @if($u->is_active) <span class="text-success"> <b>Aktívny</b> </span><br> @endif
                                    <small>{{ $u->email }}</small><br>
                                    <small>
                                        {{ $u->currentPackage ? ($u->currentPackage->getName()." (".$u->currentPackage->classes_left."|". \App\Models\User\Student::stars_i_reserved($u->id) .")") : "-" }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <button onclick="window.location.href='{{ route('user.profile', $u->id) }}'"
                                            class="btn btn-inverse-info btn-sm"><i
                                                class="fa fa-search"></i></button>
                                </td>
                                <td>{{ intval($u->new_user) }}</td>
                                <td>{{ $u->is_active ? 1 : 0 }}</td>
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
                            <th> @lang('general.active_package')
                                <span
                                        data-custom-class="tooltip-info" data-toggle="tooltip"
                                        data-placement="top" title=""
                                        data-original-title="Nerezervované | Rezervované(neabsolvované)"
                                ><i class="fa fa-question-circle"></i></span>
                            </th>
                            <th> @lang('general.actions') </th>
                            <th></th>
                            <th></th>
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
                                        {{ $u->profile->first_name }} {{ $u->profile->last_name }}</a>
                                    @if($u->new_user) <span class="text-golden"> <b>Nový</b> </span> @endif
                                    @if($u->is_active) <span class="text-success"> <b>Aktívny</b> </span> @endif
                                </td>
                                <td> {{ $u->email }}</td>
                                <td>
                                    {{ $u->currentPackage ? ($u->currentPackage->getName()." (".$u->currentPackage->classes_left."|". \App\Models\User\Student::stars_i_reserved($u->id) .")") : "-" }}
                                </td>
                                <td>
                                    <button onclick="window.location.href='{{ route('user.profile', $u->id) }}'"
                                            class="btn btn-inverse-info btn-sm mx-1 pull-right"><i
                                                class="fa fa-search"></i> @lang('general.profile')</button>
                                </td>
                                <td>{{ intval($u->new_user) }}</td>
                                <td>{{ $u->is_active ? 1 : 0 }}</td>
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
            if (window.mobilecheck()) {
                $('#table_users_mobile').show();
                $('#table_users_mobile').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 25,
                    "lengthChange": false,
                    "language": dt_language,
                    "order": [[3, 'desc'], [4, 'desc']],
                    "columns": [
                        {"orderable": false},
                        null,
                        {"orderable": false},
                        {"visible": false},
                        {"visible": false},
                    ]
                });
            } else {
                $('#table_users_pc').show();
                $('#table_users_pc').DataTable({
                    "aLengthMenu": [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    "iDisplayLength": 25,
                    "lengthChange": false,
                    "language": dt_language,
                    "order": [[5, 'desc'], [6, 'desc']],
                    "columns": [
                        {"orderable": false},
                        null,
                        null,
                        null,
                        {"orderable": false},
                        {"visible": false},
                        {"visible": false},
                    ]
                });
            }
        })

    </script>
@stop
