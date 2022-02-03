@extends('layouts.app')

@section('title')
    @lang('titles.language_listing')
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
                    @lang('side_menu.Languages')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12 col-md-8 order-2 order-md-1">
                        <h4 class="card-title">@lang('language.languages_we_teach')</h4>
                        <p class="card-description">@lang('language.languages_we_teach_description')</p>
                    </div>
                    <div class="col-12 col-md-4 text-right order-1 order-md-2">
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.languages.create') }}"
                               class="btn btn-success pull-right btn-sm btn-block"><i
                                    class="fa fa-plus"></i> @lang('language.language_add')</a>
                        @endif
                    </div>

                    <table class="table table-striped table-responsive" id="table_languages_mobile"
                           style="display: none; width: 100%;">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('general.detail') </th>
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($languages as $l)
                            <tr>
                                <td class="py-1" style="font-size: 1.5em">
                                    <i class="flag-icon {{ $l->icon }}"></i>
                                </td>
                                <td>
                                    {{ $l->name_sk }}
                                    <br>
                                    @if(Auth::user()->hasRole('admin'))
                                        T: {{ count($l->teachers) }}<br>
                                        S: {{ count($l->students) }}
                                    @endif
                                </td>
                                <td>
                                    <button
                                        onclick="window.location.href='{{ route('admin.languages.show', $l->id) }}'"
                                        class="btn btn-inverse-info btn-sm"><i
                                            class="fa fa-search"></i></button>
                                    @if(Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher') or (Auth::user()->hasRole('student') and Auth::user()->studying()->where('language_id', $l->id)->first()))
                                        <button
                                            onclick="window.location.href='{{ route('admin.languages.teachers', $l->id) }}'"
                                            class="btn btn-inverse-success btn-sm"><i
                                                class="fa fa-users"></i></button>
                                    @endif
                                    @if(Auth::user()->hasRole('admin') or (Auth::user()->hasRole('teacher') and Auth::user()->teaching()->where("language_id", $l->id)->first()))
                                        <button
                                            onclick="window.location.href='{{ route('admin.word_cards.index_language', $l->id) }}'"
                                            class="btn btn-inverse-primary btn-sm"><i
                                                class="mdi mdi-cards"></i></button>
                                    @endif
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                        <button
                                            onclick="window.location.href='{{ route('admin.languages.edit', $l->id) }}'"
                                            class="btn btn-inverse-warning btn-sm"><i
                                                class="fa fa-edit"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table table-striped table-responsive" id="table_languages_pc" style="display: none">
                        <thead>
                        <tr>
                            <th></th>
                            <th> @lang('general.language') </th>
                            @if(Auth::user()->hasRole('admin'))
                                <th> @lang('general.lectors') </th>
                                <th> @lang('general.students') </th>
                            @endif
                            <th> @lang('general.actions') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($languages as $l)
                            <tr>
                                <td class="py-1" style="font-size: 1.5em">
                                    <i class="flag-icon {{ $l->icon }}"></i>
                                </td>
                                <td> {{ $l->name_en }}</td>
                                @if(Auth::user()->hasRole('admin'))
                                    <td> {{ count($l->teachers) }}</td>
                                    <td> {{ count($l->students) }}</td>
                                @endif
                                <td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                        <button
                                            onclick="window.location.href='{{ route('admin.languages.edit', $l->id) }}'"
                                            class="btn btn-inverse-warning btn-sm pull-right"><i
                                                class="fa fa-edit"></i></button>
                                    @endif
                                    @if(Auth::user()->hasRole('admin') or (Auth::user()->hasRole('teacher') and Auth::user()->teaching()->where("language_id", $l->id)->first()))
                                        <button
                                            onclick="window.location.href='{{ route('admin.word_cards.index_language', $l->id) }}'"
                                            class="btn btn-inverse-primary btn-sm pull-right"><i
                                                class="mdi mdi-cards"></i></button>
                                    @endif
                                    @if(Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher') or (Auth::user()->hasRole('student') and Auth::user()->studying()->where('language_id', $l->id)->first()))
                                        <button
                                            onclick="window.location.href='{{ route('admin.languages.teachers', $l->id) }}'"
                                            class="btn btn-inverse-success btn-sm pull-right"><i
                                                class="fa fa-users"></i> @lang('language.teachers')</button>
                                    @endif
                                    <button onclick="window.location.href='{{ route('admin.languages.show', $l->id) }}'"
                                            class="btn btn-inverse-info btn-sm pull-right mx-1"><i
                                            class="fa fa-search"></i> @lang('general.detail')</button>
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
                $("#table_languages_mobile").show();
            } else {
                $("#table_languages_pc").show();
            }

        })

    </script>
@stop
