<div class="posting_area">
	<?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
		<?php echo $this->element('Post.profile_img'); ?>
        <div class="media-body communit_event">
            <?php echo $this->element('Post.username', array('class' => 'pull-left')); ?>
            <h6 class="pull-left"><?php
				echo __('Created an event');
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
					<?php echo $this->Html->image($eventImage, array('class' => 'img-responsive')); ?>
                </div>
                <div class="col-lg-8">
                    <h3><a href="<?php echo $eventUrl; ?>"><?php echo h($eventName); ?></a></h3>
                    <?php if(isset($isRepeating) && $isRepeating == 1) { ?>
                        <p ><span class="event_type everyday pull-left"></span><?php echo __('Recurring Event'); ?></p>
                    <?php } else { ?>
                        <p ><span class="event_type oneday pull-left"></span><?php echo __('One-time'); ?></p>
                    <?php } ?>
                    
                    <div class="date"><?php echo $eventStartDate; ?></div>
                    <p><?php echo $eventDescription; ?></p>
                </div>
            </div>
			
    </div>
</div>