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
                    <a href="{{ route('admin.word_cards.index') }}" class="text-primary">
                        @lang('side_menu.word_cards')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    {{ $language->name_en }}
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <h4 class="card-title">
                        <i class="flag-icon {{ $language->icon }}"></i> @lang('language.word_cards') ({{ count($cards) }})
                        <a href="{{ route("admin.word_cards.create", $language->id) }}" class="btn btn-gradient-success pull-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('language.word_card_new')
                        </a>
                    </h4>

                    <table class="table table-striped table-condensed" id="words_cards_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th><i class="flag-icon flag-icon-sk"></i></th>
                            <th><i class="flag-icon {{ $language->icon }}"></i></th>
                            <th>diff</th>
                            <th>@lang("general.Actions")</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cards as $card)
                            <tr>
                                <td>{{ $card->id }}</td>
                                <td><img src="{{ $card->getImage() }}" class="img-sm"></td>
                                <td>{{ $card->word_slovak }}</td>
                                <td>{{ $card->word_native }}</td>
                                <td>{{ $card->language_level_text() }}</td>
                                <td>
                                    <a href="{{ route("admin.word_cards.edit", $card->id) }}" class="btn btn-inverse-info btn-sm pull-right">
                                        <i class="fa fa-edit"></i> @lang("general.edit")
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

@stop
