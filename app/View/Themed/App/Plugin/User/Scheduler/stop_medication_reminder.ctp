<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb(__('My Health'), '/profile/myhealth');
$this->Html->addCrumb(__('Medication Scheduler'), '/scheduler');
$this->Html->addCrumb(__('Stop Medication Reminder'));
?>
<div class="signup_container" id="stop_medication_reminder_dialog">
	<div class="thumbnail">
		<div class="page-header">
			<h1><?php echo __('Stop Medication Reminder'); ?></h1>                   
		</div>
		<div class="signup_fields">  
			<?php if (isset($medicationName)): ?>
				<div id="confirm_message">
					<?php echo __('Are you sure to stop reminder for %s?', $medicationName); ?>
				</div>
				<div id="success_message" class="alert alert-success hide">	
					<div class="message">
						<?php echo __('The medication reminder for %s has been stopped successfully.', $medicationName); ?>
					</div>
				</div>
				<div id="error_message" class="alert alert-error hide">	
					<div class="message">
						<?php echo __('Failed to stop the medication reminder for %s.', $medicationName); ?>
					</div>
				</div>
				<div id="no_message" class="hide">
					<?php echo __('Thank you for the response.'); ?>
				</div>
				<div class="modal-footer">
					<form>
						<input name="data[id]" type="hidden" value="<?php echo $id; ?>" />
						<button type="button" class="btn btn_active ladda-button" data-style="expand-right" id="yes_btn">
							<span class="ladda-label"><?php echo __('Yes'); ?></span><span class="ladda-spinner"></span>
						</button>
						<button type="button" class="btn btn_clear" id="no_btn"><?php echo __('No'); ?></button>
					</form>
				</div>
			<?php elseif (isset($errorMessage)): ?>
				<div  class="alert alert-error ">	
					<div class="message">
						<?php echo $errorMessage; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>