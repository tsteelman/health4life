<div id="settings_container" class="hide">
	<?php
	echo $this->Form->create('User', array(
		'id' => 'user_dashboard_images_form',
		'enctype' => 'multipart/form-data',
	));
	echo $this->Form->hidden('default_photo_id');
	echo $this->Form->hidden('default_photo');
	?>
	<div id="hidden_images_container"></div>
	<div id="deleted_photos_container"></div>

	<div class="row slideshow_enable">
		<div class="col-lg-7" style="margin-left: 12px;">
			<?php
			echo $this->Form->input('is_dashboard_slideshow_enabled', array('type' => 'checkbox', 'label' => __('Enable slideshow'), 'div' => false));
			?>
		</div>
		<div class="col-lg-2 pull-right">
			<div id="bootstrapped-fine-uploader">
				<div class="qq-upload-button-selector qq-upload-button btn btn-success" style="width: auto;">
					<div><?php echo __('Upload'); ?></div>
				</div>
			</div>
		</div>
		<div class="col-lg-8" id="select_default_img_msg">Select an image to make it your default image</div>
	</div>
	
	<ul id="dashboard_image_list" class="col-lg-12 slim-scroll">
		<?php if (!empty($photos)) : ?>
			<?php foreach ($photos as $photo): ?>
				<li class="<?php echo ($defaultPhotoId === $photo['id']) ? 'selected' : ''; ?>">
					<img src="<?php echo $photo['src']; ?>" class="photo" data-photo_id="<?php echo $photo['id']; ?>" />
					<img src="/theme/App/img/close.gif" alt="X" class="remove_img hide" />
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

	<div class="col-lg-12" id="upload_btn_container">		
		<div id="uploadmessages" class="dashboard_image_upload" style="display: none;"></div>
	</div>

	<div class="col-lg-12">
		<button type="button" class="btn btn-primary ladda-button" id="save_image_settings" data-style="slide-right" data-spinner-color="#FFFFFF">
			<span class="ladda-label"><?php echo __('Save'); ?></span>
			<span class="ladda-spinner"></span>
		</button>
		<button type="reset" class="btn btn-default" id="cancel_image_settings">Cancel</button>
	</div>

	<?php
	echo $this->Form->end();
	?>
</div>