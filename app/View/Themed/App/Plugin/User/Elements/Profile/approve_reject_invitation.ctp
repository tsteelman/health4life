<div class="col-lg-12 approval_info">
<div class="alert alert-info">
    <div class="message">
        <div class="media">
            <a class="pull-left" href="#"> 
                <?php echo Common::getUserThumb($user_details['id'], $user_details['type'], 'x_small'); ?> 
            </a>
            <div class="media-body">
                <div class="pull-left">
                    <h5 class="owner pull-left"><?php echo h($user_details['username']); ?></h5>
                    <span class="pull-left" style="line-height: 35px;">&nbsp;<?php echo __('would like to add you as a friend'); ?></span>
                </div>
                <form>
                    <button id="reject_button_<?php echo $user_details['id'];?>"
                            type="button" data-style="expand-right"
                            data-spinner-color="#3581ED" 
                            class="group-member-approve-reject-btn 
                                btn btn_normal pull-right ladda-button"
                            onclick="rejectFriend('<?php echo $user_details['id'];?>', false)">
                        <span class="ladda-label"><?php echo __('Reject'); ?></span>
                        <span class="ladda-spinner"></span>
                    </button>
                    <button id="accept_button_<?php echo $user_details['id'];?>" 
                            type="button" data-style="expand-right" 
                            data-spinner-color="#3581ED"
                            class="group-member-approve-reject-btn 
                                btn btn_active pull-right ladda-button"
                            onclick="approveFriend('<?php echo $user_details['id'];?>', false)">
                        <span class="ladda-label"><?php echo __('Approve'); ?></span>
                        <span class="ladda-spinner"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
