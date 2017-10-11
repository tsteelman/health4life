<?php
$count = 0;
$imageArray = array();
$previewImages = array();
$hiddenImages = array();
$photoUrlPath = Configure::read('App.UPLOAD_PATH_URL') . '/post_photos/';
$previewFilePath = Configure::read('App.POST_IMG_PATH') . DIRECTORY_SEPARATOR;

if (isset($postPhotos) && !empty($postPhotos)) {
	foreach ($postPhotos as $photo) {
		$photoFileName = $photo['Photo']['file_name'];
		$originalPhoto = $photoUrlPath . $photoFileName;
		$previewFile = $previewFilePath . 'preview_' . $photoFileName;
		if (file_exists($previewFile)) {
			$previewPhoto = $photoUrlPath . 'preview_' . $photoFileName;
		} else {
			$previewPhoto = $originalPhoto;
		}
		$imageArray[] = array(
			'photo' => $originalPhoto,
			'preview' => $previewPhoto,
			'filename' => $photoFileName
		);
	}
	$count = count($imageArray);
	if ($count === 1) {
		$previewCount = 1;
	} elseif ($count < 4) {
		$previewCount = 2;
	} else {
		$previewCount = 4;
	}

	$previewImages = array_slice($imageArray, 0, $previewCount);
	if ($previewCount < $count) {
		$hiddenImagesCount = $count - $previewCount;
		$hiddenImages = array_slice($imageArray, $previewCount, $hiddenImagesCount);
	}

	$width = ($previewCount === 1) ? 100 : 50;
	$height = ($previewCount === 4) ? 50 : 100;

	$showOriginal = false;
	if ($count === 1) {
		if (file_exists($previewFile)) {
			$showOriginal = true;
			$previewImage = $previewImages[0];
		} else {
			$previewFile = $previewFilePath . $photoFileName;
			list($imageWidth, $imageHeight) = getimagesize($previewFile);
			$minWidth = 100;
			$minHeight = 100;
			if (($imageWidth < $minWidth) || ($imageHeight < $minHeight)) {
				$showOriginal = true;
				$previewImage = $previewImages[0];
			}
		}
	}
}
?>

<div class="posting_area" id="post_<?php echo $postId; ?>">
	<?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
		<?php echo $this->element('Post.profile_img'); ?>
		<?php echo $this->element('Post.delete_post_btn'); ?>
        <div class="media-body">           
			<?php echo $this->element('Post.username'); ?>
        </div>
    </div>
    <div class="comment_posting">
		<?php echo $this->element('Post.description'); ?>
		<div id="links" class="video_upload row">
			<?php if ($showOriginal === true) : ?>
				<a href="<?php echo $previewImage['photo']; ?>" 
				   data-gallery="#modal_<?php echo $postId; ?>">
					<img class=" media-object img-responsive image_gallery"
						 src="<?php echo $previewImage['preview']; ?>" alt="<?php echo $previewImage['filename']; ?>" />
				</a>
			<?php else: ?>
				<div id="post_photos_container">
					<div id="post_photos_inner">
						<?php
						foreach ($previewImages as $key => $previewImage):
							$top = (($key === 0) || ($key === 1)) ? 0 : 50;
							$left = (($key === 0) || ($key === 2)) ? 0 : 50;
							?>
							<a href="<?php echo $previewImage['photo']; ?>" 
							   data-gallery="#modal_<?php echo $postId; ?>">
								<div style='top: <?php echo $top; ?>%; left: <?php echo $left; ?>%; width: <?php echo $width; ?>%; height: <?php echo $height; ?>%;  background-image: url(<?php echo $previewImage['preview']; ?>);'></div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>

			<?php endif; ?>
			<?php
			if (!empty($hiddenImages)) {
				foreach ($hiddenImages as $hiddenImage) {
					?>
					<a href="<?php echo $hiddenImage['photo']; ?>" 
					   data-gallery="#modal_<?php echo $postId; ?>" class="col-lg-5 hidden">
					</a>
					<?php
				}
			}
			?>
		</div>
		<?php echo $this->element('Post.like_comment'); ?>
    </div>
</div>