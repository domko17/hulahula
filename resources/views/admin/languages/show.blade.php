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
                    <a href="{{ route('admin.languages.index') }}" class="text-primary">
                        @lang('side_menu.Languages')
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{$language->name_en}} - @lang('general.detail')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-4 p-md-4">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="border-bottom text-center">
                                <i class="flag-icon {{ $language->icon }}" style="font-size: 4em"></i>
                                <hr>
                                <h5>{{ $language->name_native }} | {{ $language->name_en }}
                                    | {{ $language->name_sk }}</h5>
                            </div>
                            <div class="border-bottom py-4">
                                <p><b>@lang('language.nearest_i_classes') <i class="fa fa-star text-golden"></i> </b>
                                </p>
                                @if(Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher') or (Auth::user()->hasRole('student') and Auth::user()->studying()->where('language_id', $language->id)->first()))
                                    @if(count($nearest_i) > 0)
                                        @foreach ($nearest_i as $c)
                                            <div class="row">
                                                <div class="col-12 py-1">
                                                    <a href="{{ route('lectures.show', $c->id) }}"
                                                       class="btn btn-inverse-primary btn-sm" style="width: 100%">
                                                        {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $c->class_date." ".$c->hour->class_start)->format("d.M \(H:i\)") }}
                                                        | {{ $c->hour->teacher->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-danger">@lang('language.no_near_i_classes')</p>
                                    @endif
                                @else
                                    <p class="text-danger">@lang('language.must_study_to_see')</p>
                                @endif
                            </div>
                            <div class="border-bottom py-4">
                                <p><b>@lang('language.nearest_c_classes') <i class="fa fa-star text-primary"></i></b>
                                </p>
                                @if(Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher') or (Auth::user()->hasRole('student') and Auth::user()->studying()->where('language_id', $language->id)->first()))

                                    @if(count($nearest_c) > 0)
                                        @foreach ($nearest_c as $c)
                                            <div class="row">
                                                <div class="col-12 py-1">
                                                    <a href="{{ route('lectures.show', $c->id) }}"
                                                       class="btn btn-inverse-primary btn-sm" style="width: 100%">
                                                        {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $c->class_date." ".$c->hour->class_start)->format("d.M \(H:i\)") }}
                                                        | {{ $c->hour->teacher ? $c->hour->teacher->name : "---" }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-danger">@lang('language.no_near_c_classes')</p>
                                    @endif
                                @else
                                    <p class="text-danger">@lang('language.must_study_to_see')</p>
                                @endif

                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="mt-4 py-2">
                                <ul class="nav nav-pills nav-pills-primary" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link show active" id="pills-contact-tab" data-toggle="pill"
                                           href="#pills-about" role="tab" aria-controls="pills-contact"
                                           aria-selected="true">@lang('language.about_language')</a>
                                    </li>
                                    @if(Auth::user()->hasRole('admin') or Auth::user()->hasRole('teacher') or (Auth::user()->hasRole('student') and Auth::user()->studying()->where('language_id', $language->id)->first()))
                                        <li class="nav-item">
                                            <a class="nav-link show" id="pills-home-tab" data-toggle="pill"
                                               href="#pills-teachers" role="tab" aria-controls="pills-home"
                                               aria-selected="false">@lang('general.lectors')</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade" id="pills-teachers" role="tabpanel"
                                     aria-labelledby="pills-home-tab">
                                    @foreach($lectors as $lector)
                                        <div class="media">

                                            <img class="mr-3 w-25 rounded"
                                                 src="{{ $lector->profile->getProfileImage() }}"
                                                 alt="lector profile image">

                                            <div class="media-body">
                                                <h4 class="mt-0">
                                                    <a href="{{ route('user.profile', $lector->id) }}"
                                                       class="text-primary">
                                                        {{ $lector->profile->title_before }}
                                                        {{ $lector->profile->first_name }}
                                                        {{ $lector->profile->last_name }}
                                                        {{ $lector->profile->title_after }}
                                                    </a>
                                                    @if(Auth::id() != $lector->id)
                                                        <button type="button" data-toggle="modal"
                                                                data-target="#sendMessageModal"
                                                                data-user-id="{{ $lector->id }}"
                                                                class="btn btn-sm btn-outline-primary pull-right send_me_msg_btn">
                                                            <i class="fa fa-envelope"></i> @lang('profile.send_me_message')
                                                        </button>
                                                    @endif
                                                </h4>
                                                <p>
                                                    <b>@lang('general.Email'):</b> {{ $lector->email }} <br>
                                                    <b>@lang('general.Phone'):</b> {{ $lector->profile->phone }}<br>
                                                    <b>@lang('profile.bio'):</b>{!! $lector->profile->bio !!}
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                <div class="tab-pane fade active show" id="pills-about" role="tabpanel"
                                     aria-labelledby="pills-contact-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            {!! $language->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('components.message_me_modal')
@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {

            $(".send_me_msg_btn").click(function () {
                let id = $(this).attr("data-user-id");
                $("#to_who").val(id);
            });

            $('#send_msg_btn').click(function () {
                let msg = $("#message_to_send").val();
                let to_who = $("#to_who").val();

                let my_id = {{ Auth::id() }};


                if (msg == '') {
                    $.toast({
                        heading: 'Error',
                        text: 'You can\'t send an empty message',
                        position: 'bottom-right',
                        icon: 'error',
                        stack: false,
                        loaderBg: '#ed3939',
                        bgColor: '#f0aaaa',
                        textColor: 'black'
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "send_message",
                        user_id: my_id,
                        reciever_id: to_who,
                        message: msg
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        $('#sendMessageModal').modal('hide');
                        $.toast({
                            heading: 'Success',
                            text: 'Message sent!',
                            position: 'bottom-right',
                            icon: 'success',
                            stack: false,
                            loaderBg: '#0eb543',
                            bgColor: '#b5ffaa',
                            textColor: 'black'
                        })

                    },
                    error: function (response) {
                        $.toast({
                            heading: 'Error',
                            text: 'Error',
                            position: 'bottom-right',
                            icon: 'error',
                            stack: false,
                            loaderBg: '#ed3939',
                            bgColor: '#f0aaaa',
                            textColor: 'black'
                        })
                    }
                })

            })
        })

    </script>
@stop
