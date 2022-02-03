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
                    <a href="{{ route('admin.languages.index') }}" class="text-primary">
                        @lang('side_menu.Languages')
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $data->name_en }} - @lang('general.editing')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('language.language_edit')</h4>
                    <p class="card-description">@lang('language.language_edit_help')</p>

                    <form method="POST" action="{{ route('admin.languages.update', $data->id) }}"
                          id="form_language_update">
                        @csrf
                        @method("PUT")

                        <div class="form-group row">
                            <label for="name_native"
                                   class="col-sm-4 col-form-label text-right">@lang('language.name_native')<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" id="name_native" name="name_native" value="{{ $data->name_native }}"
                                       minlength="2"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name_en"
                                   class="col-sm-4 col-form-label text-right">@lang('language.name_en')<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" id="name_en" name="name_en" value="{{ $data->name_en }}"
                                       minlength="2"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name_sk"
                                   class="col-sm-4 col-form-label text-right">@lang('language.name_sk')<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" id="name_sk" name="name_sk" value="{{ $data->name_sk }}"
                                       minlength="2"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="abbr"
                                   class="col-sm-4 col-form-label text-right">@lang('language.abbreviation')<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" id="abbr" name="abbr" value="{{ $data->abbr }}" minlength="2"
                                       maxlength="4"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="icon"
                                   class="col-sm-4 col-form-label text-right">@lang('general.icon')<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <button class="btn btn-inverse-dark btn-sm" role="iconpicker" data-iconset="flagicon"
                                        data-search-text="sk" data-icon="{{ $data->icon }}" name="icon"
                                        id="icon"></button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description"
                                   class="col-sm-4 col-form-label text-right">@lang('language.description')</label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description"
                                          class="form-control" rows="5">{{ $data->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 offset-4">
                                <button type="submit" class="btn btn-gradient-success"><i
                                        class="fa fa-check"></i> @lang('general.update')</button>
                                <br>
                                <small><span class="text-danger">*</span> - @lang('general.required_field')</small>
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
@stop

@section('page_scripts')
    <script src="{{ asset('vendors/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            tinymce.init({
                selector: "#description",
                height: 300,
                theme: "modern"
            });
            // validate signup form on keyup and submit
            $("#form_language_update").validate({
                rules: {
                    name_native: {
                        required: true,
                        minlength: 2
                    },
                    name_en: {
                        required: true,
                        minLength: 2
                    },
                    name_sk: {
                        required: true,
                        minLength: 2
                    },
                    abbr: {
                        required: true,
                        minLength: 2,
                        maxLength: 4
                    },
                    icon: "required"
                },
                messages: {
                    name_native: {
                        required: "@lang('validation.required',["attribute"=>__('language.name_native')])",
                        minlength: "@lang('validation.min.string', ["attribute"=>__('language.name_native'), "min"=>2])"
                    },
                    name_en: {
                        required: "@lang('validation.required',["attribute"=>__('language.name_en')])",
                        minlength: "@lang('validation.min.string', ["attribute"=>__('language.name_en'), "min"=>2])"
                    },
                    name_sk: {
                        required: "@lang('validation.required',["attribute"=>__('language.name_sk')])",
                        minlength: "@lang('validation.min.string', ["attribute"=>__('language.name_sk'), "min"=>2])"
                    },
                    abbr: {
                        required: "@lang('validation.required',["attribute"=>__('language.abbreviation')])",
                        minLength: "@lang('validation.min.string',["attribute"=>__('language.abbreviation'), "min"=>2])",
                        maxLength: "@lang('validation.max.string',["attribute"=>__('language.abbreviation'), "max"=>4])",
                    },
                    icon: {
                        required: "@lang('validation.required',["attribute"=>__('general.icon')])",
                    }
                },
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger')
                    $(element).addClass('form-control-danger')
                }
            });
        })
    </script>
@stop
