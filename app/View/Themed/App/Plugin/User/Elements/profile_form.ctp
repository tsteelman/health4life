<div class="form-group">
    <div class="row">
        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('First Name'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php echo $this->Form->input('firstname', array('type' => 'text', 'class' => 'form-control')); ?>		
        </div>

        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Last Name'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php echo $this->Form->input('lastname', array('type' => 'text', 'class' => 'form-control')); ?>
        </div>		
    </div>

</div>

<div class="form-group-container">
    <label>
        <?php echo __('Date of Birth'); ?>
        <span class="red_star_span"> *</span>
    </label>
    <div class="row dobrow">
        <div class="col-lg-4 form-group">
            <?php
            echo $this->Form->input('dob-year', array(
                'options' => $dob['year'],
                'empty' => 'Year',
                'class' => 'form-control dob-year',
                'data-rel' => "dob-year#dob-month#dob-day",
                'onchange' => "generate_day_select_box(this)",
                'default' => $defaultDOBStartingYear
            ));
            ?>
        </div>
        <div class="col-lg-4 form-group">
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
        <div class="col-lg-4 form-group">
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
<div class="form-group">
    <div class="row">
        <div class="col-lg-6 form-group">
            <label>
                <?php echo __('Gender'); ?>
                <span class="red_star_span"> *</span>
            </label>    
            <select class="form-control" name="data[User][gender]">
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
                <?php echo __('Country'); ?>
                <span class="red_star_span"> *</span>
            </label>
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

        <div class="col-lg-6 form-group">
            <div>
                <label>
                    <?php echo __('State/Province'); ?>
                    <span class="red_star_span"> *</span>
                </label>
            </div>
            <?php
            echo $this->Form->input('state', array(
                'options' => array(),
                'disabled' => true,
                'empty' => 'Select State/Province',
                'class' => 'form-control state chosen-select',
                'data-rel' => "country#state#city",
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
                <?php echo __('City'); ?>
                <span class="red_star_span"> *</span>
            </label>
            <?php
            echo $this->Form->input('city', array(
                'options' => array(),
                'disabled' => true,
                'empty' => 'Select City',
                'class' => 'form-control city chosen-select'
            ));
            ?>
        </div>

        <div class="col-lg-6 form-group">
            <div>
                <label>
                    <?php echo __('Zip'); ?>
                    <span class="red_star_span"> *</span>
                </label>
            </div>
            <input id="UserZip" type="text" class="form-control zip"
                   placeholder="<?php echo __('Please enter a valid zip'); ?>"
				   data-rel = "country#state#city#zip"
                   name="data[User][zip]">
			<span class="zip_validating"> </span>
        </div>
    </div>
</div>

<?php
if (($type != 1) && ($type != 3)) {
	?>
<div class="form-group">
<?php
echo $this->element('User.Register/diagnosis_support_form');
?>
</div>
<?php
}

?>
<div class="form-group">
    <div>
        <label>
            <?php echo __('Upload Profile Picture'); ?>
        </label><span class="optional">(Optional)</span>
    </div>
    <div id="uploadPreview">
        <?php echo $this->Html->image($defaultProfilePhoto, array('alt' => 'User Thumbnail', 'style' => 'width: 140px; height: 140px;', 'class' => "profile_brdr_5 img-responsive pull-left img-thumbnail {$profilePhotoClass}")); ?>
        <div class="col-lg-3 pull-left">
            <div id="bootstrapped-fine-uploader">
                <div class="qq-upload-button-selector qq-upload-button btn" style="width: auto;">
                    <div>Upload</div>
                </div>
            </div>
        </div>
    </div>	
    <br/>		
    <div class="row">
        <div class="col-lg-10">
            <div id="uploadmessages"></div>
        </div>	
    </div>

    <input type="hidden" value="cropfileName" name="cropfileName" id="cropfileName" />
    <input type="hidden" value="0" name="x1" id="x1" />
    <input type="hidden" value="0" name="y1" id="y1" />
    <input type="hidden" value="200" name="w" id="w" />
    <input type="hidden" value="200" name="h" id="h" />
</div>

<div class="form-group">
    <label>
        <input class="chck_box" type="checkbox" id="UserNewsletter" name="data[User][newsletter]" value="1" checked />
        <span><?php echo __('Sign up for newsletter'); ?></span>
    </label><br/>
    <label>
        <input class="chck_box" type="checkbox" id="UserVolunteer" name="data[User][volunteer]" value="1"/>
        <span><?php echo __('Sign me up to volunteer to join a support team'); ?></span>
    </label>
</div>
