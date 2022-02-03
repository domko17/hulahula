{{-- student modals --}}
@if($student)
    <div class="modal fade" id="confirmStudyChartModal" tabindex="-1" role="dialog"
         aria-labelledby="confirmStudyChartModal"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: #fff">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6 mb-3 stretch-card">
                            <div class="card">
                                <div class="card-body p-1 p-md-5">
                                    <h4>@lang('dashboard.chart_lectures_past_months')</h4>
                                    <hr>
                                    <canvas id="barChart" style="height: 200px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6 mb-3 stretch-card">
                            <div class="card">
                                <div class="card-body p-1 p-md-5">
                                    <h4>@lang('dashboard.chart_teachers')</h4>
                                    <hr>
                                    <canvas id="doughnutChart" style="height: 200px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 text-center text-md-left mt-4">
                            <h2 class="display-4">@lang('profile.student_card_title')</h2>
                            <hr>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6 mb-3">
                            <h4>@lang('profile.student_my_future_lectures')</h4>
                            <table class="table table-striped table-responsive" id="student_future_lectures_tabl_e"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($student->inst->classes_future as $i)
                                    <tr>
                                        <td>{{ $i->id }}</td>
                                        <td>{{ __('general.day_'.\Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->dayOfWeekIso) ." ". \Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->format("d.").
        __('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->month).
        " ".\Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->format("Y") }}</td>
                                        @if(optional(optional($i->hour)->teacher)->id)
                                        <td>
                                            <a href="{{ route('user.profile', optional(optional($i->hour)->teacher)->id) }}"
                                               class="text-primary">
                                                {{ optional($i->hour)->teacher->profile->first_name }} {{ $i->hour->teacher->profile->last_name }}
                                            </a>
                                        </td>
                                        @endif
                                        <td>
                                            <a href="{{ route('lectures.show', $i->id) }}"
                                               class="text-primary pull-right">
                                                <i class="fa fa-search"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6">
                            <h4>@lang('profile.student_my_past_lectures')</h4>
                            <table class="table table-striped table-responsive" id="student_past_lectures_table"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($student->inst->classes_past as $i)
                                    <tr>
                                        <td>{{ $i->id }}</td>
                                        <td>{{ __('general.day_'.\Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->dayOfWeekIso) ." ". \Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->format("d.").
        __('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->month).
        " ".\Carbon\Carbon::createFromFormat("Y-m-d", $i->class_date)->format("Y") }}</td>
                                        <td>
                                            <a href="{{ route('user.profile', $i->hour->teacher->id) }}"
                                               class="text-primary">
                                                {{ $i->hour->teacher->profile->first_name }} {{ $i->hour->teacher->profile->last_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('lectures.show', $i->id) }}"
                                               class="text-primary pull-right">
                                                <i class="fa fa-search"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    @if(!\App\Models\StudentStudyDay::userConfirmedToday(Auth::id()))
        <div class="modal fade" id="confirmStudyDayModal" tabindex="-1" role="dialog"
             aria-labelledby="confirmStudyDayModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">

                        <form method="POST" action="{{ route("dashboard.confirmStudentStudyDay") }}"
                              id="form_confirm_study_day">
                            @csrf

                            <div class="row form-group">
                                <label class="col-form-label col-8 offset-2">
                                    @lang('dashboard.student_how_many_hours')
                                </label>
                                <div class="col-6 offset-4">
                                    <input type="number" min="5" max="600" step="5" name="hours" id="hours" required value="0"
                                           class="form-control-lg">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="form_confirm_study_day" id="confirm_btn" class="btn btn-success"><i
                                class="fa fa-send"></i> @lang('general.Save')
                        </button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

{{-- other modals --}}

@if($quick_survey)
    <div class="quick_survey_box bg-light p-3 rounded-10 border border-primary border-round-10">
        <p><b>Rýchly dotazník</b>
            <a href="#" class="text-danger pull-right quick_survey_box_close"><i class="fa fa-times"></i></a>
        </p>
        <hr>
        <p>{{ $quick_survey->question }}</p>
        <a href="#quickSurveyModal" class="text-primary quick_survey_box_answer"
           data-toggle="modal"
           data-qid="{{ $quick_survey->id }}"
           data-qtype="{{ $quick_survey->type }}"
           data-qtext="{{ $quick_survey->question }}">Odpovedať</a>
    </div>

    <div class="modal fade" id="quickSurveyModal" tabindex="-1" role="dialog" aria-labelledby="quickSurveyModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h4 class="modal-title" id="question"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body py-1">
                    <input type="hidden" name="question_id" id="question_id" value="">
                    <input type="hidden" name="question_type" id="question_type" value="">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">

                    <div class="row">
                        <div class="col-12">

                            <div id="answer_type_1" class=" form-group" style="display: none;">
                                <label class="col-form-label">Vaša odpoveď</label>
                                <textarea name="answer_text" id="answer_text" rows="3" class="form-control"></textarea>
                            </div>

                            <div id="answer_type_2" style="display: none;">

                            </div>

                            <div class="form-group mb-0">
                                <div class="form-check my-1">
                                    <label class="form-check-label">
                                        <input type="checkbox"
                                               name="anonymous"
                                               value="1"
                                               id="anonymous"
                                               class="form-check-input"
                                        >Odpovedať anonymne?
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="send_survey_answer">@lang('general.send')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session()->has('feedback_created'))
    <div class="modal fade" id="feedbackCreatedModal" tabindex="-1" role="dialog"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>{{ __('feedback.success_msg') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif
