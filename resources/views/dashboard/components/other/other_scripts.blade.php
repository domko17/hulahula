<script>
    $(document).ready(function () {

        $('.main_hint_toggle').click(function () {
            $('#dashboard_hint_alert').slideToggle();
        })

        @if(session()->has('feedback_created'))
        setTimeout(() => {
            $('#feedbackCreatedModal').modal();
        }, 1500);
        @endif

    });
</script>
