<div class="row">
    <div class="col-lg-9">
        <div class="row">
            <div class="col-12 stretch-card my-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('dashboard.my_nearest_hours')</h4>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="row">
                                    <a href="#" class="btn btn-outline-primary">
                                        <div class="col-12">
                                            <i class="flag-icon flag-icon-gb"></i>
                                            English {{-- $th->language->name_en --}}
                                            (1{{-- $th->class_difficulty --}})
                                        </div>
                                        <div class="col-12">
                                            <small>01.01.1970{{-- $th->getDayName() --}}
                                                (00:00{{-- substr($th->class_start, 0, 5) --}}
                                                - 02:00{{-- substr($th->class_end, 0, 5) --}})
                                            </small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="row">
                                    <a href="#" class="btn btn-outline-secondary">
                                        <div class="col-12">
                                            <i class="flag-icon flag-icon-gb"></i>
                                            English {{-- $th->language->name_en --}}
                                            (1{{-- $th->class_difficulty --}})
                                        </div>
                                        <div class="col-12">
                                            <small>08.01.1970{{-- $th->getDayName() --}}
                                                (00:00{{-- substr($th->class_start, 0, 5) --}}
                                                - 02:00{{-- substr($th->class_end, 0, 5) --}})
                                            </small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="row">
                                    <a href="#" class="btn btn-outline-secondary">
                                        <div class="col-12">
                                            <i class="flag-icon flag-icon-gb"></i>
                                            English {{-- $th->language->name_en --}}
                                            (1{{-- $th->class_difficulty --}})
                                        </div>
                                        <div class="col-12">
                                            <small>15.01.1970{{-- $th->getDayName() --}}
                                                (00:00{{-- substr($th->class_start, 0, 5) --}}
                                                - 02:00{{-- substr($th->class_end, 0, 5) --}})
                                            </small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 stretch-card my-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('dashboard.my_languages')</h4>

                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active show" id="home-tab" data-toggle="tab" href="#home-1"
                                   role="tab"
                                   aria-controls="home" aria-selected="true"><i class="flag-icon flag-icon-gb"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link show" id="contact-tab" data-toggle="tab" href="#contact-1"
                                   role="tab"
                                   aria-controls="contact" aria-selected="false"><i class="flag-icon flag-icon-de"></i></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="home-1" role="tabpanel"
                                 aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="col-12">
                                        <div id="loader_1">
                                            <div class="dot-opacity-loader">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                        </div>
                                        <div id="calendar-lang-1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab">
                                <h4>Contact us </h4>
                                <p> Feel free to contact us if you have any questions! </p>
                                <p>
                                    <i class="mdi mdi-phone text-info"></i> +123456789 </p>
                                <p>
                                    <i class="mdi mdi-email-outline text-success"></i> contactus@example.com </p>
                            </div>
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
                        <h1 class="text-primary"><i class="fa fa-star"></i> 13</h1>
                        <hr>
                        <h1 class="text-info"><i class="fa fa-star"></i> 9</h1>
                        <hr>
                        <a href="#" class="btn btn-sm btn-gradient-success"
                           style="width: 100%;">@lang('dashboard.buy_stars')</a>
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
