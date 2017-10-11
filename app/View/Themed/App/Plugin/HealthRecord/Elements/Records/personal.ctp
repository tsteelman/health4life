<?php
echo $this->element('heading', array('headingText' => __('Personal information')));
echo $this->Form->create($modelName, $formOptions);
echo $this->Form->hidden('id');
?>
<div class="form-group">
	<div class="col-lg-4 form-group-col">
		<label><?php echo __('First Name'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('first_name', array('class' => 'form-control text-capitalize')); ?>
	</div>
	<div class="col-lg-2 form-group-col">
		<label><?php echo __('MI'); ?></label>
		<?php echo $this->Form->input('middle_name', array('class' => 'form-control text-capitalize')); ?>
	</div>
	<div class="col-lg-4 form-group-col">
		<label><?php echo __('Last Name'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('last_name', array('class' => 'form-control text-capitalize')); ?>
	</div>
</div>
<div class="form-group">
	<div class="col-lg-4 form-group-col">
		<label><?php echo __('Date of Birth'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('dob', array('type' => 'text', 'readonly' => 'readonly', 'placeholder' => Date::getDateFormatText())); ?>
	</div>
	<div class="col-lg-3 form-group-col">
		<label><?php echo __('Gender'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('gender', array('options' => $genderOptions, 'empty' => __('-Select-'))); ?>
	</div>
</div>
<div class="form-group">
	<div class="col-lg-5 form-group-col">
		<label><?php echo __('Occupation'); ?></label>
		<?php echo $this->Form->input('occupation', array('class' => 'form-control text-capitalize')); ?>
	</div>
	<div class="col-lg-5 form-group-col">
		<label><?php echo __('Marital Status'); ?></label>
		<?php echo $this->Form->input('marital_status', array('options' => $maritalStatusOptions, 'empty' => __('-Select-'))); ?>
	</div>
</div>
<div class="form-group clearfix">                 
	<div class="col-lg-5 form-group-col">
		<label><?php echo __('Ethnicity'); ?><span class="red_star_span"> *</span></label>
		<?php echo $this->Form->input('race', array('options' => $raceOptions, 'empty' => __('-Select-'))); ?>
	</div>
	<div class="col-lg-5 form-group-col" id="location_col">
		<label><?php echo __('Location'); ?><span class="red_star_span"> *</span></label>
		<?php
		echo $this->Form->input('location', array('type' => 'text', 'placeholder' => __('Search Location'), 'class' => 'form-control text-capitalize'));
		echo $this->Form->hidden('city_id');
		?>
		<div class="no_result_msg" id="no_location_msg"style="display: none;"><?php echo __('No such location'); ?></div>
	</div>
</div>
<div class="form-group">
	<div class="col-lg-5 form-group-col">
		<label><?php echo __('Do you smoke?'); ?></label>
		<?php echo $this->Form->input('smoking_status', array('options' => $smokingOptions, 'empty' => __('-Select-'))); ?>
	</div>
	<div class="col-lg-5 form-group-col">
		<label><?php echo __('Do you Drink?'); ?></label>
		<?php echo $this->Form->input('drinking_status', array('options' => $drinkingOptions, 'empty' => __('-Select-'))); ?>
	</div>
</div>
<?php
echo $this->element('buttons_row');
echo $this->Form->end();
?>
<script type="text/javascript">
	$.validator.setDefaults({
		ignore: []
	});
</script>
<?php
echo $this->jQValidator->validator();