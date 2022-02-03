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
                    <a href="{{ route('dashboard.translations') }}">
                        @lang('side_menu.translations')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('side_menu.translations') : {{ $file }}
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"></h4>
                    <p class="card-description">

                    <div class="alert alert-danger">
                        <b>Pravidlá prekladania</b><br><br>
                        <ul>
                            <li>
                                značky ako ":name" (slovo začínajúce dvojbodkou) musí ostať pôvodné
                            </li>
                            <li>
                                {{ 'značky ako "<span cla...> [preklad] </span>" musia ostať pôvodné' }}
                            </li>
                        </ul>
                    </div>

                    <form id="form_translations_save" method="POST"
                          action="{{ route('dashboard.translationsFileSave', [$file, $language]) }}">
                        @csrf

                        <button type="submit" class="btn btn-gradient-success btn-rounded btn-sm"
                                style="position:fixed; bottom:15px; right: 75px; z-index: 100">
                            @lang('general.Save')
                        </button>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-gradient-success btn-block">
                                    @lang('general.Save')
                                </button>
                                <hr>
                            </div>
                            <div class="col-sm-4 my-2 text-right">
                                <b>Kľúč</b>
                            </div>
                            <div class="col-sm-8 my-2">
                                <b>Preklady v dostupných jazykoch</b>
                            </div>
                            @foreach($translations as $t_key => $t_val)
                                @if($t_key != "")
                                    <div class="col-sm-4 mt-2 text-right">
                                        <b>{{ $t_key }}</b>
                                    </div>
                                    <div class="col-sm-8 mt-2 py-2 border border-silverish border-round-15">
                                        <label for="translation_{{ $t_key }}_sk">SK:</label>
                                        @if($language == "en")
                                            <textarea type="text" class="form-control"
                                                      name="translation_{{ $t_key }}_sk"
                                                      id="translation_{{ $t_key }}_sk">{{ $t_val }}</textarea>
                                        @else
                                            <p>{{ $t_val }}</p>
                                        @endif
                                        @foreach($langs as $l)
                                            <label class="text-uppercase"
                                                   for="translation_{{ $t_key }}_{{ $l }}">{{ $l }}:</label>
                                            <textarea type="text"
                                                      class="form-control @if( \Illuminate\Support\Facades\Lang::get($file.".".$t_key,[],$l,false) == $file.".".$t_key ) border-danger @endif"
                                                      name="translation_{{ $t_key }}_{{ $l }}"
                                                      id="translation_{{ $t_key }}_{{ $l }}"
                                            >@if( \Illuminate\Support\Facades\Lang::get($file.".".$t_key,[],$l,false) != $file.".".$t_key){{ __($file.".".$t_key,[],$l) }} @endif</textarea>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                            <div class="col-12">
                                <hr>
                                <button type="submit" class="btn btn-gradient-success btn-block">
                                    @lang('general.Save')
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <a href="#!" class="btn btn-gradient-info btn-rounded btn-sm" id="to-top"
       style="position:fixed; bottom:15px; right: 15px"><i class="fa fa-arrow-up"></i></a>
@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {

            $('#to-top').click(function () {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
            })

        })

    </script>
@stop
