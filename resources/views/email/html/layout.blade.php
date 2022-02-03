<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Hula Hula Zona</title>

    <style>
        h1 {
            text-align: center;
        }

        .head_img, .foot_img {
            text-align: center;
            margin: auto;
            width: 100%;
        }

        .head_img > img, .foot_img > img {
            width: 100%;
        }

        main {
            padding: 0;
            background-color: rgba(255, 255, 255, .5);
        }

        body{
            max-width: 800px;
            margin: auto;
        }

        section{
            padding-left: 25px;
            padding-right: 25px;
        }

    </style>
</head>
<body>
<main>
    <div class="head_img">
        @if(isset($preview))
            <img src="{!! asset('images/app/email_header.png') !!}">
        @else
            <img src="{!! $message->embed(url('images/app/email_header.png')) !!}">
        @endif
    </div>
    {{--<p>Data: {{ json_encode($content) }}</p>
    <hr>--}}
    @yield('content')
    <br>
    <br>
    <small>Na tento mail neodpovedajte prosím.</small><br>
    <small>Copyright 2019-2020 | HulaHula</small>
</main>
<footer>
    <div class="foot_img">
        @if(isset($preview))
            <img src="{!! asset('images/app/email_footer.png') !!}">
        @else
            <img src="{!! $message->embed(url('images/app/email_footer.png')) !!}">
        @endif
    </div>
</footer>
</body>
</html>
