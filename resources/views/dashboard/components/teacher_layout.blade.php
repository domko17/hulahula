<div class="row">
    <div class="col-lg-9">
        <div class="row">

            <div class="col-12 stretch-card my-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('dashboard.my_nearest_hours')</h4>
                        <hr>

                        <div class="row">
                            @foreach($teacher->classes_future_i as $tcfi)
                                <div class="col-sm-6 col-md-4">
                                    <div class="row py-1">
                                        <a href="{{ route('lectures.show', $tcfi->id) }}"
                                           class="btn btn-outline-{{ $teacher->classes_future_i[0] == $tcfi ? 'primary' : 'secondary' }}">
                                            <div class="col-12">
                                                <i class="flag-icon {{ $tcfi->language->icon }}"></i>
                                                {{ $tcfi->language->name_native }}
                                                ({{ $tcfi->hour->class_difficulty }})
                                            </div>
                                            <div class="col-12">
                                                <small>{{ \Carbon\Carbon::createFromFormat("Y-m-d", $tcfi->class_date)->format("d.m.Y") }}
                                                    ({{ substr($tcfi->hour->class_start, 0, 5) }}
                                                    - {{ substr($tcfi->hour->class_end, 0, 5) }})
                                                </small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 stretch-card my-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('dashboard.my_languages')</h4>
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($teacher->languages as $tl)
                                <li class="nav-item">
                                    <a class="nav-link {{ $teacher->languages[0] == $tl ? 'active' : '' }} show"
                                       id="home-tab" data-toggle="tab" href="#lang-{{ $tl->id }}"
                                       role="tab"
                                       aria-controls="lang" {{ $teacher->languages[0] == $tl ? 'aria-selected="true"' : '' }}><i
                                            class="flag-icon {{ $tl->icon }}"></i></a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($teacher->languages as $tl)
                                <div class="tab-pane fade {{ $teacher->languages[0] == $tl ? 'active' : '' }} show"
                                     id="lang-{{ $tl->id }}" role="tabpanel"
                                     aria-labelledby="lang-tab">
                                    <div class="media">
                                        <div class="col-12">
                                            <div id="loader_{{$tl->id}}">
                                                <div class="dot-opacity-loader">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div id="calendar-teacher-lang-{{ $tl->id }}"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="row">
            <div class="col-12 stretch-card my-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('dashboard.my_stars')</h4>
                        <hr>
                        <h1 class="text-primary"><i class="fa fa-star"></i> {{ $teacher->inst->stars_i() }}</h1>
                        <hr>
                        <h1 class="text-info"><i class="fa fa-star"></i> {{ $teacher->inst->stars_c() }}</h1>
                    </div>
                </div>
            </div>

            <div class="col-12 stretch-card my-1">
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
            </div>
        </div>
    </div>
</div>
