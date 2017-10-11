<div class="medical_conditions">
	<?php
	echo $this->element('heading', array('headingText' => __('Have you had these vaccinations?')));
	echo $this->Form->create($modelName, $formOptions);
	echo $this->Form->hidden('id');
	foreach ($vaccinations as $ageRange => $ageRangeVaccinations):
		?>
		<h4><?php echo $ageRange; ?></h4>
		<div class="form-group">
			<?php echo $this->element('checkbox_list', array('fieldName' => 'Vaccination', 'items' => $ageRangeVaccinations)); ?>
		</div>
		<?php
	endforeach;
	echo $this->element('buttons_row');
	echo $this->Form->end();
	?>
</div>