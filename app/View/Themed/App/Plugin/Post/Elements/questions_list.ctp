<?php
if (!empty($questions)) {
	foreach ($questions as $questionPost) {
		$element = $questionPost['element'];
		unset($questionPost['element']);
		$this->set($questionPost);
		echo $this->element($element);
	}
	unset($questionPost);
	if (isset($nextPageOffset)) {
		?>
		<div class="col-lg-12 text-center show_more_questions">
			<a data-offset="<?php echo $nextPageOffset; ?>">
				<?php echo __('Show More Questions'); ?>
			</a>
		</div>
		<?php
	}
}
?>
<div id="no_questions_msg" class="<?php echo empty($questions) ? '' : 'hide'; ?>">
	<?php
	echo $this->element('warning', array('message' => __('No questions yet!'), 'hideCloseBtn' => true));
	?>
</div>