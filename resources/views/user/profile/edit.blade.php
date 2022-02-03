@extends("layouts.app")

@section('content')
    <div class="page-header mt-2 mb-2 mb-mt-4 mt-md-0">
        <h3 class="page-title">
            <button onclick="window.location.href='{{ route('dashboard') }}'"
                    class="page-title-icon btn btn-gradient-primary btn-icon btn-rounded btn-sm">
                <i class="mdi mdi-home"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="text-dark"></a>
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb px-1 px-md-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-primary">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    @lang('general.profile')
                </li>
                <li class="breadcrumb-item active">
                    {{ $user->name }} - @lang('general.editing')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            @include('user.profile.components.edit_profile_image')
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-12 col-md-6 text-center text-md-left">
                                    <h3>{{ $profile->getFullName() }}</h3>
                                </div>
                                <div class="col-12 col-md-6 text-center text-md-right">
                                    <button type="submit" form="form_user_profile_edit"
                                            class="btn btn-gradient-success btn-sm"><i
                                            class="fa fa-check"></i> @lang('general.Save')</button>
                                    <a href="{{ route('user.profile', $user->id) }}"
                                       class="btn btn-gradient-danger btn-sm"><i
                                            class="fa fa-times"></i> @lang('general.Cancel')</a>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>

                            <form action="{{ route('user.profile.update', $user->id) }}" method="POST"
                                  id="form_user_profile_edit">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    @include('user.profile.components.edit_profile_tab')

                                    @if(Auth::user()->hasRole('admin') and Auth::id() != $user->id and $user->hasRole('student'))
                                        @include('user.profile.components.edit_student_tab')
                                    @endif

                                    @if(Auth::user()->hasRole('admin') and Auth::id() != $user->id and $user->hasRole('teacher'))
                                        @include('user.profile.components.edit_teacher_tab')
                                    @endif

                                    <div class="col-12 col-md-6 text-center text-md-right">
                                        <hr>
                                        <button type="submit"
                                                class="btn btn-gradient-success"><i
                                                class="fa fa-fw fa-check"></i> @lang('general.Save')</button>
                                    </div>
                                </div>

                            </form>
                            <form method="POST" action="{{ route('user.profile.set_package_for_student', $user->id) }}"
                                  class="set_package_for_student_form">
                                @csrf
                                <input type="hidden" name="package_id" id="package_id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@section("page_css")

    <link rel="stylesheet" href="{{ asset("css/croppie.css") }}">

    <style>
        input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }
    </style>

@stop

@section('page_scripts')

    <script src="{{ asset('vendors/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset("js/file-upload.js") }}"></script>
    <script src="{{ asset("js/croppie.js") }}"></script>
    <script src="{{ asset("js/toastr.js") }}"></script>

    <script>
        let crop = $('#profile_image_crop').croppie({
            viewport: {
                width: 200,
                height: 200,
                type: 'circle'
            }
        });

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                console.log("§d.as§dasdas");
                reader.onload = function (e) {
                    $('#profile_image_crop').croppie('bind', {
                        url: e.target.result
                    });
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#imageUp').on('change', function () {
            readFile(this);
        });

        $('.set_package').click(function () {
            let pid = $(this).data('pid');
            $('#package_id').val(pid);
            console.log($('#set_package_for_student_form').length);
            $('.set_package_for_student_form').submit();
        });


        $("#datepicker-popup").datepicker({
            @if( $profile->birthday )
            startDate: '{{ \Carbon\Carbon::createFromFormat("Y-m-d",$profile->birthday)->format("d/m/Y") }}',
            @else
            startView: 'decade',
            @endif
            format: "dd/mm/yyyy",
            autoclose: true,
            enableOnReadonly: true,
            todayHighlight: true,
        });


        tinymce.init({
            selector: "#bio",
            height: 300,
            theme: "modern",
            plugins: window.mobilecheck() ? [] : [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
            ],
        });

        $('#profile_image_crop_save').click(function () {
            $("#upload_progress_info").show(function () {
                $(this).animate(500);
            });
            crop.croppie('result', {
                type: "base64",
                size: {width: 800},
            }).then(function (resp) {
                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "save_profile_image_croppie",
                        imageBase64: resp,
                        user_id: {{ $profile->user_id }}//
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        console.log(response);
                        $("#upload_progress_info").hide(function () {
                            $(this).animate(500);
                        });
                        $.toast({
                            heading: '{{ __('messages.upload_ok') }}',
                            text: '{{ __('messages.upload_profile_picture_ok') }}',
                            position: 'bottom-right',
                            icon: 'success',
                            stack: false,
                            loaderBg: '#0eb543',
                            bgColor: '#b5ffaa',
                            textColor: 'black'
                        })
                    },
                    error: function (response) {
                        $("#upload_progress_info").hide(function () {
                            $(this).animate(500);
                        });
                        $.toast({
                            heading: '{{ __('messages.upload_nok') }}',
                            text: '{{ __('messages.upload_profile_picture_nok') }}',
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
        });


        @if(Auth::user()->hasRole('admin') and Auth::id() != $user->id and $user->hasRole('student'))

        $("#discount_eur").change(function () {
            let base = 22.9;
            let input = parseFloat($(this).val());
            let diff = base - input;

            /*console.log(input);
            console.log(diff);
            console.log(Math.floor((diff / base) * 100));*/
            $("#discount").val(Math.floor((diff / base) * 100))
        });
        $("#discount_c_eur").change(function () {
            let base = 9.9;
            let input = parseFloat($(this).val());
            let diff = base - input;

            /*console.log(input);
            console.log(diff);
            console.log(Math.floor((diff / base) * 100));*/
            $("#discount_c").val(Math.floor((diff / base) * 100))
        });

        @endif

        $(document).ready(function () {
            $(".hide_mobile").each(function () {
                $(this).hide();
            });
        })
    </script>
@stop
