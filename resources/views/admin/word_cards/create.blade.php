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
                    <a href="{{ route('admin.word_cards.index') }}" class="text-primary">
                        @lang('side_menu.word_cards')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    {{ $language->name_en }} - @lang('general.creating')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{ route("admin.word_cards.store") }}" id="form_create_card">
                        @csrf

                        <input type="hidden" name="language_id" value="{{ $language->id }}">
                        <input type="hidden" name="image_base64" id="_image" value="">

                        <div class="row">
                            <div class="col-4 offset-4" style="min-height: 250px">
                                <img class="img-thumbnail rounded-circle w-100" id="profile_image_crop"
                                     src="{{ asset("images/app/Placeholders/profile_male.png") }}">
                            </div>
                        </div>
                        <br><br><br>
                        <div class="row form-group">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="file" id="imageUp" name="img" class="file-upload-default"
                                           accept="image/*">
                                    <div class="input-group col-4 offset-4">
                                        <input type="text" class="form-control file-upload-info"
                                               placeholder="Upload Image" style=" display: none;">
                                        <button class="file-upload-browse btn btn-inverse-success pull-right btn-block"
                                                type="button">@lang('general.upload')</button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right">@lang('language.word_card_word_sk')</label>
                            <div class="col-4">
                                <input type="text" name="word_sk" id="word_sk" class="form-control" required>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right">@lang('language.word_card_word_native'): <i
                                    class="flag-icon {{ $language->icon }}"></i> {{ $language->name_en }}</label>
                            <div class="col-4">
                                <input type="text" name="word_native" id="word_native" class="form-control" required>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right">@lang('language.word_card_difficulty'): </label>
                            <div class="col-4">
                                <select name="word_diff" id="word_diff" class="form-control" required>
                                    <option value="0" disabled selected>@lang('general.select_option')</option>
                                    <option value="1">A1</option>
                                    <option value="2">A2</option>
                                    <option value="3">B1</option>
                                    <option value="4">B2</option>
                                    <option value="5">C1</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-4 offset-4">
                                <button type="button" class="btn btn-gradient-success btn-block" id="save_btn">
                                    @lang('general.Save')
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@stop

@section("page_css")

    <link rel="stylesheet" href="{{ asset("css/croppie.css") }}">

    <style>
        input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }
    </style>

@stop

@section('page_scripts')

    <script src="{{ asset("js/file-upload.js") }}"></script>
    <script src="{{ asset("js/croppie.js") }}"></script>
    <script>

        $(document).ready(function () {
            let crop = $('#profile_image_crop').croppie();

            function readFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    console.log("§d.as§dasdas");
                    reader.onload = function (e) {
                        $('#profile_image_crop').croppie('bind', {
                            url: e.target.result
                        });
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#form_create_card").validate({
                rules: {
                    word_sk: "required",
                    word_native: "required",
                    word_diff: "required",
                },
                messages:{
                    word_sk: {
                        required: "Toto pole je povinné",
                    },
                    word_native: {
                        required: "Toto pole je povinné",
                    },
                    word_diff: {
                        required: "Toto pole je povinné",
                    },
                },
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger')
                    $(element).addClass('form-control-danger')
                }
            })

            $('#imageUp').on('change', function () {
                readFile(this);
            });

            $("#save_btn").click(function () {
                crop.croppie('result', {
                    type: "base64",
                }).then(function (resp) {
                    $("#_image").val(resp);

                    $("#form_create_card").submit();
                });
            })
        })

    </script>
@stop
