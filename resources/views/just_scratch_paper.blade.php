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
                    @lang('side_menu.messages')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            @foreach($chat_with as $p)
                                <div class="row m-0 my-1 p-1 bg-light border-bottom">
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-4 text-center">
                                                <img src="{{ $p->profile->getProfileImage() }}"
                                                     class="rounded-circle" width="100%">
                                            </div>
                                            <div class="col-8 p-0 py-2">
                                                <b>{{ $p->name }}</b>
                                            </div>
                                            <div class="col-12 text-muted py-1">
                                                {{ substr($current_user->lastMessageWith($p->id)->message,0,20) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 p-0">
                                        <button class="btn btn-outline-primary p-0 load_messages"
                                                style="height: 100%; width: 100%"
                                                data-imageurl="{{ $p->profile->getProfileImage() }}"
                                                data-user_id="{{$p->id}}"
                                                data-user_name="{{ $p->name }}">
                                            <i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <div class="col-8" style="max-height: 600px">
                            <div class="row">
                                <div class="col-12 bg-inverse-primary py-3 my-2">
                                    <img src="{{ asset('images/app/Placeholders/profile_male.png') }}"
                                         class="img-sm"
                                         id="profile_img">
                                    <b id="user_name">Hula Pomocník</b>
                                </div>

                                <div class="row px-2" style="max-height: 400px; width: 100%; overflow: scroll"
                                     id="chat_wrapper">
                                    <div class="col-12 my-1 border-top py-1">
                                        <img src="{{ asset('images/app/Placeholders/profile_male.png') }}"
                                             class="img-ss">
                                        <small>{{ \Carbon\Carbon::now()->format('d,M Y H:i') }}</small>
                                    </div>
                                    <div class="col-8">
                                        <p><i class="fa fa-chevron-right text-muted"></i>
                                            Ahoj. Klikni na fialové tlačidlo pri mene v ľavom paneli pre otvorenie
                                            konverzácie. Na správy môžeš odpovedať napísaním svojej odpovede v políčku
                                            nižšie. Ak chceš niekomu poslať správu, nájdi si jeho profil v systéme a v
                                            ňom nájdeš tlačidlo pre začatie konverzácie.

                                            Tvoj Hula Hula tím.
                                        </p>
                                    </div>

                                    <div class="col-12 text-right border-top py-1">
                                        <small>{{ \Carbon\Carbon::now()->format('d,M Y H:i') }}</small>
                                        <img src="{{ $current_user->profile->getProfileImage() }}"
                                             class="img-ss">
                                    </div>
                                    <div class="col-8 offset-4 text-right my-1">
                                        <p>Ahoj. Čo tu možem robiť? <i
                                                class="fa fa-chevron-left text-primary"></i></p>
                                    </div>

                                </div>
                                <div class="col-12 bg-light py-3 my-2">
                                    <div class="row">
                                        <div class="col-10">
                                            <textarea rows="2" class="form-control" id="msg_text"></textarea>
                                            <input type="hidden" value="" id="msg_to_who">
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-rounded btn-gradient-success p-1"
                                                    style="height: 100%; width: 100%">
                                                <i class="fa fa-send"></i>
                                            </button>
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

    {{-- hidden templates --}}
    <div id="templates" style="display: none;">
        <div class="col-12" style="display: none;" id="loader">
            <div class="dot-opacity-loader">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div class="col-12 my-1 py-1 border-top other_img" style="display: none">
            <img src=""
                 class="img-ss">
            <small id="date"></small>
        </div>
        <div class="col-8 other_msg" style="display: none">
            <p><i class="fa fa-chevron-right text-muted"></i>
                <span id="here"></span></p>
        </div>

        <div class="col-12 text-right py-1 border-top me_img" style="display: none">
            <small id="date"></small>
            <img src=""
                 class="img-ss">
        </div>
        <div class="col-8 offset-4 text-right my-1 me_msg" style="display: none">
            <p><span id="here"></span> <i
                    class="fa fa-chevron-left text-primary"></i></p>
        </div>
    </div>
@stop

@section('page_css')

@stop

@section('page_scripts')

    <script>

        $(document).ready(function () {

            $('.load_messages').click(function () {
                let d_img = $(this).data("imageurl");
                let d_name = $(this).data("user_name");
                let d_id = $(this).data("user_id");
                let my_id = {{ $current_user->id }};
                let my_img = '{{ $current_user->profile->getProfileImage() }}';


                var wrapper = $("#chat_wrapper");
                wrapper.empty();
                wrapper.append($("#loader").clone().show());
                wrapper.find("#loader").show(function () {
                    $(this).animate(500)
                });

                var templates = $("#templates");


                $("#profile_img").attr('src', d_img);
                $("#user_name").html(d_name);
                $("#msg_to_who").val(d_id);

                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "get_messages",
                        user_id: {{ $current_user->id}},
                        reciever_id: d_id
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        $.each(JSON.parse(response.messages), function () {
                            console.log(this.id);
                            if (this.sender_id === my_id) {
                                let tmp_i = templates.find(".me_img").clone();
                                let tmp_m = templates.find(".me_msg").clone();

                                tmp_i.find("img").attr('src', my_img);
                                tmp_m.find("#here").html(this.message);

                                wrapper.append(tmp_i);
                                wrapper.append(tmp_m);
                            } else {
                                let tmp_i = templates.find(".other_img").clone();
                                let tmp_m = templates.find(".other_msg").clone();

                                tmp_i.find("img").attr('src', d_img);
                                tmp_m.find("#here").html(this.message);

                                wrapper.append(tmp_i);
                                wrapper.append(tmp_m);
                            }
                        });
                        $('#chat_wrapper > div').each(function () {
                            console.log($(this));
                            $(this).show(function () {
                                $(this).animate(500)
                            })
                        });

                        wrapper.find("#loader").hide(function () {
                            $(this).animate(500);
                        });
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
                });

            })

        });

    </script>

@stop
