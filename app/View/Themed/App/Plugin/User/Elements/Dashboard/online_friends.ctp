<div id="online_friends_list">
    <?php if (isset($onlineFriends) && $onlineFriends != NULL) { ?>
        <?php foreach ($onlineFriends as $friend) { ?>
            <div class="media friends_list_dashboard" id="<?php echo $friend['friend_id']; ?>" <?php if ( $friend['is_online'] ) { ?> data-id="<?php echo $friend['friend_id'] ?>" <?php } ?>>
                <a class="pull-left" href="<?php echo Common::getUserProfileLink($friend['friend_name'], true); ?>">
                    <?php echo Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'x_small', 'user_x_small_thumb media-object'); ?>                    
                </a>
                <div class="media-body">
                    <h5 class="online_friends_username pull-left">
                        <?php 
                        echo Common::getUserProfileLink($friend['friend_name'], FALSE, 'pull-left'); 
                        //echo $friend['friend_name'];
                        ?>
                    </h5>
                    <!--<div class="online_status pull-right <?php // if ( $friend['is_online'] ) { echo "online"; } ?>"></div>-->
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
            </div>
            <?php
        }
    } else {
        ?>
        <div class="media friends_list_dashboard" id="no_result_found">
            <div class="media-body">
                <h5>
                    <?php echo 'No friends found'; ?>
                </h5>
            </div>
        </div>
        <?php
    }
    ?>
</div>
