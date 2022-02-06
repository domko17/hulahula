<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-primary">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center text-dark">
        <a class="navbar-brand brand-logo" href="{{ route("dashboard") }}">
            @if(Auth::user()->theme == 1)
                <img src="{{ asset('images/app/hulahula_logo.svg') }}" alt="logo"
                     style="height: 95%; padding: 1.3em; width: auto">
            @else
                <img src="{{ asset('images/app/hulahula_logo_white.png') }}" alt="logo"
                     style="">
            @endif
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ route("dashboard") }}">
            <img
                src="{{ Auth::user()->theme == 1 ? asset('images/app/hulahula_logo_sm.png') : asset('images/app/hulahula_logo_sm_white.png') }}"
                style="width: auto;;">
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <ul class="navbar-nav navbar-nav-right">
            @if(Auth::user()->hasRole('student'))
                <li class="nav-item d-none d-lg-block full-screen-link">
                    <a class="nav-link">
                        @if($pckg = Auth::user()->currentPackage)
                            @lang('general.active_package'):&nbsp;
                            <b>{{ $pckg->getName() }}</b>
                        @else
                            <b>
                                @lang('general.no_active_package')</b>
                        @endif
                    </a>
                </li>
            @endif

            <!-- Start: Locale change dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="locale_change" href="#" data-toggle="dropdown"
                   aria-expanded="false">
                    <i class="flag-icon flag-icon-{{ \Illuminate\Support\Facades\App::getLocale() == 'en' ? 'gb' : \Illuminate\Support\Facades\App::getLocale()}}"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                     aria-labelledby="locale_change">
                    <h6 class="p-1 mb-0">@lang('general.locale_change')</h6>
                    @if(\Illuminate\Support\Facades\App::getLocale() != 'en')
                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'en')}}">
                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                    class="flag-icon flag-icon-gb"></i> English</p>
                        </a>
                    @endif
                    @if(\Illuminate\Support\Facades\App::getLocale() != 'sk')
                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'sk')}}">
                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                    class="flag-icon flag-icon-sk"></i> Slovensky</p>
                        </a>
                    @endif
                    @if(\Illuminate\Support\Facades\App::getLocale() != 'de')
                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'de')}}">
                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                    class="flag-icon flag-icon-de"></i> Deutsch</p>
                        </a>
                    @endif
                    @if(\Illuminate\Support\Facades\App::getLocale() != 'ru')
                        <a class="dropdown-item preview-item" href="{{route('set_locale', 'ru')}}">
                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                    class="flag-icon flag-icon-ru"></i> Pусский</p>
                        </a>
                    @endif
                </div>
            </li>
            <!-- End: Locale change dropdown -->

            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle @if(count($new_messages) > 0) animated pulse infinite @endif"
                   id="messageDropdown" href="#" data-toggle="dropdown"
                   aria-expanded="false">
                    <i class="mdi mdi-email-outline"></i>
                    @if(count($new_messages) > 0)
                        <span class="count-symbol bg-gradient-success"></span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                     aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0">@lang('dashboard.messages')</h6>
                    <div class="dropdown-divider"></div>
                    @if(count($new_messages) > 0)
                        @foreach($new_messages as $m)
                            <a class="dropdown-item preview-item" href="{{ route('messages.index') }}">
                                <div class="preview-thumbnail">
                                    <img src="{{ $m->sender->profile->getProfileImage() }}" alt="image"
                                         class="profile-pic">
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject mb-1 font-weight-normal">
                                        @lang('dashboard.new_message_from', ["name" => $m->sender->name])
                                    </h6>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach
                        <a href="{{ route('messages.index') }}" class="text-primary"><h6
                                class="p-3 mb-0 text-center">@lang('dashboard.all_messages')</h6></a>
                    @else
                        <h6 class="p-3 mb-0 text-center">@lang('dashboard.no_messages')</h6>
                    @endif
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle @if($notif_count) animated pulse infinite @endif "
                   id="notificationDropdown" href="#"
                   data-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    @if($notif_count)
                        <span class="count-symbol bg-danger"></span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                     aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">@lang("general.notifications")</h6>
                    <div class="dropdown-divider"></div>

                    @include('layouts.components.notifications_items')
                </div>
            </li>
                <li class="nav-item nav-profile dropdown text-white">
                    <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown"
                       aria-expanded="false">
                        <div class="nav-profile-img">
                            <img src="{!! Auth::user()->profile->getProfileImage() !!}" alt="profile placeholder">
                            <span class="availability-status online"></span>
                        </div>
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                        </div>
                    </a>
                    <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="{{ route("user.profile", Auth::id()) }}">
                            <i class="mdi mdi-face-profile mr-2 text-success"></i>
                            @lang("side_menu.profile")
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout mr-2 text-primary"></i>{{ __('general.logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>


        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
