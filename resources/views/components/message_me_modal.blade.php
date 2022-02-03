<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog"
     aria-labelledby="sendMessageModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="sendMessageModalLabel">@lang('chat.send_a_message_modal_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body py-0">
                <p class="message_me_add_name">{{ __('chat.send_a_message_modal_help') }}</p>
                <div class="row form-group">
                    <input type="hidden" name="to_who" id="to_who" value="">
                    <div class="col-12">
                        <textarea class="form-control" id="message_to_send" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="send_msg_btn" class="btn btn-success"><i
                        class="fa fa-send"></i> @lang('general.send')
                </button>
                <button type="button" class="btn btn-light"
                        data-dismiss="modal">@lang('general.Cancel')</button>
            </div>
        </div>
    </div>
</div>
