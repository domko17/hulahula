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
                <div class="card-body px-0 py-2 p-md-4">
                    <div class="row">

                        <!-- Profile header -->
                        @include('user.profile.components.show_profile_header')

                        <!-- Left panel -->
                        <div class="col-lg-3">
                            @include('user.profile.components.show_left_panel')
                        </div>

                        <!-- Right main panel -->
                        <div class="col-lg-9">
                            @include('user.profile.components.show_right_panel')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::id() != $user->id)
        @include('components.message_me_modal')
    @endif

    @if($teacher)
        @include('calendar.teacher.calendar_modals')
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
                                            <textarea class="form-control py-2 py-md-0" name="note" id="note" rows="3"
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

    <link rel="stylesheet" href="{{ asset('vendors/zambuto_calendar/zambuto_calendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/zambuto_calendar/zambuto_custom_style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">

@stop

@section('page_scripts')
    <script src="{{ asset('vendors/zambuto_calendar/zambuto_calendar.js') }}"></script>
    <script src="{{ asset('vendors/zambuto_calendar/zambuto_custom_script.js') }}"></script>
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>

    <script>
        $(function () {
            $('#languages_study').chosen({
                width: "100%"
            });
        })
    </script>

    @include('components.scripts.delete_alert')
    @include('components.scripts.send_message')

    @if($teacher)
        @include('calendar.teacher.calendar_script')
    @endif

    @if($student)
        @include('user.profile.components.scripts.profile_student_script')
    @endif
@stop
