<div class="photo_preview">
	<img class="preview_img" src="<?php echo $fileurl; ?>">
            <?php
            echo $this->Html->image('close_preview.png', array(
                'alt' => 'X',
                'title' => 'Remove',
                'class' => 'hide remove_photo'
            ));
            ?>
	<input type="hidden" name="data[Post][Photo][]" value="<?php echo $fileName; ?>">
</div>