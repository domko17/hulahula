<script>
    $(document).ready(function () {
        $.fn.andSelf = function () {
            return this.addBack.apply(this, arguments);
        };
        $('.full-width').owlCarousel({
            loop: true,
            margin: 20,
            items: 1,
            nav: true,
            autoplay: true,
            autoplayTimeout: 10000,
            navText: ["<i class='mdi mdi-chevron-left text-center'></i>", "<i class='mdi mdi-chevron-right text-center'></i>"]
        });
    });
</script>
