<script>
    $(document).ready(function () {
        function myDateFunction(id, language_id, fromModal) {
            console.log(id);
            $("#date-popover").hide();
            var date = $("#" + id).data("date");
            var hasEvent = $("#" + id).data("hasEvent");
            let tid = $("#teacher_id").val();
            let templates = $("#event_student_modal_templates");
            let col_i = $("#col_individual");
            let col_c = $("#col_collective");
            let col_m = $("#col_enrolled");

            col_i.empty();
            col_c.empty();
            col_m.empty();

            if (!hasEvent) {
                return false;
            }
            //console.log(date);
            //console.log(tid);

            $("#title_day").html(date);

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "profile-student-events",
                    student_id: {{ $student->inst->id }},
                    language_id: language_id,
                    date: date,
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $("#title_day").html(response.title_date);
                    $("#event_title_icon").removeClass();
                    $("#event_title_icon").addClass('flag-icon');
                    $("#event_title_icon").addClass(response.language_flag);

                    //console.log(response);
                    $.each(JSON.parse(response.classes_i), function () {
                        console.log(this);
                        let tmp = templates.find("#row-individual").clone();

                        tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                        tmp.find("#teacher_name").html(this.teacher_name);

                        let link = tmp.find(".class_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".class_link").attr("href", link);

                        col_i.append(tmp);
                    });
                    $.each(JSON.parse(response.classes_c), function () {
                        console.log(this);

                        let tmp = templates.find("#row-collective").clone();

                        tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                        tmp.find("#teacher_name").html(this.teacher_name);

                        let link = tmp.find(".class_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".class_link").attr("href", link);

                        col_c.append(tmp);
                    });
                    $.each(JSON.parse(response.enrolled), function () {
                        console.log(this);

                        let tmp = templates.find("#row-enrolled").clone();

                        tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                        tmp.find("#teacher_name").html(this.teacher_name);

                        let link = tmp.find(".class_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".class_link").attr("href", link);

                        col_m.append(tmp);
                    });
                    $("#eventStudentModal").modal();
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

        @foreach($student->languages as $sl)
        $("#calendar-student-lang-{{$sl->id}}").zabuto_calendar({
            language: "{{Auth::user()->profile->locale}}",
            show_days: true,
            weekstartson: 1,
            nav_icon: {
                prev: '<i class="fa fa-chevron-circle-left"></i>',
                next: '<i class="fa fa-chevron-circle-right"></i>'
            },
            action: function () {
                return myDateFunction(this.id, '{{$sl->id}}', false);
            },
            data: [
                @foreach($student->classes_future as $tc) //studentove buduce hodiny
                    @if($tc->language->id == $sl->id)
                {
                    date: '{{ $tc->class_date }}',
                    classname: "event-taken-classes-teacher"
                },
                    @endif
                    @endforeach
                @foreach($student->days_with_free_classes["$sl->id"] as $d) //buduce volne terminy
                {
                    date: '{{ $d }}',
                    classname: "event-free-classes",
                },
                    @endforeach
                @foreach($student->classes_past as $tc) //studentove absolvovane minule hodiny
                    @if($tc->language->id == $sl->id)
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
        /*var calendarEl{{$sl->id}} = document.getElementById('calendar-student-lang-{{$sl->id}}');
        var calendar{{$sl->id}} = new FullCalendar.Calendar(calendarEl{{$sl->id}}, {
            plugins: ['dayGrid', 'timeGrid', 'list'],
            defaultView: "dayGridMonth",
            locale: '{{ Auth::user()->profile->locale }}', // the initial locale
            header: {
                left: window.mobilecheck() ? "prev,next" : "prev,next today",
                center: window.mobilecheck() ? "" : 'title',
                right: window.mobilecheck() ? 'dayGridMonth,dayGridWeek' : 'dayGridMonth,dayGridWeek,timeGridDay',
            },
            defaultDate: '{{ \Carbon\Carbon::now()->format("Y-m-d") }}',
            navLinks: true,
            editable: false,
            timeZone: 'CET',
            eventLimit: 3,
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: false
            },
            events: [
                @foreach($student->classes_future as $tc) //studentove absolvovane minule hodiny
                    @if($tc->language->id == $sl->id)
        {
            title: '\n{{$tc->hour->teacher ? $tc->hour->teacher->profile->first_name[0].". ".$tc->hour->teacher->profile->last_name : "..."}}',
                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->teacherHour ? $tc->teacherHour->class_start : $tc->collectiveHour->class_start}}',
                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->teacherHour ? $tc->teacherHour->class_end : $tc->collectiveHour->class_end}}',
                    url: '{{ route('lectures.show', $tc->id) }}',
                    backgroundColor: @if($tc->canceled) secondary
                    @else info @endif ,
                    borderColor: @if($tc->teacherHour) golden @else primary @endif ,
                    textColor: "#000"
                },
                @endif
        @endforeach
        @foreach($student->days_with_free_classes["$sl->id"] as $d) //buduce volne terminy
                {
                    title: '{{ __('lecture.free_lectures_cal_1') }} \n {{ __('lecture.free_lectures_cal_2') }}!',
                    start: '{{$d}}',
                    allDay: true,
                    backgroundColor: success,
                    borderColor: success,
                    textColor: "#000",
                    extendedProps: {
                        language_id: '{{ $sl->id }}',
                    },
                },
                @endforeach
        @foreach($student->classes_past as $tc) //studentove absolvovane minule hodiny
                    @if($tc->language->id == $sl->id)
        {
            id: '{{ $tc->id }}',
                    title: '\n{{$tc->hour->teacher->profile->first_name[0].". ".$tc->hour->teacher->profile->last_name}}',
                    start: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->teacherHour ? $tc->teacherHour->class_start : $tc->collectiveHour->class_start}}',
                    end: '{{\Carbon\Carbon::createFromFormat("Y-m-d", $tc->class_date)->format("Y-m-d")}}T{{$tc->teacherHour ? $tc->teacherHour->class_end : $tc->collectiveHour->class_end}}',
                    url: '{{ route('lectures.show', $tc->id) }}',
                    backgroundColor: silverish,
                    borderColor: @if($tc->teacherHour) golden @else primary @endif ,
                    textColor: "#000",
                },
                @endif
        @endforeach

        ],
        eventClick: function (event) {
            let date = new Date(event.event.start);
            date = date.toISOString().substr(0, 10);
            let language_id = event.event.extendedProps.language_id;
            if (!event.event.id) {
                $("#eventModal").modal();
                let col_i = $("#col-individual");
                let col_c = $("#col-collective");

                col_i.empty();
                col_c.empty();

                $.ajax({
                    url: "{{ route("ajax_int") }}",
                        method: "POST",
                        data: {
                            action: "get_students_available_future_classes",
                            student_id: {{ $student->inst->id }},
                            language_id: language_id,
                            date: date,
                        },
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (response) {
                            //console.log(response);

                            let templates = $("#event_modal_templates");

                            $("#title_day").html(response.title_date);
                            $("#event_title_icon").removeClass();
                            $("#event_title_icon").addClass('flag-icon');
                            $("#event_title_icon").addClass(response.language_flag);


                            $.each(JSON.parse(response.classes_i), function () {
                                console.log(this);
                                let tmp = templates.find("#row-individual").clone();

                                tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                                tmp.find("#teacher_name").html(this.teacher_name);

                                let link = tmp.find(".class_link").attr("href");
                                link = link.substr(0, link.length - 1) + this.id;
                                tmp.find(".class_link").attr("href", link);

                                col_i.append(tmp);
                            });
                            $.each(JSON.parse(response.classes_c), function () {
                                console.log(this);

                                let tmp = templates.find("#row-collective").clone();

                                tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                                tmp.find("#teacher_name").html(this.teacher_name);

                                let link = tmp.find(".class_link").attr("href");
                                link = link.substr(0, link.length - 1) + this.id;
                                tmp.find(".class_link").attr("href", link);

                                col_c.append(tmp);
                            });
                        },
                        error: function (response) {
                            $.toast({
                                heading: 'Error',
                                text: 'Error',
                                position: 'bottom-right',
                                icon: 'error',
                                stack: false,
                                loaderBg: '#ed3939',
                                bgColor: '#f0aaaa',
                                textColor: 'black'
                            })
                        }
                    })
                }
            }
        });

        calendar{{$sl->id}}.render();*/
        $('#loader_s_{{$sl->id}}').hide(function () {
            $(this).animate(250);
        });
        @endforeach

        /*$('#student-tab a').on('click', function (e) {
            $(this).tab('show').after(function () {
                let i = $(this).data("target-id");
                if (!i) {
                    calendar.render();
                }
                ;
                @foreach($student->languages as $sl)
        if (i == {{ $sl->id }}) {
                    calendar{{$sl->id}}.render();
                }
                ;
                @endforeach
        })
    });*/

        var c3LineChart = c3.generate({
            bindto: '#c3-line-chart',
            data: {
                x: 'x',
                columns: [
                    ['x' @foreach($student->chart_data as $cd) {!! ",'$cd[0]' " !!} @endforeach],
                    ['Počet hodín' @foreach($student->chart_data as $cd) {{ $cd[1] ? ",".(round($cd[1]->hours / 6) / 10)." " : ",0 " }} @endforeach],
                ],
                type: 'bar',
            },
            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%Y-%m-%d'
                    }
                },
                y: {
                    show: true,
                    inner: true,
                    max: 8
                }
            },
            bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
            },
            color: {
                pattern: ['rgba(88,216,163,1)', 'rgba(237,28,36,0.6)', 'rgba(4,189,254,0.6)']
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 30,
                left: 0,
            }
        });

        {{--@if(count($student->languages) > 0)
        $("#pills-profile-tab").click(function () {
            calendar{{$student->languages[0]->id}}.render();
        });
        @endif--}}

    })
</script>
