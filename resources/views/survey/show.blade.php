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
                    <a href="{{ route('survey.index') }}" class="text-primary">
                        @lang('side_menu.survey')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('side_menu.survey') - @lang('general.detail')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('survey.question')({{ $question->id }}) - @lang('general.detail')
                        <a href="{{ route('survey.index') }}"
                           class="btn btn-info btn-sm pull-right"> @lang('general.back') @lang('side_menu.survey')</a>
                    </h4>
                    <hr>
                    <h3>@lang('survey.question'): {{ $question->question }}</h3>
                    <p>@lang('survey.question_visible_to')
                        : {{ $question->students ? __('general.students') .", " : "" }}
                        {{ $question->teachers ? __('side_menu.teachers') : "" }}</p>
                    <p>@lang('survey.answer_type'): @lang('survey.type_'.$question->type)</p>
                    <hr>
                    <h4>@lang('survey.answers_count'): {{ count($answers) }}</h4>
                    <div class="row">
                        @foreach($answers as $a)
                            <div class="col-6 border border-silverish border-round-10">
                                @if($a->anonymous)
                                    <h4 class="text-muted">Anonym</h4>
                                @else
                                    <a href="{{ route('user.profile', $a->author->id) }}" class="text-primary">
                                        <h4>{{ $a->author->name }}</h4>
                                    </a>
                                @endif
                                @if($question->type == 1)
                                    <p>{{ $a->answer }}</p>
                                @else
                                    <p>-- {{ $a->answer }} --</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
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

        })

    </script>
@stop
