<div class="row">
    <div class="col-lg-12 approval_info">    
<div class="alert alert-info">
    <div class="message">
        <div class="media">
            <a class="pull-left" href="#"> 
                <?php echo Common::getUserThumb($invitedUserId, $invitedUserType, 'x_small'); ?> 
            </a>
            <div class="media-body">
                <div class="pull-left">
                    <h5 class="owner pull-left"><?php echo $invitedUserName; ?></h5>
                    <span class="pull-left" style="line-height: 35px;">&nbsp;<?php echo __('has invited you to the community'); ?></span>
                </div>
                <form>
                    <?php
                    echo $this->Form->hidden('community_id', array('value' => $community['Community']['id']));
                    ?>
                    <button id="reject_invitation_btn" type="button" data-style="expand-right" data-spinner-color="#3581ED" class="group-member-approve-reject-btn btn btn_normal pull-right ladda-button">
                        <span class="ladda-label"><?php echo __('Reject'); ?></span>
                        <span class="ladda-spinner"></span>
                    </button>
                    <button id="approve_invitation_btn" type="button" data-style="expand-right" data-spinner-color="#3581ED" class="group-member-approve-reject-btn btn btn_active pull-right ladda-button">
                        <span class="ladda-label"><?php echo __('Approve'); ?></span>
                        <span class="ladda-spinner"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>