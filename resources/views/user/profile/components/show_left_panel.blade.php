<div class="col-12 px-0 mb-4 text-center">
    <img src="{{ $profile->getProfileImage() }}" alt="profile"
         style="width: 100%; max-width: 150px;"
         class="rounded-circle mb-0 {{ $user->is_online() ? ($user->is_online() == 1 ? 'profile_img_online':($user->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}">
</div>
<div class="px-2 px-md-1">
    <ul class="nav nav-pills nav-pills-primary" id="pills-tab" role="tablist">
        <li class="nav-item m-0" style="width: 100%">
            <a class="nav-link @if(!$teacher) show active @endif border border-primary show text-center py-1"
               id="pills-home-tab" data-toggle="pill"
               href="#pills-base_info" role="tab" aria-controls="pills-home"
               aria-selected="false">@lang('general.About_me')</a>
        </li>

        @if($student)
            <li class="nav-item m-0" style="width: 100%">
                <a class="nav-link border border-primary show text-center py-1"
                   id="pills-student-card-tab" data-toggle="pill"
                   href="#pills-student-card" role="tab" aria-controls="pills-student-card"
                   aria-selected="false">@lang('profile.student_card')</a>
            </li>
        @endif

        @if($teacher)
            <li class="nav-item m-0" style="width: 100%">
                <a class="nav-link show active bg-gradient-info text-light text-center py-1"
                   id="pills-teaching_schedule-tab" data-toggle="pill"
                   href="#pills-teaching_schedule" role="tab"
                   aria-controls="pills-contact"
                   aria-selected="true">@lang('profile.teaching_schedule')</a>
            </li>
            {{--<li class="nav-item m-0" style="width: 100%">
                <a class="nav-link border border-primary show active text-center py-1"
                   id="pills-teacher-card-tab" data-toggle="pill"
                   href="#pills-teacher-card" role="tab" aria-controls="pills-home"
                   aria-selected="true">@lang('profile.teacher_card')</a>
            </li>--}}
        @endif
    </ul>
</div>
<div class="border-bottom py-2 py-md-4 px-2 px-md-0">
    @if($teacher)
        @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
            <p class="mb-1 mb-md-2">
                <a href="{{ route('admin.teachers.teachers_hours', $teacher->inst->id) }}"
                   data-custom-class="tooltip-primary" data-toggle="tooltip"
                   data-placement="top" title=""
                   data-original-title="Otvoriť zoznam odučených hodín"
                   class="btn btn-sm btn-outline-silverish"><i
                        class="fa fa-star text-golden"></i>
                    {{ count($teacher->inst->classes_i_unpaid()) }}
                </a>
            </p>
        @endif
        <p class="mb-1 mb-md-2"><b>@lang('profile.im_teaching')</b><br></p>
        <div>
            @foreach($teacher->inst->teaching as $l)
                <a href="{{ route('admin.languages.show', $l->id) }}"
                   class="btn btn-sm btn-outline-dark p-1 my-1">
                    <i class="flag-icon {{ $l->icon }}"></i> {{ $l->name_en}}
                </a>
            @endforeach
        </div>
        @if($student)
            <hr>
        @endif
    @endif
    @if($student)
        <p class="mb-1 mb-md-2">
            <b>@lang('profile.im_studying')
                @if($user->id == \Illuminate\Support\Facades\Auth::id())
                    <a href="#addStudyLanguageModal" data-toggle="modal" class="text-primary">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                @endif
            </b><br></p>
        <div>
            @foreach($student as $l)
                <a href="{{ route('admin.languages.show', $l->id) }}"
                   class="btn btn-sm btn-outline-dark p-1 my-1">
                    <i class="flag-icon {{ $l->icon }}"></i> {{ $l->name_en}}
                </a>
                @if((\Illuminate\Support\Facades\Auth::user()->hasRole('teacher') and \Illuminate\Support\Facades\Auth::user()->teaching()->where('languages.id', $l->id)->first())
 or \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                    <button type="button"
                            data-language="{{ $l->id }}"
                            data-current="{{ $student_instance->classes_past()->count() > 3 ? $user->studyLevelOfLanguage($l->id) : -1 }}"
                            id="evaluateStudentLangOpen"
                            class="btn btn-sm btn-outline-primary p-1 my-1">
                        Ohodnotiť
                    </button>
                @endif
                @if( $student_instance->classes_past()->count() > 3)
                    <div class="progress progress-xl">
                        <div
                            class="progress-bar @if(intval($user->studyLevelOfLanguage($l->id)) == 1) bg-danger
@elseif(intval($user->studyLevelOfLanguage($l->id)) == 2) bg-golden @elseif(intval($user->studyLevelOfLanguage($l->id)) == 3) bg-info
@elseif(intval($user->studyLevelOfLanguage($l->id)) == 4) bg-success @else bg-primary @endif" role="progressbar"
                            style="width: {{ intval($user->studyLevelOfLanguage($l->id))*20 }}%"
                            aria-valuenow="{{ intval($user->studyLevelOfLanguage($l->id)) }}"
                            aria-valuemin="0"
                            aria-valuemax="5">
                            <b>@lang('profile.level_rating'):{{ $user->studyLevelOfLanguage_text($l->id) }}</b>
                        </div>
                    </div>
                @else
                    <div class="progress progress-xl">
                        <div
                            class="progress-bar bg-silverish" role="progressbar"
                            style="width: 100%"
                            aria-valuenow="0"
                            aria-valuemin="0"
                            aria-valuemax="0">
                            <b>@lang( 'profile.level_not_yet_rated')</b>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
<div class="p-2 py-md-4 px-md-0">
    {{--
        <p class="mb-1 mb-md-2"><b>@lang('profile.contact_me')</b></p>
    --}}
    {{--<p class="mb-1 mb-md-2">
        <b><i class="fa fa-phone"></i> </b>
        <span
            class="text-muted">{{ substr($profile->phone,0,4)." ".substr($profile->phone,4,3)." ".substr($profile->phone,7,3) }}</span>
    </p>
    <p class="mb-1 mb-md-2">
        <b><i class="mdi mdi-gmail"></i> </b>
        <a href="mailto:{{ $user->email }}" class="text-muted">{{ $user->email }}</a>
    </p>--}}
    @if(Auth::id() != $user->id)
        <button type="button" class="btn btn-outline-primary px-2" data-toggle="modal"
                data-target="#sendMessageModal" style="width: 100%">
            @lang('profile.send_me_message')
        </button>
    @endif
</div>

<div class="modal fade" id="addStudyLanguageModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollSmartModalLabel">
                    {{ __('profile.student_change_study_languages_title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body py-1">
                <form method="post" action="{{ route('user.profile.changeStudyLanguages', $user->id) }}"
                      id="changeStudentStudyLanguagesForm">
                    @csrf
                    <div class="col-12 col-md-4 offset-md-4 text-center">
                        <label for="languages_study" class="col-form-label">
                            @lang('profile.i_study_languages')
                        </label>
                    </div>
                    <div class="form-group col-10 offset-1">
                        <select id="languages_study" name="languages_study[]" multiple
                                class="form-control form-control-sm chosen-select" required>
                            @foreach(\App\Models\Language::all() as $l)
                                <option value="{{ $l->id }}"
                                        @if($user->studying()->where('languages.id', $l->id)->first()) selected @endif>
                                    {{ $l->name_sk }}</option>
                            @endforeach
                        </select>
                        <br><small class="text-muted">@lang('profile.do_not_leave_empty')</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="changeStudentStudyLanguagesForm"
                        class="btn btn-success">@lang('general.Save')</button>
                <button type="button" class="btn btn-light"
                        data-dismiss="modal">@lang('general.Cancel')</button>
            </div>
        </div>
    </div>
</div>

@if((\Illuminate\Support\Facades\Auth::user()->hasRole('teacher'))
 or \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
    <div class="modal fade" id="evaluateStudentLangModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body py-1">
                    <form method="post" action="{{ route('user.profile.evaluateLanguage', $user->id) }}"
                          id="evaluateStudentLangForm">
                        @csrf

                        <input type="hidden" name="language_id" id="evaluate_lang_id" value="0">

                        <div class="form-group col-10 offset-1 mt-3">
                            <select id="level_lang" name="level_lang"
                                    class="form-control form-control-lg col-12">
                                <option value="-1" selected disabled>Nehodnotené</option>
                                <option value="1">A1</option>
                                <option value="2">A2</option>
                                <option value="3">B1</option>
                                <option value="4">B2</option>
                                <option value="5">C1</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="evaluateStudentLangForm"
                            class="btn btn-success">@lang('general.Save')</button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endif
