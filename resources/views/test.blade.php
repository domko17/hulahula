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
                    TEST PAGE
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-3">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>Hodnotenie lektora: <br>Meno Priezvisko</h4>
                </div>
                <div class="col-12 text-center">
                    <img alt src="{{ asset('images/app/Placeholders/profile_male.png') }}" class="rounded-circle"
                         style="max-width: 150px; width: 100%">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-9">
            <div class="container">
                <div class="row questions">
                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">1. Lektor bol pripravený na
                            hodinu?</p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <input type="hidden" name="feedback_answer_1" value="">
                            <h5 class="d-none d-md-inline">Vôbec nie</h5>
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
                            <h5 class="d-none d-md-inline">Áno</h5>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">2. Lektor bol dochvíľny? </p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <input type="hidden" name="feedback_answer_2" value="">
                            <h5 class="d-none d-md-inline">Vôbec nie</h5>
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
                            <h5 class="d-none d-md-inline">Áno</h5>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">3. Lektor bol príjemný?</p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <input type="hidden" name="feedback_answer_3" value="">
                            <h5 class="d-none d-md-inline">Vôbec nie</h5>
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
                            <h5 class="d-none d-md-inline">Áno</h5>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">4. Lektor bol vnímavý k Vašim
                            požiadavkám a individualite?</p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <input type="hidden" name="feedback_answer_4" value="">
                            <h5 class="d-none d-md-inline">Vôbec nie</h5>
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
                            <h5 class="d-none d-md-inline">Áno</h5>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">5. Lektor Vás dostatočne
                            motivuje, prípadne demonštruje Váš pokrok?</p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <input type="hidden" name="feedback_answer_5" value="">
                            <h5 class="d-none d-md-inline">Vôbec nie</h5>
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
                            <h5 class="d-none d-md-inline">Áno</h5>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">6. Odporučili by ste lektora
                            ďalším študentom?</p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <input type="hidden" name="feedback_answer_6" value="">
                            <h5 class="d-none d-md-inline">Vôbec nie</h5>
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
                            <h5 class="d-none d-md-inline">Áno</h5>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <p class="mb-1 text-center text-md-left" style="font-size:20px">7. Máte určité výhrady k výučbe,
                            prípadne požiadavky alebo postrehy ako
                            zlepšiť
                            kvalitu
                            našich služieb?</p>
                    </div>
                    <div class="col-sm-12 mb-2 text-center">
                        <div class="rating">
                            <textarea name="feedback_answer_7" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12 my-2 col-md-6 offset-md-3">
                        <button type="button" class="btn btn-block btn-gradient-success">Odoslať hodnotenie</button>
                    </div>
                </div>
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

        .questions > div >p {
            font-size: 16px!important;
        }

    </style>

@stop

@section('page_scripts')
    <script>
        $(document).ready(function () {

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
            })

        })

    </script>
@stop
