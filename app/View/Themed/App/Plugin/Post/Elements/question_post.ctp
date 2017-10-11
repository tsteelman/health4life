<div class="questionnaire_div" id="post_<?php echo $postId; ?>">
	<div class="disease_question_div">
		<div class="media">
			<?php echo $this->element('Post.profile_img'); ?>
			<div class="media-body">
				<?php echo $this->element('Post.delete_post_btn'); ?>
				<div class="disease_questions"><?php echo $question; ?></div>
				<?php echo $this->element('Post.username'); ?>
			</div>
		</div>		
		<p>
			<span><?php echo __('Answers'); ?> (<span class="answer_count"><?php echo $answerCount; ?></span>)</span>
			<a class="add_answer_link"><?php echo __('Add answer'); ?></a>
		</p>
	</div>
	<?php echo $this->element('Post.answer_form'); ?>
	<div class="question_answers_div">
		<?php echo $this->element('Post.answers_list'); ?>
		<?php if ($showMoreAnswersLink === true): ?>
			<div class="load_more_answer">
				<a data-postid="<?php echo $postId; ?>">
					<?php echo __('Load More Answers'); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>