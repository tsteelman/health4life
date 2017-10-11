<?php
if (!empty($notification['content']['additional_info'])):
	$additionalInfo = $notification['content']['additional_info'];
	$activityType = $notification['content']['activity_type'];
	?>
	<form class="additional_info_frm">
		<input type="hidden" class="activity_type" value="<?php echo $activityType; ?>" />
		<?php foreach ($additionalInfo as $field => $value): ?>
			<input type="hidden" name="<?php echo $field; ?>" value="<?php echo $value; ?>" />
		<?php endforeach; ?>
	</form>
<?php endif; ?>