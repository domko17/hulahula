@extends('layouts.app')

@section('title')

@stop

@section('content')
    <div class="page-header mt-2 mb-2 mb-mt-4 mt-md-0">
        <h3 class="page-title">
            <button onclick="window.location.href='{{ route('dashboard') }}'"
                    class="page-title-icon btn btn-gradient-primary btn-icon btn-rounded btn-sm">
                <i class="mdi mdi-home"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="text-dark"></a>
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb px-1 px-md-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-primary">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    @lang('general.profile')
                </li>
                <li class="breadcrumb-item active">
                    {{ $user->name }} - @lang('general.detail')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="border-bottom text-center p-3 pb-0 mb-3">
                                <img src="{{ $profile->getProfileImage() }}" alt="profile" style="width: 100%;"
                                     class="rounded-circle mb-0 {{ $user->is_online() ? ($user->is_online() == 1 ? 'profile_img_online':($user->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}">
                            </div>
                            <div>
                                <ul class="nav nav-pills nav-pills-primary" id="pills-tab" role="tablist">
                                    @if($teacher and \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                        <li class="nav-item m-0" style="width: 100%">
                                            <a class="nav-link border border-primary show text-center py-1"
                                               id="pills-home-tab" data-toggle="pill"
                                               href="#pills-base_info" role="tab" aria-controls="pills-home"
                                               aria-selected="false">@lang('general.About_me')</a>
                                        </li>
                                    @else
                                        <li class="nav-item m-0" style="width: 100%">
                                            <a class="nav-link border border-primary show active text-center py-1"
                                               id="pills-home-tab" data-toggle="pill"
                                               href="#pills-base_info" role="tab" aria-controls="pills-home"
                                               aria-selected="true">@lang('general.About_me')</a>
                                        </li>
                                    @endif
                                    @if((Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher')) and $user->hasRole('student'))
                                        <li class="nav-item m-0" style="width: 100%">
                                            <a class="nav-link bg-gradient-info text-light show text-center py-1"
                                               id="pills-teachers_notes-tab" data-toggle="pill"
                                               href="#pills-teachers_notes" role="tab" aria-controls="pills-contact"
                                               aria-selected="false">@lang('profile.teachers_notes')</a>
                                        </li>
                                    @endif
                                    @if($teacher)
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                            <li class="nav-item m-0" style="width: 100%">
                                                <a class="nav-link show active bg-gradient-info text-light text-center py-1"
                                                   id="pills-teaching_schedule-tab" data-toggle="pill"
                                                   href="#pills-teaching_schedule" role="tab"
                                                   aria-controls="pills-contact"
                                                   aria-selected="true">@lang('profile.teaching_schedule')</a>
                                            </li>
                                        @else
                                            <li class="nav-item m-0" style="width: 100%">
                                                <a class="nav-link bg-gradient-info text-light show text-center py-1"
                                                   id="pills-teaching_schedule-tab" data-toggle="pill"
                                                   href="#pills-teaching_schedule" role="tab"
                                                   aria-controls="pills-contact"
                                                   aria-selected="false">@lang('profile.teaching_schedule')</a>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div>
                            <div class="border-bottom py-4">
                                @if($teacher)
                                    @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
                                        <p>
                                            <b><a href="{{ route('admin.teachers.teachers_hours', $teacher_instance->id) }}"
                                                  data-custom-class="tooltip-info" data-toggle="tooltip"
                                                  data-placement="top" title=""
                                                  data-original-title="Otvoriť zoznam odučených hodín"
                                                  class="btn btn-sm btn-outline-silverish"><i
                                                        class="fa fa-star text-golden"></i>
                                                    {{ count($teacher_instance->classes_i_unpaid()) }}
                                                    <i class="fa fa-star text-primary"></i>
                                                    {{ count($teacher_instance->classes_c_unpaid()) }}
                                                </a>
                                                <br>
                                            </b>
                                            <u>@lang('profile.salary_i'):</u> {{ $profile->teacher_salary_i }}€/h.<br>
                                            <u>@lang('profile.salary_c'):</u> {{ $profile->teacher_salary_c }}€/h.
                                        </p>
                                    @endif
                                    <p><b>@lang('profile.im_teaching')</b><br>
                                    </p>
                                    <div>
                                        @foreach($teacher as $l)
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
                                    @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
                                        <p>
                                            <i class="fa fa-question-circle"
                                               data-custom-class="tooltip-success" data-toggle="tooltip"
                                               data-placement="top" title=""
                                               data-original-title="{{ __('profile.student_stars_tooltip',
                                               ['si_all' => $profile->stars_individual + \App\Models\User\Student::stars_i_reserved($user->id),
                                               'si_reserved' => \App\Models\User\Student::stars_i_reserved($user->id),
                                               'sc_all' => $profile->stars_collective + \App\Models\User\Student::stars_c_reserved($user->id),
                                               'sc_reserved' => \App\Models\User\Student::stars_c_reserved($user->id)]) }}"></i>
                                            <b><i class="fa fa-star text-golden"></i> {{ $profile->stars_individual + \App\Models\User\Student::stars_i_reserved($user->id) }}
                                                <i class="fa fa-star text-primary"></i> {{ $profile->stars_collective + \App\Models\User\Student::stars_c_reserved($user->id) }}
                                            </b>
                                            <br>
                                            @if(intval($profile->discount) != 0 or Auth::user()->hasRole('admin'))
                                                @lang('profile.discount'): <i
                                                    class="fa fa-star text-golden"></i> {{ $profile->discount_i }}% | <i
                                                    class="fa fa-star text-primary"></i> {{ $profile->discount_c }}%
                                            @endif
                                        </p>
                                    @endif
                                    <p><b>@lang('profile.im_studying')</b><br>
                                    </p>
                                    <div>
                                        @foreach($student as $l)
                                            <a href="{{ route('admin.languages.show', $l->id) }}"
                                               class="btn btn-sm btn-outline-dark p-1 my-1">
                                                <i class="flag-icon {{ $l->icon }}"></i> {{ $l->name_en}}
                                            </a>
                                            <div class="progress progress-xl">
                                                <div
                                                    class="progress-bar @if(intval($user->studyLevelOfLanguage($l->id)) == 1) bg-danger
@elseif(intval($user->studyLevelOfLanguage($l->id)) == 2) bg-golden @elseif(intval($user->studyLevelOfLanguage($l->id)) == 3) bg-info
@elseif(intval($user->studyLevelOfLanguage($l->id)) == 4) bg-success @else bg-primary @endif" role="progressbar"
                                                    style="width: {{ intval($user->studyLevelOfLanguage($l->id))*20 }}%"
                                                    aria-valuenow="{{ intval($user->studyLevelOfLanguage($l->id)) }}"
                                                    aria-valuemin="1"
                                                    aria-valuemax="5">
                                                    <b>{{ $user->studyLevelOfLanguage_text($l->id) }}</b>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="py-4">
                                <p><b>@lang('profile.contact_me')</b></p>
                                <p class="clearfix">
                                    <span class="float-left"> @lang('general.Phone') </span>
                                    <span
                                        class="float-right text-muted"> {{ substr($profile->phone,0,4)." ".substr($profile->phone,4,3)." ".substr($profile->phone,7,3) }} </span>
                                </p>
                                <p class="clearfix">
                                    <span class="float-left"> @lang('general.Email') </span>
                                    <span class="float-right text-muted"> {{ $user->email }} </span>
                                </p>
                                @if(Auth::id() != $user->id)
                                    <button type="button" class="btn btn-outline-primary px-2" data-toggle="modal"
                                            data-target="#sendMessageModal" style="width: 100%">
                                        @lang('profile.send_me_message')
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0">{{ $profile->getFullName() }}</h3>
                                    <p class="my-0">
                                        @foreach($user->roles as $r)
                                            {{ __('general.role_'.$r->name) }}
                                            @if($r != $user->roles[count($user->roles)-1])
                                                &nbsp;|&nbsp;
                                            @endif
                                        @endforeach
                                    </p>
                                </div>
                                <div>
                                    @if(Auth::user()->hasRole('admin'))
                                        <div class="dropdown">
                                            <button class="btn btn-gradient-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuOutlineButton1" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"> @lang('general.edit')
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1"
                                                 x-placement="bottom-start"
                                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 42px, 0px);">
                                                <a class="dropdown-item"
                                                   href="{{ route('user.profile.edit', $user->id) }}">@lang('profile.edit')</a>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.users.edit', $user->id) }}">@lang('general.edit_roles')</a>
                                            </div>
                                        </div>
                                    @elseif (Auth::id() == $user->id)
                                        <a href="{{ route('user.profile.edit', $user->id) }}"
                                           class="btn btn-gradient-primary">@lang('profile.edit')</a>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-content border-0" id="pills-tabContent">
                                @if($teacher and \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                    <div class="tab-pane fade" id="pills-base_info" role="tabpanel"
                                         aria-labelledby="pills-home-tab">
                                        @else
                                            <div class="tab-pane fade active show" id="pills-base_info" role="tabpanel"
                                                 aria-labelledby="pills-home-tab">
                                                @endif
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="row">
                                                            <div class="col-8">
                                                            </div>
                                                            <div class="col-4 border-right">
                                                                <p
                                                                    class="pull-right"><b>@lang('general.Base_info')</b>
                                                                </p>
                                                            </div>
                                                            <div class="col-8">
                                                                <p
                                                                    class="pull-right">{{ $profile->birthday ?
                                                                    (\Carbon\Carbon::createFromFormat("Y-m-d", $profile->birthday)->format("d.").
                                __('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d", $profile->birthday)->month).
                                " ".\Carbon\Carbon::createFromFormat("Y-m-d", $profile->birthday)->format("Y")) :
                                 "" }}</p>
                                                            </div>
                                                            <div class="col-4 border-right border-left">
                                                                <p
                                                                    class="pull-right text-muted">@lang('general.Birthday')</p>
                                                            </div>

                                                            @if($teacher)
                                                                <div class="col-8">
                                                                    <p
                                                                        class="pull-right">{{ $profile->iban }}</p>
                                                                </div>
                                                                <div class="col-4 border-right border-left">
                                                                    <p
                                                                        class="pull-right text-muted">IBAN</p>
                                                                </div>
                                                                <div class="col-8">
                                                                    <p
                                                                        class="pull-right">
                                                                        <a href="{{ $profile->zune_link }}">Link</a></p>
                                                                </div>
                                                                <div class="col-4 border-right border-left">
                                                                    <p
                                                                        class="pull-right text-muted">Zoom</p>
                                                                </div>
                                                            @endif

                                                            <div class="col-8">
                                                                <p
                                                                    class="pull-right">{{ $profile->nationality }}</p>
                                                            </div>
                                                            <div class="col-4 border-right border-left">
                                                                <p
                                                                    class="pull-right text-muted">@lang('general.nationality')</p>
                                                            </div>

                                                            <div class="col-8">
                                                                <p
                                                                    class="pull-right">{{ $profile->gender }}</p>
                                                            </div>
                                                            <div class="col-4 border-right border-left">
                                                                <p
                                                                    class="pull-right text-muted">@lang('general.Gender')</p>
                                                            </div>

                                                            <div class="col-8">
                                                                <p
                                                                    class="pull-right">{{ substr($profile->phone,0,4)." ".substr($profile->phone,4,3)." ".substr($profile->phone,7,3) }}</p>
                                                            </div>
                                                            <div class="col-4 border-right border-left">
                                                                <p
                                                                    class="pull-right text-muted">@lang('general.Phone')</p>
                                                            </div>

                                                            <div class="col-8">
                                                                <p
                                                                    class="pull-right">{{ $profile->user->email }}</p>
                                                            </div>
                                                            <div class="col-4 border-right border-left">
                                                                <p
                                                                    class="pull-right text-muted">@lang('general.Email')</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="row">
                                                            <div class="col-5 border-left">
                                                                <p><b>@lang('general.Address')</b></p>
                                                            </div>
                                                            <div class="col-7">
                                                            </div>
                                                            <div class="col-5 border-right border-left">
                                                                <p
                                                                    class="text-muted">@lang('general.Street')</p>
                                                            </div>
                                                            <div class="col-7">
                                                                {{ $profile->street ." ".$profile->street_number  }}
                                                            </div>
                                                            <div class="col-5 border-right border-left">
                                                                <p
                                                                    class="text-muted">@lang('general.City')</p>
                                                            </div>
                                                            <div class="col-7">
                                                                {{ $profile->city }}
                                                            </div>
                                                            <div class="col-5 border-right border-left">
                                                                <p
                                                                    class="text-muted">@lang('general.Zip')</p>
                                                            </div>
                                                            <div class="col-7">
                                                                {{ $profile->zip }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <hr>
                                                        <p><b>Bio:</b></p>
                                                        @if(strlen($profile->bio) == 0)
                                                            ...
                                                        @else
                                                            {!! $profile->bio !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if((Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher')) and $user->hasRole('student') )
                                                <div class="tab-pane fade" id="pills-teachers_notes" role="tabpanel"
                                                     aria-labelledby="pills-contact-tab">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h2>@lang('profile.teachers_notes')
                                                                <a href="#addNoteModal" data-toggle="modal"
                                                                   class="text-success pull-right"><i
                                                                        class="fa fa-plus"></i></a></h2>
                                                            <hr>
                                                        </div>
                                                        <div class="col-12">
                                                            @foreach($student_instance->teachers_notes as $note)
                                                                <div class="row">
                                                                    <div class="col-1">
                                                                        <img
                                                                            src="{{ $note->author->profile->getProfileImage() }}"
                                                                            class="img-xs">
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <a href="{{ route('user.profile', $note->author->id) }}"
                                                                           class="text-primary">
                                                                            {{ $note->author->name }}
                                                                        </a>
                                                                        <br>
                                                                        <small class="text-muted">
                                                                            {{ substr($note->created_at,0,10) }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        {{ $note->text }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($teacher)
                                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                                                    <div class="tab-pane fade active show" id="pills-teaching_schedule"
                                                         role="tabpanel"
                                                         aria-labelledby="pills-contact-tab">
                                                        @else
                                                            <div class="tab-pane fade" id="pills-teaching_schedule"
                                                                 role="tabpanel"
                                                                 aria-labelledby="pills-contact-tab">
                                                                @endif
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h2>@lang('profile.im_teaching')
                                                                        </h2>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div id="calendar"></div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        @if(count($teacher_hours) > 0)
                                                                            <div class="row mt-4">
                                                                                <div class="col-12">
                                                                                    <h3>@lang('profile.teaching_hours_set')
                                                                                        @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
                                                                                            <a href="#addHoursModal"
                                                                                               data-toggle="modal"
                                                                                               class="text-success pull-right"><i
                                                                                                    class="fa fa-plus"></i></a>
                                                                                        @endif
                                                                                    </h3>
                                                                                    <hr>
                                                                                </div>
                                                                                @for($i = 1; $i < 8; $i++)
                                                                                    @php
                                                                                        $j=0;
                                                                                    @endphp
                                                                                    <div class="col-12">
                                                                                        <div class="row">
                                                                                            <div class="col-12">
                                                                                                @switch($i)
                                                                                                    @case(1)
                                                                                                    <b class="text-danger">@lang('general.monday')</b>
                                                                                                    @break
                                                                                                    @case(2)
                                                                                                    <b class="text-warning">@lang('general.tuesday')</b>
                                                                                                    @break
                                                                                                    @case(3)
                                                                                                    <b class="text-golden">@lang('general.wednesday')</b>
                                                                                                    @break
                                                                                                    @case(4)
                                                                                                    <b class="text-info">@lang('general.thursday')</b>
                                                                                                    @break
                                                                                                    @case(5)
                                                                                                    <b class="text-success">@lang('general.friday')</b>
                                                                                                    @break
                                                                                                    @case(6)
                                                                                                    <b class="text-primary">@lang('general.saturday')</b>
                                                                                                    @break
                                                                                                    @case(7)
                                                                                                    <b class="text-dark">@lang('general.sunday')</b>
                                                                                                    @break
                                                                                                @endswitch
                                                                                            </div>
                                                                                            @foreach($teacher_hours as $th)
                                                                                                @if($th->day == $i)
                                                                                                    <div
                                                                                                        class="col-sm-6 col-md-4">
                                                                                                        <div
                                                                                                            class="row">
                                                                                                            <div
                                                                                                                class="col-12">
                                                                                                                <i class="flag-icon {{ $th->language->icon }}"></i>
                                                                                                                {{ substr($th->class_start, 0, 5) }}
                                                                                                                - {{ substr($th->class_end, 0, 5) }}
                                                                                                                <a href="#"
                                                                                                                   class="text-danger pull-right delete-alert"
                                                                                                                   data-item-id="{{ $th->id }}"><i
                                                                                                                        class="fa fa-times"></i></a>
                                                                                                                {{ Form::open(['method' => 'DELETE',
                                                                                                                'route' => ['user.profile.teacher.deleteHour', $user->id],
                                                                                                                'id' => 'item-del-'. $th->id  ]) }}
                                                                                                                {{ Form::hidden('hour_id', $th->id) }}
                                                                                                                {{ Form::close() }}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    @php
                                                                                                        $j++;
                                                                                                    @endphp
                                                                                                @endif
                                                                                            @endforeach
                                                                                            @if($j==0)
                                                                                                <div class="col-12">
                                                                                                    ---
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                        <hr>
                                                                                    </div>
                                                                                @endfor
                                                                            </div>
                                                                        @else
                                                                            <div class="row mt-4">
                                                                                <div class="col-12">
                                                                                    <h3>@lang('profile.no_teaching_hours_set')
                                                                                        @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
                                                                                            <a href="#addHoursModal"
                                                                                               data-toggle="modal"
                                                                                               class="text-success pull-right"><i
                                                                                                    class="fa fa-plus"></i></a>
                                                                                        @endif
                                                                                    </h3>
                                                                                    <hr>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if(Auth::id() != $user->id)
                <div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog"
                     aria-labelledby="sendMessageModalLabel"
                     aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sendMessageModalLabel">**Napíš mi!</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col-12">
                                        <textarea class="form-control" id="message_to_send" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="send_msg_btn" class="btn btn-success"><i
                                        class="fa fa-send"></i> @lang('general.send')
                                </button>
                                <button type="button" class="btn btn-light"
                                        data-dismiss="modal">@lang('general.Cancel')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($teacher)
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
                                                <select class="form-control" name="day" required>
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
                                                <input type="time" class="form-control" min="04:00" max="22:00"
                                                       name="class_start"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="row form-group my-0">
                                            <label for="" class="col-4 col-form-label text-right">@lang('lecture.end')
                                                *</label>
                                            <div class="col-6">
                                                <input type="time" class="form-control" min="04:01" max="23:00"
                                                       name="class_end"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="row form-group my-0">
                                            <label for=""
                                                   class="col-4 col-form-label text-right">@lang('general.language')
                                                *</label>
                                            <div class="col-4">
                                                <select class="form-control" name="language" required>
                                                    @foreach($teacher as $l)
                                                        <option value="{{ $l->id }}">{{ $l->name_en }}</option>
                                                    @endforeach
                                                </select>
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
                @endif

                <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
                     aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Modal body text goes here.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success"
                                        data-dismiss="modal">@lang('general.Create')</button>
                                <button type="button" class="btn btn-light"
                                        data-dismiss="modal">@lang('general.Cancel')</button>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
            @if((Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher')) and $user->hasRole('student') )
                <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog"
                     aria-labelledby="addNoteModalLabel"
                     aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addNoteModalLabel">@lang('profile.teacher_add_note')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route("user.profile.student.add_note", $user->id) }}" method="POST"
                                      id="form_teacher_note_add">
                                    @csrf

                                    <input type="hidden" name="teacher_id" value="{{ Auth::id() }}">
                                    <div class="row form-group">
                                        <label class="col-form-label col-10 offset-1"
                                               for="note">@lang('profile.note')</label>
                                        <div class="col-10 offset-1">
                                            <textarea class="form-control" name="note" id="note" rows="3"
                                                      required></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" form="form_teacher_note_add"
                                        class="btn btn-success">@lang('general.Save')</button>
                                <button type="button" class="btn btn-light"
                                        data-dismiss="modal">@lang('general.Cancel')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @stop

            @section('page_css')
                <link rel="stylesheet" href="{{ asset("vendors/fullcalendar/packages/core/main.css") }}">
                <link rel="stylesheet" href="{{ asset("vendors/fullcalendar/packages/daygrid/main.css") }}">
                <link rel="stylesheet" href="{{ asset("vendors/fullcalendar/packages/bootstrap/main.css") }}">
                <link rel="stylesheet" href="{{ asset("vendors/fullcalendar/packages/timegrid/main.css") }}">
                <link rel="stylesheet" href="{{ asset("vendors/fullcalendar/packages/list/main.css") }}">
            @stop

            @section('page_scripts')
                <script src="{{ asset("vendors/fullcalendar/packages/core/main.js") }}"></script>
                <script src="{{ asset("vendors/fullcalendar/packages/core/locales-all.js") }}"></script>
                <script src="{{ asset("vendors/fullcalendar/packages/daygrid/main.js") }}"></script>
                <script src="{{ asset("vendors/fullcalendar/packages/bootstrap/main.js") }}"></script>
                <script src="{{ asset("vendors/fullcalendar/packages/timegrid/main.js") }}"></script>
                <script src="{{ asset("vendors/fullcalendar/packages/list/main.js") }}"></script>

                <script>
                    var golden = "#ffd261";
                    var primary = "#a02f67";
                    var silverish = "#a4a2a1";
                    var secondary = "#7a7877";
                    var danger = "#fe7c96";
                    var warning = "#fed713";
                    var info = "#198ae3";
                    var success = "#1bcfb4";

                    $(document).ready(function () {

                        $('.delete-alert').click(function (e) {
                            var id = $(this).attr("data-item-id");
                            console.log(id);
                            swal({
                                title: "Prosím podvtďte akciu",
                                text: "Akcia: zmazanie preferencie vyučovacieho času individuálnych hodín.",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        document.getElementById('item-del-' + id).submit();
                                    }
                                });
                        });

                        @if($teacher)

                        $("#form_add_teacher_hours").validate({
                            rules: {
                                day: "required",
                                class_start: {
                                    required: true,
                                    min: "04:00",
                                    max: "23:00"
                                },
                                class_end: {
                                    required: true,
                                    min: "04:01",
                                    max: "23:00"
                                },
                                language: "required"
                            },
                            messages: {
                                day: {
                                    required: "@lang('validation.required',["attribute"=>__('general.day')])"
                                },
                                class_start: {
                                    required: "@lang('validation.required',["attribute"=>__('class.start')])",
                                    minlength: "@lang('validation.min.string', ["attribute"=>__('class.start'), "min"=>"04:00", "max"=>"22:00"])"
                                },
                                class_end: {
                                    required: "@lang('validation.required',["attribute"=>__('class.end')])",
                                    minlength: "@lang('validation.min.string', ["attribute"=>__('class.end'), "min"=>"04:01", "max"=>"23:00"])"
                                },
                                language: {
                                    required: "@lang('validation.required',["attribute"=>__('general.language')])"
                                },
                                level: {
                                    required: "@lang('validation.required',["attribute"=>__('language.level')])",
                                }
                            },
                            errorPlacement: function (label, element) {
                                label.addClass('mt-2 text-danger');
                                label.insertAfter(element);
                            },
                            highlight: function (element, errorClass) {
                                $(element).parent().addClass('has-danger')
                                $(element).addClass('form-control-danger')
                            }
                        });

                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            plugins: ['dayGrid', 'timeGrid', 'list'],
                            defaultView: 'dayGridMonth',
                            locale: '{{ Auth::user()->profile->locale }}', // the initial locale
                            header: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,dayGridWeek,timeGridDay'
                            },
                            defaultDate: '{{ \Carbon\Carbon::now()->format("Y-m-d") }}',
                            navLinks: true,
                            editable: false,
                            eventLimit: 3,
                            eventTimeFormat: {
                                hour: 'numeric',
                                minute: '2-digit',
                                meridiem: false
                            },
                            events: [
                                    @if($nearest_meeting)
                                {
                                    id: 1,
                                    title: '{{"\\n".__('meeting.meeting')."!"}}',
                                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $nearest_meeting->day)->format("Y-m-d")}}T{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $nearest_meeting->start)->format("H:i:s")}}',
                                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $nearest_meeting->day)->format("Y-m-d")}}T{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $nearest_meeting->end)->format("H:i:s")}}',
                                    url: '{{ route('teacher.nearest_meeting', $nearest_meeting->id) }}',
                                    backgroundColor: info,
                                    borderColor: silverish,
                                    textColor: "#000",
                                },
                                    @endif
                                    @foreach($teacher_instance->classes_i_all as $tc)
                                    @if(($tc->is_past() and count($tc->students) > 0) or !$tc->is_past())
                                {
                                    title: '',
                                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_start}}',
                                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_end}}',
                                    url: '{{ route('lectures.show', $tc->id) }}',
                                    backgroundColor: @if($tc->canceled) secondary
                                    @elseif($tc->is_past()) silverish @elseif($tc->is_free()) success
                                    @else danger @endif ,
                                    borderColor: golden,
                                    textColor: "#000",
                                },
                                    @endif
                                    @endforeach
                                    @foreach($teacher_instance->classes_c_all as $tc)
                                    @if(($tc->is_past() and count($tc->students) > 0) or !$tc->is_past())
                                {
                                    title: '{{count($tc->students) == 0 ? "" : (count($tc->students) == 1 ? "\\nS: ".$tc->students[0]->user->name : "\\nS: ".count($tc->students)."/".$tc->hour->class_limit )}}',
                                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_start}}',
                                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_end}}',
                                    url: '{{ route('lectures.show', $tc->id) }}',
                                    backgroundColor: @if($tc->canceled) secondary
                                    @elseif($tc->is_past()) silverish @elseif($tc->is_free()) success
                                    @else danger @endif ,
                                    borderColor: primary,
                                    textColor: "#000",
                                },
                                @endif
                                @endforeach

                            ],
                        });
                        calendar.render();
                        @endif

                        $('#send_msg_btn').click(function () {
                            let msg = $("#message_to_send").val();
                            let to_who = {{ $user->id }};

                            let my_id = {{ Auth::id() }};


                            if (msg == '') {
                                $.toast({
                                    heading: 'Error',
                                    text: 'You can\'t send an empty message',
                                    position: 'bottom-right',
                                    icon: 'error',
                                    stack: false,
                                    loaderBg: '#ed3939',
                                    bgColor: '#f0aaaa',
                                    textColor: 'black'
                                });
                                return;
                            }

                            $.ajax({
                                url: "{{ route("ajax_int") }}",
                                method: "POST",
                                data: {
                                    action: "send_message",
                                    user_id: my_id,
                                    reciever_id: to_who,
                                    message: msg
                                },
                                dataType: 'json',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function (response) {
                                    $('#sendMessageModal').modal('hide');
                                    $.toast({
                                        heading: 'Success',
                                        text: 'Message sent!',
                                        position: 'bottom-right',
                                        icon: 'success',
                                        stack: false,
                                        loaderBg: '#0eb543',
                                        bgColor: '#b5ffaa',
                                        textColor: 'black'
                                    })

                                },
                                error: function (response) {
                                    $.toast({
                                        heading: 'Error',
                                        text: 'Error',
                                        position: 'bottom-right',
                                        icon: 'error',
                                        stack: false,
                                        loaderBg: '#ed3939',
                                        bgColor: '#f0aaaa',
                                        textColor: 'black'
                                    })
                                }
                            })

                        })

                        $("#pills-teaching_schedule-tab").click(function () {
                            calendar.render();
                        })
                    })


                </script>
@stop
