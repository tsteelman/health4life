<div class="form-group">
    <label>
        <?php echo __('Relationship with Patient'); ?>
        <span class="red_star_span"> *</span>
    </label>
    <div class="row">
        <div class="col-lg-6">
            <?php
            echo $this->Form->input('patient-relationship', array(
                'options' => $patientRelation,
                'empty' => 'Relationship',
                'class' => 'form-control',
                'data-rel' => 'sdsadas',
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Patient First Name'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php echo $this->Form->input('patient-firstname', array('type' => 'text', 'class' => 'form-control')); ?>		
        </div>

        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Patient Last Name'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php echo $this->Form->input('patient-lastname', array('type' => 'text', 'class' => 'form-control')); ?>
        </div>		
    </div>

</div>

<div class="form-group-container">
    <label>
        <?php echo __('Patient Date of Birth'); ?>
        <span class="red_star_span"> *</span>
    </label>
    <div class="row patient_dob_row">
        <div class="col-lg-4 form-group">
            <?php
            echo $this->Form->input('patient-dob-year', array(
                'options' => $dob['year'],
                'empty' => 'Year',
                'class' => 'form-control patient-dob-year',
                'data-rel' => "patient-dob-year#patient-dob-month#patient-dob-day",
                'onchange' => "generate_day_select_box(this)",
				'default' => $defaultDOBStartingYear
            ));
            ?>
        </div>
        <div class="col-lg-4 form-group">
            <?php
            echo $this->Form->input('patient-dob-month', array(
                'options' => $dob['month'],
                'empty' => 'Month',
                'class' => 'form-control patient-dob-month',
                'data-rel' => "patient-dob-year#patient-dob-month#patient-dob-day",
                'onchange' => "generate_day_select_box(this)"
            ));
            ?>
        </div>
        <div class="col-lg-4 form-group">
            <?php
            echo $this->Form->input('patient-dob-day', array(
                'options' => $dob['day'],
                'empty' => 'Day',
                'class' => 'form-control patient-dob-day',
                'data-rel' => "patient-dob-year#patient-dob-month#patient-dob-day",
                'onchange' => "generate_day_select_box(this)"
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Patient Gender'); ?>
                <span class="red_star_span"> *</span>
            </label>    
            <select class="form-control" name="data[User][patient-gender]">
                <option value="">Select Gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Patient Country'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php
            echo $this->Form->input('patient-country', array(
                'options' => $countryList,
                'empty' => 'Select Country',
                'class' => 'form-control patient-country',
                'data-rel' => "patient-country#patient-state#patient-city#patient-zip",
                'onchange' => "getStateList(this)"
            ));
            ?>
        </div>

        <div class="col-lg-6 form-group">
            <div>
                <label>
                    <?php echo __('Patient State/Province'); ?>
                    <span class="red_star_span"> *</span>
                </label>
            </div>
            <?php
            echo $this->Form->input('patient-state', array(
                'options' => array(),
                'disabled' => true,
                'empty' => 'Select State/Province',
                'class' => 'form-control patient-state',
                'data-rel' => "patient-country#patient-state#patient-city",
                'onchange' => "getCityList(this)"
            ));
            ?>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="row">
        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Patient City'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php
            echo $this->Form->input('patient-city', array(
                'options' => array(),
                'disabled' => true,
                'empty' => 'Select City',
                'class' => 'form-control patient-city'
            ));
            ?>
        </div>

        <div class="col-lg-6 form-group">
            <div>
                <label>
                    <?php echo __('Patient Zip'); ?>
                    <span class="red_star_span"> *</span>
                </label>
            </div>
            <input id="PatientZip" type="text" class="form-control patient-zip"
                   placeholder="<?php echo __('Please enter a valid zip'); ?>"
				   data-rel = "patient-country#patient-state#patient-city#patient-zip"
                   name="data[User][patient-zip]">
			<span class="zip_validating"> </span>
        </div>
    </div>
</div>


<div class=" flt_lft btn_area">
    <button type="button" class="btn btn-prev"><?php echo $this->Html->image('back_arow.png', array('alt' => 'Back')); ?>&nbsp;Back</button>
    <button type="button" class="btn btn-next">Next &nbsp;</button>
</div>