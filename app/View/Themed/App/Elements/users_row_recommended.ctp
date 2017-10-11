<div id="recommendedFriendList" class="event_wraper">
	<div class="friends_list recommended_friends_list">
		<div class="media">
			<a class="pull-left" href="<?php echo Common::getUserProfileLink($newuser['User']['username'], TRUE); ?>"> 
				<?php echo __(Common::getUserThumb($newuser['User']['id'], $newuser['User']['type'], 'small')); ?>  
			</a>        
			<div class="media-body">
				<div class="pull-left">
					<h5>
						<a class="owner" href="<?php echo Common::getUserProfileLink($newuser['User']['username'], TRUE); ?>">
							<?php echo __(h($newuser['User']['username'])); ?>
						</a>
					</h5>
					<span><?php echo __(h($newuser['City']['description']) . ", "); ?></span>
					<span><?php echo __(h($newuser['State']['description']) . ", "); ?></span>
					<span><?php echo __(h($newuser['Country']['short_name'])); ?></span>
				</div>
				<?php
				switch ($newuser['status']) {
					case 0 :
						?>    
						<button id="add_button_<?php echo $newuser['User']['id']; ?>" type="button" class="btn btn_normal pull-right ladda-button"
								data-style="expand-right"
								data-spinner-color="#3581ED"
								onclick="addFriend('<?php echo $newuser['User']['id']; ?>', true)"> 
							<span class="ladda-spinner"></span><?php echo __('Add Friend') ?>
						</button>
						<?php
						break;
					case 1 :
						?>
						<button class="btn btn_normal pull-right disabled"
								data-style="slide-right">
									<?php echo __('Waiting For Approval') ?>
						</button>
						<?php
						break;
				}
				?>

			</div>
		</div>
	</div>
</div>