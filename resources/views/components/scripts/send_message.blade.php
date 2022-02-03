<script>
    $(document).ready(function () {

        $('#send_msg_btn').click(function () {
            let msg = $("#message_to_send").val();
            let to_who = {{ $user->id }};

            $(".message_me_add_name").html($(".message_me_add_name").html() + "{{ $user->name}}");

            let my_id = {{ Auth::id() }};


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
                    user_id: my_id,
                    reciever_id: to_who,
                    message: msg
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $('#sendMessageModal').modal('hide');
                    $.toast({
                        heading: 'Success',
                        text: 'Message sent!',
                        position: 'bottom-right',
                        icon: 'success',
                        stack: false,
                        loaderBg: '#0eb543',
                        bgColor: '#b5ffaa',
                        textColor: 'black'
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
    });
</script>
