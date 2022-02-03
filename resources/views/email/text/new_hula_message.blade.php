@section('content')

    {{__('email.new_hula_message_title', [], $content->lang)}}

    @if(!isset($content->group))
        {{__('email.new_hula_message_text1', ['name' => $content->name], $content->lang)}}
    @else
        {{__('email.new_hula_message_text1_group', ['name' => $content->name], $content->lang)}}
    @endif

@stop
