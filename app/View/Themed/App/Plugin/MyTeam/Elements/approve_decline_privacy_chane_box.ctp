<?php if ( isset( $isPendingPrivacyApproval )) : ?>
    <div class="approval_container">
        <h4 class="pull-left"><?php echo __('%s wants to make this group public',$privacyRequester); ?></h4>
        <div class="pull-right">
            <button type="button" class="btn btn_active ladda-button approve_public_privacy" data-style="slide-right"	data-team_id="<?php echo $team['id']; ?>">
                    <?php echo __('Approve'); ?>
            </button>
            <button type="button" class="btn btn_normal ladda-button decline_public_privacy" data-style="slide-right"	data-team_id="<?php echo $team['id']; ?>">
                    <?php echo __('Decline'); ?>
            </button>           
        </div>
    </div>
<?php endif; ?>