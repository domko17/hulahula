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
                    @lang('side_menu.contact_us')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">

        <div class="col-12 col-sm-6 col-md-4 order-2">
            <h3>@lang('dashboard.contact_our_admins')</h3>
            <hr>
            <div class="row">
                @foreach($admins as $admin)
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-12 p-4 text-center">
                                        <img src="{{ $admin->profile->getProfileImage() }}"
                                             style="width: 100%; max-width: 250px; border-radius: 50%">
                                    </div>
                                    <div class="col-12">
                                        <h4 class="text-center mb-0">
                                            <a href="{{ route("user.profile", $admin->id) }}" class="text-primary">
                                                {{ $admin->name }}
                                                @if($admin->id == 1) <small class="text-danger">Developer</small> @endif
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                                <br>
                                <p class="my-1 text-center"><b><i class="fa fa-phone"></i>
                                        :</b>
                                    @if($admin->profile->phone)
                                        <a class="text-primary"
                                           href="tel: {{ $admin->profile->phone }}"> {{ $admin->profile->phone }}</a>
                                    @else
                                        ---
                                    @endif
                                </p>
                                @if(Auth::id() != $admin->id)
                                    <button class="btn btn-block btn-inverse-primary send_me_msg_btn px-1" type="button"
                                            data-toggle="modal"
                                            data-target="#sendMessageModal" data-user-id="{{$admin->id}}"
                                    >@lang('profile.contact_me')</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-8 order-1">
            <h3>@lang('dashboard.contact_my_teachers')</h3>
            <hr>
            <div class="row">
                @foreach($teachers as $t)
                    <div class="col-12 col-sm-12 col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-12 p-4 text-center">
                                        <img src="{{ $t->profile->getProfileImage() }}" class="text-center"
                                             style="width: 100%; max-width: 200px; border-radius: 50%">
                                    </div>
                                    <div class="col-12">
                                        <h4 class="text-center mb-0">
                                            <a href="{{ route("user.profile", $t->id) }}" class="text-primary">
                                                {{ $t->name }}
                                            </a>
                                        </h4>
                                        <p class="text-center">
                                            @foreach($t->teaching as $l)
                                                <i class="flag-icon {{ $l->icon }}"></i>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                                <br>
                                <p class="my-1 text-center"><b><i class="fa fa-phone"></i>
                                        :</b>
                                    @if($t->profile->phone)
                                        <a class="text-primary"
                                           href="tel: {{ $t->profile->phone }}"> {{ $t->profile->phone }}</a>
                                    @else
                                        ---
                                    @endif
                                </p>
                                {{--<p class="my-1"><b><i class="mdi mdi-gmail"></i> :</b>
                                    <a href="mailto:{{ $t->email }}">{{ $t->email }}</a></p>--}}
                                @if(Auth::id() != $t->id)
                                    <button class="btn btn-block btn-inverse-primary send_me_msg_btn px-1" type="button"
                                            data-toggle="modal"
                                            data-target="#sendMessageModal" data-user-id="{{$t->id}}"
                                    >@lang('profile.contact_me')</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
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
