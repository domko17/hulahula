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
                    <a href="{{ route('materials.index') }}" class="text-primary">
                        @lang("side_menu.materials")
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang("side_menu.materials") - @lang("general.creating")
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('side_menu.materials')</h4>
                    <p class="card-description"></p>

                    <form id="form_create_material" method="POST" enctype="multipart/form-data"
                          action="{{ route("materials.store") }}">
                        @csrf

                        <div class="row form-group">
                            <label for="language" class="col-form-label col-4 text-right">
                                @lang("general.language")*
                            </label>
                            <div class="col-8 col-md-4">
                                <select name="language_id" id="language" class="form-control" required>
                                    <option value="0" disabled selected>@lang("general.select_option")</option>
                                    @foreach ($languages as $l)
                                        <option value="{{ $l->id }}">{{ $l->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="type" class="col-form-label col-4 text-right">
                                @lang("general.Type")*
                            </label>
                            <div class="col-8 col-md-4">
                                <select name="type" id="type" class="form-control" required>
                                    <option value="0" disabled selected>@lang("general.select_option")</option>
                                    <option value="1">URL</option>
                                    <option value="2">YouTube Video</option>
                                    <option value="3">@lang('general.document')</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="name" class="col-form-label col-4 text-right">
                                @lang("general.title")*
                            </label>
                            <div class="col-8">
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row form-group" id="link_field" style="display: none;">
                            <label for="link" class="col-form-label col-4 text-right">
                                @lang("general.url_link")*
                            </label>
                            <div class="col-8">
                                <input type="url" name="link" id="link" class="form-control">
                            </div>
                        </div>

                        <div class="row form-group" id="upload_field_1" style="display: none;">
                            <label for="upload" class="col-form-label col-4 text-right">
                                @lang("general.document")*
                            </label>
                            <div class="col-8">
                                <input type="file" name="file_1" id="file_1" class="form-control"
                                       accept="application/*">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 offset-0 col-md-4 offset-md-4">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-success">@lang('general.create')</button>
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

            function hide_fields() {
                $("#link_field").hide(function () {
                    $(this).animate(500)
                }).find("input[type=url]").attr("required", false);

                $("#upload_field_1").hide(function () {
                    $(this).animate(500)
                }).find("input[type=file]").attr("required", false)
            };

            $("#type").change(function () {
                let val = $("#type option:selected").val();

                hide_fields();

                if (val == 1 || val == 2) {
                    $("#link_field").show(function () {
                        $(this).animate(500)
                    }).find("input[type=url]").attr("required", true);
                } else if (val == 3) {
                    $("#upload_field_1").show(function () {
                        $(this).animate(500)
                    }).find("input[type=file]").attr("required", true);
                }

            })

        });

    </script>
@stop
