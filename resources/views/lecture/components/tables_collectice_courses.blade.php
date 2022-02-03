<table class="table table-striped table-condensed pl-1 pl-md-0" id="table_collective_courses_mobile"
       style="display: none; width: 100%;">
    <thead>
    <tr>
        <th>@lang('general.detail')</th>
        <th><i class="mdi mdi-counter"></i></th>
        <th>@lang('general.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($courses as $c)
        <tr>
            <td>
                <i class="flag-icon {{ $c->language->icon }}"></i><br>
                <b>@foreach(json_decode($c->day) as $d) {{ __('general.day_'.$d)."," }} @endforeach <br>
                    {{ substr($c->class_start, 0,5) . " - " . substr($c->class_end, 0,5) }}</b><br>
                T: @if($c->teacher)
                    <a href="{{ route('user.profile', $c->teacher->id) }}"
                       class="text-primary"> {{ $c->teacher->profile->last_name }}</a>
                @else
                    -
                @endif
                /
                @if($c->sub_teacher)
                    <a href="{{ route('user.profile', $c->sub_teacher->id) }}"
                       class="text-primary"> {{ $c->sub_teacher->profile->last_name }}</a>
                @else
                    -
                @endif<br>
                Limit: {{ $c->class_limit }}
            </td>
            <td class="text-{{count($c->classes_future) > 0 ? "success": "danger" }}">
                <b>{{ count($c->classes_future) }}</b>
                @if(count($c->classes_future) == 0)
                    <span class="text-danger animated infinite pulse slower"
                          data-custom-class="tooltip-danger" data-toggle="tooltip"
                          data-placement="top" title=""
                          data-original-title="@lang('lecture.collective_courses_no_courses_planned')"><i
                            class="fa fa-exclamation-triangle"></i> </span>
                @endif
            </td>
            <td class="text-right">
                <a href="#prolongCourseModal" data-toggle="modal"
                   class="btn btn-sm btn-inverse-info prolong_modal_open_btn"
                   data-id="{{$c->id}}">
                    <i class="fa fa-plus"></i>
                </a>
                <a href="#"
                   class="delete-alert btn btn-sm btn-inverse-danger"
                   data-item-id="{{ $c->id }}"><i
                        class="fa fa-times"></i></a>
                {{ Form::open(['method' => 'DELETE',
                'route' => ['lectures.collective_courses.destroy', $c->id],
                'id' => 'item-del-'. $c->id  ]) }}
                {{ Form::hidden('collective_hour_id', $c->id) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-striped table-condensed table-responsive" id="table_collective_courses_pc"
       style="display: none">
    <thead>
    <tr>
        <th>@lang('general.days')</th>
        <th>@lang('general.time')</th>
        <th>@lang('general.language')</th>
        <th>@lang('language.teachers')</th>
        <th>@lang('lecture.class_limit')</th>
        <th><i class="mdi mdi-counter"></i></th>
        <th>@lang('general.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($courses as $c)
        <tr>
            <td>@foreach(json_decode($c->day) as $d) {{ __('general.day_'.$d)."," }} @endforeach</td>
            <td>{{ substr($c->class_start, 0,5) . " - " . substr($c->class_end, 0,5) }}</td>
            <td><i class="flag-icon {{ $c->language->icon }}"></i></td>
            <td>
                @if($c->teacher)
                    <a href="{{ route('user.profile', $c->teacher->id) }}"
                       class="text-primary"> {{ $c->teacher->profile->last_name }}</a>
                @else
                    -
                @endif
                /
                @if($c->sub_teacher)
                    <a href="{{ route('user.profile', $c->sub_teacher->id) }}"
                       class="text-primary"> {{ $c->sub_teacher->profile->last_name }}</a>
                @else
                    -
                @endif
            </td>
            <td>{{ $c->class_limit }}</td>
            <td class="text-{{count($c->classes_future) > 0 ? "success": "danger" }}">
                <b>{{ count($c->classes_future) }}</b>
                @if(count($c->classes_future) == 0)
                    <span class="text-danger animated infinite pulse slower"
                          data-custom-class="tooltip-danger" data-toggle="tooltip"
                          data-placement="top" title=""
                          data-original-title="@lang('lecture.collective_courses_no_courses_planned')"><i
                            class="fa fa-exclamation-triangle"></i> </span>
                @endif
            </td>
            <td class="text-right">
                <a href="#prolongCourseModal" data-toggle="modal"
                   class="btn btn-sm btn-inverse-info px-1 py-0 prolong_modal_open_btn"
                   data-id="{{$c->id}}">
                    <i class="fa fa-plus"></i>
                </a>
                <a href="#"
                   class="delete-alert btn btn-sm btn-inverse-danger px-1 py-0"
                   data-item-id="{{ $c->id }}"><i
                        class="fa fa-times"></i></a>
                {{ Form::open(['method' => 'DELETE',
                'route' => ['lectures.collective_courses.destroy', $c->id],
                'id' => 'item-del-'. $c->id  ]) }}
                {{ Form::hidden('collective_hour_id', $c->id) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
