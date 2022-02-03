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
                    @lang('dashboard.feedback')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-3">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>@lang('feedback.lecture_feedback'):<br>
                        <a href="{{ route('user.profile', $model->teacher_id) }}" class="text-primary">
                            {{ $model->teacher->name }}
                        </a></h3>
                </div>
                <div class="col-12 text-center">
                    <img alt src="{{ $model->teacher->profile->getProfileImage() }}" class="rounded-circle"
                         style="max-width: 150px; width: 100%">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-9">
            <div class="container">
                <form method="POST" action="{{ route('feedback.update', $model->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row questions">
                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_1')</p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <input type="hidden" name="feedback_answer_1" value="{{ $data->feedback_answer_1 }}">
                                <h5 class="d-none d-md-inline">@lang('feedback.not_at_all')</h5>
                                <i class="fa fa-star rate_star" data-value="0"></i>
                                <i class="fa fa-star rate_star" data-value="1"></i>
                                <i class="fa fa-star rate_star" data-value="2"></i>
                                <i class="fa fa-star rate_star" data-value="3"></i>
                                <i class="fa fa-star rate_star" data-value="4"></i>
                                <i class="fa fa-star rate_star" data-value="5"></i>
                                <i class="fa fa-star rate_star" data-value="6"></i>
                                <i class="fa fa-star rate_star" data-value="7"></i>
                                <i class="fa fa-star rate_star" data-value="8"></i>
                                <i class="fa fa-star rate_star" data-value="9"></i>
                                <h5 class="d-none d-md-inline">@lang('feedback.yes_completely')</h5>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_2') </p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <input type="hidden" name="feedback_answer_2" value="{{ $data->feedback_answer_2 }}">
                                <h5 class="d-none d-md-inline">@lang('feedback.not_at_all')</h5>
                                <i class="fa fa-star rate_star" data-value="0"></i>
                                <i class="fa fa-star rate_star" data-value="1"></i>
                                <i class="fa fa-star rate_star" data-value="2"></i>
                                <i class="fa fa-star rate_star" data-value="3"></i>
                                <i class="fa fa-star rate_star" data-value="4"></i>
                                <i class="fa fa-star rate_star" data-value="5"></i>
                                <i class="fa fa-star rate_star" data-value="6"></i>
                                <i class="fa fa-star rate_star" data-value="7"></i>
                                <i class="fa fa-star rate_star" data-value="8"></i>
                                <i class="fa fa-star rate_star" data-value="9"></i>
                                <h5 class="d-none d-md-inline">@lang('feedback.yes_completely')</h5>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_3')</p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <input type="hidden" name="feedback_answer_3" value="{{ $data->feedback_answer_3 }}">
                                <h5 class="d-none d-md-inline">@lang('feedback.not_at_all')</h5>
                                <i class="fa fa-star rate_star" data-value="0"></i>
                                <i class="fa fa-star rate_star" data-value="1"></i>
                                <i class="fa fa-star rate_star" data-value="2"></i>
                                <i class="fa fa-star rate_star" data-value="3"></i>
                                <i class="fa fa-star rate_star" data-value="4"></i>
                                <i class="fa fa-star rate_star" data-value="5"></i>
                                <i class="fa fa-star rate_star" data-value="6"></i>
                                <i class="fa fa-star rate_star" data-value="7"></i>
                                <i class="fa fa-star rate_star" data-value="8"></i>
                                <i class="fa fa-star rate_star" data-value="9"></i>
                                <h5 class="d-none d-md-inline">@lang('feedback.yes_completely')</h5>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_4')</p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <input type="hidden" name="feedback_answer_4" value="{{ $data->feedback_answer_4 }}">
                                <h5 class="d-none d-md-inline">@lang('feedback.not_at_all')</h5>
                                <i class="fa fa-star rate_star" data-value="0"></i>
                                <i class="fa fa-star rate_star" data-value="1"></i>
                                <i class="fa fa-star rate_star" data-value="2"></i>
                                <i class="fa fa-star rate_star" data-value="3"></i>
                                <i class="fa fa-star rate_star" data-value="4"></i>
                                <i class="fa fa-star rate_star" data-value="5"></i>
                                <i class="fa fa-star rate_star" data-value="6"></i>
                                <i class="fa fa-star rate_star" data-value="7"></i>
                                <i class="fa fa-star rate_star" data-value="8"></i>
                                <i class="fa fa-star rate_star" data-value="9"></i>
                                <h5 class="d-none d-md-inline">@lang('feedback.yes_completely')</h5>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_5')</p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <input type="hidden" name="feedback_answer_5" value="{{ $data->feedback_answer_5 }}">
                                <h5 class="d-none d-md-inline">@lang('feedback.not_at_all')</h5>
                                <i class="fa fa-star rate_star" data-value="0"></i>
                                <i class="fa fa-star rate_star" data-value="1"></i>
                                <i class="fa fa-star rate_star" data-value="2"></i>
                                <i class="fa fa-star rate_star" data-value="3"></i>
                                <i class="fa fa-star rate_star" data-value="4"></i>
                                <i class="fa fa-star rate_star" data-value="5"></i>
                                <i class="fa fa-star rate_star" data-value="6"></i>
                                <i class="fa fa-star rate_star" data-value="7"></i>
                                <i class="fa fa-star rate_star" data-value="8"></i>
                                <i class="fa fa-star rate_star" data-value="9"></i>
                                <h5 class="d-none d-md-inline">@lang('feedback.yes_completely')</h5>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_6')</p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <input type="hidden" name="feedback_answer_6" value="{{ $data->feedback_answer_6 }}">
                                <h5 class="d-none d-md-inline">@lang('feedback.not_at_all')</h5>
                                <i class="fa fa-star rate_star" data-value="0"></i>
                                <i class="fa fa-star rate_star" data-value="1"></i>
                                <i class="fa fa-star rate_star" data-value="2"></i>
                                <i class="fa fa-star rate_star" data-value="3"></i>
                                <i class="fa fa-star rate_star" data-value="4"></i>
                                <i class="fa fa-star rate_star" data-value="5"></i>
                                <i class="fa fa-star rate_star" data-value="6"></i>
                                <i class="fa fa-star rate_star" data-value="7"></i>
                                <i class="fa fa-star rate_star" data-value="8"></i>
                                <i class="fa fa-star rate_star" data-value="9"></i>
                                <h5 class="d-none d-md-inline">@lang('feedback.yes_completely')</h5>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="mb-1 text-center text-md-left"
                               style="font-size:20px">@lang('feedback.question_7')</p>
                        </div>
                        <div class="col-sm-12 mb-2 text-center">
                            <div class="rating">
                                <textarea name="feedback_answer_7" rows="5"
                                          class="form-control">{{ $data->feedback_answer_7 }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <button type="submit"
                                    class="btn btn-gradient-success btn-block">@lang('general.Save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('page_css')

    <style>

        .rate_star {
            cursor: pointer;
            font-size: 18px;
        }

        .rate_star.hover {
            color: #7a244e !important;
        }

        .rate_star.chosen {
            color: #ffd261;
        }

        @media only screen and (max-width: 456px) {
            .rate_star {
                font-size: 18px;
            }
        }

        .questions > div > p {
            font-size: 16px !important;
        }

    </style>

@stop

@section('page_scripts')
    <script>
        $(document).ready(function () {

            $('.rate_star').each(function() {
                let parent = $(this).parent();
                let value = parent.find('input').val();
                parent.children().each(function () {
                    let tmp_value = $(this).data('value');
                    if (tmp_value <= value)
                        $(this).addClass('chosen');
                    else
                        $(this).removeClass('chosen');
                })
            })

            $('.rate_star').hover(
                function () {
                    let value = $(this).data('value');
                    let parent = $(this).parent();
                    parent.children().each(function () {
                        let tmp_value = $(this).data('value');
                        if (tmp_value <= value)
                            $(this).addClass('hover');
                        else
                            $(this).removeClass('hover');
                    })
                },
                function () {
                    let parent = $(this).parent();
                    parent.children().each(function () {
                        $(this).removeClass('hover');
                    })
                }
            )

            $(".rate_star").click(function () {
                let value = $(this).data('value');
                let parent = $(this).parent();
                parent.find("input").val(value);
                parent.children().each(function () {
                    let tmp_value = $(this).data('value');
                    if (tmp_value <= value)
                        $(this).addClass('chosen');
                    else
                        $(this).removeClass('chosen');
                })
            });

            $('.feedback_send').click(function () {

            })


        })

    </script>
@stop
