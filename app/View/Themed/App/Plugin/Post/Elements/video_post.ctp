<div class="posting_area" id="post_<?php echo $postId; ?>">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img'); ?>
        <?php echo $this->element('Post.delete_post_btn'); ?>
        <div class="media-body">            
            <?php echo $this->element('Post.username'); ?>
                        <?php if (!empty($postedUserDiseaseName)): ?>
                <div class="posted_user_disease pull-left"><?php echo h($postedUserDiseaseName); ?>,&nbsp;&nbsp;</div>
            <?php endif; ?>
           <span class="pull-left timeago" datetime="<?php echo $postedTimeISO; ?>" title="<?php echo $postCreatedTime; ?>"><?php echo $postedTimeAgo; ?></span>
        </div>
    </div>
    <div class="comment_posting">
			<?php echo $this->element('Post.description'); ?>
            <?php if (isset($video) && !empty($video)) : ?>
                <div class="video_upload play_video" data-video_id="<?php echo $video['video_id']; ?>">
                    <?php
                    if (isset($video['thumbnail_url'])) {
                        echo $this->Html->image(preg_replace('#^https?://#', '//', 
                                $video['thumbnail_url']), array(
                            'class' => 'img-responsive media-object',
                            'alt' => '...'
                        ));
                        echo $this->Html->image('video-active.png', array(
                            'class' => 'video_play_icon',
                            'alt' => 'play'
                        ));
                    }
                    ?>
                </div>
            <?php endif; ?>
            <?php echo $this->element('Post.like_comment'); ?>
    </div>
</div>