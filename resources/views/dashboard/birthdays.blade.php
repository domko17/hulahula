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
                    Narodeniny
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">&#x1F382;&#x1F382;&#x1F382;V najbližších dňoch majú narodeniny títo
                        užívatelia &#x1F382;&#x1F382;&#x1F382;</h4>
                    <p class="card-description"></p>
                    <hr>
                    <div class="row">
                        @foreach($users as $u)
                            @if($u == $users[0])
                                <div class="col-12 text-center animated pulse infinite slower">
                                    <h2 class="text-primary">{{ __('general.day_'.(intval(\Carbon\Carbon::createFromFormat("Y-m-d", \Carbon\Carbon::now()->year.substr($u->birthday,4))->dayOfWeekIso))) }}
                                        , {{\Carbon\Carbon::createFromFormat("Y-m-d",$u->birthday)->format("d.m")}}
                                        .{{\Carbon\Carbon::now()->year}}</h2>
                                    <h3><a href="{{ route('user.profile', $u->id) }}"
                                           class="text-dark">{{ $u->first_name." ".$u->last_name }}</a><br>
                                        @foreach ($u->user->roles as $role)
                                            <span
                                                class="badge badge-gradient-{{ $role->name == 'teacher' ? "primary" : ($role->name == 'student' ? 'success' : ($role->name == 'admin' ? 'info' : ($role->name == 'developer' ? "danger" : "secondary")))  }}">
                                        {{ $role->display_name }}
                                    </span>
                                        @endforeach</h3>
                                    <div class="text-center">
                                        <img src="{{ $u->getProfileImage() }}"
                                             style="max-width: 250px; border:1.5em solid #ffd261 !important"
                                             class="rounded-circle">
                                    </div>
                                    <hr>
                                </div>
                            @else
                                <div class="col-12 col-md-6 col-lg-4 text-center">
                                    <h3>{{ __('general.day_'.(intval(\Carbon\Carbon::createFromFormat("Y-m-d", \Carbon\Carbon::now()->year.substr($u->birthday,4))->dayOfWeekIso))) }}
                                        , {{\Carbon\Carbon::createFromFormat("Y-m-d",$u->birthday)->format("d.m")}}
                                        .{{\Carbon\Carbon::now()->year}}</h3>
                                    <h5><a href="{{ route('user.profile', $u->id) }}"
                                           class="text-dark">{{ $u->first_name." ".$u->last_name }}</a><br>
                                        @foreach ($u->user->roles as $role)
                                            <span
                                                class="badge badge-gradient-{{ $role->name == 'teacher' ? "primary" : ($role->name == 'student' ? 'success' : ($role->name == 'admin' ? 'info' : ($role->name == 'developer' ? "danger" : "secondary")))  }}">
                                        {{ $role->display_name }}
                                    </span>
                                        @endforeach</h5>
                                    <div class="text-center">
                                        <img src="{{ $u->getProfileImage() }}" style="max-width: 100px;"
                                             class="rounded-circle">
                                    </div>
                                </div>
                            @endif
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
