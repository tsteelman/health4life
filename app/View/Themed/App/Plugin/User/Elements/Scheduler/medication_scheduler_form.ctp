<?php
$formId = 'medication_scheduler_form';
$inputDefaults = array(
	'label' => false,
	'div' => false,
	'class' => 'form-control'
);
$medicineForms = MedicationSchedulerForm::getMedicineForms();
$dosageUnits = MedicationSchedulerForm::getDosageUnits();
$medicineRoutes = MedicationSchedulerForm::getMedicineRoutes();
$repeatFrequency = MedicationSchedulerForm::getRepeatFrequency();
$yearOptions = Date::getBirthYears();
$monthOptions = Date::getMonths();
$dayOptions = Date::getDays();
$model = 'MedicationSchedulerForm';
echo $this->Form->create($model, array(
	'id' => $formId,
	'inputDefaults' => $inputDefaults,
	'method' => 'POST'
));
echo $this->Form->hidden('id');
echo $this->Form->hidden('selected_date');
?>
<div class="col-lg-12 clearb">
    <div class="form-group col-lg-6">
		<label><?php echo __('Enter medication name'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('medicine_name', array('placeholder' => 'eg: Prednisone')); ?>
		<?php echo $this->Form->hidden('medicine_id'); ?>
	</div>
	<div class="form-group-container col-lg-6 dose_row row">
		<label class="col-lg-12"><?php echo __('Dose/Strength'); ?><span class="red_star_span"> *</span></label>
		<div class="col-lg-6 form-group">
			<?php echo $this->Form->input('dosage', array('maxlength' => 10)); ?>
		</div>
		<div class="col-lg-6 form-group dosage_unit_col">
			<?php
			echo $this->Form->input('dosage_unit', array(
				'empty' => '-Select-',
				'options' => $dosageUnits
			));
			?>
		</div>
		<span class="error_span hide"></span>
	</div>
</div>
<div class="col-lg-12 clearb">
    <div class="form-group col-lg-6">
		<label><?php echo __('Form'); ?></label>
		<?php
		echo $this->Form->input('form', array(
			'empty' => '-Select-',
			'options' => $medicineForms
		));
		?>
    </div>
	<div class="form-group col-lg-6">
		<label><?php echo __('Number/Amount to be given each time'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('amount', array('maxlength' => 10)); ?>
	</div>
</div>
<div class="col-lg-12 clearb">
    <div class="form-group col-lg-6">
		<label><?php echo __('Route of administration'); ?></label>
		<?php
		echo $this->Form->input('route', array(
			'empty' => '-Select-',
			'options' => $medicineRoutes
		));
		?>
    </div>
	<div class="form-group col-lg-6">
		<label><?php echo __('Frequency (how often is the medication taken)'); ?></label>
		<?php
		echo $this->Form->input('repeat_frequency', array(
			'empty' => 'None',
			'options' => $repeatFrequency
		));
		?>
	</div>
</div>
<div class="col-lg-12 clearb">
    <div class="form-group col-lg-6">
		<label><?php echo __('Additional instructions'); ?></label>
		<?php echo $this->Form->input('additional_instructions', array('placeholder' => 'eg: take on a full stomach', 'maxlength' => 100)); ?>
	</div>
    <div class="form-group col-lg-6">
		<label><?php echo __('Prescribing health care provider'); ?></label>
		<?php echo $this->Form->input('prescribed_by', array('maxlength' => 100)); ?>
    </div>
</div>
<div class="col-lg-12 clearb">
    <div class="form-group col-lg-6 start_date_row">
		<label><?php echo __('Medication start date'); ?></label>
		<div class="form-group">
			<?php
			echo $this->Form->input('start_year', array(
				'options' => $yearOptions,
				'class' => 'col-lg-4',
				'empty' => 'Year',
			));
			echo $this->Form->input('start_month', array(
				'options' => $monthOptions,
				'class' => 'col-lg-5',
				'empty' => 'Month',
			));
			echo $this->Form->input('start_day', array(
				'options' => $dayOptions,
				'class' => 'col-lg-3',
				'empty' => 'Day'
			));
			?>
		</div>
	</div>
    <div class="form-group col-lg-6">
		<label><?php echo __('Medication stop date'); ?></label>
		<?php echo $this->Form->input('end_date', array('type' => 'text', 'readonly' => 'readonly', 'disabled' => 'disabled')); ?>
		<input type="checkbox" id="remind_until_cancelled" checked="checked"/><?php echo __('Until Cancelled'); ?>
    </div>
</div>
<div class="col-lg-12 clearb">
	<div class="form-group col-lg-6">
		<label><?php echo __('Indication'); ?></label>
		<?php echo $this->Form->input('indication', array('placeholder' => 'eg: high blood pressure', 'maxlength' => 100)); ?>
    </div>
    <div class="col-lg-6" id="medication_time">
		<label><?php echo __('Administration time'); ?><span class="red_star_span"> *</span></label>
		<ul id="selected_times">
		</ul>
		<div class="form-group clearb" id="new_time">
			<div class="col-lg-12 form-group-col" style="padding-left: 0px;padding-right: 0px;">
				<?php echo $this->Form->input('time', array('type' => 'text', 'placeholder' => 'Select time')); ?>
			</div>
			<span class="hide" id="medication_time_error"></span>
		</div>
    </div>
</div>
<br clear="all" />
<div class="hide" id="sample_time_row">
	<?php echo $this->element('User.Scheduler/medication_time', array('index' => 'index')); ?>
</div>
<input type="hidden" id="last_time_row_index" />
<?php
echo $this->Form->end();
echo $this->jQValidator->validator();
echo $this->AssetCompress->script('scheduler');