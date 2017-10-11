<?php
echo $this->Form->create('Comment', array(
	'url' => '/post/api/addComment',
	'class' => 'comment_form',
	'method' => 'POST',
	'enctype' => 'multipart/form-data',
	'default' => false
));
echo $this->Form->hidden('post_id', array('value' => $postId));
?>
<div class="form-group">
	<?php echo $this->Form->textarea('comment_text', array('class' => 'form-control')); ?>
</div>

<?php $showAnonymousCheckbox = false; if ($showAnonymousCheckbox === true): ?>
	<div class="form-group comment_anonymous_box">
		<?php
		$checkboxId = "{$postId}_CommentIsAnonymous";
		echo $this->Form->checkbox('is_anonymous', array('id' => $checkboxId));
		echo $this->Form->label('is_anonymous', __('Post comment anonymously'), array('for' => $checkboxId));
		?>
	</div>
<?php endif; ?>

<?php echo $this->Form->end(); ?>