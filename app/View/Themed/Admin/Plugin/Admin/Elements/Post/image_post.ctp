<?php
$count = 0;
$imageArray = array();
$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/post_photos/';

if (isset($postPhotos) && !empty($postPhotos)) {
	foreach ($postPhotos as $photos) {
		$imageArray[] = $photoPath . $photos['Photo']['file_name'];
	}
	$count = count($imageArray);
}
?>

<div class="posting_area" id="post_<?php echo $postId; ?>">
    <div class="media">
		<?php echo $this->element('Admin.Post/profile_img'); ?>
        <div class="media-body">
			<?php echo $this->element('Admin.Post/username'); ?>
            <h4 class="wordwrap"><?php echo $description; ?></h4>
            <div id="links" class="video_upload row">
				<?php for ($i = 0; $i < $count; $i++) { ?>
					<img class="media-object img-responsive pull-left post_img" src="<?php echo $imageArray[$i]; ?>"
						 alt="<?php echo $imageArray[$i]; ?>" />
					 <?php } ?>
            </div>
			<?php echo $this->element('Admin.Post/like_comment'); ?>
        </div>
    </div>
</div>