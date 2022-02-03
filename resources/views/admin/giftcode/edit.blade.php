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
                    {{ $gc->code }} - @lang("general.editing")
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


                    <form action="{{ route("admin.gift_codes.update", $gc->id) }}" method="POST" id="giftcode_edit_form">
                        @csrf
                        @method("PUT")

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right" for="stars_i">
                                @lang("profile.stars_i") *
                            </label>
                            <div class="col-4">
                                <input type="number" min="0" max="100" step="1" required class="form-control"
                                       name="stars_i" id="stars_i" value="{{ $gc->stars_i }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right" for="stars_c">
                                @lang("profile.stars_c") *
                            </label>
                            <div class="col-4">
                                <input type="number" min="0" max="100" step="1" required class="form-control"
                                       name="stars_c" id="stars_c" value="{{ $gc->stars_c }}">
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
                                        <option value="{{ $l->id }}" @if($gc->language_id == $l->id) selected @endif>{{ $l->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label class="col-form-label col-4 text-right" for="language_id">
                                @lang("general.comment")
                            </label>
                            <div class="col-4">
                                <textarea name="comment" id="comment" rows="3" class="form-control">{{ $gc->comment }}</textarea>
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

            $("#giftcode_edit_form").validate({
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
                    $("#giftcode_edit_form").submit();
                }
            })

        })

    </script>
@stop
