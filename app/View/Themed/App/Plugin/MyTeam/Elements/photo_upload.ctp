<?php
echo $this->Form->hidden('image');
echo $this->Html->image($teamImage, array(
	'alt' => 'Default Team Photo',
	'id' => 'preview_img',
	'class' => 'userphoto',
        'data-default_image' => $teamImage
));
?>
<p><?php echo __('Change Team Photo'); ?></p>
<div class="row">
	<div>
            <div id="bootstrapped-fine-uploader" class="width_auto">
			<div class="qq-upload-button-selector qq-upload-button btn">
				<div><?php echo __('Upload'); ?></div>
			</div>
		</div>
	</div>
</div>
<div id="uploadmessages" style="display: none;"></div>