<?php
echo $this->Form->create($modelName, array(
	'class' => 'form-horizontal',
	'id' => $changePasswordFormId,
	'url'=>'/admin/users/changeAdminUserPassword',
	'inputDefaults' => array(
		'label' => false,
		'div' => false
	)
));
echo $this->Form->hidden('id');
echo $this->Form->hidden('username');
echo $this->Form->hidden('email');
echo $this->element('Admin.Users/list_form_fields', array('fields' => $changePasswordFields));
?>

<hr/>

<div class="form-actions">
	<button class="btn btn-info" type="submit">
		<i class="icon-ok bigger-110"></i>
		<?php echo __('Save'); ?>
	</button>

	&nbsp; &nbsp; &nbsp;
	<button class="btn" type="reset">
		<i class="icon-undo bigger-110"></i>
		<?php echo __('Reset'); ?>
	</button>
</div>

<?php echo $this->Form->end(); ?>