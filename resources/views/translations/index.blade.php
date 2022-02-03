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
                            <th>Subor prekladov</th>
                            <th>Status prekladov</th>
                            <th style="width: 15%">Preklady</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $f)
                            @if(! in_array($f->getFileNameWithoutExtension(), ['validation']))
                                <tr>
                                    <td class="text-capitalize">{{ $f->getFileNameWithoutExtension() }}</td>
                                    <td>
                                        @foreach($langs as $lang)
                                            <i class="flag-icon flag-icon-{{ $lang == 'en' ? 'gb' : $lang }}"></i>
                                            @if($statuses[$f->getFileNameWithoutExtension()][$lang])
                                                <i data-custom-class="tooltip-danger" data-toggle="tooltip"
                                                   data-placement="top" title=""
                                                   data-original-title="Chýbajúce preklady v jazyku {{ $lang .": ".$statuses[$f->getFileNameWithoutExtension()][$lang] }}"
                                                   class="fa fa-fw fa-times text-danger"></i>
                                            @else
                                                <i data-custom-class="tooltip-success" data-toggle="tooltip"
                                                   data-placement="top" title=""
                                                   data-original-title="Preklady pre jazyk {{ $lang }} sú v poriadku"
                                                   class="fa fa-fw fa-check text-success"></i>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($langs as $lang)
                                            <a href="{{ route('dashboard.translationsFile', ['file' => $f->getFileNameWithoutExtension(), 'lang' => $lang]) }}"
                                               class="text-info ml-2">
                                                <i class="flag-icon flag-icon-{{ $lang == 'en' ? 'gb' : $lang }}"></i>
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
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
