<div <?php echo (isset($id)) ? "id='{$id}'" : ''; ?> class="condition_row <?php echo (isset($hide)) ? 'hide' : ''; ?>">
	<?php if ($index !== 0): ?>
		<div class="col-lg-2 pull-right">
			<button type="button" class="close" aria-hidden="true">&times;</button>
		</div>
	<?php endif; ?>
	<div class="form-group">
		<label>
			<?php echo __('Diagnosis'); ?><span class="red_star_span"> *</span>
		</label>	
		<?php
		echo $this->Form->input("PatientDisease.{$index}.disease_name", array(
			'label' => false,
			'div' => false,
			'class' => 'form-control disease_search',
			'placeholder' => __('Type to select')
		));
		echo $this->Form->hidden("PatientDisease.{$index}.disease_id", array('class' => 'disease_id_hidden'));
		?>
	</div>
	<div class="row diagnosis_date_row">
		<div class="col-lg-5">
			<div class="form-group">
				<label>
					<?php echo __('Date of Diagnosis'); ?>
				</label>	
				<?php
				echo $this->Form->input("PatientDisease.{$index}.diagnosis_date_year", array(
					'label' => false,
					'div' => false,
					'options' => $dob['diagnosisYear'],
					'empty' => __('Year'),
					'class' => 'form-control diagnosed-year'
				));
				?>
			</div>
		</div>
	</div>
	<div class="form-group medication_row">
		<label>
			<?php echo __('Medication(s)'); ?>
		</label>
		<ul class="facelist">
			<li class="token-input">
				<?php
				echo $this->Form->input("PatientDisease.{$index}.user_treatments", array(
					'label' => false,
					'div' => false,
					'type' => 'text',
					'class' => 'medication_input',
					'placeholder' => 'Multiple medications can be added'
				));
				echo $this->Form->hidden("PatientDisease.{$index}.treatment_id", array('class' => 'treatment_id_hidden'));
				?>
			</li>
		</ul>
		<div class="result_list" style="display:none;"></div>
	</div>
</div>