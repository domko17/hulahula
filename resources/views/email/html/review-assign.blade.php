@extends('email.html.layout')

@section('content')
    <h1>{{__('email.review_assigned_title')}}</h1>
    <br>
    <br>
    <section>
        <p>{{__('email.review_assigned_text_1', ['name' => $content['reviewer']->first_name." ".$content['reviewer']->last_name])}}</p>
        <p>{{__('email.review_assigned_text_2')}}</p>
        <a href="{{route('review.myReview.index')}}">{{__('email.general_system_link')}}</a>
    </section>
@stop