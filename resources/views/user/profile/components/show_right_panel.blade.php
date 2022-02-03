<div class="row">
    <div class="col-12 col-md-8 text-center text-md-left">
        <h3 class="mb-0">{{ $profile->getFullName() }}
            @if(!$user->active) <span class="text-danger animated infinite pulse slow"> <small><u>Neaktívny</u></small></span> @endif
        </h3>
        <p class="my-0">
            @foreach($user->roles as $r)
                {{ __('general.role_'.$r->name) }}
                @if($r != $user->roles[count($user->roles)-1]) &nbsp;|&nbsp; @endif
            @endforeach
        </p>
        @if($user->hasRole('student'))
            <br>
            <p>
                @if($user->currentPackage)
                    @lang('general.active_package'):
                    <strong
                        class="text-primary">{{ $user->currentPackage ? $user->currentPackage->getName() : __('general.no_active_package') }}</strong>
                    <br>
                    @lang('dashboard.package_classes_remaining'):
                    <strong>{{ $user->currentPackage->classes_left }}</strong>
                @else
                    @lang('general.active_package'):
                    <strong class="text-danger"> {{ __('general.no_active_package') }}</strong>
                @endif
            </p>
        @endif
    </div>

    {{-- Action Button (profile edit, [user edit] --}}
    <div class="col-12 col-md-4 text-center text-md-right">
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
    <div class="col-12">
        <hr>
    </div>
</div>
<div class="tab-content pt-0 border-0" id="pills-tabContent">
    <div class="tab-pane @if(!$teacher) active show @endif fade" id="pills-base_info" role="tabpanel"
         aria-labelledby="pills-home-tab">
        <div class="row">
            <div class="col-12">
                @if($user->id == \Illuminate\Support\Facades\Auth::id() or
 \Illuminate\Support\Facades\Auth::user()->hasRole('admin') or \Illuminate\Support\Facades\Auth::user()->hasRole('teacher') or
  \Illuminate\Support\Facades\Auth::user()->hasRole('developer'))
                    <h2 class="display-4">
                        @lang('general.Base_info')
                    </h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p class="">
                                <span class="text-muted">@lang('general.Birthday'):</span>
                                {{ $profile->birthday ?
                                                (\Carbon\Carbon::createFromFormat("Y-m-d", $profile->birthday)->format("d.").
            __('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d", $profile->birthday)->month).
            " ".\Carbon\Carbon::createFromFormat("Y-m-d", $profile->birthday)->format("Y")) :
             "" }}</p>
                        </div>
                        @if($teacher)
                            <div class="col-12 col-md-6">
                                <p class="">
                                    <span class="text-muted">IBAN:</span>
                                    {{ $profile->iban }}
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
                                <p class="">
                                    <span class="text-muted">ZOOM:</span>
                                    {{ $profile->zune_link }}
                                </p>
                            </div>
                        @endif
                        <div class="col-12 col-md-6">
                            <p class="">
                                <span class="text-muted">@lang('general.nationality'):</span>
                                {{ $profile->nationality }}
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="">
                                <span class="text-muted">@lang('general.Phone'):</span>
                                {{ $profile->phone }}
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="">
                                <span class="text-muted">@lang('general.Email'):</span>
                                {{ $profile->user->email }}
                            </p>
                        </div>
                    </div>
                    <h2 class="display-4">
                        @lang('general.Address')
                    </h2>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p class="">
                                @lang('general.Street'):
                                {{ $profile->street ." ".$profile->street_number  }}
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="">
                                @lang('general.City'):
                                {{ $profile->zip . ($profile->city ? ',' : '') }} {{ $profile->city}}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            @if($teacher)
                <div class="col-12">
                    <hr>
                    <p><b>Bio:</b></p>
                    @if(strlen($profile->bio) == 0)
                        ...
                    @else
                        <div class="" style="width: 100%">
                            {!! $profile->bio !!}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($student)
        <div class="tab-pane fade" id="pills-student-card" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="row">
                <div class="col-12 col-md-8 text-center text-md-left">
                    <h2 class="display-4">@lang('profile.student_card_title')</h2>
                    <hr>
                </div>
                <div class="col-12 mb-3">
                    <h4>@lang('profile.student_my_future_lectures')</h4>


                      <table class="table table-striped" {{--id="student_future_lectures_table"--}}
                           style="width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('general.Date')</th>
                            <th>@lang('general.Teacher')</th>
                            <th>@lang('general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student_instance->classes_future as $i) 
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
                                                            <a href="{{ route('lectures.show', $i->id) }}" class="text-primary pull-right">
                                                                <i class="fa fa-search"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                        @endforeach
                        </tbody>
                    </table>



                </div>
                <div class="col-12 col-md-6">
                    <h4>@lang('profile.student_my_past_lectures')</h4>
                    <table class="table table-striped table-responsive" id="student_past_lectures_table"
                           style="width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('general.Date')</th>
                            <th>@lang('general.Teacher')</th>
                            <th>@lang('general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student_instance->classes_past as $i)
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
                                    <a href="{{ route('lectures.show', $i->id) }}" class="text-primary pull-right">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-6">
                    <h4>@lang('profile.student_my_packages_history')</h4>
                    <table class="table table-striped table-responsive" id="student_past_lectures_table"
                           style="width: 100%;">
                        <thead>
                        <tr>
                            <th>@lang('order.package')</th>
                            <th>@lang('profile.Date_purchased')</th>
                            <th>@lang('profile.LastLecture')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student_instance->packagesHistory as $p)
                            <tr>
                                <td>{{ $p->getName() }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $p->updated_at)->format("d-m-Y") }} </td>
                                <td>
                                    @if($p->lastLecture)
                                        {{ $p->lastLecture->class_date }}
                                    @else
                                        ---
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if($teacher)
        <div class="tab-pane fade active show"
             id="pills-teaching_schedule"
             role="tabpanel"
             aria-labelledby="pills-contact-tab">

            <div class="row">
                <div class="col-12">
                    @include('calendar.teacher.calendar_base')
                </div>
            </div>
        </div>
    @endif
</div>
