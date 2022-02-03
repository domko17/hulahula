<script>
    $(document).ready(function () {
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
            "order": [[0, 'desc']],
            "columns": [
                {"visible": false},
                null,
                null,
                {"orderable": false}
            ]
        });
    })
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('teacher')
 or \Illuminate\Support\Facades\Auth::user()->hasRole('admin'))

    $(function () {
        $('#evaluateStudentLangOpen').click(function () {
            const lang_id = $(this).data('language');
            const current_eval = $(this).data('current');
            $('#evaluate_lang_id').val(lang_id);
            if (current_eval){
                $('#level_lang option').each(function() {
                    if($(this).val() == current_eval)
                        $(this).prop('selected', true)
                })
            }
            $('#evaluateStudentLangModal').modal();
        })
    })

    @endif


</script>
