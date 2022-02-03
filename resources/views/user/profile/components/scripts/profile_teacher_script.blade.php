<script>
    $(document).ready(function () {
        $("#form_add_teacher_hours").validate({
            rules: {
                day: "required",
                class_start: {
                    required: true,
                    min: "04:00",
                    max: "23:00"
                },
                class_end: {
                    required: true,
                    min: "04:01",
                    max: "23:00"
                },
                language: "required"
            },
            messages: {
                day: {
                    required: "@lang('validation.required',["attribute"=>__('general.day')])"
                },
                class_start: {
                    required: "@lang('validation.required',["attribute"=>__('class.start')])",
                    minlength: "@lang('validation.min.string', ["attribute"=>__('class.start'), "min"=>"04:00", "max"=>"22:00"])"
                },
                class_end: {
                    required: "@lang('validation.required',["attribute"=>__('class.end')])",
                    minlength: "@lang('validation.min.string', ["attribute"=>__('class.end'), "min"=>"04:01", "max"=>"23:00"])"
                },
                language: {
                    required: "@lang('validation.required',["attribute"=>__('general.language')])"
                },
                level: {
                    required: "@lang('validation.required',["attribute"=>__('language.level')])",
                }
            },
            errorPlacement: function (label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function (element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

        $("#calendar_2").zabuto_calendar({
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
            action_nav: function () {
                return myNavFunction(this.id);
            },
            data: [
                    @if($nearest_meeting)
                {
                    date: '{{$nearest_meeting->day}}',
                    classname: "event-meeting"
                },
                    @endif
                    @foreach($teacher_instance->classes_i_all as $tc)
                    @if(($tc->is_past() and count($tc->students) > 0) or (!$tc->is_past() and $tc->canceled == 0))
                {
                    date: '{{ $tc->class_date }}',
                    classname: @if($tc->is_past()) "event-past-class"
                    @elseif(count($tc->students) == 0) "event-free-classes"
                    @else "event-taken-classes-teacher" @endif ,
                },
                    @endif
                    @endforeach
                    @foreach($teacher_instance->classes_c_all as $tc)
                    @if(($tc->is_past() and count($tc->students) > 0) or (!$tc->is_past() and $tc->canceled == 0))
                {
                    date: '{{ $tc->class_date }}',
                    classname: @if($tc->is_past()) "event-past-class"
                    @elseif(count($tc->students) == 0) "event-free-classes"
                    @else "event-taken-classes-teacher" @endif ,
                },
                @endif
                @endforeach
            ]
        }).after(function () {
            let _id = $(this).attr('id').split('_')[2];
            calendar_s_id = _id;
            myNavFunction('zabuto_calendar_' + _id + "_nav-next", true);
        });

        function myDateFunction(id, fromModal) {
            $("#date-popover").hide();
            var date = $("#" + id).data("date");
            var hasEvent = $("#" + id).data("hasEvent");
            let templates = $("#event_modal_templates");
            let col_a = $("#col_available");
            let col_m = $("#col_enrolled");
            let tid = {{ $user->id }};

            $('.none_enrolled').show();

            col_a.empty();
            col_m.empty();

            if (!hasEvent) {
                return false;
            }

            $("#title_day").html(date);

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "profile-teacher-events",
                    date: date,
                    teacher_id: tid,
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $("#title_day").html(response.title_date);
                    if (!response.is_past) {
                        $.each(response.available, function () {
                            if (this.classes.length > 0) {
                                let teacher_id = this.teacher.id;
                                let teacher_name = this.teacher.name;
                                let teacher_image = this.teacher.image;
                                let tmp = templates.find("#row-available").clone();
                                let tmp_avail_all = tmp.find("#availables");
                                tmp.find("#teacher_name").html(teacher_name);
                                tmp.find("#teacher_image").attr('src', teacher_image);
                                $.each(this.classes, function () {
                                    let tmp_avail = templates.find("#single_available_template").clone();
                                    tmp_avail.attr('id', '');
                                    let link = tmp_avail.find(".class_link").attr("href");
                                    if (this.class_instance) { //already existing class
                                        tmp_avail.find("#class_time").html(this.th.class_start.substr(0, 5) + " - " + this.th.class_end.substr(0, 5));
                                        link = link.substr(0, link.length - 1) + this.class_instance.id;
                                    } else { //potential class
                                        tmp_avail.find("#class_time").html(this.class_start.substr(0, 5) + " - " + this.class_end.substr(0, 5));
                                        link = link.substr(0, link.length - 1) + this.id + "/preview/" + date;
                                    }
                                    tmp_avail.find(".class_link").attr("href", link);
                                    tmp_avail_all.append(tmp_avail);
                                });
                                col_a.append(tmp);
                            }
                        });
                    }
                    $.each(JSON.parse(response.enrolled), function () {
                        $('.none_enrolled').hide();
                        let tmp = templates.find("#row-enrolled").clone();

                        tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                        tmp.find("#teacher_name").html(this.teacher_name);

                        let link = tmp.find(".class_link").attr("href");
                        link = link.substr(0, link.length - 1) + this.id;
                        tmp.find(".class_link").attr("href", link);

                        col_m.append(tmp);
                    });
                    $("#eventModal").modal();
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

        function myNavFunction(id, initial = false) {
            $('.loader_wrap_s').fadeIn('fast');
            let nav = $("#" + id).data("navigation");
            let to = $("#" + id).data("to");
            let month;
            let year;
            if (initial) {
                year = to.year;
                if (to.month == 1) {
                    month = 12;
                    year -= 1;
                } else month = to.month - 1;
            } else {
                month = to.month;
                year = to.year;
            }
            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "load_teacher_calendar",
                    teacher_id: {{ $user->id }},
                    month: month,
                    year: year,
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    for (let day in response.days) {
                        if (response.days[day]) {
                            $('#zabuto_calendar_' + calendar_s_id + '_' + day + "_day").addClass('event-free-classes');
                            $('#zabuto_calendar_' + calendar_s_id + '_' + day).data('hasEvent', true);
                        }
                    }
                    $('.loader_wrap_s').fadeOut('fast');
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
                    });
                    $('.loader_wrap_s').fadeOut('fast');
                }
            });
            return true;
        }
    })
</script>
