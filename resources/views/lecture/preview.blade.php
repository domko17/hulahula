@extends('layouts.app')

@section('title')

@stop

@section('content')
    <!--suppress ALL -->
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
                    @if($current_user->hasRole('admin'))
                        <a href="{{ route('lectures.index') }}">
                            @lang('side_menu.Lections')
                        </a>
                    @else
                        @lang('side_menu.Lections')
                    @endif
                </li>
                <li class="breadcrumb-item active">
                    ID:xxx - @lang('general.preview')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="stretch-card m-0">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <b> @lang("lecture.free")</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="stretch-card m-0">
                <div class="card">
                    <div class="card-header bg-silverish text-white text-center">
                        @lang("lecture.this_is_preview")
                    </div>
                </div>
            </div>
        </div>

        <!-- Side column -->
        <div class="col-lg-4">
            <!-- class title and date -->
            <div class="stretch-card my-3">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h1><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::createFromFormat("Y-m-d", $lecture->class_date)->format("d.").
                                __('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d", $lecture->class_date)->month).
                                " ".\Carbon\Carbon::createFromFormat("Y-m-d", $lecture->class_date)->format("Y") }}</h1>
                                <h3>
                                    <i class="fa fa-clock-o"></i> {{ substr($detail->class_start, 0, 5) }}
                                    - {{ substr($detail->class_end,0 ,5) }}
                                </h3>
                            </div>
                            {{-- admin/ucitel moze zrusit hodinu --}}
                            @if($current_user->id == $detail->user_id or $current_user->hasRole('admin'))
                                <div class="col-12">
                                    <form action="{{ route('lectures.cancel_lecture_from_preview') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="teacher_hour_id" value="{{ $detail->id }}">
                                        <input type="hidden" name="date" value="{{ $lecture->class_date }}">
                                        <button type="submit" class="btn btn-block btn-gradient-danger">
                                            @lang( 'lecture.cancel_lecture' )
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- class teachers -->
            <div class="stretch-card my-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang("general.Teacher") -
                            <a href="{{ route("user.profile", $teacher->id) }}"
                               class="text-primary">{{ $teacher->profile->first_name }} {{ $teacher->profile->last_name }}</a><br>
                            @foreach($teacher->teaching as $l)
                                <i class="flag-icon {{ $l->icon }}"></i>
                            @endforeach
                        </h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <img src="{{ $teacher->profile->getProfileImage() }}"
                                     class="rounded-circle" style="width: 100%; max-width: 200px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main column-->
        <div class="col-lg-8">
            <div class="stretch-card my-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h2 class="display-4">@lang('general.Student'): </h2>
                                <hr>
                            </div>
                            <div class="col-6">
                                <h4>
                                    @if($current_user->hasRole('student'))
                                        @if( $lecture->is_enroll_locked())
                                            <span class="text-secondary pull-right">
                                                        @lang( 'lecture.enroll_locked' )
                                                @if( $current_user->currentPackage->type != 1)
                                                    <i class="fa fa-question-circle"
                                                       data-custom-class="tooltip-secondaty"
                                                       data-toggle="tooltip"
                                                       data-placement="top" title=""
                                                       data-original-title="@lang( 'lecture.enroll_locked_hint' )"></i>
                                                @endif
                                                    </span>
                                        @elseif($current_user->canEnrollClass())
                                            {{-- Student sa moze prhlast na hodinu sam --}}
                                            <form method="POST" id="form_enroll_student"
                                                  action="{{ route("lectures.enroll_from_preview") }}">
                                                @csrf
                                                <input type="hidden" name="student_id"
                                                       value="{{ Auth::id() }}">
                                                <input type="hidden" name="teacher_hour_id" value="{{ $detail->id }}">
                                                <input type="hidden" name="date" value="{{ $lecture->class_date }}">
                                            </form>
                                            @if($current_user->currentPackage->type == 1) {{-- Ak SMART balicek, nastane iny prompt ... zaapisuje sa na vsetky 20 hodin z balicka o takomto case --}}
                                            <a href="#" data-thid="{{ $lecture->teacher_hour }}"
                                               class="text-success pull-right enroll_student_SMART">
                                                <i class="fa fa-sign-in"></i> @lang('lecture.i_want_this_class')
                                            </a>
                                            @else {{-- PREM.INDI. / EXTRA / STARTER - zapisuje sa jednorazovo na hodiny --}}
                                            <a href="#"
                                               class="text-success pull-right enroll_student">
                                                <i class="fa fa-sign-in"></i> @lang('lecture.i_want_this_class')
                                            </a>
                                            @endif
                                        @else
                                            {{-- nemoze sa zahlasiť--}}
                                            <span class="text-danger pull-right">
                                                <a href="{{ route("buy_stars.index") }}" class="text-danger">
                                                       @lang('lecture.not_enough_stars')</a>
                                            </span>
                                        @endif
                                    @elseif(($current_user->hasRole('teacher') and $lecture->hour->user_id == $current_user->id) or $current_user->hasRole('admin'))
                                        <form method="POST" id="form_create_lecture_from_preview"
                                              action="{{ route("lectures.create_lecture_from_preview") }}">
                                            @csrf
                                            <input type="hidden" name="teacher_hour_id" value="{{ $detail->id }}">
                                            <input type="hidden" name="date" value="{{ $lecture->class_date }}">
                                            <input type="hidden" name="lecture_data"
                                                   value="{{ json_encode($lecture) }}">
                                            <a href="#"
                                               class="text-success pull-right create_lecture_from_preview">
                                                <i class="fa fa-sign-in"></i> Vytvoriť hodinu
                                            </a>
                                        </form>
                                    @else
                                        {{--Host Vidi ze je volne miesto--}}
                                        <span class="text-success pull-right">
                                            <i class="fa fa-sign-in"></i> @lang('lecture.free')
                                        </span>
                                    @endif
                                </h4>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>@lang('lecture.no_students')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="stretch-card my-3">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            <i class="fa fa-align-left"></i> @lang('lecture.info')
                        </h4>
                        <hr>
                        <p>@lang('lecture.no_lecture_info')</p>
                    </div>
                </div>
            </div>


            <div class="stretch-card my-3">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fa fa-file"></i> @lang('lecture.available_materials')</h4>
                        <hr class="mt-3 mb-1">
                        <div class="row">
                            <div class="col-12">
                                <p class="py-2">@lang('lecture.no_material')</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modaly -->
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
                    <form method="POST" id="enroll_smart_student_form"
                          action="{{ route("lectures.enroll_from_preview") }}">
                        @csrf
                        <input type="hidden" name="student_id"
                               value="{{ Auth::id() }}">
                        <input type="hidden" name="teacher_hour_id" value="{{ $detail->id }}">
                        <input type="hidden" name="date" value="{{ $lecture->class_date }}">

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
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ asset('vendors/css/bootstrap-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">

@stop

@section('page_scripts')
    <script src="{{ asset('vendors/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/tinymce.min.js') }}"></script>

    <script>

        $("#students").chosen({
            width: "100%"
        });

        $(document).ready(function () {

            $('.enroll_student_SMART').click(function () {
                $('#enrollSmartModal').modal();
                let th_id = $(this).data('thid');
                console.log(th_id);
                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "student_smart_days_for_study",
                        student_id: {{ \Illuminate\Support\Facades\Auth::id() }},
                        th_id: th_id
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        let days_container = $('#days_for_choose');

                        $.each(response.th, function () {
                            let selected = "";
                            if (this.id == th_id) selected = "checked";
                            days_container.append($('<div class="form-group"></div>')
                                .append($('<div class="form-check m-0"></div>')
                                    .append($('<label class="form-check-label m-0"></label>')
                                        .append($('<input type="checkbox" name="smart_th[]" class="form-check-input" value="' + this.id + '"' + selected + '>'))
                                        .append('<i class="input-helper"></i>')
                                        .append($('<p class="ml-4"></p>')
                                            .append('' + this.day_name + " : " + this.class_start.substr(0, 5) + " - " + this.class_end.substr(0, 5))
                                        )
                                    )
                                )
                            )
                        });
                    },
                    error: function (response) {
                        $.toast({
                            heading: 'Error',
                            text: 'AJAX-Error',
                            position: 'bottom-right',
                            icon: 'error',
                            stack: false,
                            loaderBg: '#ed3939',
                            bgColor: '#f0aaaa',
                            textColor: 'black'
                        })
                    }
                })
            });

            $('#check_smart_student_enroll').click(function () {
                let max_checks = 2;
                let days_chosen = $('#days_for_choose input:checked').length;
                let err_container = $('#days_for_choose_err');
                err_container.hide('slow');

                if (days_chosen > max_checks || days_chosen == 0) {
                    err_container.show('slow');
                    return;
                }

                let title = '@lang('lecture.enroll_smart_title')';
                let text = "@lang('lecture.enroll_smart_text')";
                getPrompt2(title, text)
            });

            $('.create_lecture_from_preview').click(function () {
                $('#form_create_lecture_from_preview').submit();
            })

            $('.enroll_student').click(function () {
                let title = '@lang('lecture.enroll_title')';
                let text = "@lang('lecture.enroll_text')";
                getPrompt(title, text)
            })
        })

        function getPrompt(title, text) {
            swal({
                title: title,
                text: text,
                showCancelButton: true,
                buttons: {
                    cancel: {
                        text: "@lang('general.cancel')",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    },
                    confirm: {
                        text: "@lang('general.confirm')",
                        value: true,
                        visible: true,
                        className: "btn btn-success",
                        closeModal: true
                    }
                }
            }).then((result) => {
                if (result) {
                    console.log("confirmed");
                    $('#form_enroll_student').submit();
                }
            })
        }

        function getPrompt2(title, text) {
            swal({
                title: title,
                text: text,
                showCancelButton: true,
                buttons: {
                    cancel: {
                        text: "@lang('general.cancel')",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    },
                    confirm: {
                        text: "@lang('general.confirm')",
                        value: true,
                        visible: true,
                        className: "btn btn-success",
                        closeModal: true
                    }
                }
            }).then((result) => {
                if (result) {
                    console.log("confirmed");
                    $('#enroll_smart_student_form').submit();
                }
            })
        }
    </script>
@stop
