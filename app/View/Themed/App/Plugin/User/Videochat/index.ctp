<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Video Chat');
?>
<?php $this->extend('Profile/view'); ?>
<h2>Video Chat</h2>
<div id="online_video_friends_list">
<?php
$no_users = TRUE;
if (!empty($onlineFriends)) {
    foreach ($onlineFriends as $friend) {
        if ( $friend['is_online']) {
            $no_users = FALSE;
        ?>

        <div class="event col-lg-12 video-chat-user" <?php if ( $friend['is_online'] ) { ?> data-id="<?php echo $friend['friend_id'] ?>" <?php } ?>>
            <div class="pull-left">
                <?php echo Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'x_small', 'user_x_small_thumb media-object'); ?>
            </div>
            <div class="indvdl_list name_details pull-left">
                <a href="javascript:void(0)<?php //echo Common::getUserProfileLink($friend['friend_name'], TRUE); ?>" 
                   data-hovercard="<?php echo $friend['friend_name']; ?>" class="owner online_friend_tag" title="<?php echo __(h($friend['friend_name'])); ?>">
                       <?php echo __(h($friend['friend_name'])); ?>
                </a>
                <div class="user_disease_list" title="<?php  echo $friend['diseases']; ?>">
                    <?php echo $friend['diseases']; ?>
                </div>
            </div>
            <div class="online_status pull-left <?php if ( $friend['is_online'] ) { echo "online"; } ?>"></div>
        </div>
        <?php
        }
    }
} 

if ($no_users) {
    ?>
    <div class="event col-lg-12">
        <div class="indvdl_list name_details pull-left">
            <?php echo __('No Online Members Found.') ?>
        </div>
    </div>
    <?php
}
?>
</div>