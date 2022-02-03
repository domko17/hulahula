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
                    @lang('side_menu.word_cards')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        @foreach($languages as $l)
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 text-center">
                                <h1><i class="flag-icon {{ $l->icon }}"></i></h1>
                                <br>
                                <h3><i class="mdi mdi-cards"></i> {{ count($l->word_cards) }}</h3>
                                <hr>
                                <a href="{{ route('admin.word_cards.index_language', $l->id) }}" class="text-primary">
                                    <h4><i class="fa fa-chevron-right"></i> @lang('language.word_cards')</h4></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('page_css')

@stop

@section('page_scripts')

@stop
