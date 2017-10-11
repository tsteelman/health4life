<div class="modal fade" id="report_abuse_post_dialog" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><?php echo __('Report Abuse'); ?></h4>
			</div>
			<div class="modal-body">
				<?php echo __('You have chosen to report this post as inappropriate.'); ?><br /><br />
				<?php echo __('Please provide feedback (optional):'); ?>
				<br/>
				<form style="margin-top:20px;">
					<input name="data[post_id]" type="hidden" />
					<textarea name="data[reason]" class="form-control" placeholder="<?php echo __('Please enter your comments here'); ?>" ></textarea>
					<?php echo $this->element('Post.report_abuse_actions', array('type' => 'post')); ?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_active" id="confirm_report_abuse_post"><?php echo __('OK'); ?></button>
				<button type="button" class="btn btn_clear" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
			</div>
		</div>
	</div>
</div>