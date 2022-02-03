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
                <li class="breadcrumb-item active">
                    <a href="{{ route('admin.meetings.index') }}" class="text-primary">
                        @lang('side_menu.meetings')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('side_menu.meetings') - @lang('general.creating')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        @lang('side_menu.meetings') - @lang('general.creating')
                    </h4>

                    <form method="POST" action="{{ route('admin.meetings.store') }}">
                        @csrf

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="day">@lang('general.day') <span
                                        class="input_req col-form-label">*</span> </label>
                                <div id="datepicker-popup"
                                     class="input-group date datepicker">
                                    <input type="text"
                                           class="form-control border border-primary form-control border border-primary-sm"
                                           name="day" id="day"
                                           data-inputmask="'alias': 'date'"
                                           im-insert="true"
                                           placeholder="dd/mm/yyyy"
                                           required>
                                    <span style="display: none;"
                                          class="input-group-addon input-group-append border-left"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="start">Čas (od)*<span
                                        class="input_req col-form-label">*</span> </label>
                                <input type="time" class="form-control border border-primary" min="04:00" max="21:00"
                                       name="start" id="start"
                                       required>

                            </div>
                            <div class="form-group col-md-4">
                                <label for="end">Čas (do)*<span
                                        class="input_req col-form-label">*</span> </label>

                                <input type="time" class="form-control border border-primary" min="04:00" max="21:00"
                                       name="end" id="end"
                                       required>

                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="type">@lang('general.Type') <span
                                        class="input_req col-form-label">*</span> </label>
                                <select name="type" id="type" class="form-control border border-primary border-0">
                                    <option value="1">@lang('meeting.all_school')</option>
                                    <option value="2">@lang('meeting.by_language')</option>
                                    <option value="3">@lang('meeting.custom')</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4" id="language_select" style="display: none;">
                                <label for="language">@lang('general.language') <span
                                        class="input_req col-form-label">*</span> </label>
                                <select name="language" id="language"
                                        class="form-control border border-primary border-0">
                                    @foreach ($languages as $l)
                                        <option value="{{ $l->id }}">{{ $l->name_sk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-9" id="teacher_select" style="display: none;">
                                <label for="teacher">@lang('general.Teacher') <span
                                        class="input_req col-form-label">*</span> </label>
                                <select name="teacher[]" id="teacher"
                                        class="form-control border border-primary chosen-select" multiple>
                                    @foreach ($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="comment">@lang('general.comment')</label>
                                <textarea id="comment" name="comment" class="form-control border border-primary"
                                          rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-gradient-success"><i
                                        class="fa fa-check"></i> @lang('general.Save')</button>
                            </div>
                        </div>
                    </form>
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

    <script>

        $(document).ready(function () {
            $("#datepicker-popup").datepicker({
                startDate: '{{ \Carbon\Carbon::now()->format("d/m/Y") }}',
                format: "dd/mm/yyyy",
                autoclose: true,
                enableOnReadonly: true,
                todayHighlight: true,
            });

            $("#teacher").chosen({
                width: "100%"
            });

            $("#type").change(function () {
                let val = $(this).val();
                let ts = $("#teacher_select");
                let ls = $("#language_select");

                if (val == 1) {
                    ts.hide(function () {
                        $(this).animate()
                    });
                    ts.find('select').attr('required', false);
                    ls.hide(function () {
                        $(this).animate()
                    });
                    ls.find('select').attr('required', false);
                } else if (val == 2) {
                    ts.hide(function () {
                        $(this).animate()
                    });
                    ts.find('select').attr('required', false);
                    ls.show(function () {
                        $(this).animate()
                    });
                    ls.find('select').attr('required', true);
                } else if (val == 3) {
                    ls.hide(function () {
                        $(this).animate()
                    });
                    ls.find('select').attr('required', false);
                    ts.show(function () {
                        $(this).animate()
                    });
                    ts.find('select').attr('required', true);
                }
            })
        })

    </script>
@stop
