<div class="medical_conditions">
	<?php
	echo $this->element('heading', array('headingText' => __('Are you allergic to any of the following?')));
	echo $this->Form->create($modelName, $formOptions);
	echo $this->Form->hidden('id');
	?>

	<h4><?php echo __('Medicines'); ?></h4>
	<div class="form-group">
		<?php echo $this->element('checkbox_list', array('fieldName' => 'AllergicMedicine', 'items' => $allergicMedicines)); ?>
		<div class="col-lg-4">
			<span><?php echo __('Other'); ?></span>
			<?php echo $this->Form->input('other_allergic_medicines', array('type' => 'text', 'class' => 'form-control text-capitalize')); ?>
		</div>
	</div>

	<h4><?php echo __('Food'); ?></h4>
	<div class="form-group">
		<?php echo $this->element('checkbox_list', array('fieldName' => 'AllergicFood', 'items' => $allergicFoodItems)); ?>
		<div class="col-lg-4">
			<span><?php echo __('Other'); ?></span>
			<?php echo $this->Form->input('other_allergic_food_items', array('type' => 'text', 'class' => 'form-control text-capitalize')); ?>
		</div>
	</div>

	<h4><?php echo __('Environmental Allergies'); ?></h4>
	<div class="form-group">
		<?php echo $this->element('checkbox_list', array('fieldName' => 'EnvironmentalAllergy', 'items' => $environmentalAllergies)); ?>
		<div class="col-lg-4">
			<span><?php echo __('Other'); ?></span>
			<?php echo $this->Form->input('other_environmental_allergies', array('type' => 'text', 'class' => 'form-control text-capitalize')); ?>
		</div>
	</div>

	<?php
	echo $this->element('buttons_row');
	echo $this->Form->end();
	?>
</div>