<div class="medical_conditions">
	<?php
	echo $this->element('heading', array('headingText' => __('Do you have, or have you had, any of the following?')));
	echo $this->Form->create($modelName, $formOptions);
	echo $this->Form->hidden('id');
	?>

	<div class="form-group">
		<?php echo $this->element('checkbox_list', array('fieldName' => 'Condition', 'items' => $conditions)); ?>
		<div class="col-lg-8">
			<span><?php echo __('Other Conditions'); ?></span>
			<?php echo $this->Form->input('other_conditions', array('type' => 'text', 'class' => 'form-control text-capitalize')); ?>
		</div>
	</div>

	<?php echo $this->element('heading', array('headingText' => __('Have you had any of the following childhood illnesses?'))); ?>
	<div class="form-group">
		<?php echo $this->element('checkbox_list', array('fieldName' => 'ChildhoodIllness', 'items' => $childhoodIllnesses)); ?>
	</div>

	<?php
	echo $this->element('buttons_row');
	echo $this->Form->end();
	?>
</div>