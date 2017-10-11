<div class="report_abuse_actions">
	<br clear="all" />
	<div class="text-center"><?php echo __('Further Actions'); ?></div>
	<br clear="all" />
<!--	<div>
		<?php $actionId1 = "block_{$type}_anonymous_messaging_action"; ?>
		<input type="radio" value="block_anonymous_messaging" name="data[action]" id="<?php echo $actionId1; ?>" />
		<label for="<?php echo $actionId1; ?>"><?php echo __('I would like to block this user from posting anonymous messages to my message board'); ?></label>
	</div>-->
	<div>
		<?php $actionId2 = "block_{$type}_messaging_action"; ?>
		<input type="checkbox" value="block_messaging" name="data[action]" id="<?php echo $actionId2; ?>" />
		<label for="<?php echo $actionId2; ?>"><?php echo __('I would like to block this user from posting any messages to my message board'); ?></label>
	</div>
</div> 