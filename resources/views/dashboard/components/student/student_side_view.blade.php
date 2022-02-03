<div class="col-12 stretch-card my-2 px-2">
    <div class="card">
        <div class="card-body p-2 p-md-4">
            <small>
                @lang('dashboard.any_questions_contact')
            </small>
            <p><small></small></p>
            <small><a href="{{ route('user.profile', 4) }}" class="text-primary d-block">
                    Vojtech Paumer
                </a></small>
            <small><a href="mailto: paumer@hulahula.sk" class="text-primary d-block">
                    paumer@hulahula.sk
                </a></small>
            <small><a href="mailto: +421948235352" class="text-primary d-block">
                    +421 948 235 352
                </a></small>
            <br>
            <small><a href="{{ route('dashboard.contact') }}" class="text-success">
                    @lang('side_menu.contact_us')
                </a></small>
        </div>
    </div>
</div>

<div class="col-12 stretch-card my-2 px-2">
    <div class="card">
        <div class="card-body p-2 p-md-4">
            @if($pckg = Auth::user()->currentPackage)
                <h3 class="text-center text-md-left">
                    <span class="text-primary">
                            {{ $pckg->getName() }}
                    </span>
                </h3>
                <p>@lang('dashboard.package_classes_remaining'): <strong>{{ $pckg->classes_left }}</strong></p>
            @else
                <h3 class="text-center text-md-left">
                    <span class="text-primary">
                           @lang('general.no_active_package')
                    </span>
                </h3>
            @endif
            <hr>
            <a href="{{ route('buy_stars.index') }}" class="btn btn-sm btn-gradient-success"
               style="width: 100%;">@lang('dashboard.buy_stars')</a>
        </div>
    </div>
</div>

@if($student->can_feedback)
    <div class="col-12 stretch-card my-2 px-2">
        <div class="card border border-info">
            <div class="card-body p-2 p-md-4">
                <h4 class="card-title">@lang('dashboard.feedback')</h4>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <p>@lang('feedback.please_give_feedback')</p>
                    </div>
                    <div class="col-12 d-flex flex-wrap">
                        <img src="{{ $student->can_feedback->profile->getProfileImage() }}" class="mr-3"
                             style="width: 50px">
                        <h4 class="d-inline">{{ $student->can_feedback->name }}<br>
                            <a href="{{ route('feedback.createFeedback', $student->can_feedback->id) }}"
                               class="text-info">@lang('dashboard.give_feedback')</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="col-12 stretch-card my-2 px-2">
    <div class="card">
        <div class="card-body p-2 p-md-4 py-md-3">
            <div class="row py-1">
                @if(! \App\Models\StudentStudyDay::userConfirmedToday(Auth::id()))
                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-sm btn-primary btn-block"
                                data-toggle="modal" data-target="#confirmStudyDayModal">
                            @lang('dashboard.student_self_study')
                        </button>
                    </div>
                @endif
                <div class="col-12 order-2 order-md-1">
                    <p class="mb-0">@lang('dashboard.student_all_time_study_days_hours'):</p>
                    <p style="font-size: 1.7em"
                       class="text-primary text-right mb-0">
                        {{ $student->chart_data['days_all'] }}
                        @lang('general.days') |
                        {{ $student->chart_data['classes_all'] }}
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

<div class="col-12 grid-margin stretch-card my-2 px-2">
    <div class="card">
        <div class="card-body p-2 p-md-4 py-md-3">
            <h4 class="card-title">@lang('order.redeem_code')</h4>
            <p class="card-description">@lang('order.redeem_code_hint')</p>

            <form>
                <div class="row form-group">
                    <div class="col-12">
                        <input type="text" name="code" id="code" class="form-control"
                               placeholder="xx-xxxxxxxxxx"
                               required>
                    </div>
                    <div class="col-12 my-2">
                        <button type="button" id="redeem_coupon_btn"
                                class="btn btn-gradient-success btn-block text-uppercase">@lang('order.redeem_btn')</button>
                    </div>
                    <div class="col-12 text-danger text-center">
                        <span id="redeem_hint" class="text-danger"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
