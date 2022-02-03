@if( $teacher ) <br><br> @endif

@include('calendar.student.calendar_base')
@include('calendar.student.calendar_modals')

<div class="alert alert-info mt-4">
    <p>@lang('dashboard.lecture_request_text')<br>
        @lang('dashboard.lecture_request_text2')</p>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#requestLectureModal">
        @lang('dashboard.lecture_request_btn')
    </button>
</div>

<div class="modal fade" id="requestLectureModal" tabindex="-1" role="dialog"
     aria-labelledby="eventModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel">@lang('dashboard.request_lecture')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body py-1">

                <form id="form_student_request_lecture" method="POST"
                      action="{{ route('makeLectureRequest') }}">
                    @csrf

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
                        <label for="" class="col-4 col-form-label text-right">@lang('general.language')
                            *</label>
                        <div class="col-6">
                            <select type="time" class="form-control py-2 py-md-0"
                                    name="class_language"
                                    required>
                                <option value="0" @if(count($user->studying) > 1) @endif disabled>---</option>
                                @foreach($user->studying as $l)
                                    <option value="{{ $l->id }}"
                                            @if(count($user->studying) == 1) selected @endif >{{ $l->name_native }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </form>
                <p>* - @lang('general.required_field')</p>
            </div>
            <div class="modal-footer">
                <button type="submit" form="form_student_request_lecture"
                        class="btn btn-success">@lang('general.Create')</button>
                <button type="button" class="btn btn-light"
                        data-dismiss="modal">@lang('general.Cancel')</button>
            </div>
        </div>
    </div>
</div>

@if(session()->has('package_type') and intval(session()->get('package_type')) == 99)
    <div class="modal fade" id="starterLectureReservedModal" tabindex="-1" role="dialog"
         aria-labelledby="eventModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollSmartModalLabel">
                        {{ __('lecture.congratulations') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-1">
                    <p>
                        @lang('lecture.congratulation_starter_lecture_reserved')
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif
