<div class="posting_area" id="post_<?php echo $postId; ?>">
	<?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
		<?php echo $this->element('Post.profile_img'); ?>
         <?php echo $this->element('Post.delete_post_btn'); ?>
        <div class="media-body">
            <div style="height: auto;">                       
                <div class="clearfix">
                    <?php echo $this->element('Post.username', array('custom' => true, 'class' => 'pull-left')); ?>
			<span class="pull-left feeling_condition <?php echo $smileyClass; ?>" title="<?php echo $healthStatus; ?>"></span> 
                        <?php if (isset($healthStatusComment) && ($healthStatusComment !== '')) : ?>
                            <span class="wordwrap"><?php echo $healthStatusComment; ?></span>
			<?php endif; ?>		                        
                </div>
                <?php if (!empty($postedUserDiseaseName)): ?>
                    <span class="posted_user_disease"><?php echo h($postedUserDiseaseName); ?>,</span>&nbsp;
                <?php endif; ?><span class="timeago" datetime="<?php echo $postedTimeISO; ?>" title="<?php echo $postCreatedTime; ?>"><?php echo $postedTimeAgo; ?></span>
                            
            </div>
            <br clear="all" />
			
        </div>
    </div>
    <div class="comment_posting">
	
			<?php echo $this->element('Post.like_comment'); ?>
    </div>
</div>