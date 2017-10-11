<div class="col-lg-6 col-md-6 role_selection">
	<h2><?php echo __('Choose Your Role First'); ?></h2>
	<?php echo $this->element('User.Register/roles'); ?>
	<div class="signup_video" data-video="<?php echo $videoUrl; ?>">
		<img src="/theme/App/img/tmp/video_default.png">
	</div>
	<?php echo $this->element('User.Register/map'); ?>
	<div class="followus_div">
		<h3><?php echo __('Sign up and follow us on'); ?></h3>
		<ul>
			<li><a class="follow_fb" href="<?php echo Configure::read('App.fbLink'); ?>" target="_blank"></a></li>
			<li><a class="follow_twt" href="<?php echo Configure::read('App.twitterLink'); ?>" target="_blank"></a></li>
			<li><a class="follow_g" href="<?php echo Configure::read('App.googleLink'); ?>" target="_blank"></a></li>
			<li><a class="follow_lin" href="<?php echo Configure::read('App.linkedInLink'); ?>" target="_blank"></a></li>
			<li><a class="follow_youtube" href="<?php echo Configure::read('App.youtubeLink'); ?>" target="_blank"></a></li>
		</ul>
	</div>
</div>