<?php
echo $this->AssetCompress->css('jquery.bxslider');
?>

<div class="col-lg-12 my_photos">

	<span class="setting pull-right"></span>

	<?php echo $this->element('Dashboard/Photos/image_settings'); ?>

	<div id="dashboard_image_container">
		<img src="<?php echo $defaultPhoto; ?>" />
	</div>

	<?php echo $this->element('Dashboard/Photos/slideshow'); ?>

	<div id="chat_notification_container" style="display: none;"></div>

</div>