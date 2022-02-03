<script>
    $(document).ready(function () {
        $('.delete-alert').click(function (e) {
            var id = $(this).attr("data-item-id");
            console.log(id);
            swal({
                title: "Prosím podvrďte akciu",
                text: "Akcia: zmazanie záznamu.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        document.getElementById('item-del-' + id).submit();
                    }
                });
        });
    });
</script>
