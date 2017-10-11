<a class="pull-left posted_user_thumb <?php echo $postedUserThumbCursorClass; ?>">
	<?php echo $postedUserThumb; ?>
	<?php if (!empty($postedUserSmileyClass)): ?>
		<span class="pull-right feeling_condition <?php echo $postedUserSmileyClass; ?>" title="<?php echo $postedUserHealthStatus; ?>"></span>
	<?php endif; ?>
</a>