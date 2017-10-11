
<?php if ($loggedIn): ?>
    <!-- Logged in header -->
    <?php // echo $this->element('layout/play_login_audio'); ?>
	<?php echo $this->element('layout/play_notification_music'); ?>
    <?php echo $this->element('layout/logged_in_header'); ?>
<?php else: ?>
    <!-- Logged Out header -->
    <?php echo $this->element('layout/logged_out_header'); ?>
<?php endif; ?>