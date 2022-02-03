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
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.languages.index') }}" class="text-primary">
                        @lang('side_menu.Languages')
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {!! __('language.language_lectors', ["icon" => $language->icon]) !!}
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12">
                        <h4 class="card-title">{!! __('language.language_lectors', ["icon" => $language->icon]) !!}</h4>
                        <p class="card-description">@lang('language.language_lectors_help')</p>
                    </div>

                    <table class="table table-striped table-responsive" id="table_language_teachers_mobile"
                           style="display: none; width: 100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('general.detail') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teachers as $t)
                            <tr>
                                <td class="py-1" style="font-size: 1.5em">
                                    <img src="{{ $t->profile->getProfileImage() }}" class="img-lg rounded-circle">
                                </td>
                                <td>
                                    {{ $t->name }}
                                    <br>
                                    <small>{{ $t->email }}</small>
                                </td>
                                <td>
                                    <button onclick="window.location.href='{{ route('user.profile', $t->id) }}'"
                                            class="btn btn-inverse-info btn-sm pull-right"><i
                                            class="fa fa-search"></i> @lang('general.profile')</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table table-striped table-responsive" id="table_language_teachers_pc"
                           style="display: none;">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('general.Name_surname') </th>
                            {{--<th> @lang('profile.stars_i') </th>
                            <th> @lang('profile.stars_c') </th>--}}
                            <th> @lang('general.Email') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teachers as $t)
                            <tr>
                                <td class="py-1" style="font-size: 1.5em">
                                    <img src="{{ $t->profile->getProfileImage() }}" class="img-lg rounded-circle">
                                </td>
                                <td> {{ $t->name }}</td>
                                <td> {{ $t->email }}</td>
                                {{--<td> {{ $t->stars_i() }}</td>
                                <td> {{ $t->stars_c() }}</td>--}}

                                <td>
                                    <button onclick="window.location.href='{{ route('user.profile', $t->id) }}'"
                                            class="btn btn-inverse-info btn-sm pull-right"><i
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

            if (window.mobilecheck()) {
                $("#table_language_teachers_mobile").show();
            } else {
                $("#table_language_teachers_pc").show();
            }

        })

    </script>
@stop
