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
                    ...
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('survey.new_question_title')</h4>
                    <hr>

                    <form action="{{ route('survey.store') }}" method="POST">
                        @csrf

                        <div class="row form-group">
                            <label for="question" class="col-form-label col-12">@lang('survey.question')</label>
                            <div class="col-12">
                                <input type="text" name="question" id="question"
                                       class="form-control border border-primary" required value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 form-group">
                                <label class="col-form-label">@lang('survey.answer_type')</label>
                                <select name="type" id="type" class="form-control border-primary" required>
                                    <option value="1" selected>@lang('survey.type_1')</option>
                                    <option value="2">@lang('survey.type_2')</option>
                                    {{--<option value="3">@lang('survey.type_3')</option>--}}
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label class="col-form-label mb-0 pb-2">@lang('survey.question_visible_to')</label>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" name="visible" id="visible_custom_users" value="1"
                                               class="form-check-input visible_type_radio" checked
                                        > @lang('general.students'), @lang('general.Teacher')
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" name="visible" id="visible_custom_users" value="2"
                                               class="form-check-input visible_type_radio"
                                        > @lang('general.students')
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" name="visible" id="visible_custom_users" value="3"
                                               class="form-check-input visible_type_radio"
                                        > @lang('general.Teacher')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit"
                                        class="btn btn-gradient-success">@lang('general.Save')</button>
                                <a href="{{ route('survey.index') }}" class="btn btn-secondary">@lang('general.Cancel')</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {

        })

    </script>
@stop
