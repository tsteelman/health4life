<div class="posting_area poll_post_area" id="post_<?php echo $postId; ?>">
    <div class="media">
		<?php echo $this->element('Admin.Post/profile_img'); ?>
        <div class="media-body">
            <div class="poll_rates poll_popup">
				<?php echo $this->element('Admin.Post/username'); ?>
				<?php if (isset($poll_name)): ?>
					<h4 class="poll_name"><?php echo h($poll_name); ?></h4>
				<?php endif; ?>
				<?php if (isset($poll_details['Poll']['title'])) : ?>
					<h4><?php echo h($poll_details['Poll']['title']); ?></h4>
				<?php endif; ?>

				<?php
				$totalVotes = $vote_details['totalVotes'];
				$totalChoices = $vote_details['totalChoices'];
				$width = 0;
				foreach ($poll_details['PollChoices'] as $choices) {
					$width = 0;
					if ($totalVotes > 0) {
						$width = round(($choices['votes'] / $totalVotes) * 100);
					}
					?>
					<div class="poll_results">
						<div class="poll_question"> <?php echo h($choices['option']); ?></div>
						<div class="poll_details no_of_polls">
							<div class="polled_option" style="width:<?php echo $width . '%'; ?>">
							</div>
							<span class="poll_votes_count"><?php echo $choices['votes'] . ' vote(s)'; ?></span>
							<span class="poll_percentage"> <?php echo $width . '%'; ?></span>
						</div>
					</div>
				<?php } ?>                
            </div>
			<?php echo $this->element('Admin.Post/like_comment'); ?>
        </div>
    </div>
</div>