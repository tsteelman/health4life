<div class="posting_area" id="post_<?php echo $postId; ?>">
    <div class="media">
		<?php echo $this->element('Admin.Post/profile_img'); ?>
        <div class="media-body">
			<?php echo $this->element('Admin.Post/username'); ?>
			<?php if (isset($title) && ($title !== '')) : ?>
				<h4 class="wordwrap"><?php echo $title; ?></h4>
			<?php endif; ?>
			<?php if (isset($description) && ($description !== '')) : ?>
				<p class="wordwrap"><?php echo $description; ?></p>
			<?php endif; ?>
			<?php if (isset($additional_info) && !empty($additional_info)) : ?>
				<?php if ($additional_info['link_video_iframe'] !== '') : ?>
					<?php echo html_entity_decode(str_replace('display: none;', '', $additional_info['link_video_iframe'])); ?>
				<?php elseif ($additional_info['link_image'] !== '') : ?>
					<a href="<?php echo $additional_info['link_url']; ?>" target="_blank">
						<img src="<?php echo $additional_info['link_image']; ?>" class="url_img"/>
					</a> 
				<?php endif; ?>
				<p><?php echo $additional_info['link_cannonical_url']; ?></p>
				<?php if ($additional_info['link_title'] !== '') : ?>
					<p>
						<a href="<?php echo $additional_info['link_url']; ?>" target="_blank">
							<?php echo $additional_info['link_title']; ?>
						</a>
					</p>
				<?php endif; ?>
				<?php if ($additional_info['link_description'] !== '') : ?>
					<p><?php echo html_entity_decode($additional_info['link_description']); ?></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php echo $this->element('Admin.Post/like_comment'); ?>
        </div>
    </div>
</div>