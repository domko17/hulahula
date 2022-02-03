<div class="modal fade" id="prolongCourseModal" tabindex="-1" role="dialog"
     aria-labelledby="prolongCourseModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('lectures.collective_courses.prolong') }}" method="POST">
                @csrf
                <input type="hidden" name="collective_hour_id" id="collective_hour_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="prolongCourseModalLabel">**Predĺžiť kurz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-12">
                            <input type="number" class="form-control" name="count" id="count" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i
                            class="fa fa-send"></i> @lang('general.confirm')
                    </button>
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">@lang('general.Cancel')</button>
                </div>
            </form>
        </div>
    </div>
</div>
