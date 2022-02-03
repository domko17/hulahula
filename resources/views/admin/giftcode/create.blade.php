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
                    <a href="{{ route("admin.gift_codes.index") }}">@lang('side_menu.gift_codes')</a>
                </li>
                <li class="breadcrumb-item active">
                    @lang("general.creating")
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"></h4>
                    <p class="card-description"></p>


                    <form action="{{ route("admin.gift_codes.store") }}" method="POST" id="giftcode_create_form">
                        @csrf


                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right" for="package_id">
                                {{ __('order.package') }} *
                            </label>
                            <div class="col-4">
                                <select name="package_id" id="package_id" class="form-control">
                                    @foreach(\App\Models\Helper::PACKAGES as $ix => $p)
                                        <option value="{{ $ix }}">{{ $p['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right" for="package_class_count">
                                Počet hodín v balíku *
                            </label>
                            <div class="col-4">
                                <input type="number" min="0" max="100" step="1" required class="form-control"
                                       name="package_class_count" id="package_class_count" value="0">
                            </div>
                            <div class="col-8 offset-4" id="hint" style="display: none">
                                <small class="text-danger">@lang('order.order_form_help')</small>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <hr>
                            </div>
                            <label class="col-form-label col-4 text-right" for="language_id">
                                @lang("general.language")
                            </label>
                            <div class="col-4">
                                <select class="form-control"
                                        name="language_id" id="language_id">
                                    <option value="0" selected>---</option>
                                    @foreach ($languages as $l)
                                        <option value="{{ $l->id }}">{{ $l->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right" for="language_id">
                                @lang("general.comment")
                            </label>
                            <div class="col-4">
                                <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-4"></div>
                            <div class="col-6">
                                <button type="button" class="btn btn-gradient-success" id="send_form_btn">
                                    @lang("general.create")
                                </button>
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

            $("#stars_c").change(function () {
                $("#hint").hide(function () {
                    $(this).animate();
                })
            });

            $("#stars_i").change(function () {
                $("#hint").hide(function () {
                    $(this).animate();
                })
            });

            $("#giftcode_create_form").validate({
                rules: {},
                messages: {},
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger')
                    $(element).addClass('form-control-danger')
                }
            });

            $("#send_form_btn").click(function () {
                let v1 = parseInt($("#stars_i").val());
                let v2 = parseInt($("#stars_c").val());

                if (v1 === 0 && v2 === 0) {
                    $("#hint").show(function () {
                        $(this).animate();
                    })
                } else {
                    $("#giftcode_create_form").submit();
                }
            })

        })

    </script>
@stop
