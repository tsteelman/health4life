<?php
if (!empty($answers)) {
	foreach ($answers as $answerData) {
		echo $this->element('Post.answer_row', $answerData);
	}
}
?>
<p class="no_answers_msg <?php echo (empty($answers)) ? '' : 'hide' ?>"><?php echo('No answers yet'); ?></p>