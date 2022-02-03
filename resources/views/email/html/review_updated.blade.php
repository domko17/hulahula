@extends('email.html.layout')

@section('content')
    <h1>{{__('email.review_updated_title')}}</h1>
    <br>
    <br>
    <section>
        <p>{{__('email.review_updated_text_1', ['name' => $content['contribution_author']->first_name." ".$content['contribution_author']->last_name])}}</p>
        <p>{{__('email.review_updated_text_2')}}</p>
        <a href="{{route('user.myContribution.index')}}">{{__('email.general_system_link')}}</a>
    </section>
@stop