@extends('email.html.layout')

@section('content')
    <h1>{{__('email.review_rejected_title')}}</h1>
    <br>
    <br>
    <section>
        <p>{{__('email.review_rejected_text_1', ['name' => $content['review_assigned_by']->first_name." ".$content['review_assigned_by']->last_name])}}</p>
        <p>{{__('email.review_rejected_text_2', ['name' => $content['reviewer']->first_name." ".$content['reviewer']->last_name])}}</p>
        <a href="{{route('user.myContribution.index')}}">{{__('email.general_system_link')}}</a>
    </section>
@stop