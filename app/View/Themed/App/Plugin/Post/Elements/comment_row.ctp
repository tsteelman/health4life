<div class="comment_section" id="comment_<?php echo $commentId; ?>">
    <div class="media clearfix">
		<div class="commenter_status pull-left">
			<?php
			echo $this->Html->link($commentedUserThumb, $commentedUserProfileUrl, array(
				'class' => "{$commentedUserThumbCursorClass}",
				'escape' => false
			));
			?>
			
			<?php if (!empty($commentedUserHealthStatus)): ?>
				<span class="pull-right feeling_condition <?php echo $commentedUserSmileyClass; ?>" title="<?php echo $commentedUserHealthStatus; ?>"></span>
			<?php endif; ?>
				
		</div>		
		<?php if ($canDelete || $canReportAbuse) : ?>
			<div class="btn-group pull-right edit_field hide">
				<button type="button" data-toggle="dropdown" class="pull-right edit_comment"></button>
				<ul class="dropdown-menu">
					<?php if ($canDelete): ?>
						<li><a class="report_abuse delete_comment delete_comment_btn" data-comment_id="<?php echo $commentId; ?>" ><?php echo __('Delete'); ?></a></li>
					<?php endif; ?>
					<?php if ($canReportAbuse): ?>
						<li><a class="report_abuse report_abuse_comment" data-comment_id="<?php echo $commentId; ?>"><?php echo __('Report Abuse'); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
        <div class="media-body">
            <h5 class="pull-left">
				<?php echo $commentedUserLink; ?>
            </h5>
			<?php if (!empty($truncatedCommentText)): ?>
				<span class="comments truncated_text">
					<?php echo $truncatedCommentText; ?>
					<a class="more_text"><?php echo __('more...'); ?></a>
				</span>
				<span class="comments hide full_text">
                                    <?php echo $commentText; ?>
                                     <a class="less_text"><?php echo __('less...'); ?></a>
                                </span>
			<?php else: ?>
				<span class="comments"><?php echo $commentText; ?></span>
			<?php endif; ?>
            <span class="timeago" datetime="<?php echo $commentedTimeISO; ?>"><?php echo $commentedTimeAgo; ?></span>
        </div>
    </div>
</div>