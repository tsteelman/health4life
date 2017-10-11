
<!-- Modal -->
<div class="modal fade inviteFriend" id="inviteFriends" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close invite_close_button" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" style="color:#fff">Invite Friends</h4>
            </div>
            <div class="modal-body">
                <div class="import_contact_step_1">
                    <div class="form-group">
                        <form id="invite_friends_form_calendar">
                            <?php echo $this->element('invite_friends'); ?>
                        </form>
                    </div>
                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Invitation has been sent
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e1e1e1 !important;">
                <button id="close_invite_button" type="button" class="cancel_modal_btn btn btn-default invite_close_button calendar_invite_close" >Close</button>
                <!--<button id="invite_button" type="button" class="btn btn-primary ladda-button" data-style="expand-right" onclick = "inviteFriends(<?php // echo $community_id . ',' . $current_user . ',2';  ?>);" disabled><span class="ladda-label">Invite</span><span class="ladda-spinner"></span></button>-->
                <button id="invite_button" type="button" class="btn btn-primary invite_okay_btn_calendar" disabled>Ok</button>
            <!--invite_okay_btn_calendar-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->