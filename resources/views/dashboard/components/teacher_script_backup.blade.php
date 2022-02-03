<script>
    $(document).ready(function () {
        function myDateFunction(id, fromModal) {
            console.log(id);
            $("#date-popover").hide();
            var date = $("#" + id).data("date");
            var hasEvent = $("#" + id).data("hasEvent");
            let tid = $("#teacher_id").val();
            let templates = $("#event_teacher_modal_templates");
            let col_i = $("#col_individual");
            let col_c = $("#col_collective");
            let col_m = $("#col_meeting");

            col_i.empty();
            col_c.empty();
            col_m.empty();

            if (!hasEvent) {
                return false;
            }
            //console.log(date);
            //console.log(tid);

            $("#eventsTeacherModalLabel").html(date);

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "profile-teacher-events",
                    day: date,
                    teacher_id: tid,
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    //console.log(response);

                    if (response.meeting != 'null') {
                        $("#div_meeting").show();
                    } else {
                        $("#div_meeting").hide();
                    }
                    if (response.individual != '[]') {
                        $("#div_individual").show();
                    } else {
                        $("#div_individual").hide();
                    }
                    if (response.collective != '[]') {
                        $("#div_collective").show();
                    } else {
                        $("#div_collective").hide();
                    }


                    $.each(JSON.parse(response.individual), function () {
                        //console.log(this);
                        let tmp = templates.find("#row-individual").clone();

                        tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                        if (this.studs.length > 0) {
                            let tmp_str = "";
                            $.each(this.studs, function () {
                                tmp_str += this.student.last_name + ", ";
                            });
                            tmp.find("#teacher_name").html(tmp_str);
                        } else {
                            tmp.find("#teacher_name").html("---");
                        }

                        let link = tmp.find(".class_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".class_link").attr("href", link);

                        col_i.append(tmp);
                    });
                    $.each(JSON.parse(response.collective), function () {
                        //console.log(this);

                        let tmp = templates.find("#row-collective").clone();

                        tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                        if (this.studs.length > 0) {
                            let tmp_str = "";
                            $.each(this.studs, function () {
                                tmp_str += this.student.last_name + ", ";
                            });
                            tmp.find("#teacher_name").html(tmp_str);
                        } else {
                            tmp.find("#teacher_name").html("---");
                        }

                        let link = tmp.find(".class_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".class_link").attr("href", link);

                        col_c.append(tmp);
                    });
                    if (response.meeting != 'null') {
                        let meeting = JSON.parse(response.meeting);
                        //console.log("MEETING: ", response.meeting);
                        let tmp = templates.find("#row-meeting").clone();

                        tmp.find("#meeting_time").html(meeting.start.substr(11, 5) + " - " + meeting.end.substr(11, 5));
                        tmp.find("#meeting_title").html("Meeting");

                        let link = tmp.find(".meeting_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".meeting_link").attr("href", link);

                        col_m.append(tmp);
                    }
                    $("#eventsTeacherModal").modal();
                },
                error: function (response) {
                    $.toast({
                        heading: 'Error',
                        text: 'AJAX-Error',
                        position: 'bottom-right',
                        icon: 'error',
                        stack: false,
                        loaderBg: '#ed3939',
                        bgColor: '#f0aaaa',
                        textColor: 'black'
                    })
                }
            });
            return true;
        }

        @foreach($teacher->languages as $sl)
        $("#calendar-teacher-lang-{{$sl->id}}").zabuto_calendar({
            language: "{{Auth::user()->profile->locale}}",
            show_days: true,
            weekstartson: 1,
            nav_icon: {
                prev: '<i class="fa fa-chevron-circle-left"></i>',
                next: '<i class="fa fa-chevron-circle-right"></i>'
            },
            action: function () {
                return myDateFunction(this.id, false);
            },
            data: [
                    @if($teacher->nearest_meeting)
                {
                    date: '{{$teacher->nearest_meeting->day}}',
                    classname: "event-meeting"
                },
                    @endif
                    @foreach($teacher->classes_i as $tc)
                    @if($tc->hour->language_id == $sl->id and (($tc->is_past() and count($tc->students) > 0) or !$tc->is_past()))
                {
                    date: '{{ $tc->class_date }}',
                    classname: @if($tc->is_past()) "event-past-class"
                    @elseif(count($tc->students) == 0) "event-free-classes"
                        @else "event-taken-classes-teacher" @endif ,
                },
                    @endif
                    @endforeach
                    @foreach($teacher->classes_c as $tc)
                    @if($tc->hour->language_id == $sl->id and (($tc->is_past() and count($tc->students) > 0) or !$tc->is_past()))
                {
                    date: '{{ $tc->class_date }}',
                    classname: @if($tc->is_past()) "event-past-class"
                    @elseif(count($tc->students) == 0) "event-free-classes"
                        @else "event-taken-classes-teacher" @endif ,
                },
                @endif
                @endforeach
            ]
        });
        /*var calendarEl{{$sl->id}} = document.getElementById('calendar-teacher-lang-{{$sl->id}}');
        var calendar{{$sl->id}} = new FullCalendar.Calendar(calendarEl{{$sl->id}}, {
            plugins: ['dayGrid', 'timeGrid', 'list'],
            defaultView: 'dayGridMonth',
            locale: '{{ Auth::user()->profile->locale }}', // the initial locale
            header: {
                left: window.mobilecheck() ? "prev,next" : "prev,next today",
                center: window.mobilecheck() ? "" : 'title',
                right: window.mobilecheck() ? 'dayGridMonth,dayGridWeek' : 'dayGridMonth,dayGridWeek,timeGridDay',
            },
            defaultDate: '{{ \Carbon\Carbon::now()->format("Y-m-d") }}',
            navLinks: true,
            editable: false,
            eventLimit: 3,
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false
            },
            events: [
                    @if($teacher->nearest_meeting)
                {
                    id: 1,
                    title: '{{"\\n".__('meeting.meeting')."!"}}',
                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $teacher->nearest_meeting->day)->format("Y-m-d")}}T{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $teacher->nearest_meeting->start)->format("H:i:s")}}',
                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $teacher->nearest_meeting->day)->format("Y-m-d")}}T{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $teacher->nearest_meeting->end)->format("H:i:s")}}',
                    url: '{{ route('teacher.nearest_meeting', $teacher->nearest_meeting->id) }}',
                    backgroundColor: info,
                    borderColor: silverish,
                    textColor: "#000",
                },
                    @endif
                    @foreach($teacher->classes_i as $tc)
                    @if($tc->hour->language_id == $sl->id and (($tc->is_past() and count($tc->students) > 0) or !$tc->is_past()))
                {
                    title: '',
                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_start}}',
                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_end}}',
                    url: '{{ route('lectures.show', $tc->id) }}',
                    backgroundColor: @if($tc->is_past()) silverish
                    @elseif($tc->canceled) secondary @elseif($tc->is_free()) success
                    @else danger @endif ,
                    borderColor: golden,
                    textColor: "#000",
                },
                    @endif
                    @endforeach
                    @foreach($teacher->classes_c as $tc)
                    @if($tc->hour->language_id == $sl->id and (($tc->is_past() and count($tc->students) > 0) or !$tc->is_past()))
                {
                    title: '{{count($tc->students) == 0 ? "" : (count($tc->students) == 1 ? "\\nS: ".$tc->students[0]->user->name : "\\nS: ".count($tc->students)."/".$tc->hour->class_limit )}}',
                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_start}}',
                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->hour->class_end}}',
                    url: '{{ route('lectures.show', $tc->id) }}',
                    backgroundColor: @if($tc->is_past()) silverish
                    @elseif($tc->canceled) secondary @elseif($tc->is_free()) success
                    @else danger @endif ,
                    borderColor: primary,
                    textColor: "#000",
                },
                @endif
                @endforeach

            ]
        });*/

        //calendar{{$sl->id}}.render();
        $('#loader_{{$sl->id}}').hide(function () {
            $(this).animate(250);
        });
        @endforeach

        $('#teacher-tab a').on('click', function (e) {
            $(this).tab('show').after(function () {
                let i = $(this).data("target-id");
                if (!i) {
                    calendar.render();
                }
                ;
                @foreach($teacher->languages as $sl)
                if (i == {{ $sl->id }}) {
                    calendar{{$sl->id}}.render();
                }
                ;
                @endforeach
            })
        });
    })
</script>
