<?php $userRoleBg = strtolower($user_details['user_role'])."_profile"; ?>
<div id="profile-hovercard" class="<?php // echo $userRoleBg; ?>">    
    <div class="media">
        <a class="pull-left" href="<?php echo $user_details['profile_url']; ?>">
            <img class="default_pic" />
            <?php echo $user_details['img']; ?>
        </a>
        <div class="media-body">
            <div class="cover-username">
                <a href="<?php echo $user_details['profile_url']; ?>">
                    <?php
                    if (!$is_same) {
                        echo h($user_details['username']);
                    } else {
                        echo h($user_details['username']) . ' (You)';
                    }
                    ?>
                </a>
            </div>
            <div style="padding: 5px 0px;" class="clearfix">
                <div class="pull-left <?php echo $userRoleBg; ?>_role"><?php echo h($user_details['user_role']); ?></div>
                <div style="margin:0px 0px 0px 10px; display: inline-block; " class="pull-left feeling_condition <?php echo $user_details['feeling']; ?>"></div>
            </div>
            <div class="block"> 
                <img src="/theme/App/img/profile_location.png" alt="...">
                <span><?php echo $user_details['location']; ?></span>
            </div>
            
        </div>
        

    </div>
    <div class="clearfix" style="padding:0px 10px;">
        <?php if(!empty($user_details['about_me'])) { ?>        
            <p><strong>About:</strong> <?php echo h($user_details['about_me']); ?></p>   
        <?php } ?>        
        <?php if(!empty($user_details['disease'])) { ?>
                <p><strong>Diagnosis:</strong> <?php echo h($user_details['disease']); ?></p>
        <?php } ?>
        <?php if(!empty($user_details['treatment'])) { ?>
                <p><strong>Medication:</strong> <?php echo h($user_details['treatment']); ?></p>
        <?php } ?>

    </div>    
    <?php if (!$is_same) {
        ?>
        <div class="actions">
            <?php
            switch ($friend_status) {
                case MyFriends::STATUS_CONFIRMED :
                    ?>
                    <button id="remove_hovercard_button_<?php echo $user_details['id']; ?>" type="button" 
                            class="uibutton confirm btn btn_add pull-left ladda-button"
                            data-friend_id="<?php echo $user_details['id']; ?>"
                            data-style="expand-right"
                            data-spinner-color="#3581ED"
                            onclick="hovercardRemoveFriend(<?php echo $user_details['id']; ?>, true)">
                                <?php echo __('Remove friend'); ?>
                    </button>
                    <?php
                    break;
                case 0:
                    ?>
                    <button id="add_button_<?php echo $user_details['id']; ?>" type="button"
                            class="uibutton confirm btn btn_add pull-left ladda-button"
                            data-style="expand-right"
                            data-spinner-color="#3581ED"
                            onclick="addFriend('<?php echo $user_details['id']; ?>', true)">
                                <?php echo __('Add as friend'); ?>
                    </button>
                    <?php
                    break;
                case MyFriends::STATUS_REQUEST_SENT:
                    ?>
                    <button type="button" class="uibutton confirm btn btn_add pull-left" disabled>
                        <?php echo __('Awaiting approval'); ?>
                    </button>    
                    <?php
                    break;
                case MyFriends::STATUS_REQUEST_RECIEVED
                    ?>
                    <button type="button" class="uibutton confirm btn btn_add pull-left" disabled>
                        <?php echo __('Awaiting your response'); ?>
                    </button>
                <?php
            }
                ?>
                <button data-toggle="modal" data-target="#composeMessage" data-username = "<?php echo h($user_details['username']); ?>" data-user-id ="<?php echo $user_details['id']; ?>" data-backdrop="static" data-keyboard="true" class="btn message_button btn_more pull-right ladda-button" data-style="slide-right">
                    <span class="ladda-spinner"></span>Message				                                    
                </button>
            </div>
            <?php
    }
    ?>
</div>
<script>
$('.profile_hovercard .media a img:last').load(function(){
  // hide/remove the loading image
  $('.default_pic').hide();
});
</script>