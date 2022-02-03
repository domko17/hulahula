@extends('layouts.app')

@section('title')
    Dashboard
@stop

@section('content')
    @if( count($banners) > 0 )
        @include('dashboard.components.other.banners')
    @endif

    @if( $guest )
        @include('dashboard.components.guest_layout')
    @else
        @include('dashboard.components.main_layout')
    @endif
@stop

@section('page_css')
    <style>
        .quick_survey_box {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: none;
            z-index: 20;
            width: 300px;
            max-width: 300px;
            box-shadow: #222222 5px 5px 5px;
            border-width: 2px !important;
        }

        .quick_survey_box:before {
            content: "";
            width: 0px;
            height: 0px;
            position: absolute;
            border-left: 10px solid transparent;
            border-right: 10px solid #a02f67;
            border-top: 10px solid #a02f67;
            border-bottom: 10px solid transparent;
            box-shadow: #222222 5px -1px 5px -2px;
            background-color: transparent;
            right: 20px;
            bottom: -23px;
        }

        .quick_survey_box:after {
            content: "";
            width: 0px;
            height: 0px;
            position: absolute;
            border-left: 10px solid transparent;
            border-right: 10px solid #fff;
            border-top: 10px solid #fff;
            border-bottom: 10px solid transparent;
            background-color: transparent;
            right: 24px;
            bottom: -13px;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('vendors/zambuto_calendar/zambuto_calendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/zambuto_calendar/zambuto_custom_style.css') }}">

@stop

@section('page_scripts')
    <script src="{{ asset('vendors/zambuto_calendar/zambuto_calendar.js') }}"></script>
    <script src="{{ asset('vendors/zambuto_calendar/zambuto_custom_script.js') }}"></script>

    @include("components.scripts.delete_alert")

    @if( count($banners) > 0 )
        @include("dashboard.components.other.banners_script")
    @endif

    @if( $quick_survey )
        @include("dashboard.components.other.survey_script")
    @endif

    @if( $student )
        @include("dashboard.components.student.student_script")
    @endif

    @if( $teacher )
        @include("dashboard.components.teacher.teacher_script")
    @endif

    @include("dashboard.components.other.other_scripts")
@stop
