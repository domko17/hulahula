<script>

    $(document).ready(function () {

        if(window.mobilecheck()){
            $('#chat_help_mobile').show();
            $('#chat_help_pc').hide();
        }else{
            $('#chat_help_mobile').hide();
            $('#chat_help_pc').show();
        }

        function redirectToProfile(id){
            let url = "{{ route('user.profile',0) }}";
            url = url.substring(0, url.length - 1);
            url = url + id;
            window.location = url;
        }

        $(".profile_redirect").click(function () {
            let id = $(this).data("user_id");
            if(id > 0){
                redirectToProfile(id);
            }
        });

        $("#recipients").chosen({
            width: "100%",
        });

        $("#members").chosen({
            width: "100%",
        });

        $('.load_messages').click(function () {
            let d_img = $(this).data("imageurl");
            let d_name = $(this).data("user_name");
            let d_id = $(this).data("user_id");
            let g_id = $(this).data("group_id");
            let my_id = {{ $current_user->id }};
            let my_img = '{{ $current_user->profile->getProfileImage() }}';

            if (d_id) {
                //console.log("D_ID:", d_id);
                $("#msg_to_who").val(d_id);
                $("#is_group_chat").val(0);
                $("#group_members_show_btn").hide(function () {
                    $(this).animate(500)
                });
                $("#profile_redirect_1").attr("data-user_id", d_id);
                $("#profile_redirect_2").attr("data-user_id", d_id);
            } else if (g_id) {
                //console.log("G_ID:", g_id);
                $("#msg_to_who").val(g_id);
                $("#is_group_chat").val(1);
                $("#group_members_show_btn").show(function () {
                    $(this).animate(500)
                });
                $("#profile_redirect_1").attr("data-user_id", "0");
                $("#profile_redirect_2").attr("data-user_id", "0");
            }

            $("#chat_overlay_1").hide(function () {
                $(this).animate(500);
            }).after(function () {
                $("#chat_box").show(function () {
                    $(this).animate(500);
                })
            });


            var wrapper = $("#chat_wrapper");
            wrapper.empty();
            wrapper.append($("#loader").clone().show());
            wrapper.find("#loader").show(function () {
                $(this).animate(500)
            });

            var templates = $("#templates");


            $("#profile_img").attr('src', d_img);
            $("#user_name").html(d_name);

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "get_messages",
                    user_id: {{ $current_user->id}},
                    reciever_id: d_id,
                    group_id: g_id
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    let firstMsg = null;
                    $.each(JSON.parse(response.messages), function () {
                        //console.log(this);
                        if (this.sender_id == my_id) {
                            let tmp_i = templates.find(".me_img").clone();
                            let tmp_m = templates.find(".me_msg").clone();

                            tmp_i.find("img").attr('src', my_img);
                            if (g_id) {
                                tmp_i.find("#date").html("Ja - " + this.created_at.slice(0, 16));
                            } else {
                                tmp_i.find("#date").html(this.created_at.slice(0, 16));
                            }
                            tmp_m.find("#here").html(this.message);
                            tmp_m.attr('id', "");
                            if (!firstMsg) firstMsg = tmp_m;
                            wrapper.prepend(tmp_m);
                            wrapper.prepend(tmp_i);
                        } else {

                            let tmp_i = templates.find(".other_img").clone();
                            let tmp_m = templates.find(".other_msg").clone();

                            if (g_id) {
                                tmp_i.find("img").attr('src', this.sender_img);
                            } else {
                                tmp_i.find("img").attr('src', d_img);
                            }
                            if (g_id) {
                                tmp_i.find("#date").html(this.sender_name + " - " + this.created_at.slice(0, 16));
                            } else {
                                tmp_i.find("#date").html(this.created_at.slice(0, 16));
                            }
                            tmp_m.find("#here").html(this.message);
                            tmp_m.attr('id', "");
                            if (!firstMsg) firstMsg = tmp_m;
                            wrapper.prepend(tmp_m);
                            wrapper.prepend(tmp_i);
                        }
                    });

                    firstMsg.attr('id', "newest");

                    $('#chat_wrapper > div').each(function () {
                        $(this).show(function () {
                            $(this).animate(500)
                        })
                    });

                    wrapper.find("#loader").hide(function () {
                        $(this).animate(500);
                    });

                    var offset = $("#newest").offset();
                    $('#chat_wrapper').animate({
                        scrollTop: offset.top,
                        scrollLeft: offset.left
                    }, 1000);
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
            });

        });

        $('#send_msg_btn').click(function () {
            let msg = $("#msg_text").val();
            let to_who = $("#msg_to_who").val();
            let is_group = $("#is_group_chat").val();

            let my_id = {{ $current_user->id }};
            let my_img = '{{ $current_user->profile->getProfileImage() }}';

            var wrapper = $("#chat_wrapper");

            var templates = $("#templates");

            console.log(msg);
            console.log(to_who);

            if (to_who == '') {
                $.toast({
                    heading: 'Error',
                    text: 'You can\'t send a message to Hula Pomocník',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }

            if (msg == '') {
                $.toast({
                    heading: 'Error',
                    text: 'You can\'t send an empty message',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "send_message",
                    user_id: {{ $current_user->id}},
                    reciever_id: to_who,
                    is_group: is_group,
                    message: msg
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {

                    $('#chat_wrapper').find("#newest").attr("id", '');

                    let tmp_i = templates.find(".me_img").clone().show();
                    let tmp_m = templates.find(".me_msg").clone().show();

                    tmp_i.find("img").attr('src', my_img);
                    var d = new Date();
                    var n = d.toISOString();
                    n = n.replace('T', ' ');
                    n = n.slice(0, 16);
                    tmp_i.find("#date").html(n);
                    tmp_m.find("#here").html(msg);
                    tmp_m.attr('id', "newest");
                    wrapper.append(tmp_i);
                    wrapper.append(tmp_m);
                    $("#newest")[0].scrollIntoView();
                    $("#msg_text").val('');
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

        });

        $('#send_group_msg_btn').click(function () {
            let msg = $("#message_to_send").val();
            let to_who = [];
            $("#recipients option:selected").each(function (e) {
                //console.log($(this).val());
                to_who.push($(this).val());
            });

            let my_id = {{ Auth::id() }};

            //console.log(msg);
            //console.log(to_who);
            //console.log(my_id);

            if (msg == '') {
                $.toast({
                    heading: 'Error',
                    text: 'You can\'t send an empty message',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }
            if (to_who.length == 0) {
                $.toast({
                    heading: 'Error',
                    text: 'Please Select recipients',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "send_group_message",
                    user_id: my_id,
                    recievers: to_who,
                    message: msg
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $('#groupMessageModal').modal('hide');
                    $.toast({
                        heading: 'Success',
                        text: 'Message sent!',
                        position: 'bottom-right',
                        icon: 'success',
                        stack: false,
                        loaderBg: '#0eb543',
                        bgColor: '#b5ffaa',
                        textColor: 'black',
                        afterHidden: function () {
                            location.reload();
                        }
                    })

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

        });

        $('#create_group_msg_btn').click(function () {
            let msg = $("#message_to_send_group").val();
            let title = $("#group_name").val();
            let members = [];
            $("#members option:selected").each(function (e) {
                //console.log($(this).val());
                members.push($(this).val());
            });

            let my_id = {{ Auth::id() }};

            //console.log(title);
            //console.log(members);
            //console.log(msg);
            //console.log(my_id);

            if (title.length == 0) {
                $.toast({
                    heading: 'Error',
                    text: 'Please set group name',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }
            if (members.length == 0) {
                $.toast({
                    heading: 'Error',
                    text: 'Please Select group members',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }
            if (msg == '') {
                $.toast({
                    heading: 'Error',
                    text: 'You can\'t send an empty message',
                    position: 'bottom-right',
                    icon: 'error',
                    stack: false,
                    loaderBg: '#ed3939',
                    bgColor: '#f0aaaa',
                    textColor: 'black'
                });
                return;
            }

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "create_message_group",
                    admin_id: my_id,
                    members: members,
                    title: title,
                    message: msg
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $('#createMessageGroupModal').modal('hide');
                    $.toast({
                        heading: 'Success',
                        text: 'Message sent!',
                        position: 'bottom-right',
                        icon: 'success',
                        stack: false,
                        loaderBg: '#0eb543',
                        bgColor: '#b5ffaa',
                        textColor: 'black',
                        afterHidden: function () {
                            location.reload();
                        }
                    })

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
        });

        $("#group_members_show_btn").click(function () {
            let group_id = $("#msg_to_who").val();
            let div = $("#members_div");
            div.children().remove();

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "get_chat_group_members",
                    group_id: group_id
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    //console.log(response.members);
                    $.each(response.members, function () {

                        let tmp = $("#templates").find("#member_template").clone();
                        let name = this.name;
                        if (this.admin == true) {
                            name = name + " (Admin)";
                        }

                        tmp.find("img").attr('src', this.img);
                        tmp.find('#member_name').html(name);
                        div.append(tmp);
                        tmp.show(function () {
                            $(this).animate(500);
                        })
                    });
                },
                error: function (response) {
                    $.toast({
                        heading: 'Error',
                        text: 'Server Error: 500',
                        position: 'bottom-right',
                        icon: 'error',
                        stack: false,
                        loaderBg: '#ed3939',
                        bgColor: '#f0aaaa',
                        textColor: 'black'
                    })
                }
            })
        })
    });

</script>
