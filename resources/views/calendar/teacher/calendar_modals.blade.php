@if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
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
                                @foreach($teacher->classes_all as $tcfi)
                                    @if($i < 3 and !$tcfi->is_past() and !$tcfi->is_free() and $tcfi->canceled == 0)
                                        <div class="col-12">
                                            <div class="row py-1 px-2 text-center">
                                                <a href="{{ route('lectures.show', $tcfi->id) }}"
                                                   class="btn btn-golden btn-block"
                                                   style="padding-left: 0; padding-right: 0;">
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
@endif

<div class="modal fade" id="eventsTeacherModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
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
                    <div class="col-12 mt-2">
                        <h4 class="text-center"> @lang('lecture.teacher_enrolled_classes')</h4>
                        <p class="none_enrolled text-center m-0">
                            @lang('general.teacher_no_enrolled_classes')
                        </p>
                        <div id="col_enrolled"></div>
                    </div>

                    <div class="col-12">
                        <hr class="border-primary">
                        <h4 class="text-center">@lang('lecture.teacher_available_lectures')</h4>
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

<div id="eventTeacherModalTemplates" style="display: none;">
    <div class="row my-0" id="row-enrolled">
        <div class="col-4">
            <b><span id="class_time">xx:xx - yy:yy</span></b>
        </div>
        <div class="col-6">
            <b>
                <img src="" id="teacher_image" style="max-width: 30px; border-radius: 50%">
                <span id="teacher_name">Name Surname</span>
            </b>
        </div>
        <div class="col-2">
            <a href="{{ route("lectures.show", 0) }}" class="btn btn-sm btn-info m-0 py-1 class_link">
                <i class="fa fa-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="row my-2" id="row-available">
        <div class="col-12 mb-1">
                <span> @lang('general.Teacher'):&nbsp;
                    <img src="" id="teacher_image" alt="Teacher's profile image"
                         style="max-width: 30px; border-radius: 50%">
                    <strong id="teacher_name"></strong>
                </span>
        </div>
        <div id="availables" class="col-12"></div>
    </div>
    <div id="single_available_template" class="d-flex justify-content-between">
        <div class="ml-5">
            <b><span id="class_time">xx:xx - yy:yy</span></b>
        </div>
        <div class="ml-2">
            <a href="{{ route("lectures.show", 0) }}" class="text-primary m-0 py-1 class_link font-weight-bold">
                @lang('general.teacher_show_class') <i class="fa fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

@if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
    <div class="modal fade" id="addHoursModal" tabindex="-1" role="dialog"
         aria-labelledby="addHoursModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">@lang('profile.new_teaching_hour')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-1">
                    <form id="form_add_teacher_hours" method="POST"
                          action="{{ route('user.profile.teacher.saveHours', $user->id) }}">
                        @csrf

                        <input type="hidden" name="teacher_id" value="{{ $user->id }}">

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('general.day')
                                *</label>
                            <div class="col-4">
                                <select class="form-control py-2 py-md-0" name="day" required>
                                    <option value="1">@lang('general.monday')</option>
                                    <option value="2">@lang('general.tuesday')</option>
                                    <option value="3">@lang('general.wednesday')</option>
                                    <option value="4">@lang('general.thursday')</option>
                                    <option value="5">@lang('general.friday')</option>
                                    <option value="6">@lang('general.saturday')</option>
                                    <option value="7">@lang('general.sunday')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.start')
                                *</label>
                            <div class="col-6">
                                <input type="time" class="form-control py-2 py-md-0" min="04:00" max="22:00"
                                       name="class_start"
                                       required>
                            </div>
                        </div>

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.end')
                                *</label>
                            <div class="col-6">
                                <input type="time" class="form-control py-2 py-md-0" min="04:01" max="23:00"
                                       name="class_end"
                                       required>
                            </div>
                        </div>

                    </form>
                    <p>* - @lang('general.required_field')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="form_add_teacher_hours"
                            class="btn btn-success">@lang('general.Create')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addOneTimeHourModal" tabindex="-1" role="dialog"
         aria-labelledby="addOneTimeHourModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">@lang('profile.new_one_time_class')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-1">

                    <form id="form_add_teacher_one_time_hour" method="POST"
                          action="{{ route('user.profile.teacher.saveOneTimeHour', $user->id) }}">
                        @csrf

                        <input type="hidden" name="teacher_id" value="{{ $user->id }}">

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('general.day')
                                *</label>
                            <div class="col-8 col-md-6">
                                <input type="date" name="day" id="day" class="form-control py-2 py-md-0"
                                       value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}"
                                       min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}">
                            </div>
                        </div>

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.start')
                                *</label>
                            <div class="col-6">
                                <input type="time" class="form-control py-2 py-md-0" min="04:00" max="22:00"
                                       name="class_start"
                                       required>
                            </div>
                        </div>

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.end')
                                *</label>
                            <div class="col-6">
                                <input type="time" class="form-control py-2 py-md-0" min="04:01" max="23:00"
                                       name="class_end"
                                       required>
                            </div>
                        </div>

                    </form>
                    <p>* - @lang('general.required_field')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="form_add_teacher_one_time_hour"
                            class="btn btn-success">@lang('general.Create')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addVacationModal" tabindex="-1" role="dialog"
         aria-labelledby="addVacationModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">@lang('profile.new_vacation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-1">

                    <form id="form_add_vacation" method="POST"
                          action="{{ route('user.profile.teacher.addVacation', $user->id) }}">
                        @csrf

                        <input type="hidden" name="teacher_id" value="{{ $user->id }}">

                        <div class="row form-group my-0">
                            <label for=""
                                   class="col-4 col-form-label text-right">@lang('profile.vacation_start')</label>
                            <div class="col-8 col-md-6">
                                <input type="date" name="day_start" id="day_start" class="form-control py-2 py-md-0"
                                       value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}"
                                       min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}">
                            </div>
                        </div>

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('profile.vacation_end')</label>
                            <div class="col-8 col-md-6">
                                <input type="date" name="day_end" id="day_end" class="form-control py-2 py-md-0"
                                       value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}"
                                       min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}">
                            </div>
                        </div>

                        <div class="row form-group my-0">
                            <label for="" class="col-4 col-form-label text-right">@lang('profile.description')</label>
                            <div class="col-8 col-md-6">
                                <textarea name="description" id="description" class="form-control py-2 py-md-0"
                                          rows="5"></textarea>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="form_add_vacation"
                            class="btn btn-success">@lang('general.Create')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="calendar-teacher-legend-modal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Legenda
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body py-1">
                <h3 class="text-success">Zelená značka</h3>
                <p>V ten deň mám nerezervované termíny</p>
                <h3 class="text-info">Modrá značka</h3>
                <p>V ten deň mám rezervované termíny</p>
                <h3 class="text-danger">Červená značka</h3>
                <p>V ten deň som zrušil/zrušila nejaký termín</p>
                <h3 class="text-secondary">Sivá značka</h3>
                <p>V ten deň som mal/mala s niekým hodinu</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light"
                        data-dismiss="modal">@lang('general.Cancel')</button>
            </div>
        </div>
    </div>
</div>
