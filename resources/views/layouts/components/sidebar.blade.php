<nav class="sidebar sidebar-offcanvas border-left border-primary" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="{{ route('user.profile', Auth::id()) }}" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{!! Auth::user()->profile->getProfileImage() !!}" alt="profile_placeholder">
                    <span class="login-status offline"></span> <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column ellipsis">
                    <span class="font-weight-bold mb-2">
                        {{ Auth::user()->profile->first_name . " " . Auth::user()->profile->last_name}}
                    </span>
                    <span class="text-secondary text-small text-wrap">
                        @foreach(Auth::user()->roles as$r){{ __('general.role_'.$r->name) }} @endforeach
                    </span>
                </div>
            </a>
            <hr class="my-0 my-md-1 border border-primary">
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route("dashboard") }}">
                <span class="menu-title">@lang("general.dashboard")</span>
                <i class="fa fa-home menu-icon"></i>
            </a>
        </li>

        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('teacher'))
            <li class="nav-item">
                <a class="nav-link"
                   href="{{ route("materials.index") }}">
                    <span class="menu-title">@lang('side_menu.materials')</span>
                    <i class="mdi mdi-file-document-box menu-icon"></i>
                </a>
            </li>
        @endif

        @if(Auth::user()->hasRole('admin'))
            {{-- ------ ADMIN ------ --}}
            <li class="nav-item">
                <a class="nav-link"
                   href="{{ route("admin.package-orders.index") }}">
                    <span class="menu-title">@lang('side_menu.star_orders')</span>
                    @if($has_new_orders) <span
                            class="badge badge-gradient-danger text-white animated infinite flash slower"><i
                                class="fa fa-search"></i></span>@endif
                    <i class="fa fa-star-o menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   href="{{ route('admin.users.students.index') }}">
                    <span class="menu-title">@lang('side_menu.students')</span>
                    <i class="fa fa-user-circle-o menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   href="{{ route('admin.teachers.index') }}">
                    <span class="menu-title">@lang('side_menu.teachers')</span>
                    <i class="fa fa-user-circle-o menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   href="{{ route("lectures.index") }}">
                    <span class="menu-title">@lang('side_menu.Lections')</span>
                    <i class="fa fa-mortar-board menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic"
                   aria-expanded="false"
                   aria-controls="ui-basic">
                    <span class="menu-title">@lang('side_menu.School')</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('developer'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i
                                            class="fa fa-user-circle-o"></i>&nbsp;@lang('side_menu.Users')</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.guests.index') }}"><i
                                            class="fa fa-user-circle-o"></i>&nbsp;@lang('side_menu.guests')</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.email-queue.index') }}"><i
                                            class="fa fa-envelope"></i>&nbsp;@lang('side_menu.Emails')</a></li>
                            <div class="dropdown-divider"></div>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.meetings.index') }}"><i
                                        class="mdi mdi-presentation"></i>&nbsp;@lang('side_menu.meetings')</a></li>

                        <li class="nav-item"><a class="nav-link" href="{{ route("materials.index") }}"><i
                                        class="mdi mdi-file-document-box"></i>&nbsp;@lang('side_menu.materials')</a>
                        </li>
                        {{--<li class="nav-item"><a class="nav-link" href="{{ route('admin.word_cards.index') }}"><i
                                    class="mdi mdi-cards"></i>&nbsp;@lang('side_menu.word_cards')</a></li>--}}
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.gift_codes.index') }}"><i
                                        class="fa fa-ticket"></i>&nbsp;@lang('side_menu.gift_codes')</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route("admin.languages.index") }}"><i
                                        class="fa fa-flag"></i>&nbsp;@lang('side_menu.Languages')</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.banners.index') }}"><i
                                        class="mdi mdi-image-area"></i>&nbsp;@lang('side_menu.banners')</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('survey.index') }}"><i
                                        class="fa fa-question"></i>&nbsp;@lang('side_menu.survey')</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('feedback.index') }}"><i
                                        class="fa fa-file"></i>&nbsp;@lang('side_menu.feedback')</a></li>
                        {{--<li class="nav-item"><a class="nav-link" href="{{ route('dashboard.translations') }}"><i
                                    class="fa fa-language"></i>&nbsp;@lang('side_menu.translations')</a></li>--}}
                    </ul>
                </div>
            </li>
            {{-- ------ /ADMIN ----- --}}

        @endif

        @if(Auth::user()->hasRole('student'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route("materials.students_material", Auth::id()) }}">
                    <span class="menu-title">@lang("side_menu.materials")</span>
                    <i class="mdi mdi-file-document-box menu-icon"></i>
                </a>
            </li>
        @endif

        @if(
        Auth::user()->hasRole('guest') or
        Auth::user()->hasRole('student') or
        Auth::user()->hasRole('developer'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route("buy_stars.index") }}">
                    <span class="menu-title">@lang("dashboard.buy_stars")</span>
                    <i class="fa fa-money menu-icon"></i>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('feedback.indexStudent', Auth::id()) }}">
                <span class="menu-title">@lang('side_menu.feedback')</span>
                <i class="mdi mdi-file-check menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route("dashboard.contact") }}">
                <span class="menu-title">@lang('side_menu.contact_us')</span>
                <i class="mdi mdi-contacts menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route("user.profile", Auth::id()) }}">
                <span class="menu-title">@lang("side_menu.profile")</span>
                <i class="mdi mdi-face menu-icon"></i>
            </a>
        </li>

        {{--<li class="nav-item">
            <a class="nav-link"
               href="{{ route("messages.index") }}">
                <span class="menu-title">@lang("side_menu.messages")</span>
                @if(count($new_messages) > 0)<span
                    class="badge badge-gradient-success animated text-white flash infinite slower"><i
                        class="mdi mdi-forum"></i></span>@endif
                <i class="mdi mdi-mailbox menu-icon"></i>
            </a>
        </li>--}}


        <div class="dropdown-divider"></div>

        <li class="nav-item">
            @if(\Illuminate\Support\Facades\Auth::user()->theme == 1)
                <a href="{{ route("dashboard.themeChange") }}" class="btn btn-block btn-sm btn-gradient-dark">
                    <i class="fa fa-paint-brush menu-icon"></i> Dark mode
                </a>
            @else
                <a href="{{ route("dashboard.themeChange") }}" class="btn btn-block btn-sm btn-gradient-primary">
                    <i class="fa fa-paint-brush menu-icon"></i> Light mode
                </a>

            @endif
        </li>

    </ul>
</nav>

