<div class="posting_area" id="post_<?php echo $postId; ?>">
    <div class="media">
        <?php echo $this->element('Admin.Post/profile_img'); ?>
        <div class="media-body">
            <?php echo $this->element('Admin.Post/username'); ?>
            <?php if ($description !== ''): ?>
                <p class="wordwrap"><?php echo $description; ?></p>
            <?php endif; ?>
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
            <?php echo $this->element('Admin.Post/like_comment'); ?>
        </div>
    </div>
</div>