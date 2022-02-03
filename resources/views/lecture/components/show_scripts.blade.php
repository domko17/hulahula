<script>
    $("#students").chosen({
        width: "100%"
    });

    $(document).ready(function () {
        tinymce.init({
            selector: "#infotext",
            height: 200,
            theme: "modern",
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
        });

        $('#material_edit_table').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 10,
            "language": dt_language,
            "order": [[0, 'asc']],
            "columns": [
                null,
                null,
                {"orderable": false}
            ]
        });

        // validate signup form on keyup and submit
        $("#form_info_edit").validate({
            rules: {
                info: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name_native: {
                    required: "@lang('validation.required',["attribute"=>"Info"])",
                    minlength: "@lang('validation.min.string', ["attribute"=>"Info", "min"=>2])"
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

        $("#change_date_btn").click(function () {
            let lid = $(this).data('lecture');
            let sid = $(this).data('student');

            $('#calendar-student-reschedule').zabuto_calendar({
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
                data: []
            }).after(function () {
                let _id = $(this).attr('id').split('_')[2];
                calendar_s_id = _id;
                myNavFunction('zabuto_calendar_' + _id + "_nav-next", true);
            });
        });

        $("#one_time_sign_btn").click(function () {
            $("#form_sign_student").submit();
        });

        $("#repeat_sign_btn").click(function () {
            $("#student_repeat").val(1);
            $("#form_sign_student").submit();
        });

        $("#admin_student_reschedule").click(function () {
            let student_id = $(this).data('student');
            $("#reschedule_student_id").val(student_id);

            let lid = $(this).data('lecture');
            let sid = $(this).data('student');

            $('#calendar-student-reschedule').zabuto_calendar({
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
                data: []
            }).after(function () {
                let _id = $(this).attr('id').split('_')[2];
                calendar_s_id = _id;
                myNavFunction('zabuto_calendar_' + _id + "_nav-next", true);
            });
        });

        $('.enroll_student_SMART').click(function () {
            $('#enrollSmartModal').modal();
            let th_id = $(this).data('thid');
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

        $('.enroll_student').click(function () {
            let title = '@lang('lecture.enroll_title')';
            let text = "@lang('lecture.enroll_text')";
            getPrompt(title, text)
        })
    })
    ;

    function getPrompt(title, text) {
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
                $('#form_enroll_student').submit();
            }
        })
    }

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

    function myDateFunction(id) {
        $("#date-popover").hide();
        let container = $("#" + id);
        let date = container.data("date");
        let hasEvent = container.data("hasEvent");
        let templates = $("#event_student_modal_templates");
        let col_a = $("#col_available");
        let col_m = $("#col_enrolled");

        $('.none_enrolled').show();

        col_a.empty();
        col_m.empty();

        if (!hasEvent) {
            return false;
        }

        $("#title_day").html(date);
        $('#reschedule_date').val(date);

        $.ajax({
            url: "{{ route("ajax_int") }}",
            method: "POST",
            data: {
                action: "reschedule-student-events",
                student_id: {{ \Illuminate\Support\Facades\Auth::id() }},
                date: date,
                class_id: {{ $lecture->id }}
            },
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                //$("#title_day").html(response.title_date);

                $.each(response.available, function () {
                    if (this.classes.length > 0) {
                        let teacher_name = this.teacher.name;
                        let teacher_image = this.teacher.image;
                        let tmp = templates.find("#row-available").clone();
                        let tmp_avail_all = tmp.find("#availables");
                        tmp.find("#teacher_name").html(teacher_name);
                        tmp.find("#teacher_image").attr('src', teacher_image);
                        $.each(this.classes, function () {
                            let tmp_avail = templates.find("#single_available_template").clone();
                            tmp_avail.attr('id', '');
                            let link = tmp_avail.find(".reschedule_here");
                            let is_preview = false;
                            let _id = 0;
                            if (this.class_instance) { //already existing class
                                tmp_avail.find("#class_time").html(this.th.class_start.substr(0, 5) + " - " + this.th.class_end.substr(0, 5));
                                _id = this.class_instance.id;
                            } else { //potential class
                                tmp_avail.find("#class_time").html(this.class_start.substr(0, 5) + " - " + this.class_end.substr(0, 5));
                                is_preview = true;
                                _id = this.id;
                            }
                            link.attr('onClick', 'reschedule_here(' + is_preview + ',' + _id + ')');
                            tmp_avail_all.append(tmp_avail);
                        });
                        col_a.append(tmp);
                    }
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
                action: "get_classes_days_for_reschedule",
                month: month,
                year: year,
                class_id: {{ $lecture->id }}
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

    function reschedule_here(is_preview, id) {
        $('#reschedule_is_preview').val(is_preview);
        $('#reschedule_id').val(id);

        swal({
            title: '@lang( 'lecture.reschedule_confirm_title' )',
            text: '@lang( 'lecture.reschedule_confirm_text' )',
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
                $('#reschedule_class_form').submit();
            }
        })
    }

    {{--<!-- import ZoomMtg dependencies -->
    <script src="https://source.zoom.us/1.7.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/1.7.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/1.7.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/1.7.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/1.7.2/lib/vendor/lodash.min.js"></script>

    <!-- import ZoomMtg -->
    <script src="https://source.zoom.us/zoom-meeting-1.7.2.min.js"></script>

    <script>
        $(document).ready(function () {
            console.log('checkSystemRequirements');
            console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

            ZoomMtg.setZoomJSLib('https://dmogdx0jrul3u.cloudfront.net/1.4.2/lib', '/av')

            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareJssdk();
            const meetConfig = {
                meetingNumber: 384152005,
                leaveUrl: 'https://zona_dev.hulahula.sk/',
                passWord: '539826'
            };

            function getSignature(meetConfig) {
                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "get_zoom_signature",
                        dt: JSON.stringify({meetingData: meetConfig})
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        ZoomMtg.init({
                            debug: true, //optional
                            leaveUrl: 'http://www.zoom.us', //required
                            success: function () {
                                ZoomMtg.join({
                                    apiKey: meetConfig.apiKey,
                                    signature: response.signature,
                                    meetingNumber: meetConfig.meetingNumber,
                                    passWord: 'cnMwb01FTnl3ZHUzTGMrOFlDNE51dz09',
                                    userName: 'Maerin Hrebenar',
                                    userEmail: '',
                                    participantId: '{{ \Sodium\randombytes_random16() }}',
                                    success: (success) => {
                                        console.log("joined")
                                    },
                                    error: (error) => {
                                        console.log(error)
                                    }
                                })
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        })
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
            }

            getSignature(meetConfig);
        })
    </script>--}}

</script>
