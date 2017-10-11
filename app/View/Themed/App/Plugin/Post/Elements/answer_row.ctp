<div class="disease_answer_div" id="answer_<?php echo $answerId; ?>">
	<div class="media">
		<div class="commenter_status pull-left">
			<?php
			echo $this->Html->link($answeredUserThumb, $answeredUserProfileUrl, array(
				'class' => "{$answeredUserThumbCursorClass}",
				'escape' => false
			));
			?>
			
			<?php if (!empty($answeredUserHealthStatus)): ?>
				<span class="pull-right feeling_condition <?php echo $answeredUserSmileyClass; ?>" title="<?php echo $answeredUserHealthStatus; ?>"></span>
			<?php endif; ?>

		</div>
		<?php if ($canDeleteAnswer) : ?>
			<button type="button" data-answer_id="<?php echo $answerId; ?>" title="Delete Answer" class="close pull-right delete_answer_btn hide">&times;</button>
		<?php endif; ?>
		<div class="media-body">
			<h5 class="pull-left"><?php echo $answeredUserLink; ?></h5>
			<?php if (!empty($truncatedAnswerText)): ?>
				<p class="truncated_text">
					<?php echo $truncatedAnswerText; ?>
					<a class="more_text"><?php echo __('more...'); ?></a>
				</p>
				<p class="hide full_text">
                                    <?php echo $answerText; ?>
                                    <a class="less_text"><?php echo __('less...'); ?></a>
                                </p>
			<?php else: ?>
				<p><?php echo $answerText; ?></p>
			<?php endif; ?>
			<p class="timeago" datetime="<?php echo $answeredTimeISO; ?>"><?php echo $answeredTimeAgo; ?></p>
		</div>
	</div>	
</div>