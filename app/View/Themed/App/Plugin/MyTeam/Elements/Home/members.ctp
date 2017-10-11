<div class="team_members">
	<div class="page-header">
		<h3><?php echo __('Members'); ?></h3>
	</div>
	<?php foreach ($members as $member) : ?>
		<div class="media">
			<a href="<?php echo $member['profileUrl']; ?>" class="pull-left">
				<?php echo $member['photo']; ?>
			</a>
			<div class="media-body">
				<h5 class="owner"><?php echo Common::getUserProfileLink($member['username'], FALSE); ?></h5>
				<p class="<?php echo $member['roleClass']; ?>"><?php echo $member['roleName']; ?></p>
			</div>
		</div>
	<?php endforeach; ?>	
</div>