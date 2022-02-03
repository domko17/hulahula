<div class="row">

    {{-- Main view --}}
    <div class="col-lg-9">
        <div class="row">
            <div class="col-12 stretch-card my-2 px-2">
                <div class="card">
                    <div class="card-body p-2 p-md-4 pb-md-2">
                        <h4 class="card-title mb-1" style="text-transform: none">@lang('dashboard.my_dashboard') <i
                                class="fa fa-question-circle main_hint_toggle"></i></h4>
                        <div class="alert alert-fill-primary" id="dashboard_hint_alert" style="display:none">
                            <strong>@lang('dashboard.dashboard_welcome_title')</strong>
                            <hr>
                            <p>@lang('dashboard.dashboard_welcome_text_1')</p>
                            <p>@lang('dashboard.dashboard_welcome_text_2')</p>
                            <p>@lang('dashboard.dashboard_welcome_text_3')</p>
                            <small>@lang('dashboard.dashboard_welcome_text_4')</small>
                        </div>
                        <hr class="mb-1">
                        @if( $teacher )
                            @include('dashboard.components.teacher.teacher_main_view')
                        @endif

                        @if( $student )
                            @include('dashboard.components.student.student_main_view')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Side view --}}
    <div class="col-lg-3">
        <div class="row">
            @if( $teacher || $student)
                @include('dashboard.components.other.zoom_meeting_start')
            @endif
            @if( $teacher )
                @include('dashboard.components.teacher.teacher_side_view')
            @endif
            @if( $student )
                @include('dashboard.components.student.student_side_view')
            @endif
            @if( $user->hasRole('developer') )
                <div class="col-12">
                    <a href="{{ route('test') }}" class="btn btn-block btn-inverse-danger">Test</a>
                </div>
            @endif
        </div>
    </div>
</div>

@include('dashboard.components.other.modals')
