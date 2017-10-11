<div class="tab-pane" id="edit-password">
	<h4 class="header blue bolder smaller"><?php echo __('Change Password'); ?></h4>
	<div class="space-10"></div>

	<?php
	echo $this->Form->create($modelName, array(
		'id' => $changePasswordFormId,
		'class' => 'form-horizontal',
		'inputDefaults' => $inputDefaults
	));
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('form_id', array('value' => 'change_password'));
	echo $this->element('Admin.Users/list_form_fields', array('fields' => $changePasswordFields));
	echo $this->element('Admin.Users/Profile/action_buttons');
	echo $this->Form->end();
	?>
</div>