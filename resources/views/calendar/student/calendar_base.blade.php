<h4>
    @lang('dashboard.students_schedule')
    <button class="btn btn-inverse-info btn-sm" type="button"
            data-toggle="modal"
            data-target="#studentNearestHoursModal">@lang('dashboard.my_nearest_hours_student')
    </button>
    @if( count($student->classes_future) > 0 and
     $student->classes_future[0]->class_date == \Carbon\Carbon::now()->addDay()->format("Y-m-d") and
     $student->classes_future[0]->hour->teacher->profile->zune_link )
        <a href="{{ $student->classes_future[0]->hour->teacher->profile->zune_link }}"
           class="btn btn-gradient-success btn-sm text-uppercase"
           target="_blank">
            <i class="fa fa-external-link"></i> @lang('dashboard.enter_class')</a>
    @endif
</h4>
<div class="calendar_loader">
    <div class="loader_wrap_s">
        <div id="loader_student">
            <div class="dot-opacity-loader">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <div id="calendar-student"></div>
</div>
