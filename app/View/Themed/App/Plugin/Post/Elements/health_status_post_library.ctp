<div class="posting_area" id="post_<?php echo $postId; ?>">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img'); ?>
        <?php echo $this->element('Post.remove_from_library_btn'); ?>
        <div class="media-body library_health_media_body">
            <div class="library_posted_in_text pull-left">
                <?php echo $this->element('Post.username', array('class' => 'pull-left')); ?>
                <span class="library_health_status"><?php echo __('updated health status as %s', $healthStatus); ?></span>
            </div>
            <?php if (isset($healthStatusComment) && ($healthStatusComment !== '')) : ?>
                <p class="wordwrap"><?php echo $healthStatusComment; ?></p>
            <?php endif; ?>			
        </div>
    </div>
</div>