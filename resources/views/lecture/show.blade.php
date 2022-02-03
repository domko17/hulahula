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
                    @if( $current_user->hasRole('admin') )
                        <a href="{{ route('lectures.index') }}">
                            @lang( 'side_menu.Lections' )
                        </a>
                    @else
                        @lang( 'side_menu.Lections' )
                    @endif
                </li>
                <li class="breadcrumb-item active">
                    ID:{{ $lecture->id }} - @lang( 'general.detail' )
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="stretch-card m-0">
                <div class="card">
                    <div class="card-header
                                @if( $lecture->canceled ) bg-silverish text-white
                                @elseif( $is_past ) bg-secondary text-white
                                @elseif( !$lecture->is_free() ) bg-danger text-white
                                @else bg-success text-white
                    @endif text-center">
                        <b> @if( $lecture->canceled ) @lang( "lecture.canceled" )
                            @elseif( $is_past ) @lang( "lecture.past_lecture" )<br>{{ $lecture->cancel_reason }}
                            @elseif( !$lecture->is_free() ) @lang( "lecture.not_free" )
                            @else @lang( "lecture.free" )
                            @endif</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stretch-card my-3">
                <div class="card">
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
                            {{-- Indiv: ucitel moze zrusit hodinu | Skup: admin moze zrusit hodinu, ucitel moze prenechat miesto --}}
                            @if( !$is_past and !$lecture->canceled and ($current_user->id == $detail->user_id or $current_user->hasRole('admin')))
                                <div class="col-12">
                                    <button type="button" class="btn btn-block btn-gradient-danger"
                                            data-toggle="modal" data-target="#cancelClassModal">
                                        @lang( 'lecture.cancel_lecture' )
                                    </button>
                                </div>
                            @endif
                            {{-- Indiv: ucitel moze zrusit hodinu | Skup: admin moze zrusit hodinu, ucitel moze prenechat miesto --}}
                            @if( $is_past and !$lecture->canceled )
                                @if( ($is_individual and ($current_user->id == $detail->user_id or $current_user->hasRole('admin'))) or ($current_user->hasRole('admin') and $is_collective))
                                    <div class="col-12">
                                        <button type="button" class="btn btn-block btn-gradient-success px-2"
                                                data-toggle="modal" data-target="#addRecordingModal">
                                            @if( $lecture->recording_url )
                                                @lang( 'lecture.change_recording' )
                                            @else
                                                @lang( 'lecture.add_recording' )
                                            @endif
                                        </button>
                                    </div>
                                @endif
                                @if( ($current_user->hasRole('student') and $lecture->is_student_attending($current_user->id)) or ($is_individual and ($current_user->id == $detail->user_id or $current_user->hasRole('admin'))) or ($current_user->hasRole('admin') and $is_collective))
                                    <div class="col-12">
                                        @if( $lecture->recording_url )
                                            <a href="{{ $lecture->recording_url }}"
                                               class="btn btn-gradient-primary btn-block px-2 my-1"
                                               target="_blank">
                                                @lang( 'lecture.lecture_recording' )
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-gradient-primary btn-block px-2"
                                                    disabled>
                                                @lang( 'lecture.lecture_recording' )
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <!-- class teachers -->
            <div class="stretch-card my-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang( "general.Teacher" ) @if( $teacher ) - <a
                                href="{{ route("user.profile", $teacher->user->id) }}"
                                class="text-primary">{{ $teacher->first_name }} {{ $teacher->last_name }}</a>@endif<br>
                            @foreach( $teacher->user->teaching as $l )
                                <i class="flag-icon {{ $l->icon }}"></i>
                            @endforeach
                        </h4>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <img src="{{ $teacher->getProfileImage() }}"
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
                            @if(!($current_user->hasRole('student') and $lecture->is_student_attending($current_user->id)) or !$current_user->hasRole('student') or $current_user->hasRole('admin'))
                                <div class="col-6">
                                    @if( $current_user->currentPackage and $current_user->currentPackage->type == 1 )
                                        <h2 class="display-4">@lang( 'general.Students' ):</h2>
                                    @else
                                        <h2 class="display-4">@lang( 'general.Student' ):</h2>
                                    @endif
                                    <hr>
                                </div>
                            @endif
                            <div
                                class="@if(!($current_user->hasRole('student') and $lecture->is_student_attending($current_user->id)) or !$current_user->hasRole('student') or $current_user->hasRole('admin')) col-6 @else col-12 @endif">
                                <h4>
                                    @if( !$is_past and !$lecture->canceled )
                                        @if( $lecture->is_free() or $lecture->is_student_attending($current_user->id) )
                                            @if( ($current_user->hasRole('admin') or
                                            ($current_user->hasRole('teacher') and $lecture->hour->teacher->id == $current_user->id )) and
                                             !($current_user->hasRole('student')))
                                                {{--Admin moze pridat studenta cez modal--}}
                                                <a href="#assignStudentAdminModal" data-toggle="modal"
                                                   class="text-success pull-right">
                                                    <i class="fa fa-sign-in"></i> @lang('lecture.student_sign_up_admin')
                                                </a>
                                            @elseif( $current_user->hasRole('student') )
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
                                                @elseif( $lecture->is_student_attending($current_user->id) )
                                                    <span class="text-danger pull-right">
                                                        @lang( 'lecture.already_enrolled' )
                                                        @if( $current_user->currentPackage->type != 1)
                                                            | <a
                                                                @if( \Carbon\Carbon::now() < \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$lecture->class_date." ".$detail->class_start)->subDay() )
                                                                href="#changeClassModal"
                                                                data-toggle="modal"
                                                                class="text-danger">
                                                                @else
                                                                    class="text-muted" disabled>
                                                                @endif
                                                                @lang( 'lecture.un_enroll' )
                                                                <i class="fa fa-question-circle"
                                                                   data-custom-class="tooltip-danger"
                                                                   data-toggle="tooltip"
                                                                   data-placement="top" title=""
                                                                   data-original-title="@lang( 'lecture.un_enroll_rule' )"></i>
                                                            </a>
                                                        @endif
                                                    </span>
                                                @elseif( $current_user->canEnrollClass() )
                                                    {{-- Student sa moze prhlast na hodinu sam --}}
                                                    <form method="POST" id="form_enroll_student"
                                                          action="{{ route("lectures.sign_student", $lecture->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="student_id"
                                                               value="{{ Auth::id() }}">
                                                    </form>
                                                    @if( $current_user->currentPackage->type == 1 )
                                                        {{-- Ak SMART balicek, nastane iny prompt ... zaapisuje sa na vsetky 20 hodin z balicka o takomto case --}}
                                                        <a href="#" data-thid="{{ $lecture->teacher_hour }}"
                                                           class="text-success pull-right enroll_student_SMART">
                                                            <i class="fa fa-sign-in"></i> @lang('lecture.i_want_this_class')
                                                        </a>
                                                    @else {{-- PREM.INDI. / EXTRA - zapisuje sa jednorazovo na hodiny --}}
                                                    <a href="#"
                                                       class="text-success pull-right enroll_student">
                                                        <i class="fa fa-sign-in"></i> @lang('lecture.i_want_this_class')
                                                    </a>
                                                    @endif
                                                @else
                                                    {{--nedostatok hviezdiciek--}}
                                                    <span
                                                        class="text-danger pull-right">
                                                        <a href="{{ route("buy_stars.index") }}" class="text-danger">
                                                            @lang( 'lecture.not_enough_stars')</a>
                                                    </span>
                                                @endif
                                            @else
                                                {{--Ucitel/Host Vidi ze je volne miesto--}}
                                                <span class="text-success pull-right">
                                                    <i class="fa fa-sign-in"></i> @lang( 'lecture.free' )
                                                </span>
                                            @endif
                                        @else
                                            <span
                                                class="text-danger pull-right">@lang( 'lecture.not_free' )</span>
                                        @endif
                                    @else
                                        {{--Oznamenie o minulej/zrusenej hodine vidia vsetci--}}
                                        @if( $lecture->canceled )
                                            <span
                                                class="text-secondary pull-right">@lang( 'lecture.canceled' )</span>
                                        @else
                                            <span
                                                class="text-secondary pull-right">@lang( 'lecture.past_lecture' )</span>
                                        @endif
                                    @endif
                                </h4>
                            </div>
                            @if(!($current_user->hasRole('student') and $lecture->is_student_attending($current_user->id)) or !$current_user->hasRole('student') or $current_user->hasRole('admin'))

                                <div class="col-12">
                                    <div class="row">
                                        {{-- TODO: student vidi len obsadenost a ak je prihlaseny na danu hodinu tak vidi len seba --}}
                                        @if( count($students ) == 0)
                                            @if( $current_user->hasRole('admin') or $current_user->hasRole('teacher') or $current_user->hasRole('developer') )
                                                <div class="col-sm-12">
                                                    <h3 class="text-danger">@lang( 'lecture.no_students' )</h3>
                                                </div>
                                            @endif
                                        @else
                                            @if( $current_user->id == $teacher->user_id or
                                             $current_user->hasRole('admin') )
                                                {{--Admin a ucitel ktory uci tuto hodinu vidi detail o studentoch--}}
                                                @foreach ( $students as $student )
                                                    <div class="col-3">
                                                        <img src="{{ $student->user->profile->getProfileImage() }}"
                                                             class="rounded-circle mb-3" style="max-width: 100%">
                                                    </div>
                                                    <div class="col-9">
                                                        <a href="{{ route("user.profile", $student->user->id) }}"
                                                           class="text-primary"><h4
                                                                class="text-success">{{$student->user->name}} {{ $student->getUsedPackage() ? (" - ".\App\Models\Helper::PACKAGES[$student->getUsedPackage()]['name']) : "" }}</h4>
                                                        </a>
                                                        @if( !$is_past and !$lecture->canceled )
                                                            <a href="{{ route("lectures.un_assign_student", [$lecture->id, $student->user->id]) }}"
                                                               class="text-danger">
                                                                <i class="fa fa-times"></i>
                                                                @lang( 'lecture.un_enroll_student' )
                                                            </a>
                                                            @if( $current_user->hasRole('admin') )
                                                                <br>
                                                                <a href="#changeClassDateModal" data-toggle="modal"
                                                                   class="text-info" id="admin_student_reschedule"
                                                                   data-student="{{ $student->user->id }}"
                                                                   data-lecture="{{ $lecture->id }}">
                                                                    <i class="fa fa-edit"></i>
                                                                    @lang( 'lecture.change_date_student' )
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @elseif( $lecture->is_student_attending($current_user->id) )
                                                <div class="col-3">
                                                    <img
                                                        src="{{ $current_user->profile->getProfileImage() }}"
                                                        class="rounded-circle mb-3" style="max-width: 100%">
                                                </div>
                                                <div class="col-9">
                                                    <h3 class="text-success">{{ $current_user->name }}</h3>
                                                </div>
                                            @else
                                                {{--Neopravneny nevidia detail--}}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="stretch-card my-3 @if( empty($lecture->info) and $lecture->is_student_attending($current_user->id)) d-none d-sm-none @endif d-md-block">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            @lang( 'lecture.info' )
                            @if( !$is_past and !$lecture->canceled and
                            (($teacher and $teacher->user_id == \Illuminate\Support\Facades\Auth::id()) or $current_user->hasRole('admin')) )
                                {{--Prideleny ucitel a admin smie otvorit modal na upravu informacii ak je hodina v buducnosti--}}
                                <button type="button"
                                        class="btn btn-gradient-success btn-sm pull-right"
                                        data-toggle="modal" data-target="#editInfoModal"><i
                                        class="fa fa-edit"></i> @lang( 'general.edit' )</button>
                            @endif
                        </h4>
                        <hr>
                        {!! $lecture->info !!}
                    </div>
                </div>
            </div>

            @if( ($teacher and $teacher->user_id == \Illuminate\Support\Facades\Auth::id() ) or
             $current_user->hasRole('admin') or
             $lecture->is_student_attending($current_user->id))
                <div
                    class="stretch-card my-3 @if( empty($lecture_material) and $lecture->is_student_attending($current_user->id)) d-none d-sm-none @endif d-md-block">
                    <div class="card">
                        <div class="card-body">
                            <h4> @lang( 'lecture.available_materials' )
                                @if( !$is_past and !$lecture->canceled and (($teacher and $teacher->user_id == \Illuminate\Support\Facades\Auth::id()) or
                            $current_user->hasRole('admin')) )
                                    <button type="button" data-toggle="modal" data-target="#lectureMaterialChangeModal"
                                            class="btn btn-sm btn-gradient-success pull-right"><i
                                            class="fa fa-plus"></i></button>
                                @endif
                            </h4>
                            <hr class="mt-3 mb-1">
                            <div class="row">
                                <div class="col-12">
                                    @if( count($lecture_material) > 0 )
                                        <table class="table table-striped" id="lecture_materials_table">
                                            <thead>
                                            <tr>
                                                <th>@lang( 'general.title' )</th>
                                                <th>@lang( 'general.Type' )</th>
                                                <th>@lang( 'general.actions' )</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach( $lecture_material as $lm )
                                                <tr>
                                                    <td>{{ $lm->name }}</td>
                                                    <td>{{ $lm->get_type_name() }}</td>
                                                    <td>
                                                        @if( $lm->type == 1 )
                                                            <a href="{{ $lm->content }}"
                                                               class="btn btn-gradient-primary btn-sm pull-right"
                                                               target="_blank"><i
                                                                    class="fa fa-external-link"></i> @lang('general.url_link')
                                                            </a>
                                                        @endif
                                                        @if( $lm->type == 2 )
                                                            <a href="{{ $lm->content }}"
                                                               class="btn btn-gradient-primary btn-sm pull-right"
                                                               target="_blank"><i class="fa fa-youtube"></i> YouTube</a>
                                                        @endif
                                                        @if( $lm->type == 3 )
                                                            <a href="{{ route('materials.download', $lm->id) }}"
                                                               class="btn btn-gradient-primary btn-sm pull-right"
                                                            ><i class="fa fa-download"></i> @lang('general.download')
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="py-2">@lang( 'lecture.no_material' )</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modaly -->
    @include('lecture.components.show_modals')
@stop

@section( 'page_css' )
    <link rel="stylesheet" href="{{ asset('vendors/css/bootstrap-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/zambuto_calendar/zambuto_calendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/zambuto_calendar/zambuto_custom_style.css') }}">
@stop

@section( 'page_scripts' )
    <script src="{{ asset('vendors/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendors/zambuto_calendar/zambuto_calendar.js') }}"></script>
    <script src="{{ asset('vendors/zambuto_calendar/zambuto_custom_script.js') }}"></script>

    @include('lecture.components.show_scripts')
@stop
