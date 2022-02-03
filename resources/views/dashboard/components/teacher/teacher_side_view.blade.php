<div class="col-12 stretch-card my-2 px-2">
    <div class="card">
        <div class="card-body p-2 p-md-4">
            <h4 class="card-title">@lang('dashboard.my_stars_teacher')
                <i class="fa fa-question-circle"
                   data-custom-class="tooltip-success" data-toggle="tooltip"
                   data-placement="top" title=""
                   data-original-title="{{ __('profile.teacher_stars_tooltip') }}"></i>
            </h4>
            <hr>
            <h1 class="text-center text-md-left">
                <span class="text-golden">
                    <i class="fa fa-star"></i>
                    {{ count($teacher->inst->classes_i_unpaid()) }}
                </span>
            </h1>
        </div>
    </div>
</div>
