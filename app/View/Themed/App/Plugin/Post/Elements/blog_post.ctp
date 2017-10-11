<div class="posting_area" id="post_<?php echo $postId; ?>">
	<?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
		<?php echo $this->element('Post.profile_img'); ?>
        <?php echo $this->element('Post.delete_post_btn'); ?>
        <div class="media-body">
                <span class="po_user"><?php echo $postedUserLink; ?> added a blog,</span>&nbsp;<span class="color_95 timeago" datetime="<?php echo $postedTimeISO; ?>" 
                  title="<?php echo $postCreatedTime; ?>"><?php echo $postedTimeAgo; ?></span>            
                <?php if (isset($title) && ($title !== '')) : ?>
                <h4 class="wordwrap"><?php echo $title; ?></h4>
		<?php endif; ?>
		<?php echo $this->element('Post.description', array("isBlog" => true)); ?>			
        </div>
    </div>
    <div class="comment_posting">
		
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
		<?php echo $this->element('Post.like_comment'); ?>
    </div>
</div>