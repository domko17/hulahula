<h4 class="text-left">
    @lang('dashboard.teachers_schedule')
    @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
        <button class="btn btn-inverse-info btn-sm" type="button"
                data-toggle="modal"
                data-target="#teacherNearestHoursModal">@lang('dashboard.my_nearest_hours_teacher')
        </button>
    @endif
</h4>
<div class="calendar_loader">
    <div class="loader_wrap_t">
        <div id="loader_teacher">
            <div class="dot-opacity-loader">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <div id="calendar-teacher"></div>
</div>
@if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
    <a href="#!" id="calendar-teacher-settings-toggle">
        <h4 class="mt-2 d-inline-block">Nastavenia Kalendáru</h4>
    </a>
    <a href="#!" id="calendar-teacher-legend-toggle" class="pull-right">
        <h4 class="mt-2 d-inline-block">Legenda</h4>
    </a>
    <div id="calendar-teacher-settings" class="border border-primary p-4" style="display: none">
        <a href="#addHoursModal"
           data-toggle="modal"
           class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i>
            Pridať vyučovací čas
        </a>
        <a href="#addOneTimeHourModal"
           data-toggle="modal"
           class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i>
            @lang('profile.teacher_add_one_time_class')
        </a>
        <a href="#addVacationModal"
           data-toggle="modal"
           class="btn btn-sm btn-info">
            <i class="fa fa-plus"></i>
            @lang('profile.teacher_add_vacation')
        </a>
        <div class="row">
            <div class="col-sm-12 col-md-8">
                @if(count($teacher->teacher_hours) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4 class="text-left">@lang('profile.teaching_hours_set')</h4>
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
                                    <div class="d-flex flex-wrap">
                                        @foreach($teacher->teacher_hours as $th)
                                            @if($th->day == $i)
                                                <div class="ml-3">
                                                    {{ substr($th->class_start, 0, 5) }}
                                                    - {{ substr($th->class_end, 0, 5) }}
                                                    <a href="#"
                                                       data-custom-class="tooltip-danger" data-toggle="tooltip"
                                                       data-placement="top" title=""
                                                       data-original-title="{{ __('general.delete') }}"
                                                       class="text-danger pull-right delete-alert ml-2"
                                                       data-item-id="{{ $th->id }}"><i
                                                                class="fa fa-times"></i></a>
                                                    {{ Form::open(['method' => 'DELETE',
                                                    'route' => ['user.profile.teacher.deleteHour', $user->id],
                                                    'id' => 'item-del-'. $th->id  ]) }}
                                                    {{ Form::hidden('hour_id', $th->id) }}
                                                    {{ Form::close() }}
                                                </div>
                                                @php
                                                    $j++;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                    @if($j==0)
                                        <div class="col-12">
                                            ---
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>@lang('profile.no_teaching_hours_set')
                                @if(Auth::user()->hasRole('admin') or Auth::id() == $user->id)
                                    <a href="#addHoursModal"
                                       data-toggle="modal"
                                       class="text-success pull-right"><i
                                                class="fa fa-plus"></i></a>
                                @endif
                            </h4>
                            <hr>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-12 col-md-4">
                @if(count($teacher->vacations_future) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4 class="text-left">@lang('profile.vacations_future')</h4>
                            <hr>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="d-flex flex-wrap">
                                    @foreach($teacher->vacations_future as $v)
                                        <div class="ml-3">
                                            {{ $v->date_start }}
                                            - {{ $v->date_end }}
                                            <a href="#"
                                               data-custom-class="tooltip-danger" data-toggle="tooltip"
                                               data-placement="top" title=""
                                               data-original-title="{{ __('general.delete') }}"
                                               class="text-danger pull-right delete-alert ml-2"
                                               data-item-id="vac_{{ $v->id }}"><i
                                                        class="fa fa-times"></i></a>
                                            {{ Form::open(['method' => 'DELETE',
                                            'route' => ['user.profile.teacher.deleteVacation', $user->id],
                                            'id' => 'item-del-vac_'. $v->id  ]) }}
                                            {{ Form::hidden('vacation_id', $v->id) }}
                                            {{ Form::close() }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>@lang('profile.no_future_vacations')</h4>
                            <hr>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
