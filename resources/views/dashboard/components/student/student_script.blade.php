@include('calendar.student.calendar_script')

<script>
    $(document).ready(function () {
        var data = {
            labels: [@foreach(array_reverse($student->chart_data['bar_data']) as $k => $v){!! $loop->first ? '"'.$k.'"': ', "'.$k.'"' !!}@endforeach],
            datasets: [{
                label: '# @lang('dashboard.chart_past_classes')',
                data: [@foreach(array_reverse($student->chart_data['bar_data']) as $k => $v){{$v.", "}}@endforeach],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1,
                fill: false
            }]
        };
        var options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            legend: {
                display: false
            },
            elements: {
                point: {
                    radius: 0
                }
            }

        };
        var doughnutPieData = {
            datasets: [{
                data: [@foreach($student->chart_data['pie_data'] as $k => $v)
                    {{ $v }},
                    @endforeach],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                @foreach($student->chart_data['pie_data'] as $k => $v)
                    "{{ $k }}",
                @endforeach
            ]
        };
        var doughnutPieOptions = {
            responsive: true,
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };

        // Get context with jQuery - using jQuery's .get() method.
        if ($("#barChart").length) {
            var barChartCanvas = $("#barChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: data,
                options: options
            });
        }

        if ($("#doughnutChart").length) {
            var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
            var doughnutChart = new Chart(doughnutChartCanvas, {
                type: 'doughnut',
                data: doughnutPieData,
                options: doughnutPieOptions
            });
        }


        $('#student_future_lectures_table').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 5,
            "language": dt_language,
            "searching": false,
            "lengthChange": false,
            "order": [[0, 'asc']],
            "columns": [
                {"visible": false},
                null,
                null,
                {"orderable": false}
            ]
        });

        $('#student_past_lectures_table').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 5,
            "language": dt_language,
            "searching": false,
            "lengthChange": false,
            "order": [[0, 'asc']],
            "columns": [
                {"visible": false},
                null,
                null,
                {"orderable": false}
            ]
        });

        @if(session()->has('package_type') and intval(session()->get('package_type')) == 99)
        setTimeout(() => {
            $('#starterLectureReservedModal').modal();
        }, 1000)
        @endif

        $("#redeem_coupon_btn").click(function () {

            let code = $("#code").val();

            if (code == '') {
                $("#redeem_hint").html("Políčko nesmie byť prázdne. Zadajte kód prosím.");
                return;
            }

            $.ajax({
                url: "{{ route("ajax_int") }}",
                method: "POST",
                data: {
                    action: "redeem_coupon",
                    code: code,
                    user: {{ Auth::id() }}
                },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    if (response.status == 'ERR') {
                        $("#redeem_hint").html(response.message);
                    } else if (response.status == 'OK') {
                        let comm = 'Poukážka použitá! ';
                        if (response.comment != '') {
                            comm = comm + response.comment;
                        }
                        $.toast({
                            text: comm, // Text that is to be shown in the toast
                            heading: 'Úspech', // Optional heading to be shown on the toast
                            icon: 'success', // Type of toast icon
                            showHideTransition: 'fade', // fade, slide or plain
                            allowToastClose: false, // Boolean value true or false
                            hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                            stack: 1, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                            position: 'bottom-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values

                            textAlign: 'right',  // Text alignment i.e. left, right or center
                            loader: true,  // Whether to show loader or not. True by default
                            loaderBg: '#9EC600',  // Background color of the toast loader
                            afterHidden: function () {

                                location.reload();
                            }  // will be triggered after the toast has been hidden
                        });
                    }

                },
                error:
                    function (response) {
                        $("#redeem_hint").html("UNKNWN ERR");
                    }
            })

        });

    })
</script>
