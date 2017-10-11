<div class="posting_area">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img',array('postedUserThumb'=>$postedUserThumb)); ?>
        <div class="media-body communit_event">
            <?php echo $this->element('Post.username', array('class' => 'pull-left')); ?>
            <h6>&nbsp;<?php echo __('changed the team privacy to ' . $teamPrivacy); ?></h6>
            <div class="row event_discussion">
                
            </div>
            <?php echo $this->element('Post.like_comment'); ?>
        </div>
    </div>
</div>