@if(!$is_past and !$lecture->canceled)
    @if($current_user->hasRole('student') and
            $lecture->is_student_attending($current_user->id) and $is_individual)
        {{-- Zmena terminu / Odhlasenie sa z hodiny : voľba --}}
        <div class="modal fade" id="changeClassModal" tabindex="-1" role="dialog"
             aria-labelledby="changeClassModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-lg">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-12 my-4">
                                <a href="{{ route("lectures.un_assign_student", [$lecture->id, $current_user->id]) }}"
                                   class="btn btn-block btn-lg btn-gradient-primary text-uppercase"
                                   id="un_enroll_btn">
                                    @lang('lecture.un_enroll')
                                </a>
                            </div>

                            <div class="col-12">
                                @if($can_reschedule)
                                    <button type="button"
                                            data-dismiss="modal"
                                            data-toggle="modal" data-target="#changeClassDateModal"
                                            class="btn btn-block btn-lg btn-gradient-info text-uppercase"
                                            id="change_date_btn" data-lecture="{{ $lecture->id }}"
                                            data-student="{{ $current_user->id }}">
                                        @lang('lecture.change_date_student')
                                    </button>
                                @else
                                    <button type="button"
                                            data-custom-class="tooltip-info" data-toggle="tooltip"
                                            data-placement="top" title=""
                                            data-original-title="*hint"
                                            class="btn btn-block btn-lg btn-secondary text-uppercase disabled">
                                        @lang('lecture.change_date_student')
                                    </button>
                                    <p class="text-danger">@lang('lecture.change_date_student_hint_1')
                                        <a href="{{ route('dashboard.contact') }}"
                                           class="text-danger"> @lang('lecture.change_date_student_hint_2')</a>
                                    </p>
                                @endif
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

    {{-- Zmena terminu / Odhlasenie sa z hodiny --}}
    @if($can_reschedule)
        <div class="modal fade" id="changeClassDateModal" tabindex="-1" role="dialog"
             aria-labelledby="changeClassModalDateLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body pb-0">
                        <p>@lang('lecture.reschedule_modal_text')</p>

                        <div id="calendar-student-reschedule"></div>

                        <form method="POST" id="reschedule_class_form"
                              action="{{ route("lectures.reschedule_class", $lecture->id) }}">
                            @csrf
                            <input type="hidden" name="student_id" id="reschedule_student_id"
                                   value="{{ Auth::id() }}">
                            <input type="hidden" name="is_preview" id="reschedule_is_preview"
                                   value="">
                            <input type="hidden" name="reschedule_id" id="reschedule_id"
                                   value="">
                            <input type="hidden" name="reschedule_date" id="reschedule_date"
                                   value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

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

    <div id="event_student_modal_templates" style="display: none;">
        <div class="row my-0" id="row-enrolled">
            <div class="col-4">
                <b><span id="class_time">xx:xx - yy:yy</span></b>
            </div>
            <div class="col-6">
                <b><img src="" id="teacher_image"><span id="teacher_name">Name Surname</span></b>
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
                    <img src="" id="teacher_image" alt="Teacher's profile image" style="max-width: 30px">
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
                <a href="#" class="text-primary m-0 py-1 class_link font-weight-bold reschedule_here">
                    @lang('general.reschedule_here') <i class="fa fa-chevron-right"></i>
                </a>
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
                    <form method="POST" action="{{ route('lectures.sign_student', $lecture->id) }}"
                          id="enroll_smart_student_form">
                        @csrf

                        <input type="hidden" name="student_id" value="{{ Auth::id() }}">
                        <div class="row">
                            <div class="col-12">
                                <small>{!! __('lecture.student_smart_chose_days_text',['teacher_name'=>$lecture->hour->teacher->name]) !!}</small>
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

    {{-- Ak je hodina v buducnosti--}}
    @if(($teacher and $teacher->user_id == \Illuminate\Support\Facades\Auth::id()) or $current_user->hasRole('admin'))
        {{--Prideleny ucitel a admin smie otvorit modal na upravu informacii--}}
        <div class="modal fade" id="editInfoModal" tabindex="-1" role="dialog"
             aria-labelledby="editInfoModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInfoModalLabel">@lang('lecture.info_edit')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('lectures.edit_info', $lecture->id) }}"
                              id="form_info_edit">
                            @csrf

                            <div class="row form-group">
                                <div class="col-12">
                                                <textarea id="infotext" class="form-control" name="info"
                                                          rows="3" required>{{ $lecture->info }}</textarea>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="form_info_edit"
                                class="btn btn-success">@lang('general.Save')</button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="lectureMaterialChangeModal" tabindex="-1" role="dialog"
             aria-labelledby="lectureMaterialChangeLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="lectureMaterialChangeLabel">@lang('lecture.material_change')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                        <form method="POST" action="{{ route('lectures.edit_class_material', $lecture->id) }}"
                              id="form_class_material_edit">
                            @csrf

                            <div class="row">
                                <div class="col-12 border border-primary border-round-5 py-2 px-3 bg-inverse-light">
                                    <table class="table table-striped table-condensed" id="material_edit_table">
                                        <thead>
                                        <tr>
                                            <th>@lang('general.title')</th>
                                            <th>@lang('general.Type')</th>
                                            <th>@lang('general.actions')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($language_material as $lm)
                                            <tr>
                                                <td>{{ $lm->name }}</td>
                                                <td>{{ $lm->get_type_name() }}</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="form-check m-0">
                                                            <label class="form-check-label m-0">
                                                                <input type="checkbox" name="l_material[]"
                                                                       class="form-check-input"
                                                                       value="{{ $lm->id }}"
                                                                       @if(in_array($lm->id, $lecture_material_ids->toArray())) checked @endif><i
                                                                    class="input-helper"></i></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="form_class_material_edit"
                                class="btn btn-success">@lang('general.Save')</button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>

        {{--Modal pre zrusenie hodiny + dovod zrusenia --}}
        <div class="modal fade" id="cancelClassModal" tabindex="-1" role="dialog"
             aria-labelledby="cancelClassModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelClassModalLabel">@lang('lecture.cancel_lecture')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                        @if(count($students) > 0)
                            <p class="text-center">@lang('lecture.cancel_lecture_modal_text')</p>
                        @endif

                        <form method="POST" action="{{ route('lectures.cancel_class', $lecture->id) }}"
                              id="form_class_cancel">
                            @csrf

                            @if(count($students) > 0)
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label class="col-form-label">@lang('lecture.cancel_lecture_reason')</label>
                                        <textarea id="reason" class="form-control" name="reason"
                                                  rows="3" required></textarea>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="reason" value="---">
                                <div class="row">
                                    <div class="col-10 offset-1">
                                        @lang('lecture.cancel_lecture_confirm')
                                    </div>
                                </div>
                            @endif

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="form_class_cancel"
                                class="btn btn-success">@lang('general.confirm')</button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(($current_user->hasRole('admin') or
        ($current_user->hasRole('teacher') and $lecture->hour->teacher and $lecture->hour->teacher->id == $current_user->id )))
        {{-- Modal pre admina a uctela hodiny na upravu infa--}}
        <div class="modal fade" id="assignStudentAdminModal" tabindex="-1" role="dialog"
             aria-labelledby="assignStudentAdminModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignStudentAdminModalLabel">Pridať študentov</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('lectures.add_students_admin', $lecture->id) }}"
                              id="form_add_students_admin">
                            @csrf

                            <div class="row form-group">
                                <label class="col-form-label col-12" for="infotext">
                                    Študenti:
                                </label>
                                <div class="col-10 offset-1">
                                    <select class="form-control" name="students[]" id="students" multiple>
                                        <option value="0" selected disabled></option>
                                        @foreach( \App\User::where('active', 1)->get() as $s)
                                            @if($s->hasRole('student') && ($current_user->hasRole('admin') || count($s->studying->intersect($current_user->teaching))))
                                                <option value="{{ $s->id }}"
                                                        @if($lecture->is_student_attending(\App\Models\User\Student::find($s->id)->id)) selected
                                                        @elseif( !$s->canEnrollClass()) disabled @endif>
                                                    {{ $s->name }}
                                                    | {{ $s->currentPackage ? $s->currentPackage->getName()." (volne hodiny: ".$s->currentPackage->classes_left.")" : __('general.no_active_package') }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="form_add_students_admin"
                                class="btn btn-success">@lang('general.Create')</button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
@if($is_past and !$lecture->canceled and (($is_individual and ($current_user->id == $detail->user_id or $current_user->hasRole('admin'))) or ($current_user->hasRole('admin') and $is_collective)))

    {{-- Modal pre admina a uctela hodiny na pridanie nahrávky hodiny --}}
    <div class="modal fade" id="addRecordingModal" tabindex="-1" role="dialog"
         aria-labelledby="addRecordingModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecordingModalLabel">
                        @if($lecture->recording_url)
                            @lang('lecture.change_recording')
                        @else
                            @lang('lecture.add_recording')
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('lectures.save_recording_link', $lecture->id) }}"
                          id="form_save_recording_link">
                        @csrf

                        <div class="row form-group">
                            <label class="col-form-label col-12" for="infotext">
                                URL:
                            </label>
                            <div class="col-12">
                                <input id="link" class="form-control" name="link"
                                       required>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="form_save_recording_link"
                            class="btn btn-success">@lang('general.Create')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif
