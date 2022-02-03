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
                    @lang('meeting.teacher_nearest_meeting') - @lang('general.detail')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><span class="display-3">@lang('meeting.meeting')</span>
                        <br>
                        <span class="text-danger">{{ \Carbon\Carbon::createFromFormat("Y-m-d",$meeting->day)->day.".".__('general.month_'.\Carbon\Carbon::createFromFormat("Y-m-d",$meeting->day)->month)." ".\Carbon\Carbon::createFromFormat("Y-m-d",$meeting->day)->year }}
                            {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$meeting->start)->format("H:i")."-".\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$meeting->end)->format("H:i") }}
                        </span></h4>


                    <p><u>Info:</u><br>{{ strlen($meeting->comment) > 0 ? $meeting->comment : "[[ ".__('messages.no_info')." ]]" }}</p>

                    <p><u>Účastníci:</u><br>
                        @foreach($meeting->members as $u)
                            @if($u->id == \Illuminate\Support\Facades\Auth::id())
                                <b>{{ $u->name }}, </b>
                            @else
                                {{ $u->name }},
                            @endif
                        @endforeach
                    </p>

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
