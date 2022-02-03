<div class="row">
    <div class="col-lg-9">
        <div class="row">
            <div class="col-12 stretch-card my-2 px-2">
                <div class="card">
                    <div class="card-body p-2 p-md-4 pb-md-2">
                        <h4 class="card-title mb-1" style="text-transform: none">@lang('dashboard.my_languages')
                            <div class="text-right">
                                @if($student)
                                    <button class="btn btn-inverse-info btn-sm" type="button"
                                            data-toggle="modal"
                                            data-target="#studentNearestHoursModal">@lang('dashboard.my_nearest_hours_student')
                                    </button>
                                    @if(count($student->classes_future) > 0 and
                                     $student->classes_future[0]->class_date == \Carbon\Carbon::now()->addDay()->format("Y-m-d") and
                                     $student->classes_future[0]->hour->teacher->profile->zune_link)
                                        <a href="{{ $student->classes_future[0]->hour->teacher->profile->zune_link }}"
                                           class="btn btn-gradient-success btn-sm text-uppercase"
                                           target="_blank">
                                            <i class="fa fa-external-link"></i> @lang('dashboard.enter_class')</a>
                                    @endif
                                @endif
                                @if($teacher)
                                    <button class="btn btn-inverse-info btn-sm" type="button"
                                            data-toggle="modal"
                                            data-target="#teacherNearestHoursModal">@lang('dashboard.my_nearest_hours_teacher')
                                    </button>
                                @endif
                            </div>
                        </h4>
                        <hr class="mb-1">
                        <ul class="nav nav-pills nav-pills-primary pb-0 border-0" id="pills-tab" role="tablist"
                            style="display: inline-flex;">
                            @if($teacher)
                                <li class="nav-item" @if(!$student) style="display: none;" @endif>
                                    <a class="nav-link px-2 active" id="pills-home-tab" data-toggle="pill"
                                       href="#pills-home"
                                       role="tab" aria-controls="pills-home"
                                       aria-selected="true">@lang('dashboard.calendar_teacher')</a>
                                </li>
                            @endif
                            @if($student)
                                <li class="nav-item" @if(!$teacher) style="display: none;" @endif>
                                    <a class="nav-link px-2 @if(!$teacher)active @endif" id="pills-profile-tab"
                                       data-toggle="pill" href="#pills-profile"
                                       role="tab" aria-controls="pills-profile"
                                       aria-selected="{{ !$teacher? "true" : "false" }}false">@lang('dashboard.calendar_student')</a>
                            @endif
                        </ul>
                        <div class="tab-content border-0 p-0" id="pills-tabContent">
                            @if($teacher)
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                     aria-labelledby="pills-home-tab">
                                    <ul class="nav nav-tabs" role="tablist" id="teacher-tab">
                                        @foreach($teacher->languages as $tl)
                                            <li class="nav-item">
                                                <a class="nav-link px-3 py-2 {{ $teacher->languages[0] == $tl ? 'active' : '' }} show"
                                                   id="home-tab" data-toggle="tab" href="#lang-{{ $tl->id }}"
                                                   role="tab" data-target-id="{{ $tl->id }}"
                                                   aria-controls="lang" {{ $teacher->languages[0] == $tl ? 'aria-selected="true"' : '' }}><i
                                                        class="flag-icon {{ $tl->icon }} display-5"></i></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content p-0 pt-2 p-md-3 ">
                                        @foreach($teacher->languages as $tl)
                                            <div
                                                class="tab-pane fade {{ $teacher->languages[0] == $tl ? 'active' : '' }} show"
                                                id="lang-{{ $tl->id }}" role="tabpanel"
                                                aria-labelledby="lang-tab">
                                                <div id="loader_{{$tl->id}}">
                                                    <div class="dot-opacity-loader">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <div id="calendar-teacher-lang-{{ $tl->id }}"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($student)
                                <div class="tab-pane fade @if(!$teacher) show active @endif" id="pills-profile"
                                     role="tabpanel"
                                     aria-labelledby="pills-profile-tab">
                                    <ul class="nav nav-tabs" role="tablist" id="student-tab">
                                        @php
                                            $i=1
                                        @endphp
                                        @foreach($student->languages as $sl)
                                            <li class="nav-item">
                                                <a class="nav-link @if($i == 1) active @endif show px-3 py-2"
                                                   id="home-tab" data-toggle="tab" href="#lang-s-{{ $sl->id }}"
                                                   role="tab" data-target-id="{{ $sl->id }}"
                                                   aria-controls="lang"
                                                   @if($i == 1) aria-selected="true" @endif><i
                                                        class="flag-icon {{ $sl->icon }} display-5"></i></a>
                                            </li>
                                            @php
                                                $i++
                                            @endphp
                                        @endforeach
                                    </ul>
                                    <div class="tab-content p-0 pt-2 p-md-3 ">
                                        @php
                                            $i=1
                                        @endphp
                                        @foreach($student->languages as $sl)
                                            <div class="tab-pane fade @if($i == 1) active @endif show"
                                                 id="lang-s-{{ $sl->id }}" role="tabpanel"
                                                 aria-labelledby="lang-tab">
                                                <div id="loader_s_{{$sl->id}}">
                                                    <div class="dot-opacity-loader">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <div id="calendar-student-lang-{{ $sl->id }}"
                                                     class="callendr"></div>
                                            </div>
                                            @php
                                                $i++
                                            @endphp
                                        @endforeach
                                    </div>

                                    <div class="alert alert-primary text-left mt-2">
                                        <a href="{{ route('dashboard.contact') }}" class="text-dark">
                                            Radi by ste absolvovali hodinu, ale nenašli ste pre Vás vyhovujúci termín?
                                            Napíšte nám alebo požiadajte o hodinu niektorého z našich lektorov.
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="row">
            @if($teacher)
                <div class="col-12 stretch-card my-2 px-2">
                    <div class="card">
                        <div class="card-body p-2 p-md-4">
                            <h4 class="card-title">@lang('dashboard.my_stars_teacher')
                                <i class="fa fa-question-circle"
                                   data-custom-class="tooltip-success" data-toggle="tooltip"
                                   data-placement="top" title=""
                                   data-original-title="{{ __('profile.teacher_stars_tooltip') }}"></i>
                            </h4>
                            <hr>
                            <h1 class="text-center text-md-left">
                                <span class="text-golden"><i class="fa fa-star"></i> {{ count($teacher->inst->classes_i_unpaid()) }}
                                    {{--({{ \App\Models\User\Student::stars_i_reserved($user->id) }})--}}</span>
                                <span class="text-primary"><i class="fa fa-star"></i> {{ count($teacher->inst->classes_c_unpaid()) }}
                                    {{--({{ \App\Models\User\Student::stars_c_reserved($user->id) }})--}}</span>
                            </h1>
                        </div>
                    </div>
                </div>
            @endif
            @if($student)
                <div class="col-12 stretch-card my-2 px-2">
                    <div class="card">
                        <div class="card-body p-2 p-md-4">
                            <h4 class="card-title text-uppercase">@lang('dashboard.my_stars_student')
                                <i class="fa fa-question-circle"
                                   data-custom-class="tooltip-success" data-toggle="tooltip"
                                   data-placement="top" title=""
                                   data-original-title="{{ __('profile.student_stars_tooltip',
                                   ["si_all" => $profile->stars_individual + \App\Models\User\Student::stars_i_reserved($user->id),
                                    "si_reserved" => \App\Models\User\Student::stars_i_reserved($user->id),
                                    "sc_all" => $profile->stars_collective + \App\Models\User\Student::stars_c_reserved($user->id),
                                     "sc_reserved" => \App\Models\User\Student::stars_c_reserved($user->id)]) }}"></i>
                            </h4>
                            <hr>
                            <h1 class="text-center text-md-left">
                                <span class="text-golden"><i class="fa fa-star"></i> {{ $profile->stars_individual + \App\Models\User\Student::stars_i_reserved($user->id) }}
                                    {{--({{ \App\Models\User\Student::stars_i_reserved($user->id) }})--}}</span>
                                <span class="text-primary"><i class="fa fa-star"></i> {{ $profile->stars_collective + \App\Models\User\Student::stars_c_reserved($user->id) }}
                                    {{--({{ \App\Models\User\Student::stars_c_reserved($user->id) }})--}}</span>
                            </h1>
                            <hr>
                            <a href="{{ route('buy_stars.index') }}" class="btn btn-sm btn-gradient-success"
                               style="width: 100%;">@lang('dashboard.buy_stars')</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 stretch-card my-2 px-2">
                    <div class="card">
                        <div class="card-body p-2 p-md-4 py-md-3">
                            <div class="row py-1">
                                <div class="col-6 col-md-12">
                                    <p class="mb-0">@lang('dashboard.student_consecutive_study_days'):</p>
                                    <p style="font-size: 1.7em"
                                       class="text-primary text-left text-md-right mb-0">
                                        {{ \App\Models\StudentStudyDay::userConsecutiveStudyDays(Auth::id()) }}
                                        @lang('general.days')
                                    </p>
                                </div>
                                <div class="col-6 col-md-12">
                                    @if(! \App\Models\StudentStudyDay::userConfirmedToday(Auth::id()))
                                        <button type="button" class="btn btn-sm btn-primary btn-block"
                                                data-toggle="modal" data-target="#confirmStudyDayModal">
                                            @lang('dashboard.student_sign_study_day')
                                        </button>
                                    @else
                                        <hr>
                                    @endif
                                </div>
                                <div class="col-12 order-2 order-md-1">
                                    <p class="mb-0">@lang('dashboard.student_all_time_study_days_hours'):</p>
                                    <p style="font-size: 1.7em"
                                       class="text-primary text-right mb-0">
                                        {{ \App\Models\StudentStudyDay::userTotalStudy(Auth::id())->days_count }}
                                        @lang('general.days') |
                                        {{ \App\Models\StudentStudyDay::userTotalStudy(Auth::id())->hours }}
                                        @lang('general.hours')
                                    </p>

                                </div>
                                <div class="col-12 order-1 order-md-2">
                                    <button type="button" class="btn btn-sm btn-block btn-primary"
                                            data-toggle="modal" data-target="#confirmStudyChartModal">
                                        Graf
                                        <i class="fa fa-area-chart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
                @if($student->random_word_card)
                    <div class="col-12 my-2 px-2">
                        <div class="card">
                            <div class="card-body p-2 p-md-4 text-center">
                                <div class="row">
                                    <div class="col-6 col-md-12 pr-0 pr-md-3">
                                        <img src="{{ $student->random_word_card->getImage() }}" style="width: 100%;">
                                    </div>
                                    <div class="col-6 col-md-12 pl-1 pl-md-3">
                                        <hr>
                                        <h4><i class="flag-icon flag-icon-sk"></i>
                                            | {{ $student->random_word_card->word_slovak }}
                                        </h4>
                                        <h4>
                                            <i class="flag-icon {{ $student->random_word_card->language->icon }}"></i>
                                            | {{ $student->random_word_card->word_native }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif --}}
            @endif
            {{--<div class="col-12 stretch-card my-2 px-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('dashboard.messages')</h4>
                        <hr>
                        @if(count($messages) > 0)
                            @foreach($messages as $m)
                                <div class="row border-bottom">
                                    <div class="col-4">
                                        <img src="{{ $m->sender->profile->getProfileImage() }}"
                                             class="img-sm rounded-circle">
                                    </div>
                                    <div class="col-8">
                                        <p><b>{{ $m->sender->name }}</b></p>
                                    </div>
                                    <div class="col-12">
                                        <p>{{ substr($m->message, 0, 75) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row border-bottom">
                                <div class="col-12">
                                    <p>@lang('dashboard.no_messages')</p>
                                </div>
                            </div>
                        @endif
                        <a href="{{ route('messages.index') }}" class="btn btn-sm btn-gradient-success"
                           style="width: 100%;">@lang('dashboard.all_messages')</a>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
</div>

@include('dashboard.components.modals')
