<script>
    $(document).ready(function () {
        setTimeout(
            function () {
                $(".quick_survey_box").show(function () {
                    $(this).animate(500);
                });
            }, 5000);

        $(".quick_survey_box_close").click(function () {
            $(".quick_survey_box").hide(function () {
                $(this).animate(500);
            });
        });

        $(".quick_survey_box_answer").click(function () {
            $(".quick_survey_box").hide(function () {
                $(this).animate(500);
            });

            let qtext = $(this).data('qtext');
            let qid = $(this).data('qid');
            let qtype = $(this).data('qtype');

            $("#question").html(qtext);
            $("#question_id").val(qid);
            $("#question_type").val(qtype);

            console.log(qtype);
            $("#answer_type_" + qtype).show();
        });

        $("#send_survey_answer").click(function () {
            let qtype = $("#question_type").val();
            let qid = $("#question_id").val();
            let user_id = $("#user_id").val();
            let anon = 0;
            let answ = "";

            if ($("#anonymous").is(':checked')) anon = 1;

            if (qtype == 1) {
                answ = $("#answer_text").val();
                if (answ.length > 0) {
                    $("#quickSurveyModal").modal("toggle");
                } else {
                    console.log("empty answer");
                    $.toast({
                        heading: 'Chyba',
                        text: 'Prosíme, nenechajte vašu odpoveď prázdnu',
                        position: 'bottom-right',
                        icon: 'error',
                        stack: false,
                        loaderBg: '#ed3939',
                        bgColor: '#f0aaaa',
                        textColor: 'black'
                    });
                    return;
                }
            }

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "send_answer_quick_survey",
                    qid: qid,
                    user_id: user_id,
                    anon: anon,
                    answer: answ,
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    console.log(response);
                    $.toast({
                        heading: 'Odpoveď odoslaná',
                        text: 'Ďakujeme za vašu odpoveď :)',
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
                        text: 'Error: 500',
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
