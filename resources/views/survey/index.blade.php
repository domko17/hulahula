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
                    @lang('side_menu.survey')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('survey.survey_listing')
                        <a href="{{ route('survey.create') }}"
                           class="btn btn-sm btn-gradient-success pull-right">@lang('survey.new_question')</a></h4>

                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>@lang('survey.question')</th>
                            <th>@lang('survey.answer_type')</th>
                            <th>@lang('survey.answers_count')</th>
                            <th>@lang('survey.question_visible_to')</th>
                            <th style="width: 15%">@lang('general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($questions as $q)
                            <tr>
                                <td>{{ $q->question }}</td>
                                <td>{{ __('survey.type_'.$q->type) }}</td>
                                <td>{{ count($q->answers) }}</td>
                                <td>{{ $q->students ? "S":"_" }} | {{ $q->teachers ? "T":"_" }}</td>
                                <td>
                                    <a href="#!" data-item-id="{{ $q->id }}"
                                       class="btn btn-danger btn-sm listing_controls pull-right delete-alert"><i
                                            class="fa fa-times fa-fw"></i></a>
                                    {{ Form::open(['method' => 'DELETE', 'route' => ['survey.destroy', $q->id ],
                                            'id' => 'item-del-'. $q->id  ])
                                        }}
                                    {{ Form::hidden('question_id', $q->id) }}
                                    {{ Form::close() }}
                                    <a href="{{ route('survey.show', $q->id) }}" class="btn btn-inverse-info btn-sm pull-right ml-2">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
            $('.delete-alert').click(function (e) {
                var id = $(this).attr("data-item-id");
                console.log(id);
                swal({
                    title: "Prosím podvtďte akciu",
                    text: "Akcia: zmazanie otázky dotazníku.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            document.getElementById('item-del-' + id).submit();
                        }
                    });
            });
        })

    </script>
@stop
