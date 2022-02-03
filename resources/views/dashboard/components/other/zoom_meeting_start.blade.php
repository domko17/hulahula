<div class="col-12 stretch-card my-2 px-2">
    <div class="card">
        <div class="card-body p-2 p-md-4">
            @if(Cache('zoom_meeting_duration'))
                <script>
                    var date = '{{Cache('zoom_meeting_duration')->start_datetime}}'.split(' ');
                    var end = new Date(''+date[0].split('-')[0]+'/'+date[0].split('-')[1]+'/'+date[0].split('-')[2]+' '+date[1].split(':')[0]+':'+date[1].split(':')[1]+'');
                    var _second = 1000;
                    var _minute = _second * 60;
                    var _hour = _minute * 60;
                    var _day = _hour * 24;
                    var timer;

                    function showRemaining() {
                        var now = new Date();
                        var distance = end - now;
                        if (distance < 0) {
                            clearInterval(timer);
                            document.getElementById("loadingTitle").style.visibility = "hidden";
                            document.getElementById('join_meeting').removeAttribute('disabled');
                            document.getElementById('countdown').innerHTML = '@lang('meeting.zoom_join_meeting') </br></br> @lang('meeting.zoom_current_meeting')';
                            return;
                        }

                        var days = Math.floor(distance / _day);
                        var hours = Math.floor((distance % _day ) /_hour);
                        var minutes = Math.floor((distance % _hour) / _minute);
                        var seconds = Math.floor((distance % _minute) / _second);

                        document.getElementById('countdown').innerHTML = hours  + ' : ';
                        document.getElementById('countdown').innerHTML += minutes + ' : ';
                        document.getElementById('countdown').innerHTML += seconds + '';
                    }
                    timer = setInterval(showRemaining, 1000);
                </script>

                <form action="{{ route('zoom_index') }}" class="navbar-form navbar-right" id="meeting_form">
                    <input type="hidden" name="meeting_number" value="{{Cache('zoom_meeting_duration')->zoom_meeting_id}}">
                    <input type="hidden" name="display_name" value="{{Auth::user()->name}}">
                    <input type="hidden" name="meeting_pwd" value="{{Cache('zoom_meeting_duration')->password}}">
                    <input type="hidden" name="meeting_email" value="{{Auth::user()->email}}">
                    <input type="hidden" name="meeting_role" value=1>
                    @if(Lang::locale() == "en")
                        <input type="hidden" name="meeting_lang" value="en-US">
                    @elseif(Lang::locale() == "de")
                        <input type="hidden" name="meeting_lang" value="de-DE">
                    @elseif(Lang::locale() == "ru")
                        <input type="hidden" name="meeting_lang" value="ru-RU">
                    @elseif(Lang::locale() == "sk")
                        <input type="hidden" name="meeting_lang" value="sk-SK">
                    @endif
                    <input type="hidden" name="meeting_china" value=0>

                    <button name="button_submit" id="join_meeting" class="btn btn-sm btn-primary btn-block" formtarget="_blank" disabled>
                        <div id="loadingTitle">@lang( 'meeting.zoom_meeting' )</div> <div id="countdown"></div>
                    </button>
                </form>
            @else
                <p> @lang( 'meeting.zoom_non_meeting' ) </p><hr>
            @endif

        </div>
    </div>
</div>

