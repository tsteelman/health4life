<div class="posting_area">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img', array('postedUserThumb' => $postedUserThumb)); ?>
        <?php  echo $this->element('Post.remove_from_library_btn'); ?>
        <div class="media-body communit_event">
            <?php echo $this->element('Post.posted_in_details'); ?>
            <?php echo $this->element('Post.username', array('class' => 'pull-left')); ?>
            <h6>&nbsp;<?php echo __('created a community'); ?></h6>
            <div class="row event_discussion">
                <div class="col-lg-4">
                    <?php echo $this->Html->image($communityImage, array('class' => 'img-responsive')); ?>
                </div>
                <div class="col-lg-8">
                    <h3><?php echo $communityName; ?></h3>
                    <p><?php echo $communityDescription; ?></p>
                </div>
            </div>
            <?php // echo $this->element('Post.like_comment'); ?>
        </div>
    </div>
</div>