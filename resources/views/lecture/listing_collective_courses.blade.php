@extends('layouts.app')

@section('title')

@stop

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
                    <a href="{{ route('lectures.index') }}" class="text-primary">
                        @lang('side_menu.Lections')
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    @lang('lecture.collective_courses')
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin px-0 stretch-card">
            <div class="card">
                <div class="card-body p-2 p-md-4">
                    <div class="col-12 col-md-8 order-2 order-md-1">
                        <h4 class="card-title">@lang('lecture.collective_courses')</h4>
                    </div>
                    <div class="col-12 col-md-4 text-right order-1 order-md-2">
                        <button type="button" data-toggle="modal" data-target="#createCollectiveModal"
                                class="btn btn-gradient-success btn-sm btn-block">
                            <i class="fa fa-plus"></i> @lang('lecture.create_collective')
                        </button>
                    </div>

                    @include('lecture.components.tables_collectice_courses')
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    @include('lecture.components.modals')

@stop

@section('page_css')

@stop

@section('page_scripts')
    <script>

        $(document).ready(function () {

            if(window.mobilecheck()){
                $("#table_collective_courses_mobile").show();
            }else{
                $("#table_collective_courses_pc").show();
            }

            $('.delete-alert').click(function (e) {
                var id = $(this).attr("data-item-id");
                console.log(id);
                swal({
                    title: "Prosím podvtďte akciu",
                    text: "Akcia: zmazanie nastavenia skupinového kurzu.",
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

            $(".prolong_modal_open_btn").click(function () {
                let id = $(this).data('id');
                $("#collective_hour_id").val(id);
            })

            $("#language").change(function () {
                let val = $("#language option:selected").val();
                $.ajax({
                    url: "{{ route("ajax_int") }}",
                    method: "POST",
                    data: {
                        action: "get_language_teachers",
                        language_id: val
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        $(".teacher_select").each(function () {
                            $(this).show(function () {
                                $(this).animate()
                            })
                        });

                        $("#teacher_select").empty();
                        $("#sub_teacher_select").empty();
                        $("#teacher_select").append($("<option></option>").attr("value", 0).html("..."));
                        $("#sub_teacher_select").append($("<option></option>").attr("value", 0).html("..."));
                        for (var data in response.data) {
//console.log(response.data[data]);
                            $("#teacher_select").append($("<option></option>").attr("value", response.data[data].id).html(response.data[data].name));
                            $("#sub_teacher_select").append($("<option></option>").attr("value", response.data[data].id).html(response.data[data].name));
                        }
                        ;
                    }
                })
            })
        })

    </script>
@stop
