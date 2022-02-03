<div class="modal fade" id="studentNearestHoursModal" tabindex="-1" role="dialog"
     aria-labelledby="studentNearestHoursModal"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body pb-0">
                <div class="row">
                    @if(count($student->classes_future) > 0)
                        @foreach($student->classes_future->take(4) as $scf)
                            <div class="col-12">
                                @if($student->classes_future[0] == $scf)
                                    <h4>@lang('dashboard.nearest_hour_student')</h4>
                                @elseif($student->classes_future[1] == $scf)
                                    <hr class="border-primary">
                                    <h4>@lang('dashboard.nearest_hour_other_student')</h4>
                                @endif
                                <div class="row py-1 px-3">
                                    <a href="{{ route('lectures.show', $scf->id) }}"
                                       class="text-dark"
                                       style="padding-left: 0; padding-right: 0; display: block; width: 100%">
                                        <div class="col-12 d-flex justify-content-between">
                                                <span>
                                                {{ __('general.day_'.\Carbon\Carbon::createFromFormat("Y-m-d", $scf->class_date)->dayOfWeekIso) ." ". \Carbon\Carbon::createFromFormat("Y-m-d", $scf->class_date)->format("d.").
        __('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d", $scf->class_date)->month).
        " ".\Carbon\Carbon::createFromFormat("Y-m-d", $scf->class_date)->format("Y") }}
                                                @if($scf->hour)
                                                ({{ substr($scf->hour->class_start, 0, 5) }}
                                                - {{ substr($scf->hour->class_end, 0, 5) }})</span>
                                                @endif
                                            <span
                                                class="text-primary {{ $student->classes_future[0] == $scf ? "animated flash slow infinite" : "" }}"><i
                                                    class="fa fa-chevron-right"></i></span>
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
                    <span id="title_day"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body py-1">
                <div class="row">
                    @if(!$student->package || !$student->package->classes_left)
                        <div class="col-12 mb-1 alert alert-danger text-center">
                            <a href="{{ route("buy_stars.index") }}" class="text-danger">
                                @lang('lecture.not_enough_stars')</a>
                        </div>
                    @endif
                    <div class="col-12 mt-2 enrolled_section">
                        <h4 class="text-center"> @lang('lecture.student_enrolled_classes')</h4>
                        <p class="none_enrolled text-center m-0">
                            @lang('general.no_enrolled_classes')
                        </p>
                        <div id="col_enrolled"></div>
                    </div>

                    <div class="col-12">
                        <hr class="border-primary">
                        <h4 class="text-center">@lang('lecture.student_available_lectures')</h4>
                    </div>
                    <div class="col-12">
                        <div id="col_available"></div>
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

<div id="eventStudentModalTemplates" style="display: none;">
    <div class="row my-0" id="row-enrolled">
        <div class="col-4">
            <b><span id="class_time">xx:xx - yy:yy</span></b>
        </div>
        <div class="col-6">
            <b><img src="" id="teacher_image"><span id="teacher_name">Name Surname</span></b>
        </div>
        <div class="col-2">
            <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-info m-0 py-1 text-primary class_link">
                <i class="fa fa-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="row my-2" id="row-available">
        <div class="col-12 mb-1">
                <span> @lang('general.Teacher'):&nbsp;
                    <img src="" id="teacher_image" alt="Teacher's profile image" style="max-width: 30px">
                    <a href="#!" id="teacher_profile_link" class="text-primary"><strong id="teacher_name"></strong></a>
                    <div id="teacher_lang_icons" class="d-inline-block"></div>
                </span>
        </div>
        <div id="availables" class="col-12"></div>
    </div>
    <div id="single_available_template" class="d-flex justify-content-between">
        <div class="ml-0 ml-md-5">
            <b><span id="class_time">xx:xx - yy:yy</span></b>
        </div>
        <div class="ml-2">
            @if(!$student->package || !$student->package->classes_left)
                <span href="" class="disabled m-0 py-1 class_link font-weight-bold" style="cursor: not-allowed">
                    @lang('general.show_class') <i class="fa fa-chevron-right"></i>
                </span>
            @else
                @if($student->package->type == 1)
                    <a href="#!" class="text-primary class_link smart enroll_student_SMART m-0 py-1 font-weight-bold">
                        @lang('general.show_class') <i class="fa fa-chevron-right"></i>
                    </a>
                @else
                    <a href="#" class="text-primary class_link m-0 py-1 enroll_student_SMART font-weight-bold">
                        @lang('general.show_class') <i class="fa fa-chevron-right"></i>
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="enrollSmartModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollSmartModalLabel">
                    {{ __('lecture.student_smart_chose_days', ['max'=>2]) }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body py-1">
                <form method="POST" action=""
                      id="enroll_smart_student_form">
                    @csrf
                    <input type="hidden" value="{{ route("lectures.enroll_from_preview") }}" id="form_action_preview_smart">
                    <input type="hidden" value="{{ route('lectures.sign_student', 0) }}" id="form_action_smart">
                    <input type="hidden" name="student_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="teacher_hour_id" id="form_input_thid" value="">
                    <input type="hidden" name="date" id="form_input_date" value="">
                    <div class="row">
                        <div class="col-12">
                            <small id="modal-description">{!! __('lecture.student_smart_chose_days_text',['teacher_name'=>'']) !!}</small>
                            <hr class="border-primary">
                        </div>
                        <div class="col-12">
                            <div id="days_for_choose"></div>
                        </div>
                        <div class="col-sm-12" id="days_for_choose_err" style="display: none">
                            <p class="text-danger">
                                {{ __('lecture.enroll_smart_days_limit_exceeded', ['max'=> 2]) }}
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="check_smart_student_enroll"
                        class="btn btn-success">@lang('general.Save')</button>
                <button type="button" class="btn btn-light"
                        data-dismiss="modal">@lang('general.Cancel')</button>
            </div>
        </div>
    </div>
</div>
