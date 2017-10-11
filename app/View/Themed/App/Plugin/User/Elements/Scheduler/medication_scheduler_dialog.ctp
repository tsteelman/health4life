<div class="modal fade" id="medication_scheduler_dialog" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title add_title hide"><?php echo __('Add a Medication'); ?></h4>
				<h4 class="modal-title edit_title hide"><?php echo __('Edit Medication'); ?></h4>
			</div>
			<div class="modal-body">				
				<?php echo $this->element('User.Scheduler/medication_scheduler_form'); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_active ladda-button" data-style="expand-right" id="save_medication_schedule">
					<span class="ladda-label"><?php echo __('Save'); ?></span><span class="ladda-spinner"></span>
				</button>
				<button type="button" class="btn btn_clear" data-dismiss="modal" id="cancel_medication_schedule"><?php echo __('Cancel'); ?></button>
			</div>
		</div>
	</div>
</div>