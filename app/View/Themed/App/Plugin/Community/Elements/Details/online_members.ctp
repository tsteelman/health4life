<?php
if (!empty($onlineMembers)) {
    foreach ($onlineMembers as $member) {        
        ?>
        <div class="event col-xs-12" <?php if ( $member['is_online'] ) { ?> data-id="<?php echo $member['friend_id'] ?>" <?php } ?>>
            <div class="pull-left">
				<a class="pull-left" href="<?php echo Common::getUserProfileLink($member['friend_name'], true); ?>">
					<?php echo Common::getUserThumb($member['friend_id'], $member['friend_type'], 'x_small', 'user_x_small_thumb media-object'); ?>
				</a>
			</div>
            <div class="indvdl_list name_details pull-left">
                <a href="<?php echo Common::getUserProfileLink($member['friend_name'], TRUE); ?>" 
                   class="owner online_friend_tag" title="<?php echo __(h($member['friend_name'])); ?>">
                       <?php echo $member['friend_name'];  ?>
                </a>
                <div class="user_disease_list" title="<?php  echo h($member['diseases']); ?>">
                    <?php echo h($member['diseases']); ?>
                </div>
            </div>
            <!--<div class="online_status pull-left <?php // if ( $member['is_online'] ) { echo "online"; } ?>"></div>-->
              <?php
            $onlineStatusArray = array('away','available');
            
            if ( isset($member['is_online']) && in_array($member['online_status'], $onlineStatusArray)) { 
                switch ($member['online_status']) {
                    case 'available':
                        $onlineStatusClass = 'online';
                        break;
                    case 'away':
                        $onlineStatusClass = 'away';
                        break;
                }
            } else {
                $onlineStatusClass = '';
            } ?>
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