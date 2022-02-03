{{-- student modals --}}

@if($student and !\App\Models\StudentStudyDay::userConfirmedToday(Auth::id()))
    <div class="modal fade" id="confirmStudyDayModal" tabindex="-1" role="dialog"
         aria-labelledby="confirmStudyDayModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">

                    <form method="POST" action="{{ route("dashboard.confirmStudentStudyDay") }}"
                          id="form_confirm_study_day">
                        @csrf

                        <div class="row form-group">
                            <label class="col-form-label col-10 offset-1 text-center" for="hours">
                                @lang('dashboard.student_how_many_hours')
                            </label>
                            <div class="col-8 offset-2">
                                <input type="number" min="10" max="600" name="hours" id="hours" required value="70"
                                       class="form-control form-control-lg">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="form_confirm_study_day" id="confirm_btn"
                            class="btn btn-success text-uppercase"><i
                            class="fa fa-send"></i> @lang('general.Save')
                    </button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if($student)
    <div class="modal fade" id="confirmStudyChartModal" tabindex="-1" role="dialog"
         aria-labelledby="confirmStudyChartModal"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 stretch-card">
                            <div class="card">
                                <div class="card-body p-5">
                                    <div id="c3-line-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="studentNearestHoursModal" tabindex="-1" role="dialog"
         aria-labelledby="studentNearestHoursModal"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <div class="row">
                        @if(count($student->classes_future) > 0)
                            @foreach($student->classes_future->take(4) as $scf)
                                <div class="col-6">
                                    <div class="row py-1 px-3 text-center">
                                        <a href="{{ route('lectures.show', $scf->id) }}"
                                           class="btn btn-block btn-outline-{{ $student->classes_future[0] == $scf ? 'info' : 'secondary' }}"
                                           style="padding-left: 0; padding-right: 0;">
                                            <div class="col-12">
                                                <i class="flag-icon {{ $scf->language->icon }}"></i>
                                                {{ $scf->hour->teacher->name }}
                                                <i class="fa fa-star text-{{ $scf->collectiveHour ? "primary" : "golden" }}"></i>
                                            </div>
                                            <div class="col-12">
                                                <small>{{ \Carbon\Carbon::createFromFormat("Y-m-d", $scf->class_date)->format("d.m.Y") }}
                                                    ({{ substr($scf->hour->class_start, 0, 5) }}
                                                    - {{ substr($scf->hour->class_end, 0, 5) }})
                                                </small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                @lang('dashboard.no_nearest_hours_student')
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="eventStudentModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="flag-icon " id="event_title_icon"></i>&nbsp;
                        <span id="title_day"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-1">
                    <div class="row">
                        <div class="col-12">
                            <h4> @lang('lecture.student_enrolled_classes')</h4>
                            <div id="col_enrolled"></div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <h4>@lang('lecture.student_available_lectures')</h4>
                        </div>
                        <div class="col-12">
                            <h5><i class="fa fa-star text-golden"></i> @lang('lecture.individual')</h5>
                            <div id="col_individual"></div>
                        </div>
                        <div class="col-12">
                            <h5><i class="fa fa-star text-primary"></i> @lang('lecture.collective')</h5>
                            <div id="col_collective"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <div id="event_student_modal_templates" style="display: none;">
        <div class="row my-0" id="row-enrolled">
            <div class="col-4">
                <b><p>
                        <span id="class_time">xx:xx - yy:yy</span>
                    </p></b>
            </div>
            <div class="col-6">
                <b><p>
                        <span id="teacher_name">Name Surname</span>
                    </p></b>
            </div>
            <div class="col-2">
                <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-info m-0 py-1 class_link">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="row my-0" id="row-individual">
            <div class="col-4">
                <b><p>
                        <span id="class_time">xx:xx - yy:yy</span>
                    </p></b>
            </div>
            <div class="col-6">
                <b><p>
                        <span id="teacher_name">Name Surname</span>
                    </p></b>
            </div>
            <div class="col-2">
                <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-golden m-0 py-1 class_link">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="row my-0" id="row-collective">
            <div class="col-4">
                <b><p>
                        <span id="class_time">xx:xx - yy:yy</span>
                    </p></b>
            </div>
            <div class="col-6">
                <b><p>
                        <span id="teacher_name">Name Surname</span>
                    </p></b>
            </div>
            <div class="col-2">
                <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-primary m-0 py-1 class_link">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
@endif

{{-- teacher modals --}}

@if($teacher)
    <div class="modal fade" id="teacherNearestHoursModal" tabindex="-1" role="dialog"
         aria-labelledby="teacherNearestHoursModal"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-transparent">
                        <div class="card-body p-3">
                            <h4 class="card-title text-uppercase mb-1">@lang('dashboard.my_nearest_hours_teacher')</h4>
                            <hr>

                            <div class="row">
                                @php
                                    $i=0;
                                @endphp
                                @foreach($teacher->classes_future_i as $tcfi)
                                    @if($i < 3 and !$tcfi->is_free() and $tcfi->canceled == 0)
                                        <div class="col-12">
                                            <div class="row py-1 px-2 text-center">
                                                <a href="{{ route('lectures.show', $tcfi->id) }}"
                                                   class="btn btn-golden btn-block"
                                                   style="padding-left: 0; padding-right: 0;">
                                                    <i class="flag-icon {{ $tcfi->language->icon }} "></i>
                                                    {{ __('general.day_'.\Carbon\Carbon::createFromFormat("Y-m-d", $tcfi->class_date)->dayOfWeek) }}
                                                    {{ substr($tcfi->hour->class_start, 0, 5) }}
                                                    {{ count($tcfi->students) == 1 ? " | ".$tcfi->students[0]->user->name : count($tcfi->students) }}
                                                </a>
                                            </div>
                                        </div>
                                        @php
                                            $i++
                                        @endphp
                                    @endif
                                @endforeach

                                <div class="col-12">
                                    <hr class="m-1">
                                </div>
                                @php
                                    $i=0;
                                @endphp
                                @foreach($teacher->classes_future_c as $tcfi)
                                    @if($i < 3 and !$tcfi->is_free())
                                        <div class="col-4">
                                            <div class="row py-1 px-2 text-center">
                                                <a href="{{ route('lectures.show', $tcfi->id) }}"
                                                   class="btn btn-primary"
                                                   style="padding-left: 0; padding-right: 0;">
                                                    <div class="col-12">
                                                        <i class="flag-icon {{ $tcfi->language->icon }}"></i>
                                                        {{ __('general.day_'.\Carbon\Carbon::createFromFormat("Y-m-d", $tcfi->class_date)->dayOfWeek) }}
                                                        {{ substr($tcfi->hour->class_start, 0, 5) }}
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        @php
                                            $i++
                                        @endphp
                                    @endif
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="eventsTeacherModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventsTeacherModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <input type="hidden" name="teacher_id" id="teacher_id" value="{{ $user->id }}">
                    <p id="tmp_p"></p>

                    <div class="row" id="div_meeting">
                        <div class="col-12">
                            <h4>@lang('meeting.meeting')</h4>
                        </div>
                        <div class="col-12" id="col_meeting">
                        </div>
                    </div>

                    <div class="row" id="div_individual">
                        <div class="col-12">
                            <i class="fa fa-star text-golden"></i> <h4>@lang('lecture.individual')</h4>
                        </div>
                        <div class="col-12" id="col_individual">
                        </div>
                    </div>

                    <div class="row" id="div_collective">
                        <div class="col-12">
                            <i class="fa fa-star text-primary"></i> <h4>@lang('lecture.collective')</h4>
                        </div>
                        <div class="col-12" id="col_collective">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <div id="event_teacher_modal_templates" style="display: none;">
        <div class="row my-0" id="row-individual">
            <div class="col-4">
                <b><p>
                        <span id="class_time">xx:xx - yy:yy</span>
                    </p></b>
            </div>
            <div class="col-6">
                <b><p>
                        <span id="teacher_name">Name Surname</span>
                    </p></b>
            </div>
            <div class="col-2">
                <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-golden m-0 py-1 class_link">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="row my-0" id="row-collective">
            <div class="col-4">
                <b><p>
                        <span id="class_time">xx:xx - yy:yy</span>
                    </p></b>
            </div>
            <div class="col-6">
                <b><p>
                        <span id="teacher_name">Name Surname</span>
                    </p></b>
            </div>
            <div class="col-2">
                <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-primary m-0 py-1 class_link">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="row my-0" id="row-meeting">
            <div class="col-4">
                <b><p>
                        <span id="meeting_time">xx:xx - yy:yy</span>
                    </p></b>
            </div>
            <div class="col-6">
                <b><p>
                        <span id="meeting_title">Miting</span>
                    </p></b>
            </div>
            <div class="col-2">
                <a href="{{ route("admin.meetings.show", 0) }}"
                   class="btn btn-sm btn-info m-0 py-1 meeting_link">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
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
