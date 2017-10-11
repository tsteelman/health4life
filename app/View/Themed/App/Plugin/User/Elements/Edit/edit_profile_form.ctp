<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('First Name'); ?>
            <span class="red_star_span"> *</span>
        </label>
    </div>
    <div class="col-lg-8 col-sm-8">
        <?php echo $this->Form->input('first_name', array('type' => 'text', 'class' => 'form-control')); ?>		
    </div>
</div>        
<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('Last Name'); ?>
            <span class="red_star_span"> *</span>
        </label>
    </div>
    <div class="col-lg-8 col-sm-8">
        <?php echo $this->Form->input('last_name', array('type' => 'text', 'class' => 'form-control')); ?>
    </div>		
</div>

<div class="form-group-container clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('Date of Birth'); ?>
			<?php if ($loggedin_user_type === User::ROLE_PATIENT): ?>
	            <span class="red_star_span"> *</span>
			<?php endif; ?>
        </label>
    </div>

    <div class="col-lg-8 col-sm-8">
        <div class="row dobrow">
            <div class="col-lg-4 col-sm-4 form-group">
                <?php
                echo $this->Form->input('dob-year', array(
                    'options' => $dob['year'],
                    'empty' => 'Year',
                    'class' => 'form-control dob-year',
                    'data-rel' => "dob-year#dob-month#dob-day",
                    'onchange' => "generate_day_select_box(this)"
                ));
                ?>
            </div>
            <div class="col-lg-4 col-sm-4 form-group">
                <?php
                echo $this->Form->input('dob-month', array(
                    'options' => $dob['month'],
                    'empty' => 'Month',
                    'class' => 'form-control dob-month',
                    'data-rel' => "dob-year#dob-month#dob-day",
                    'onchange' => "generate_day_select_box(this)"
                ));
                ?>
            </div>
            <div class="col-lg-4 col-sm-4 form-group">
                <?php
                echo $this->Form->input('dob-day', array(
                    'options' => $dob['day'],
                    'empty' => 'Day',
                    'class' => 'form-control dob-day',
                    'data-rel' => "dob-year#dob-month#dob-day",
                    'onchange' => "generate_day_select_box(this)"
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('About Me'); ?>
        </label>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?php
        echo $this->Form->input('aboutMe', array(
            'type' => 'textarea', 'class' => 'form-control'
        ));
        ?>
    </div>		
</div>

<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('Gender'); ?>
			<?php if ($loggedin_user_type === User::ROLE_PATIENT): ?>
	            <span class="red_star_span"> *</span>
			<?php endif; ?>
        </label>
    </div>
    <div class="col-lg-6 col-sm-6">            
        <?php
        echo $this->Form->input('gender', array(
            'options' => array('M' => 'Male', 'F' => 'Female'),
			'empty' => __('Select Gender')
        ));
        ?>
    </div>
</div>

<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('Country'); ?>
            <span class="red_star_span"> *</span>
        </label>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?php
        echo $this->Form->input('country', array(
            'options' => $countryList,
            'empty' => 'Select Country',
            'class' => 'form-control country chosen-select',
            'data-rel' => "country#state#city#zip",
            'onchange' => "getStateList(this)"
        ));
        ?>
    </div>
</div>

<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">            
        <label>
            <?php echo __('State/Province'); ?>
            <span class="red_star_span"> *</span>
        </label>          	
    </div>
    <div class="col-lg-6 col-sm-6">
        <?php
        echo $this->Form->input('state', array(
            'options' => $stateList,
            'empty' => 'Select State/Province',
            'class' => 'form-control state chosen-select',
            'data-rel' => "country#state#city",
            'onchange' => "getCityList(this)"
        ));
        ?>
    </div>
</div>

<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">
        <label>
            <?php echo __('City'); ?>
            <span class="red_star_span"> *</span>
        </label>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?php
        echo $this->Form->input('city', array(
            'options' => $cityList,
            'empty' => 'Select City',
            'class' => 'form-control city chosen-select'
        ));
        ?>
    </div>
</div>

<div class="clearfix form-group">
    <div class="col-lg-3 col-sm-3">

        <label>
            <?php echo __('Zip'); ?>
            <span class="red_star_span <?php echo $zipMandatoryClass; ?>"> *</span>
        </label>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?php
        echo $this->Form->input('zip', array(
            'placeholder' => __('Please enter a valid zip'),
			'data-rel' => "country#state#city#zip",
            'class' => 'form-control zip'
        ));
        ?>
		<span class="zip_validating"> </span>
    </div>

</div>

<?php $this->jQValidator->printCountryZipRegexScriptBlock(); ?>

<script>
    $('document').ready(function() {
        if ($("#UserAboutMe").val() == "") {
            $("#UserAboutMe").attr('placeholder', 'Enter details about you');
        }
    });
</script>