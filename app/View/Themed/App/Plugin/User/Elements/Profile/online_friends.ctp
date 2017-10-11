<?php
if (!empty($onlineFriends)) {
    foreach ($onlineFriends as $friend) {        
        ?>
        <div class="event col-xs-12" <?php if ( $friend['is_online'] ) { ?> data-id="<?php echo $friend['friend_id'] ?>" <?php } ?>>
            <div class="pull-left">
				<a class="pull-left" href="<?php echo Common::getUserProfileLink($friend['friend_name'], true); ?>" data-hovercard="<?php echo $friend['friend_name'];?>">
					<?php echo Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'x_small', 'user_x_small_thumb media-object'); ?>
				</a>
			</div>
            <div class="indvdl_list name_details pull-left">
                <a href="<?php echo Common::getUserProfileLink($friend['friend_name'], TRUE); ?>" 
                   class="owner online_friend_tag" data-hovercard="<?php echo $friend['friend_name'];?>">
                       <?php echo $friend['friend_name'];  ?>
                </a>
                <div class="user_disease_list" title="<?php  echo h($friend['diseases']); ?>">
                    <?php echo h($friend['diseases']); ?>
                </div>
            </div>
            <!--<div class="online_status pull-left <?php // if ( $friend['is_online'] ) { echo "online"; } ?>"></div>-->
            <?php
            $onlineStatusArray = array('away','available');
            if ( $friend['is_online'] && in_array($friend['online_status'], $onlineStatusArray)) { 
                switch ($friend['online_status']) {
                    case 'available':
                        $onlineStatusClass = 'online';
                        break;
                    case 'away':
                        $onlineStatusClass = 'away';
                        break;
                }
            } else {
                $onlineStatusClass = '';
            }?>
            <div class="online_status pull-left <?php echo $onlineStatusClass; ?>"></div>
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