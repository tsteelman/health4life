<?php
$pendingCount = 0;
if (isset($pendingUsersDetails) && $pendingUsersDetails != NULL) {
    $pendingCount = count($pendingUsersDetails);
}
$pendingUsersCounter = 0;
?>
<li class="notfctn_header keep_open pending_rquests_notification_header" >
    <?php echo __("Friends Invitation"); ?>
    <img src="/theme/App//img/notifictn_arrow.png" class="notfctn_arrow">
    <?php if ($pendingCount > 3) { ?>
        <a class="pull-right more notification_more" href="/search?type=people" ><?php echo __("more"); ?></a>
    <?php } ?>
</li>
<?php
if (isset($pendingUsersDetails) && $pendingUsersDetails != NULL) {
    foreach ($pendingUsersDetails as $userDetails) {
        $pendingUsersCounter++;
        $pending_user_li_classes = 'keep_open pending_rquests_notification';
        if ($pendingUsersCounter > 3) {
            $pending_user_li_classes = $pending_user_li_classes . ' hidden';
        }
        $user_thumb = Common::getUserThumb($userDetails['user']['user_id'], $userDetails['user']['type'], 'x_small', 'media-object');
        ?>
        <li class="<?php echo $pending_user_li_classes; ?>">
            <div class="media keep_open">
                <a class="pull-left keep_open" href="<?php echo Common::getUserProfileLink( $userDetails['user']['user_name'], TRUE); ?>" data-hovercard="<?php echo $userDetails['user']['user_name']; ?>">
                    <?php echo $user_thumb; ?>                    
                </a>
                <div class="media-body keep_open">
                    <div class="pull-left not_descrptn">
                        <h5 class="keep_open username_in_notification">
                            <?php echo Common::getUserProfileLink($userDetails['user']['user_name'], FALSE, 'pull-left keep_open', TRUE); ?>
                        </h5>
                        <span class="keep_open user_diseses_in_notification notification_content" title="<?php echo h($userDetails['diseases']); ?>"><?php echo h($userDetails['diseases']); ?></span></div>
                    <div class="pull-right confrm_btns keep_open">
                        <button id="notification_reject_button_<?php echo $userDetails['user']['user_id']; ?>" type="button" class="keep_open btn btn_normal pull-right ladda-button"
                                data-style="slide-left"
                                data-spinner-color="#3581ED"
                                onclick="rejectFriend('<?php echo $userDetails['user']['user_id']; ?>', true, true)">
                            <span class="ladda-spinner"></span><?php echo __('Decline') ?>
                        </button>
                        <button id="notification_accept_button_<?php echo $userDetails['user']['user_id']; ?>" type="button" class="keep_open btn btn_active pull-right ladda-button"
                                data-style="slide-left"
                                data-spinner-color="#3581ED"
                                onclick="approveFriend('<?php echo $userDetails['user']['user_id']; ?>', true, true)">
                            <span class="ladda-spinner"></span><?php echo __('Confirm') ?>
                        </button>
                    </div>
                </div>
            </div>
        </li>

        <?php
    }
} else {
    ?>
    <div class="col-lg-12 keep_open pending_rquests_notification_header no_pending_rquests_message">
        <?php
        echo __('No pending friends Invitation.');
        ?>
    </div>
    <?php
}