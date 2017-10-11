<div class="modal fade" id="confirm_unblock_dialog" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><?php echo __('Unblock User'); ?></h4>
			</div>
			<div class="modal-body">
				<?php echo __('Are you sure you want to unblock '); ?><span class="username">user</span>?
				<form>
					<input name="data[user_id]" type="hidden" />
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_active" id="confirm_user_unblock"><?php echo __('OK'); ?></button>
				<button type="button" class="btn btn_clear" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
			</div>
		</div>
	</div>
</div>