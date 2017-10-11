<?php
if (!empty($users)) {
    foreach ($users as $user) {
        ?>
        <div class="friends_list">
            <div class="media">
                <a class="pull-left" href="<?php echo Common::getUserProfileLink($user['User']['username'], true); ?>"> 
                    <?php echo __(Common::getUserThumb($user['User']['id'], $user['User']['type'], 'small')); ?>  
                </a>
                <div class="media-body">
                    <div class="pull-left">
                        <h5 >
                            <a class="owner" href="<?php echo Common::getUserProfileLink( $user['User']['username'], TRUE); ?>">
                                <?php echo __(h($user['User']['username'])); ?>
                            </a>
                        </h5>
                        <?php if ($user['disease'] != '') { ?>
                        <span class="disease_list_user_row" title="<?php echo __(h($user['disease'])); ?>"><?php echo __(h($user['disease'])); ?></span>
                        <?php } ?>
                        <span><?php echo __(h($user['City']['description']) . ", "); ?></span>
                        <span><?php echo __(h($user['State']['description']) . ", "); ?></span>
                        <span><?php echo __(h($user['Country']['short_name'])); ?></span>
                    </div>
                    <?php
                    switch ($user['status']) {
                        case 0 :
                            ?>                      <button id="add_button_<?php echo $user['User']['id']; ?>" type="button" class="btn btn_normal pull-right ladda-button"
                                    data-style="expand-right"
                                    data-spinner-color="#3581ED"
                                    onclick="addFriend('<?php echo $user['User']['id']; ?>', true)"> 
                                <span class="ladda-spinner"></span><?php echo __('Add Friend') ?>
                            </button>
                            <?php
                            break;
                        case 1 :
                            ?>
                            <button class="btn btn_normal pull-right disabled"
                                    data-style="slide-right" disabled="disabled">
                                        <?php echo __('Waiting For Approval') ?>
                            </button>
                            <?php
                            break;
                        case 2 :
                            ?>
                        <button data-toggle="modal" data-target="#composeMessage" data-username = "<?php echo __(h($user['User']['username'])); ?>" data-user-id ="<?php echo $user['User']['id']; ?>" data-backdrop="static" data-keyboard="true" class="btn message_button btn_normal pull-right ladda-button" data-style="slide-right">
                                        <span class="ladda-spinner"></span><?php echo __('Send Message') ?>				                                    
                        </button>                           
                            <?php
                            break;
                        case 3 :
                            ?>			                                    	
                            <button id="reject_button_<?php echo $user['User']['id']; ?>" type="button" class="btn btn_normal pull-right ladda-button"
                                    data-style="expand-right"
                                    data-spinner-color="#3581ED"
                                    onclick="rejectFriend('<?php echo $user['User']['id']; ?>', true)">
                                <span class="ladda-spinner"></span><?php echo __('Decline') ?>
                            </button>
                            <button id="accept_button_<?php echo $user['User']['id']; ?>" type="button" class="btn btn_active pull-right ladda-button"
                                    data-style="expand-right"
                                    data-spinner-color="#3581ED"
                                    onclick="approveFriend('<?php echo $user['User']['id']; ?>', true)">
                                <span class="ladda-spinner"></span><?php echo __('Confirm') ?>
                            </button>
                            <?php
                            break;
                    }
                    ?>                                    
                </div>
            </div></div>
			<?php 
		}
	}else{ 
		if(isset($pending_invitations)){
 			?> 			
 			<div class="alert alert-warning">
                            <?php echo __('There are no pending invitations.');?>
 			</div>
			<?php 
		}else if(isset($search_people)){
		?>
			<div class="friends_list">
				<div class="text-center friends_noresult_padding">
					<?php echo __('Sorry, no results containing all your search terms were found.');?>
				</div>
			</div>
		<?php 
		}else if(isset($search_people_advanced)){
		?>
			<div class="friends_list">
				<div class="text-center friends_noresult_padding">
					<?php echo __('Sorry, no results containing all your search terms were found.');?>
				</div>
			</div>
		<?php 
		}
	}
?>
