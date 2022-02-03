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
                    @lang('side_menu.feedback')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang( 'feedback.my_feedbacks' )</h4>
                    <p class="card-description"></p>
                    @if( count($feedbacks) )
                        <table class="table table-striped">
                            <tbody>
                            @foreach($feedbacks as $f)
                                <tr>
                                    <td>
                                        <img src="{{ $f->teacher->profile->getProfileImage() }}">
                                        <a href="{{ route('user.profile', $f->teacher->id) }}" class="text-primary">
                                            {{ $f->teacher->name }}
                                        </a>
                                    </td>
                                    <td>{{ $f->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('feedback.show', $f->id) }}"
                                           class="text-info pull-right ml-2">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        <a href="{{ route('feedback.edit', $f->id) }}"
                                           class="text-primary pull-right ml-2">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                    @endif
                    <h4 class="card-title">@lang( 'feedback.my_feedbacks_not_feedback_yet' )</h4>
                    <div class="row">
                        @foreach($new_feedbacks as $nf)
                            <div class="col-4 stretch-card my-2 px-2">
                                <div class="card border border-info">
                                    <div class="card-body p-2 p-md-4">
                                        <div class="row">
                                            <div class="col-12 d-flex flex-wrap">
                                                <img src="{{ $nf->profile->getProfileImage() }}" class="mr-3"
                                                     style="width: 50px">
                                                <h4 class="d-inline">{{ $nf->name }}<br>
                                                    <a href="{{ route('feedback.createFeedback', $nf->id) }}"
                                                       class="text-info">@lang('dashboard.give_feedback')</a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
