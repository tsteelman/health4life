<div class="posting_area">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img',array('postedUserThumb'=>$postedUserThumb)); ?>
        <div class="media-body communit_event">
            <?php echo $this->element('Post.username', array('class' => 'pull-left')); ?>
            <h6 class="pull-left"><?php
				echo __('Created a community');
				if (!empty($postedIn)) {
					echo __(' for %s', $postedIn);
				}
				?>
			</h6>
                        <?php if (!empty($postedUserDiseaseName)): ?>
                <div class="posted_user_disease"><?php echo h($postedUserDiseaseName); ?></div>
            <?php endif; ?>
           
        </div>
    </div>
    <div class="comment_posting">
        
            <div class="row event_discussion">
                <div class="col-lg-4">
                    <?php echo $this->Html->image($communityImage, array('class' => 'img-responsive')); ?>
                </div>
                <div class="col-lg-8">
                    <h3>
						<?php
						if (isset($communityUrl)) {
							echo $this->Html->link($communityName, $communityUrl);
						} else {
							echo $communityName;
						}
						?>
					</h3>
                    <p><?php echo $communityDescription; ?></p>
                </div>
            </div>
            
    </div>
</div>