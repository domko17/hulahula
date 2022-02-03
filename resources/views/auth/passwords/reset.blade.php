@extends('layouts.app')

@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper auth auth-bckg_1">
                <div class="row py-0 py-md-5">
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4 mx-auto" style="z-index: 2">
                        <div
                            class="auth-form-light text-left p-4 p-md-5 animated
                            @switch(rand(1, 4))
                            @case(1) fadeInDown @break
                            @case(2) fadeInRight @break
                            @case(3) fadeInLeft @break
                            @case(4) fadeIn @break
                            @endswitch">
                            <h3 class="text-primary">@lang('general.zone') - Hula Hula</h3>
                            <hr>
                            @if(\Illuminate\Support\Facades\Request::cookie("FTWeb-User"))
                                <h4>@lang('auth.login_welcome_known_greeting', ["name" => \Illuminate\Support\Facades\Request::cookie("FTWeb-User")])</h4>
                                <h6 class="font-weight-light">{!! __('auth.password_reset_2_welcome_message') !!}</h6>
                            @else
                                <h4>@lang('auth.password_reset_2_unknown_greeting')</h4>
                                <h6 class="font-weight-light">{!! __('auth.password_reset_2_welcome_message') !!}</h6>
                            @endif
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group ">
                                    <input id="email" type="email"
                                           class="form-control px-2 px-md-3 form-control-lg @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}" required autocomplete="email"
                                           placeholder="@lang('general.Email')">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <input id="password" type="password"
                                           class="form-control px-2 px-md-3 form-control-lg @error('password') is-invalid @enderror"
                                           name="password"
                                           required autocomplete="new-password"
                                           placeholder="@lang('general.Password')">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input id="password-confirm" type="password" class="form-control px-2 px-md-3 form-control-lg"
                                           name="password_confirmation" required autocomplete="new-password"
                                           placeholder="@lang('general.Password_confirm')">
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn text-uppercase">
                                        @lang('auth.reset_password')
                                    </button>
                                </div>
                            </form>
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
