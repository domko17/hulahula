<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="{{ asset('public/manifest.json') }}">
    <meta name="theme-color" content="#a02f67"/>

    <link rel="apple-touch-startup-image" href="{{ asset('images/app/hula_hula_sq.png') }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>{{ env("APP_NAME") }} | @yield("title")</title>
    <link rel="icon" href="https://hulahula.sk/wp-content/uploads/2019/06/favicon.ico" sizes="32x32"/>
    <link rel="icon" href="https://hulahula.sk/wp-content/uploads/2019/06/favicon.ico" sizes="192x192"/>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @if (!request()->is('login') and !request()->is('register') and
         !request()->is('password/reset') and !request()->is('password/reset/*') and
         !isset($exception) and !request()->is('no_conn'))
        @if(Auth::user() and Auth::user()->theme == 2)
            <link href="{{ asset('css/style_dark.css') }}" rel="stylesheet">
        @else
            <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        @endif
    @else
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="{{ asset("vendors/iconfonts/mdi/css/materialdesignicons.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendors/iconfonts/font-awesome/css/font-awesome.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendors/iconfonts/ti-icons/css/themify-icons.css") }}">
    <link rel="stylesheet" href="{{ asset("vendors/iconfonts/flag-icon-css/css/flag-icon.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendors/css/vendor.bundle.base.css") }}">
    <link rel="stylesheet" href="{{ asset("vendors/css/vendor.bundle.addons.css") }}">

    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset("css/custom.css") }}">

    @yield("page_css")

</head>
<body>
{{-- if not login/logout/.... or error page draw all navs and sidebar--}}
@if (!request()->is('login') and !request()->is('register') and
         !request()->is('password/reset') and !request()->is('password/reset/*') and
         !isset($exception) and !request()->is('no_conn') and !request()->is('thank-you'))
    <div class="container-scroller">
        @if (!request()->is('login') and !request()->is('register') and
             !request()->is('password/reset') and !request()->is('password/reset/*') and
             !isset($exception) and !request()->is('no_conn') and !request()->is('thank-you'))
            @include("layouts.components.top_navigation")
        @endif

        <div class="container-fluid page-body-wrapper">
            @include('layouts.components.sidebar')

            <div class="main-panel">
                <div class="content-wrapper p-3 pl-md-5 pr-md-5 pb-md-5 pt-md-3">

                    @yield('content')

                    <div class="alert alert-info">
                        Aplikácia je vo verzii <b>&beta; {{env('APP_VERSION')}}</b><br>
                        Prosím akékoľvek pripomienky, problémy alebo iné otázky pošlite emailom na adresu <a
                            href="mailto: paumer@hulahula.sk"
                            class="text-info"><b>paumer@hulahula.sk</b></a>.
                    </div>

                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 HulaHula s.r.o Všetky práva vyhradené. </span>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
@else {{-- else draw just content --}}
@yield('content')
@endif

<!-- Vue app.js -->
<script src="{{ mix('js/app.js') }}"></script>

<!-- plugins:js -->
<script src="{{ asset("vendors/js/vendor.bundle.base.js") }}"></script>

<script src="{{ asset("vendors/js/vendor.bundle.addons.js") }}"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="{{ asset("js/off-canvas.js") }}"></script>
<script src="{{ asset("js/misc.js") }}"></script>
<script src="{{ asset("js/todolist.js") }}"></script>
<script src="{{ asset("js/settings.js") }}"></script>
<script src="{{ asset("js/hoverable-collapse.js") }}"></script>
<!-- endinject -->

<script src="{{ asset('js/enable-push.js') }}"></script>

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker
            .register('/zona/sw.js')
            .then(function () {
                console.log("Service Worker Registered");
                initPush();
            });
    }
</script>

<script>
    const dt_language = {
        "emptyTable": "Nie sú k dispozícii žiadne dáta",
        "info": "",
        "infoEmpty": "",
        "infoFiltered": "(vyfiltrované spomedzi _MAX_ záznamov)",
        "infoPostFix": "",
        "infoThousands": ",",
        "lengthMenu": "Zobraz _MENU_ záznamov",
        "loadingRecords": "Načítavam...",
        "processing": "Spracúvam...",
        "search": "Hľadať:",
        "zeroRecords": "Nenašli sa žiadne vyhovujúce záznamy",
        "paginate": {
            "first": "Prvá",
            "last": "Posledná",
            "next": ">",
            "previous": "<"
        },
        "aria": {
            "sortAscending": ": aktivujte na zoradenie stĺpca vzostupne",
            "sortDescending": ": aktivujte na zoradenie stĺpca zostupne"
        }
    };

    window.mobilecheck = function () {
        var check = false;
        (function (a) {
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    };

    $(document).ready(function () {
            @if(session()->has('message') and session()->has('msg_type'))
        let type = "{{ session()->get('msg_type') }}";
        let msg = "{{ session()->get('message') }}";
        let icon = "success";
        let status = "Success";
        let colorbg = "#adf09e";
        let colorld = "#51b947";


        if (type == "danger") {
            status = "Error";
            icon = "error";
            colorbg = "#ff808a";
            colorld = "#bd2e31";
        }

        $.toast({
            heading: status,
            text: msg,
            position: 'bottom-right',
            icon: icon,
            stack: false,
            hideAfter: 5000,
            loaderBg: colorld,
            bgColor: colorbg,
            textColor: 'black'
        });
        @endif

        if (typeof $.fn.popover.Constructor === 'undefined') {
            throw new Error('Bootstrap Popover must be included first!');
        }

        var Popover = $.fn.popover.Constructor;

        // add customClass option to Bootstrap Tooltip
        $.extend(Popover.Default, {
            customClass: ''
        });

        var _show = Popover.prototype.show;

        Popover.prototype.show = function () {

            // invoke parent method
            _show.apply(this, Array.prototype.slice.apply(arguments));

            if (this.config.customClass) {
                var tip = this.getTipElement();
                $(tip).addClass(this.config.customClass);
            }

        };

        $('[data-toggle="popover"]').popover();

        if (typeof $.fn.tooltip.Constructor === 'undefined') {
            throw new Error('Bootstrap Tooltip must be included first!');
        }

        var Tooltip = $.fn.tooltip.Constructor;

        // add customClass option to Bootstrap Tooltip
        $.extend(Tooltip.Default, {
            customClass: ''
        });

        var _show = Tooltip.prototype.show;

        Tooltip.prototype.show = function () {

            // invoke parent method
            _show.apply(this, Array.prototype.slice.apply(arguments));

            if (this.config.customClass) {
                var tip = this.getTipElement();
                $(tip).addClass(this.config.customClass);
            }

        };
        $('[data-toggle="tooltip"]').tooltip();

        if (window.mobilecheck()) {
            $('table').each(function () {
                $(this).addClass("table-responsive");
            })
        }

        $.validator.addMethod("validPhoneNum", function (value, element) {
            const regex = "[+][0-9]+";
            const res = value.match(regex);
            return res && res.length;
        }, "Phone is not in correct form");

        $("#register_form").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                },
                phone: {
                    required: true,
                    minLength: 8,
                    validPhoneNum: true,
                }
            },
            messages: {
                name: {
                    required: "@lang('validation.required',["attribute"=>__('general.Name_surname')])"
                },
                email: {
                    required: "@lang('validation.required',["attribute"=>__('general.email')])",
                },
                password: {
                    required: "@lang('validation.required',["attribute"=>__('general.password')])",
                    minlength: "@lang('validation.min.string', ["attribute"=>__('general.password'), "min"=>"8"])"
                },
                password_confirmation: {
                    required: "@lang('validation.required',["attribute"=>__('general.Password_confirm')])",
                    equalTo: "Heslá sa nezhodujú"
                },
            },
            errorPlacement: function (label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function (element, errorClass) {
                $(element).parent().addClass('has-danger');
                $(element).addClass('form-control-danger');
            }
        })

    })
</script>

@yield("page_scripts")

<script>
    $(document).ready(function () {
        $('.dataTables_filter input').addClass('border-primary');
    });
</script>

</body>
</html>
