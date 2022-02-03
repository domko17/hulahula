@extends('layouts.app')

@section('page_css')

    <style>
        svg > polygon {
            fill: #e91e63 !important;
        }

        button[type='submit'] {
            background: #e91e63;
            border-color: #e91e63;
        }

        .main_title {
            color: #e91e63 !important;
        }

        .auth-form-light a, .auth-form-light span.text-primary {
            color: #e91e63 !important;
        }

        .no_after {
            padding: 0;
        }

        .no_after:after {
            content: none !important;
        }

        .auth_lang_change_container {
            min-width: 2rem !important;
        }

        .auth_lang_change_container > a.preview-item {
            padding: 5px 10px;
            text-align: center;
        }

        .auth_lang_change_container > a.preview-item > p.preview-subject {
            font-size: 22px;
        }

    </style>

@stop

@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper align-items-center auth auth-bckg_1">
                <div class="row py-0 py-md-5">
                    <div class="col-sm-12 col-md-7 col-lg-6 col-xl-4 mx-auto" style="z-index: 2">
                        <div
                            class="auth-form-light text-left p-4 p-md-5 animated
                            @switch(rand(1, 4))
                            @case(1) fadeInDown @break
                            @case(2) fadeInRight @break
                            @case(3) fadeInLeft @break
                            @case(4) fadeIn @break
                            @endswitch">
                            <h3 class="text-primary main_title d-flex justify-content-between">@lang('general.zone') -
                                Hula Hula
                                <a class="nav-link count-indicator dropdown-toggle no_after" id="locale_change" href="#"
                                   data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="flag-icon flag-icon-{{ \Illuminate\Support\Facades\App::getLocale() == 'en' ? 'gb' : \Illuminate\Support\Facades\App::getLocale()}}"></i>
                                </a>
                                <div
                                    class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list auth_lang_change_container"
                                    aria-labelledby="locale_change">
                                    @if(\Illuminate\Support\Facades\App::getLocale() != 'en')
                                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'en')}}">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-gb"></i></p>
                                        </a>
                                    @endif
                                    @if(\Illuminate\Support\Facades\App::getLocale() != 'sk')
                                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'sk')}}">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-sk"></i></p>
                                        </a>
                                    @endif
                                    @if(\Illuminate\Support\Facades\App::getLocale() != 'de')
                                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'de')}}">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-de"></i></p>
                                        </a>
                                    @endif
                                    @if(\Illuminate\Support\Facades\App::getLocale() != 'ru')
                                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'ru')}}">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-ru"></i></p>
                                        </a>
                                    @endif
                                </div>
                            </h3>
                            <hr>

                            <h4>{{ __('auth.register_thank_you_title') }}</h4>
                            <h6 class="font-weight-light">{!! __('auth.register_thank_you_text') !!}</h6>

                            <hr/>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-block">
                                <span class="text-light">{{ __('auth.register_thank_you_continue') }}</span>
                            </a>

                            <hr/>

                            <h6 class="font-weight-light mb-2">{{ __('auth.register_thank_you_share_text') }}</h6>

                            <button type="button" class="btn btn-facebook btn-icon"
                                    onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=https://www.hulahula.sk','facebook-share-dialog','width=626, height=436')">
                                <i class="fa fa-facebook fa-fw"></i>
                            </button>
                            <button type="button" onclick="window.open('https://www.instagram.com/hulahula_sk/')"
                                    rel="noreferrer noopener nofollow" class="btn btn-icon btn-primary">
                                <i class="fa fa-instagram fa-fw text-light"></i>
                            </button>
                            <button type="button"
                                    onclick="window.open('https://www.youtube.com/channel/UCPXFiebnU7ENeGNVLC3CisQ')"
                                    rel="noreferrer noopener nofollow" class="btn btn-icon btn-youtube">
                                <i class="fa fa-youtube fa-fw text-light"></i>
                            </button>
                            <button type="button" onclick="window.open('https://www.linkedin.com/company/hulahula')"
                                    rel="noreferrer noopener nofollow" class="btn btn-icon btn-linkedin">
                                <i class="fa fa-linkedin fa-fw text-light"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SVG separator -->
        <div class="separator">
            <svg viewBox="0 0 2560 1440"
                 preserveAspectRatio="none"
                 version="1.1"
                 xmlns="http://www.w3.org/2000/svg">
                <polygon style="fill: #a02f67" fill-opacity="0.3"
                         points="4560,2000 5000,0 -1000,0"
                         id="poly-sep">
                </polygon>
            </svg>
        </div>
    </div>
@endsection
