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
                    <a href="{{ route('admin.users.index') }}" class="text-primary">
                        @lang('side_menu.Users')
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ "(".$user->id.") ".$user->name }} - @lang('general.editing')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12">
                        <h4 class="card-title">Úprava systémového používateľa</h4>
                        <hr>
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="form_user_edit">
                        @csrf
                        @method('PUT')

                        {{--<div class="row">
                            <div class="form-group col-md-6 text-right">
                                <label for="title_before">@lang('general.title_before')</label>
                                <input id="title_before" type="text" placeholder="Mgr"
                                       value="{{ $profile->title_before }}" name="title_before"
                                       class="form-control form-control-sm col-4 offset-8 align-self-end input_rtl">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="title_after">@lang('general.title_after')</label>
                                <input id="title_after" type="text" placeholder="PhD."
                                       value="{{ $profile->title_after }}" name="title_after"
                                       class="form-control form-control-sm col-4">
                            </div>
                        </div>--}}
                        <div class="row form-group">
                            <div class="col-6 offset-3 col-md-3 offset-md-6 form-group mb-0">
                                <div class="form-check my-1">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="active"
                                               value="1"
                                               class="form-check-input"
                                               {{ $user->active ? "checked" : '' }}
                                               >Aktívny ?
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6 offset-3 col-md-6 offset-md-0 text-left text-md-right">
                                <label for="first_name"><span class="input_req">*</span> @lang('general.first_name')
                                </label>
                                <input id="first_name" type="text" placeholder="John"
                                       value="{{ $profile->first_name }}" name="first_name"
                                       class="form-control form-control-sm align-self-end"
                                       required aria-required="true">
                            </div>
                            <div class="form-group col-6 offset-3 col-md-6 offset-md-0">
                                <label for="last_name">@lang('general.last_name') <span
                                        class="input_req">*</span></label>
                                <input id="last_name" type="text" placeholder="Smith"
                                       value="{{ $profile->last_name }}" name="last_name"
                                       class="form-control form-control-sm" required aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6 offset-3 col-md-6 offset-md-0 text-left text-md-right">
                                <label for="email"><span class="input_req">*</span> @lang('general.Email')</label>
                                <input id="email" type="email" placeholder="example@domain.com"
                                       value="{{ $user->email }}" name="email" readonly
                                       class="form-control form-control-sm align-self-end"
                                       required aria-required="true">
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4 offset-md-4 text-center">
                                <p class="text-muted">Role používateľa</p>
                            </div>
                            <div class="form-group col-6 offset-3 col-md-6 offset-md-3 text-left text-md-right">
                                <select id="roles" name="roles[]" multiple
                                        class="form-control form-control-sm chosen-select"
                                        required aria-required="true">
                                    @foreach($roles as $r)
                                        @if($r->name != "guest")
                                            <option value="{{ $r->id }}"
                                                    @if($user->hasRole($r->name)) selected @endif>{{ __('general.role_'.$r->name) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" id="languages_study_row" style="display: none;">
                            <div class="col-12 col-md-4 offset-md-4 text-center">
                                <label for="languages_study" class="col-form-label">
                                    Študuje jazyky:
                                </label>
                            </div>
                            <div class="form-group col-10 offset-1 col-md-6 offset-md-3">
                                <select id="languages_study" name="languages_study[]" multiple
                                        class="form-control form-control-sm chosen-select">
                                    @foreach($languages as $l)
                                        <option value="{{ $l->id }}"
                                                @if($user->studying()->where('languages.id', $l->id)->first()) selected @endif>
                                            {{ $l->name_sk }}</option>
                                    @endforeach
                                </select>
                                <br><small class="text-muted">Nenechajte toto políčko prázdne.</small>
                            </div>
                        </div>
                        <div class="row" id="languages_teach_row" style="display: none;">
                            <div class="col-12 col-md-4 offset-md-4 text-center">
                                <label for="languages_teach" class="col-form-label">
                                    Vyučuje jazyky:
                                </label>
                            </div>
                            <div class="form-group col-10 offset-1 col-md-6 offset-md-3">
                                <select id="languages_teach" name="languages_teach[]" multiple
                                        class="form-control form-control-sm chosen-select">
                                    @foreach($languages as $l)
                                        <option value="{{ $l->id }}"
                                                @if($user->teaching()->where('languages.id', $l->id)->first()) selected @endif>
                                             {{ $l->name_sk }}</option>
                                    @endforeach
                                </select>
                                <br><small class="text-muted">Nenechajte toto políčko prázdne.</small>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-4 offset-md-4 text-center">
                                <p class="text-muted">Zmena hesla. Nechjate nevyplnené ak nechcete menit heslo.</p>
                            </div>
                            <div class="form-group col-6 offset-3 col-md-6 offset-md-0 text-left text-md-right">
                                <label for="password">@lang("general.Password")</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control form-control-sm align-self-end">
                            </div>
                            <div class="form-group col-6 offset-3 col-md-6 offset-md-0">
                                <label for="password_confirm">@lang("general.Password_confirm")
                                </label>
                                <input type="password"
                                       name="password_confirm"
                                       id="password_confirm"
                                       class="form-control form-control-sm">
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-12">
                                <div class="align-self-end">
                                    <button type="submit" class="btn btn-block btn-gradient-success"><i
                                            class="fa fa-check"></i> @lang('general.Save')</button>
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
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">

@stop

@section('page_scripts')
    <script src="{{ asset('vendors/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>

    <script>

        $(document).ready(function () {

            $("#language").chosen({
                width: "10%"
            });
            $("#languages_study").chosen({
                width: "100%"
            });
            $("#languages_teach").chosen({
                width: "100%"
            });


            $("#roles").chosen({
                width: "100%"
            });

            language_select();

            $('#roles').on('change click input load ready', function () {
                language_select();
            });

            // validate signup form on keyup and submit
            $("#form_user_edit").validate({
                rules: {
                },
                messages: {
                },
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger');
                    $(element).addClass('form-control-danger');
                }
            });
        });

        function language_select() {
            let val = $('#roles').val();

            if ($.inArray("1", val) >= 0) {
                //alert("admin")
            }
            if ($.inArray("2", val) >= 0) {
                $("#languages_teach_row").show(function () {
                    $(this).animate(500);
                });
            } else {
                $("#languages_teach_row").hide(function () {
                    $(this).animate(500);
                });
            }

            if ($.inArray("3", val) >= 0) {
                $("#languages_study_row").show(function () {
                    $(this).animate(500);
                });
            } else {
                $("#languages_study_row").hide(function () {
                    $(this).animate(500);
                });
            }

            if ($.inArray("5", val) >= 0) {
                //alert("developer")
            }

        }

    </script>
@stop
