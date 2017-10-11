<div <?php echo (isset($id)) ? "id='{$id}'" : ''; ?> class="form-group record <?php echo (isset($hide)) ? 'hide' : ''; ?>">
	<div class="col-lg-2 form-group-col">
		<?php echo $this->Form->input("{$modelName}.Surgery.{$index}.year", array('placeholder' => 'Year')); ?>
	</div>
	<div class="col-lg-5 form-group-col">
		<?php echo $this->Form->input("{$modelName}.Surgery.{$index}.type", array('placeholder' => 'Type', 'class' => 'form-control text-capitalize')); ?>
	</div>
	<div class="col-lg-4 form-group-col">
		<?php echo $this->Form->input("{$modelName}.Surgery.{$index}.residual_problem", array('placeholder' => 'Residual problem', 'class' => 'form-control text-capitalize')); ?>
	</div>
	<div class="col-lg-1 pull-right">
		<button type="button" class="close" aria-hidden="true">×</button>
	</div>
</div>