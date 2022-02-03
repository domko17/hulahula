<script>

    let student_cal_lock = false;
    $(document).ready(function () {
        function myDateFunction(id) {
            $("#date-popover").hide();
            var date = $("#" + id).data("date");
            var hasEvent = $("#" + id).data("hasEvent");
            let templates = $("#eventStudentModalTemplates");
            let col_a = $("#col_available");
            let col_m = $("#col_enrolled");

            $('.none_enrolled').show();

            col_a.empty();
            col_m.empty();

            if (!hasEvent || student_cal_lock) {
                return false;
            }

            student_cal_lock = true;

            $("#title_day").html(date);

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "profile-student-events",
                    student_id: {{ $student->inst->id }},
                    date: date,
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    student_cal_lock = false;
                    $("#title_day").html(response.title_date);
                    if (!response.is_past) {
                        $.each(response.available, function () {
                            if (this.classes.length > 0) {
                                let tmp = templates.find("#row-available").clone();
                                let tmp_avail_all = tmp.find("#availables");
                                let teacher_name = this.teacher.name;
                                let teacher_lang_icons = tmp.find('#teacher_lang_icons');
                                tmp.find("#teacher_name").html(teacher_name);
                                tmp.find("#teacher_image").attr('src', this.teacher.image);
                                tmp.find("#teacher_profile_link").attr('href', this.teacher.profile_url);
                                $.each(this.languages, function () {
                                    teacher_lang_icons.append($('<i class="mr-2 flag-icon"></i>').addClass(this.icon))
                                })
                                $.each(this.classes, function () {
                                    let tmp_avail = templates.find("#single_available_template").clone();
                                    tmp_avail.attr('id', '');
                                    let link = tmp_avail.find(".class_link");
                                    link.attr('data-teachername', teacher_name);
                                    tmp_avail.find("#class_time").html(this.th.class_start.substr(0, 5) + " - " + this.th.class_end.substr(0, 5));
                                    link.attr('data-thid', this.th.id);
                                    if (this.class_instance) { //already existing class
                                        link.attr('data-classId', this.class_instance.id);
                                        link.attr('data-preview', false);
                                    } else { //potential class
                                        link.attr('data-date', date);
                                        link.attr('data-preview', true);
                                    }
                                    console.log(this)
                                    if (!this.time_locked)
                                        tmp_avail_all.append(tmp_avail);
                                });
                                col_a.append(tmp);
                            }
                        });
                    }
                    const enrolled = JSON.parse(response.enrolled);
                    if (enrolled.length) {
                        $.each(enrolled, function () {
                            $('.none_enrolled').hide();
                            let tmp = templates.find("#row-enrolled").clone();

                            tmp.find("#class_time").html(this.start.substr(0, 5) + " - " + this.end.substr(0, 5));
                            tmp.find("#teacher_name").html(this.teacher_name);

                            let link = tmp.find(".class_link").attr("href");
                            link = link.substr(0, link.length - 1) + this.id;
                            tmp.find(".class_link").attr("href", link);

                            col_m.append(tmp);
                        });
                        $('.enrolled_section').show();
                    } else {
                        $('.enrolled_section').hide();
                    }

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
                    action: "load_days_free_classes",
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

        let calendar_s_id = "";

        let calendar_s = $("#calendar-student").zabuto_calendar({
            language: "{{Auth::user()->profile->locale}}",
            show_days: true,
            weekstartson: 1,
            show_next: 3,
            nav_icon: {
                prev: '<i class="fa fa-chevron-circle-left"></i>',
                next: '<i class="fa fa-chevron-circle-right"></i>'
            },
            action: function () {
                return myDateFunction(this.id);
            },
            action_nav: function () {
                return myNavFunction(this.id);
            },
            data: [
                @foreach( $student->classes_future as $tc ) //studentove buduce hodiny
                {
                    date: '{{ $tc->class_date }}',
                    classname: "event-taken-classes-teacher"
                },
                @endforeach
                @foreach( $student->classes_past as $tc ) //studentove absolvovane minule hodiny
                {
                    date: '{{ $tc->class_date }}',
                    classname: @if($tc->is_past()) "event-past-class"
                    @elseif(count($tc->students) == 0) "event-free-classes"
                    @else "event-taken-classes-teacher" @endif ,
                },
                @endforeach
            ]
        }).after(function () {
            let _id = $(this).attr('id').split('_')[2];
            calendar_s_id = _id;
            myNavFunction('zabuto_calendar_' + _id + "_nav-next", true);
        });

        $(document).on('click', '.enroll_student_SMART', function () {
            let desc = $('#modal-description');
            desc.html(desc.html() + "<b>" + $(this).data('teachername') + "</b>");
            let th_id = $(this).data('thid');
            let date = $(this).data('date');
            let classId = $(this).data('classid');
            let preview = $(this).data('preview');
            let form = $('#enroll_smart_student_form');
            if (preview) {
                let act = form.find('#form_action_preview_smart').val();
                form.attr('action', act);
                $('#form_input_thid').val(th_id);
                $('#form_input_date').val(date);
            } else {
                let act = form.find('#form_action_smart').val();
                act = act + classId;
                form.attr('action', act);
            }
            if (!$(this).hasClass('smart')) {
                let title = '@lang('lecture.enroll_title')';
                let text = "@lang('lecture.enroll_text')";
                getPrompt2(title, text);
                return;
            }

            $('#enrollSmartModal').modal();

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "student_smart_days_for_study",
                    student_id: {{ \Illuminate\Support\Facades\Auth::id() }},
                    th_id: th_id
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    let days_container = $('#days_for_choose');
                    days_container.empty();
                    $.each(response.th, function () {
                        let selected = "";
                        if (this.id == th_id) selected = "checked";
                        days_container.append($('<div class="form-group"></div>')
                            .append($('<div class="form-check m-0"></div>')
                                .append($('<label class="form-check-label m-0"></label>')
                                    .append($('<input type="checkbox" name="smart_th[]" class="form-check-input" value="' + this.id + '"' + selected + '>'))
                                    .append('<i class="input-helper"></i>')
                                    .append($('<p class="ml-4"></p>')
                                        .append('' + this.day_name + " : " + this.class_start.substr(0, 5) + " - " + this.class_end.substr(0, 5))
                                    )
                                )
                            )
                        )
                    });
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
            })
        });

        $('#check_smart_student_enroll').click(function () {
            let max_checks = 2;
            let days_chosen = $('#days_for_choose input:checked').length;
            let err_container = $('#days_for_choose_err');
            err_container.hide('slow');

            if (days_chosen > max_checks || days_chosen == 0) {
                err_container.show('slow');
                return;
            }

            let title = '@lang('lecture.enroll_smart_title')';
            let text = "@lang('lecture.enroll_smart_text')";
            getPrompt2(title, text)
        });
    })

    function getPrompt2(title, text) {
        swal({
            title: title,
            text: text,
            showCancelButton: true,
            buttons: {
                cancel: {
                    text: "@lang('general.cancel')",
                    value: null,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                },
                confirm: {
                    text: "@lang('general.confirm')",
                    value: true,
                    visible: true,
                    className: "btn btn-success",
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $('#enroll_smart_student_form').submit();
            }
        })
    }
</script>
