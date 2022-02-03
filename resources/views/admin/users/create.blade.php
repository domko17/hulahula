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
                        @lang('side_menu.Users')
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    @lang('general.creating')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-4 p-md-4">
                    <h4 class="card-title">@lang('users.user_create')</h4>
                    <p class="card-description">@lang('users.user_create_help')</p>

                    <form method="POST" action="{{ route('admin.users.store') }}" id="form_user_create">
                        @csrf

                        <div class="row">
                            <div class="form-group col-md-6 text-left text-md-right">
                                <label for="first_name"><span class="input_req">*</span> @lang('general.first_name')
                                </label>
                                <input id="first_name" type="text" placeholder="John"
                                       value="" name="first_name"
                                       class="form-control form-control-sm col-6 offset-md-6 align-self-end"
                                       required aria-required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">@lang('general.last_name') <span
                                        class="input_req">*</span></label>
                                <input id="last_name" type="text" placeholder="Smith"
                                       value="" name="last_name"
                                       class="form-control form-control-sm col-6" required aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 text-left text-md-right">
                                <label for="email"><span class="input_req">*</span> @lang('general.Email')</label>
                                <input id="email" type="email" placeholder="example@domain.com"
                                       value="" name="email"
                                       class="form-control form-control-sm col-8 offset-md-4 align-self-end"
                                       required aria-required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role">@lang('general.role') <span class="input_req">*</span> </label>
                                <select id="role" name="role"
                                        class="form-control form-control-sm col-8 col-md-4"
                                        required aria-required="true">
                                    <option value="0" disabled selected>@lang('general.select_option')</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}">{{ $r->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" id="lang_select" style="display: none;">
                            <div class="form-group col-md-6 text-left text-md-right"></div>
                            <div class="form-group col-md-6">
                                <label for="language">@lang('general.language') <span class="input_req">*</span>
                                </label>
                                <select id="language" name="language"
                                        class="form-control form-control-sm col-8 col-md-4"
                                        required aria-required="true">
                                    <option value="0" disabled selected>@lang('general.select_option')</option>
                                    @foreach($languages as $l)
                                        <option value="{{ $l->id }}"><i
                                                class="flag-icon {{$l->icon}}"></i> {{ $l->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6 text-left text-md-right">
                                <label for="password"><span class="input_req">*</span> @lang("general.Password")</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       value=""
                                       class="form-control form-control-sm col-8 col-md-4 offset-md-8 align-self-end "
                                       required aria-required="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirm">@lang("general.Password_confirm") <span class="input_req">*</span>
                                </label>
                                <input type="password"
                                       name="password_confirm"
                                       id="password_confirm"
                                       value=""
                                       class="form-control form-control-sm col-8 col-md-4"
                                       required aria-required="true">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-12">
                                <div class="align-self-end">
                                    <button type="submit" class="btn btn-block btn-gradient-success"><i
                                            class="fa fa-check"></i> @lang('general.create')</button>
                                    <br>
                                    <small><span class="text-danger">*</span> - @lang('general.required_field')</small>
                                </div>
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

    <script>
        $(document).ready(function () {

            $('#role').on('change click', function () {
                let val = $(this).val();
                if (val == 2 || val == 3) {
                    $('#lang_select').show(function () {
                        $(this).animate(500);
                    })
                } else {
                    $('#lang_select').hide(function () {
                        $(this).animate(500);
                    })
                }
            });

            // validate signup form on keyup and submit
            $("#form_language_create").validate({
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
