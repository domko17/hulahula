<!DOCTYPE html>

<head>
    <title>Zoom WebSDK</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.8.1/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.8.1/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

</head>

<body>
<style>
#nav-tool{
    position: relative;
    margin: 0 40%;
    top: 35%;
    border-radius: 5%;
    width: 20%;
    height: 10%;
}
.btn-primary{
    position: relative;
    color: #fff;
    background-color: #a02f67;
    border-color: #a02f67;
    line-height: 1;
    font-family: "ubuntu-bold", sans-serif;
    margin: 10%;
    border-radius: 0;
    padding: 20px;
}
.websdktest{
    margin: 0 40%;
}
</style>

<div id="nav-tool" class="navbar navbar-inverse navbar-fixed-top">

        <div id="navbar" class="websdktest">
            <form class="navbar-form navbar-right" id="meeting_form">
                <div class="form-group">
                    <input type="hidden" name="display_name" id="display_name" value="{{$meeting_info['display_name']}}" maxLength="100"
                            class="form-control"  required>
                </div>
                <div class="form-group">
                    <input type="hidden" name="meeting_number" id="meeting_number" maxLength="200"
                           class="form-control"  value="{{$meeting_info['meeting_number']}}">
                </div>
                <div class="form-group">
                    <input type="hidden" name="meeting_pwd" id="meeting_pwd" value="{{$meeting_info['meeting_pwd']}}"
                           maxLength="32" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="meeting_email" id="meeting_email"  value="{{$meeting_info['meeting_email']}}"
                           maxLength="32" class="form-control">
                </div>

                <div class="form-group">
                    <input type="hidden" name="meeting_role" id="meeting_role"  value="{{$meeting_info['meeting_role']}}"
                           maxLength="32" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="meeting_china" id="meeting_china"  value="{{$meeting_info['meeting_china']}}"
                           maxLength="32"  class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="meeting_lang" id="meeting_lang"  value="{{$meeting_info['meeting_lang']}}"
                           maxLength="32"  class="form-control">
                </div>
                <button type="submit" class="btn btn-primary" id="join_meeting">@lang( 'meeting.zoom_started_meeting' )</button>
            </form>
        </div>
</div>

<script src="https://source.zoom.us/1.8.1/lib/vendor/react.min.js"></script>
<script src="https://source.zoom.us/1.8.1/lib/vendor/react-dom.min.js"></script>
<script src="https://source.zoom.us/1.8.1/lib/vendor/redux.min.js"></script>
<script src="https://source.zoom.us/1.8.1/lib/vendor/redux-thunk.min.js"></script>
<script src="https://source.zoom.us/1.8.1/lib/vendor/jquery.min.js"></script>
<script src="https://source.zoom.us/1.8.1/lib/vendor/lodash.min.js"></script>

<script src="https://source.zoom.us/zoom-meeting-1.8.1.min.js"></script>
<script src="Zoom/tool.js"></script>
<script src="Zoom/vconsole.js"></script>
<script src="Zoom/index.js"></script>
</body>
</html>
