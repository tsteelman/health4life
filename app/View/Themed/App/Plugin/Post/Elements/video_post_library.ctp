<div class="posting_area" id="post_<?php echo $postId; ?>">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img'); ?>
            <?php echo $this->element('Post.remove_from_library_btn'); ?>
        <div class="media-body">
            <?php echo $this->element('Post.username'); ?>
            <?php echo $this->element('Post.posted_in_details'); ?>
            <?php echo $this->element('Post.description'); ?>
            <?php if (isset($video) && !empty($video)) : ?>
                <div class="video_upload play_video" data-video_id="<?php echo $video['video_id']; ?>">
                    <?php
                    if (isset($video['thumbnail_url'])) {
                        echo $this->Html->image($video['thumbnail_url'], array(
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
            <?php // echo $this->element('Post.like_comment'); ?>
        </div>
    </div>
</div>