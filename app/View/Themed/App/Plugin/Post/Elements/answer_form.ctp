<div class="add_answer answer_form_container hide">
	<div class="media">
		<a href="#" class="pull-left">
			<?php echo Common::getUserThumb($loggedin_userid, $loggedin_user_type, 'x_small', 'media-object normal_thumb'); ?>
			<?php echo Common::getAnonymousUserThumb('x_small', 'media-object hidden anonymous_thumb'); ?>
		</a>
		<div class="media-body">
			<h5 class="owner original_username"><?php echo $username; ?></h5>
			<h5 class="owner anonymous_username hidden"><?php echo __('Anonymous'); ?></h5>
		</div>
	</div>
	<div class="submit_answer">
		<?php
		echo $this->Form->create('Answer', array(
			'url' => '/post/api/addAnswer',
			'class' => 'answer_form',
			'method' => 'POST',
			'enctype' => 'multipart/form-data',
			'default' => false
		));
		echo $this->Form->hidden('post_id', array('value' => $postId));
		?>
		<?php echo $this->Form->textarea('answer', array('class' => 'form-control', 'placeholder' => __('Add your answer'))); ?>

		<div class="row">
			<div class="col-lg-8 error_container">
<!--				<div class="form-group comment_anonymous_box ">
					<?php
					$checkboxId = "{$postId}_AnswerIsAnonymous";
					echo $this->Form->checkbox('is_anonymous', array('id' => $checkboxId));
					echo $this->Form->label('is_anonymous', __('Post answer anonymously'), array('for' => $checkboxId));
					?>
				</div>-->
			</div>
			<div class="col-lg-4">
				<button type="button" data-style="slide-right" data-spinner-color="#3581ED" class="btn btn_add pull-right add_answer_btn ladda-button">
					<span class="ladda-label"><?php echo __('Add Answer'); ?></span>
					<span class="ladda-spinner"></span>
				</button>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>