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
                    <a href="{{ route('admin.banners.index') }}" class="text-primary">
                        @lang('side_menu.banners')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('side_menu.banners')({{ $banner->id }}) - @lang('general.editing')
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

                    <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="title" class="col-form-label">@lang('banners.title') (Max: 50)</label>
                                <input type="text" name="title" id="title" maxlength="50"
                                       class="form-control border border-primary"
                                       value="{{ $banner->title }}" required>
                            </div>
                            <div class="col-8 form-group">
                                <label for="description" class="col-form-label">@lang('banners.description') (Max:
                                    150)</label>
                                <input type="text" name="description" id="description" maxlength="150"
                                       class="form-control border border-primary"
                                       value="{{ $banner->description }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="type" class="col-form-label">@lang('banners.type')</label>
                                <select name="type" id="type"
                                        class="form-control border border-primary border-0" required>
                                    <option value="1"
                                            @if($banner->type == 1) selected @endif>@lang('banners.type_1')</option>
                                    <option value="2"
                                            @if($banner->type == 2) selected @endif>@lang('banners.type_2')</option>
                                    <option value="3"
                                            @if($banner->type == 3) selected @endif>@lang('banners.type_3')</option>
                                </select>
                            </div>
                            <div class="col-8 form-group">
                                <div id="type_1" style="display: none;">
                                    <label for="bg_color" class="col-form-label">@lang('banners.bg_color')</label>
                                    <select name="bg_color" id="bg_color"
                                            class="form-control border border-primary border-0" required>
                                        <option value="1"
                                                @if($banner->bckg_colour == 1) selected @endif>@lang('banners.bg_purple')</option>
                                        <option value="2"
                                                @if($banner->bckg_colour == 2) selected @endif>@lang('banners.bg_blue')</option>
                                        <option value="3"
                                                @if($banner->bckg_colour == 3) selected @endif>@lang('banners.bg_yellow')</option>
                                        <option value="4"
                                                @if($banner->bckg_colour == 4) selected @endif>@lang('banners.bg_red')</option>
                                        <option value="5"
                                                @if($banner->bckg_colour == 5) selected @endif>@lang('banners.bg_green')</option>
                                        <option value="6"
                                                @if($banner->bckg_colour == 6) selected @endif>@lang('banners.bg_silver')</option>
                                        <option value="7"
                                                @if($banner->bckg_colour == 7) selected @endif>@lang('banners.bg_black')</option>
                                    </select>
                                </div>
                                <div id="type_2" style="display: none;">
                                    <label for="img" class="col-form-label">@lang('general.image') (Pref. dim.:
                                        W:900px|H:250px) @if($banner->image)
                                           @lang('banners.has_image_hint') @endif</label>
                                    @if(!$banner->image) <input type="hidden" id="has_image" value="1"> @endif
                                    <input type="file" name="img" id="img" accept="image/jpeg,image/png" maxlength="100"
                                           class="form-control border border-primary"
                                           @if(!$banner->image) required @endif>
                                </div>
                                <div id="type_3" style="display: none;">
                                    <label for="url" class="col-form-label">@lang('banners.video_url') (YouTube)</label>
                                    <input type="url" name="url" id="url"
                                           class="form-control border border-primary"
                                           value="{{ $banner->url }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="card border border-silverish" id="banner_preview">
                            <div class="card-body py-3">
                                <h5 class="card-title">@lang('banners.preview')</h5>
                                <div class="owl-carousel owl-theme full-width">
                                    <div class="item">
                                        <div class="card text-dark">
                                            <div id="prev_type_1" class="bg-gradient-primary text-white p-5"
                                                 style="width: 100%; height:250px">
                                                <div style="position: relative; top: 35%;">
                                                    <h3 class="text-center ex_title">Third Slide
                                                        Label</h3>
                                                    <h6 class="card-text mb-4 font-weight-normal text-center ex_desc"
                                                    >Nulla
                                                        vitae elit libero, a pharetra augue mollis
                                                        interdum.</h6>
                                                </div>
                                            </div>
                                            <div id="prev_type_2" class="bg-gradient-primary text-white p-5"
                                                 style="width: 100%; height:250px;
                                                     display: none;
                                                     background-size: cover;
                                                     background-image: url({{ $banner->image ? asset('images/banners/'.$banner->image):asset('images/app/AuthBackgrounds/auth_bckg_1.jpg')}})">
                                                <div
                                                    style="position: relative; top: 70%">
                                                    <h3 class="text-center ex_title">Third Slide
                                                        Label</h3>
                                                    <h6 class="card-text mb-4 font-weight-normal text-center ex_desc"
                                                    >Nulla
                                                        vitae elit libero, a pharetra augue mollis
                                                        interdum.</h6>
                                                </div>
                                            </div>
                                            <div id="prev_type_3" class="bg-gradient-primary text-white row"
                                                 style="width: 100%; height:250px; display: none;">

                                                <iframe height="250" style="width: 50%;"
                                                        src="{{ $banner->url ? $banner->url : "https://www.youtube.com/embed/9bZkp7q19f0" }}"
                                                        frameborder="0" id="prev_yt_iframe" class="col-6"
                                                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                                <div class="col-6">
                                                    <div style="position: relative; top: 35%;">
                                                        <h3 class="text-center ex_title">Third Slide
                                                            Label</h3>
                                                        <h6 class="card-text mb-4 font-weight-normal text-center ex_desc"
                                                        >Nulla
                                                            vitae elit libero, a pharetra augue mollis
                                                            interdum.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="ext_url" class="col-form-label">@lang('banners.ext_url')</label>
                                <input type="url" name="ext_url" id="ext_url"
                                       class="form-control border border-primary"
                                       value="{{ $banner->ext_link }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h4>@lang('banners.visible_to')</h4>
                                <hr class="my-1">
                            </div>
                            <div class="col-4 form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" name="visible_all" id="visible_all" value="1"
                                               class="form-check-input visible_type_radio"
                                               @if($visibility->type == 1) checked @endif> @lang('banners.visible_to_all')
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" name="visible_all" id="visible_custom_select" value="2"
                                               class="form-check-input visible_type_radio"
                                               @if($visibility->type == 2) checked @endif> @lang('banners.visible_custom_select')
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" name="visible_all" id="visible_custom_users" value="3"
                                               class="form-check-input visible_type_radio"
                                               @if($visibility->type == 3) checked @endif> @lang('banners.visible_custom_user')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-8" id="visible_custom_div" style="display: none;">
                                <div class="row">
                                    <div class="col-12 my-0 form-group">
                                        <div class="row my-1">
                                            <div class="col-12 form-group mb-0">
                                                <p>@lang('general.role'): <i class="fa fa-question-circle"
                                                              data-custom-class="tooltip-primary"
                                                              data-toggle="tooltip" data-delay="500"
                                                              data-placement="top" title=""
                                                              data-original-title="@lang('banners.visibility_roles_tooltip')"></i>
                                                </p>
                                            </div>
                                            <div class="col-4 form-group mb-0">
                                                <div class="form-check my-1">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="visible_guests"
                                                               value="1"
                                                               class="form-check-input"
                                                               @if($visibility->guests) checked @endif>@lang('general.role_guest')
                                                        <i
                                                            class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-4 form-group mb-0">
                                                <div class="form-check my-1">
                                                    <label class="form-check-label">
                                                        <input type="checkbox"
                                                               name="visible_students"
                                                               value="1"
                                                               class="form-check-input"
                                                               @if($visibility->students) checked @endif>@lang('general.students')
                                                        <i
                                                            class="input-helper"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-4 form-group mb-0">
                                                <div class="form-check my-1">
                                                    <label class="form-check-label">
                                                        <input type="checkbox"
                                                               name="visible_teachers"
                                                               value="1"
                                                               class="form-check-input"
                                                               @if($visibility->teachers) checked @endif>@lang('general.Teacher')
                                                        <i
                                                            class="input-helper"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mx-1">
                                    </div>
                                    <div class="col-12 my-0">
                                        <div class="row my-1">
                                            <div class="col-12 form-group mb-0">
                                                <p>@lang('general.language'): <i class="fa fa-question-circle"
                                                                data-custom-class="tooltip-primary"
                                                                data-toggle="tooltip" data-delay="500"
                                                                data-placement="top" title=""
                                                                data-original-title="@lang('banners.visibility_languages_tooltip')"></i>
                                                </p>
                                            </div>
                                            @foreach(\App\Models\Language::all() as $l)
                                                <div class="col-4 form-group mb-0">
                                                    <div class="form-check my-1">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="visible_lang[]"
                                                                   value="{{$l->id}}" class="form-check-input"
                                                                   @if($visibility->languages and in_array($l->id, $visibility->languages)) checked @endif>{{$l->name_sk}}
                                                            <i
                                                                class="input-helper"></i></label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8" id="visible_users_div" style="display: none;">
                                <div class="row form-group">
                                    <div class="col-8">
                                        <label for="visible_users" class="col-form-label">@lang('general.users')</label>
                                        <select class="form-control border-0 border border-primary"
                                                name="visible_users[]"
                                                id="visible_users" multiple>
                                            @foreach(\App\User::all() as $u)
                                                <option value="{{ $u->id }}"
                                                        @if($visibility->users and in_array($u->id, $visibility->users)) selected @endif>{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group text-right">
                                <button type="submit" class="btn btn-gradient-success">@lang('general.Save')</button>
                                <a href="{{ route('admin.banners.index') }}"
                                   class="btn btn-gradient-secondary">@lang('general.back')</a>
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

        function toggleVisibilityDivs(type) {
            if (type == 1) {
                $("#visible_custom_div").hide(function () {
                    $(this).animate()
                });
                $("#visible_users_div").hide(function () {
                    $(this).animate()
                });
            } else if (type == 2) {
                $("#visible_custom_div").show(function () {
                    $(this).animate()
                });
                $("#visible_users_div").hide(function () {
                    $(this).animate()
                });
            } else if (type == 3) {
                $("#visible_custom_div").hide(function () {
                    $(this).animate()
                });
                $("#visible_users_div").show(function () {
                    $(this).animate()
                });
            }
        }

        let prev_1 = $("#prev_type_1");
        let prev_2 = $("#prev_type_2");
        let prev_3 = $("#prev_type_3");

        function toggleElems() {
            let type = $('#type option:selected').val();
            console.log(type);

            let has_image = $("#has_image");

            let div_t1 = $("#type_1");
            let div_t2 = $("#type_2");
            let div_t3 = $("#type_3");

            if (type == 1) {
                div_t1.show(function () {
                    $(this).animate()
                });
                div_t1.find('input').attr('required', true);
                div_t2.hide(function () {
                    $(this).animate()
                });
                div_t2.find('input').attr('required', false);
                div_t3.hide(function () {
                    $(this).animate()
                });
                div_t3.find('input').attr('required', false);
                prev_1.show();
                prev_2.hide();
                prev_3.hide();
            } else if (type == 2) {
                div_t1.hide(function () {
                    $(this).animate()
                });
                div_t1.find('input').attr('required', false);
                div_t2.show(function () {
                    $(this).animate()
                });
                if (!has_image) div_t2.find('input').attr('required', true);
                else div_t2.find('input').attr('required', false);
                div_t3.hide(function () {
                    $(this).animate()
                });
                div_t3.find('input').attr('required', false);
                prev_1.hide();
                prev_2.show();
                prev_3.hide();
            } else if (type == 3) {
                div_t1.hide(function () {
                    $(this).animate()
                });
                div_t1.find('input').attr('required', false);
                div_t2.hide(function () {
                    $(this).animate()
                });
                div_t2.find('input').attr('required', false);
                div_t3.show(function () {
                    $(this).animate()
                });
                div_t3.find('input').attr('required', true);
                prev_1.hide();
                prev_2.hide();
                prev_3.show();
            }
        }

        function setPreviewBgColor(val) {
            let prev = $("#prev_type_1");
            if (val == 1) {
                prev.addClass("bg-gradient-primary");
            } else if (val == 2) {
                prev.addClass("bg-gradient-info");
            } else if (val == 3) {
                prev.addClass("bg-gradient-warning");
            } else if (val == 4) {
                prev.addClass("bg-gradient-danger");
            } else if (val == 5) {
                prev.addClass("bg-gradient-success");
            } else if (val == 6) {
                prev.addClass("bg-gradient-secondary");
            } else if (val == 7) {
                prev.addClass("bg-gradient-dark");
            }
        }

        function setPreview() {

            $(".ex_title").each(function () {
                $(this).html($("#title").val())
            });
            $(".ex_desc").each(function () {
                $(this).html($("#description").val())
            });
            setPreviewBgColor($("#bg_color option:selected").val())
        }

        $(document).ready(function () {

            $("#visible_users").chosen({
                width: "100%"
            });

            $.fn.andSelf = function () {
                return this.addBack.apply(this, arguments);
            };
            $('.full-width').owlCarousel({
                loop: false,
                margin: 10,
                items: 1,
                nav: false,
                autoplay: false,
                autoplayTimeout: 5500,
                navText: ["<i class='mdi mdi-chevron-left'></i>", "<i class='mdi mdi-chevron-right'></i>"]
            });

            toggleElems();
            toggleVisibilityDivs('{{ $visibility->type }}');
            setPreview();

            $("#type").change(function () {
                toggleElems()
            });

            $("#title").on("input click change", function () {
                let val = $(this).val();

                $(".ex_title").each(function () {
                    $(this).html(val)
                });
            });
            $("#description").on("input click change", function () {
                let val = $(this).val();

                $(".ex_desc").each(function () {
                    $(this).html(val)
                });
            });
            $("#bg_color").on("change", function () {
                let val = $("#bg_color option:selected").val();
                let prev = $("#prev_type_1");
                let all_bg = "bg-gradient-success bg-gradient-primary " +
                    "bg-gradient-info bg-gradient-secondary bg-gradient-danger " +
                    "bg-gradient-warning bg-gradient-dark";
                prev.removeClass(all_bg);

                setPreviewBgColor(val);
            });

            $(".visible_type_radio").change(function () {
                let val = $(this).val();
                console.log(val);

                toggleVisibilityDivs(val);
            });

            $("#url").on("change input ready", function () {
                let base_yt = "https://www.youtube.com/embed/";
                let val = $(this).val();

                console.log(val);

                let tmp = val.split("/");

                let res = "";
                if (tmp[tmp.length - 1].startsWith("watch?v=")) {
                    res = tmp[tmp.length - 1].split("=")[1];
                } else {
                    res = tmp[tmp.length - 1];
                }
                base_yt = base_yt + res;
                $("#prev_yt_iframe").attr('src', base_yt);
            });

        })

    </script>
@stop
