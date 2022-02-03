<div class="modal fade" id="makePaymentModal" tabindex="-1" role="dialog"
     aria-labelledby="makePaymentModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="makePaymentModalLabel">**Potvrdenie akcie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-12">
                        <form action="{{ route("admin.teachers.make_payment") }}" method="POST" id="formSignPyment">
                            @csrf

                            <input type="hidden" name="teacher_id" id="teacher_id" value="">

                            <div class="row form-group">
                                <div class="col-10 offset-1">
                                    <p>Pre potvrdenie zadajte svoje heslo</p>
                                </div>
                                <div class="col-10 offset-1">
                                    <input type="password" required
                                           class="form-control" name="confirm_password" id="pass_conf">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" form="formSignPyment" id="confirm_btn" class="btn btn-success"><i
                        class="fa fa-chevron-right"></i> Potvrdzujem
                </button>
                <button type="button" class="btn btn-light"
                        data-dismiss="modal">@lang('general.Cancel')</button>
            </div>
        </div>
    </div>
</div>
