@extends('email.html.layout')

@section('content')
    <h1>{{__('email.new_hula_message_title', [], $content->lang)}}</h1>
    <br>
    <br>
    <section>
        @if(!isset($content->group))
            <h4>{{__('email.new_hula_message_text1', ['name' => $content->name], $content->lang)}}</h4>
        @else
            <h4>{{__('email.new_hula_message_text1_group', ['name' => $content->name], $content->lang)}}</h4>
        @endif

        <p>{{ isset($content->sender_email) ? $content->sender_email : "" }}</p>
        <br>
        <br>
        <p>{{ isset($content->message) ? $content->message : "" }}</p>
        {{--<a href="{{ route('messages.index') }}">
            @lang('side_menu.messages')
        </a>--}}
    </section>
@stop
