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
                    @lang('side_menu.translations')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"></h4>
                    <p class="card-description"></p>
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Hodnotenie lektora</th>
                            <th>Hodnotenie pridal</th>
                            <th>Naposledy upravené</th>
                            <th style="width: 15%">@lang('general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($feedbacks as $f)
                            <tr>
                                <td>
                                    <img src="{{ $f->teacher->profile->getProfileImage() }}">
                                    <a href="{{ route('user.profile', $f->teacher->id) }}" class="text-primary">
                                        {{ $f->teacher->name }}
                                    </a>
                                </td>
                                <td>
                                    <img src="{{ $f->student->profile->getProfileImage() }}">
                                    <a href="{{ route('user.profile', $f->student->id) }}" class="text-primary">
                                        {{ $f->student->name }}
                                    </a>
                                </td>
                                <td>{{ $f->updated_at }}</td>
                                <td>
                                    <a href="{{ route('feedback.show', $f->id) }}"
                                       class="text-info pull-right ml-2">
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

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {

        })

    </script>
@stop
