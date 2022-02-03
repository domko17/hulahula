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
        <div class="col-lg-4 grid-margin order-2 order-md-1">
            <div class="card border-round-10 border border-primary">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12">
                            @if($current_user->hasRole('admin') or $current_user->hasRole('teacher'))

                                <a role="button" data-toggle="modal" class="btn btn-sm btn-outline-primary"
                                   href="#groupMessageModal" id="group_message_modal_btn">
                                    <span data-custom-class="tooltip-primary"
                                          data-toggle="tooltip" data-delay="750"
                                          data-placement="top" title=""
                                          data-original-title="@lang('chat.tooltip_new_group_message')">
                                    @lang('general.group_message')
                                    </span>
                                </a>
                                <a role="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                   href="#createMessageGroupModal" id="create_message_group_modal_btn">
                                    <span data-custom-class="tooltip-primary"
                                          data-toggle="tooltip" data-delay="750"
                                          data-placement="top" title=""
                                          data-original-title="@lang('chat.tooltip_create_new_group_message')"
                                    ><i class="fa fa-group"></i> <i class="fa fa-plus"></i></span>
                                </a>

                                <a class="btn btn-sm btn-gradient-primary pull-right"
                                   href="{{ route('messages.index') }}"
                                   data-custom-class="tooltip-primary"
                                   data-toggle="tooltip" data-delay="750"
                                   data-placement="top" title=""
                                   data-original-title="@lang('chat.tooltip_refresh')">
                                    <i class="fa fa-refresh"></i>
                                </a>
                            @else

                                <a class="btn btn-sm btn-gradient-primary"
                                   href="{{ route('messages.index') }}"
                                   data-custom-class="tooltip-primary"
                                   data-toggle="tooltip" data-delay="750"
                                   data-placement="top" title=""
                                   data-original-title="@lang('chat.tooltip_refresh')">
                                    <i class="fa fa-refresh"></i></i>
                                </a>
                            @endif

                            <hr class="mt-2 mb-0">
                        </div>
                    </div>
                    <div class="row my-1">
                        <div class="col-12">
                            <h3>
                                @lang('dashboard.messages')
                            </h3>
                        </div>
                    </div>
                    @if(count($chat_groups) > 0)
                        <h6>@lang('chat.my_groups')</h6>
                        @foreach($chat_groups as $g)
                            <div class="row m-1 pl-4 pr-1 py-1 border-round-10 border border-silverish">
                                <div class="col-2 p-0 text-center">
                                    <img src="{{ asset('images/app/Placeholders/group_2.png') }}"
                                         width="100%"
                                         class="rounded-circle">
                                </div>
                                <div class="col-8">
                                    <b>{{ $g->name }}</b>&nbsp;
                                    <br>{!! substr($g->lastMessage()->message,0,25) !!}{{strlen($g->lastMessage()->message) > 25 ? "..." : ""}}
                                </div>
                                <div class="col-2 p-0">
                                    <button
                                        class="btn btn-outline-primary btn-sm load_messages border-round-10 px-2 pull-right"
                                        data-imageurl="{{ asset('images/app/Placeholders/group_2.png') }}"
                                        style="height: 100%"
                                        data-group_id="{{$g->id}}"
                                        data-user_name="{{ $g->name }}">
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <hr class="m-2">
                        <h6>@lang('chat.private_chats')</h6>
                    @endif
                    @if(count($chat_with) == 0)
                        @lang('chat.no_chats_yet')
                    @else
                        @foreach($chat_with as $p)
                            <div class="row m-1 pl-4 pr-1 py-1 border-round-10 @if($current_user->lastMessageWith($p->id)->reciever_id == $current_user->id and
                                        !$current_user->lastMessageWith($p->id)->is_read())
                                border border-primary
                                @else
                                border border-secondary
                                    @endif
                                ">
                                <div class="col-2 p-0 text-center">
                                    <img src="{{ $p->profile->getProfileImage() }}"
                                         width="100%"
                                         class="rounded-circle {{ $p->is_online() ? ($p->is_online() == 1 ? 'profile_img_online':($p->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}">
                                </div>
                                <div class="col-8">
                                    <b>{{ $p->name }}</b>&nbsp;
                                    @if($current_user->lastMessageWith($p->id)->reciever_id == $current_user->id and
                                    !$current_user->lastMessageWith($p->id)->is_read())
                                        <b class="text-primary">@lang('general.new')</b>
                                    @endif
                                    <br>{!! substr($current_user->lastMessageWith($p->id)->message,0,25) !!}{{strlen($current_user->lastMessageWith($p->id)->message) > 25 ? "..." : ""}}
                                    @if($current_user->lastMessageWith($p->id)->read)
                                        <i class="mdi mdi-check"></i>
                                    @endif
                                </div>
                                <div class="col-2 p-0">
                                    <button class="btn btn-outline-primary btn-sm
                                @if($current_user->lastMessageWith($p->id)->reciever_id == $current_user->id and
                                        !$current_user->lastMessageWith($p->id)->is_read())
                                        animated tada infinite slow
                                        @endif
                                        load_messages border-round-10 px-2 pull-right"
                                            data-imageurl="{{ $p->profile->getProfileImage() }}"
                                            style="height: 100%"
                                            data-user_id="{{$p->id}}"
                                            data-user_name="{{ $p->name }}">
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8 grid-margin stretch-card order-1 order-md-2">
            <div class="card border-round-10 border border-primary">
                <div class="bg-inverse-silverish border-round-10" id="chat_overlay_1"
                     style="width: 100%; height: 100%; position: absolute; z-index: 10;">
                    <div class="row align-items-center h-100">
                        <div class="col-10 col-md-6 mx-auto">
                            <div class="jumbotron bg-transparent text-muted mb-0 p-2">
                                <div id="chat_help_pc" style="display: none">
                                    <h1 class="text-center">
                                        <i class="fa fa-chevron-left animated bounce infinite slower"></i>
                                    </h1>
                                    <h4>@lang('chat.help_1')</h4>
                                </div>
                                <div id="chat_help_mobile" style="display: none;">
                                    <h1 class="text-center">
                                        <i class="fa fa-chevron-down animated bounce infinite slower"></i>
                                    </h1>
                                    <h4>@lang('chat.help_1_mobile')</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body hide px-4 px-md-5 py-0 py-md-3" id="chat_box" style="padding: 1rem 2.5rem">
                    <div class="row">
                        <div class="col-12 bg-primary py-2 my-2 border-round-10">
                            <a href="#" data-user_id="" class="text-white profile_redirect text-decoration-none"
                               id="profile_redirect_1">
                            <img src="#"
                                 class="img-sm border border-light rounded-circle"
                                 id="profile_img">
                            </a>
                            &nbsp;<a href="#" data-user_id="" class="text-white profile_redirect"
                                     id="profile_redirect_2">
                                <b id="user_name" class="text-white"></b>
                            </a>
                            <a href="#groupMembersModal" data-toggle="modal" role="button"
                               class="text-white pull-right rounded-circle border border-light mx-auto"
                               id="group_members_show_btn"><i class="fa fa-group"></i> </a>
                        </div>

                        <div class="px-4" style="max-height: 400px; width: 100%; overflow: scroll"
                             id="chat_wrapper">
                        </div>
                        <div class="col-12 {{ $current_user->theme == 1 ? "bg-inverse-dark" : "bg-transparent" }} px-2 px-md-3 py-3 my-2 border-round-10">
                            <div class="row">
                                <div class="col-9 col-md-10">
                                    <textarea rows="3" class="form-control p-2" name="msg_text"
                                              id="msg_text"></textarea>
                                    <input type="hidden" value="" id="msg_to_who">
                                    <input type="hidden" value="" id="is_group_chat">
                                </div>
                                <div class="col-3 col-md-2 pl-0 pl-md-2">
                                    <button class="btn btn-rounded btn-gradient-success p-1"
                                            style="height: 100%; width: 100%" id="send_msg_btn">
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

    {{-- Modals--}}
    @if($current_user->hasRole('admin') or $current_user->hasRole('teacher'))
        <div class="modal fade" id="groupMessageModal" tabindex="-1" role="dialog"
             aria-labelledby="groupMessageModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="groupMessageModalLabel">@lang('general.group_message')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                        <div class="row form-group">
                            <div class="col-12">
                                <label class="col-form-label" for="recipients">
                                    @lang('general.recipients')
                                </label>
                                <select name="recipients[]" id="recipients" multiple required
                                        class="form-control">
                                    @foreach(\App\User::where('id', "!=", $current_user->id)->get() as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <label class="col-form-label" for="recipients">
                                    @lang('general.message')
                                </label>
                                <textarea class="form-control" id="message_to_send" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="send_group_msg_btn" class="btn btn-success"><i
                                class="fa fa-send"></i> @lang('general.send')
                        </button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createMessageGroupModal" tabindex="-1" role="dialog"
             aria-labelledby="createMessageGroupModal"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMessageGroupModal">@lang('general.create_message_group')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                        <div class="row form-group mb-1">
                            <div class="col-12">
                                <label class="col-form-label my-0" for="recipients">
                                    @lang('chat.group_name')
                                </label>
                                <input type="text" class="form-control" name="group_name" id="group_name" value=""
                                       required>
                            </div>
                        </div>
                        <div class="row form-group mb-1">
                            <div class="col-12">
                                <label class="col-form-label my-0" for="recipients">
                                    @lang('general.members')
                                </label>
                                <select name="members[]" id="members" multiple required
                                        class="form-control">
                                    @foreach(\App\User::where('id', "!=", $current_user->id)->get() as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-group mb-1">
                            <div class="col-12">
                                <label class="col-form-label my-0" for="recipients">
                                    @lang('general.message')
                                </label>
                                <textarea class="form-control" id="message_to_send_group" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="create_group_msg_btn" class="btn btn-success"><i
                                class="fa fa-send"></i> @lang('chat.create_and_send')
                        </button>
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">@lang('general.Cancel')</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="groupMembersModal" tabindex="-1" role="dialog"
         aria-labelledby="groupMembersModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupMembersModalLabel">@lang('chat.group_members')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-0" id="members_div">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </div>
        </div>
    </div>


    {{-- hidden templates --}}
    <div id="templates" style="display: none;">
        <div class="col-12" style="display: none;" id="loader">
            <div class="dot-opacity-loader">
                <span></span><span></span><span></span>
            </div>
        </div>

        <div class="col-12 my-1 py-1 other_img" style="display: none">
            <img src="" class="img-ss">
            <small id="date"></small>
        </div>
        <div class="col-12 col-md-8 {{ $current_user->theme == 1 ? "bg-light" : "bg-transparent" }} py-1 px-4 border-round-10 other_msg border border-secondary" style="display: none">
            <p><i class="fa fa-chevron-right text-muted"></i>
                <span id="here"></span></p>
        </div>

        <div class="col-12 text-right py-1 me_img" style="display: none">
            <small id="date"></small>
            <img src="" class="img-ss">
        </div>
        <div class="col-12 col-md-8 offset-md-4 text-right my-1 {{ $current_user->theme == 1 ? "bg-light" : "bg-transparent" }} py-1 px-4 border-round-10 border border-primary me_msg"
             style="display: none">
            <p><span id="here"></span> <i class="fa fa-chevron-left text-primary"></i></p>
        </div>

        <div class="row my-1" id="member_template" style="display: none">
            <div class="col-3 text-center">
                <img id="member_img" src="" class="img-sm">
            </div>
            <div class="col-9">
                <p id="member_name"></p>
            </div>
        </div>
    </div>
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ asset('vendors/chosen/chosen.css') }}">
@stop

@section('page_scripts')
    <script src="{{ asset('vendors/chosen/chosen.jquery.js') }}"></script>

    @include('message.components.script')

@stop
