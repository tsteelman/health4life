<?php
if (!empty($usersWithDisease)) {
    foreach ($usersWithDisease as $member) {
        ?>
        <div class="event col-xs-12">
            <div class="pull-left">
                <?php echo Common::getUserThumb($member['friend_id'], $member['friend_type'], 'x_small'); ?>
            </div>
            <div class="indvdl_list name_details pull-left">
                <a href="<?php echo Common::getUserProfileLink($member['friend_name'], TRUE); ?>" 
                   <?php if ($loggedIn) { ?>
                   data-hovercard="<?php echo $member['friend_name']; ?>"
                   <?php } ?>
                   class="owner" title="<?php echo __(h($member['friend_name'])); ?>">
                       <?php echo __(h($member['friend_name'])); ?>
                </a>
                <div class="user_disease_list" title="<?php echo h($member['diseases']); ?>">
                    <?php echo h($member['diseases']); ?>
                </div>
                
            </div>
            <div class="online_status pull-left <?php if ( $member['is_online'] ) { echo "online"; } ?>"></div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="event col-xs-12">
        <div class="indvdl_list name_details pull-left">
            <?php echo __('No Members Found.') ?>
        </div>
    </div>
    <?php
}
?>