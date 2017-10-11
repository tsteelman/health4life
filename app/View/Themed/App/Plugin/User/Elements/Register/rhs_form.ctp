<div class="col-lg-6 col-md-6 rhs_form">
    <div class="role_form">
	<h2 id="form_heading"><?php echo __('Patient SignUp'); ?></h2>
	<?php
	echo $this->Form->create(null, array(
		'url' => array('plugin' => null, 'controller' => 'user', 'action' => 'register'),
		'type' => 'file',
		'id' => $formId,
		'inputDefaults' => array(
			'label' => false,
			'div' => false,
		)
	));
	echo $this->Form->hidden('type', array('value' => $defaultUserType));
	echo $this->Form->hidden('timezone');
	?>
	<div class="account_details">
		<div class="form_sections"><?php echo __('Account Details'); ?></div>
		<div class="form-group">
			<label>
				<?php echo __('Username'); ?><span class="red_star_span"> *</span>
			</label>
			<?php echo $this->Form->input('username', array('autocomplete' => 'off', 'class' => 'form-control', 'placeholder' => __('User Name'))); ?>
		</div>
		<div class="form-group">
			<label>
				<?php echo __('Email'); ?><span class="red_star_span"> *</span>
			</label>	
			<?php echo $this->Form->input('email', array('autocomplete' => 'off', 'type' => 'email', 'class' => 'form-control', 'placeholder' => __('Email address'))); ?>
		</div>
		<div class="form-group" id="password">
			<label>
				<?php echo __('Password'); ?><span class="red_star_span"> *</span>
			</label>	
			<?php echo $this->Form->input('password', array('autocomplete' => 'off', 'type' => 'password', 'class' => 'form-control password', 'placeholder' => __('Six or more characters'))); ?>
		</div>
		<div class="form-group">
			<label>
				<?php echo __('Confirm Password'); ?><span class="red_star_span"> *</span>
			</label>	
			<?php echo $this->Form->input('confirm-password', array('type' => 'password', 'class' => 'form-control', 'placeholder' => __('Six or more characters'))); ?>
		</div>
	</div>

	<div class="personal_info_div">
		<div class="form_sections"><?php echo __('Personal Information'); ?></div>
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label>
						<?php echo __('First Name'); ?><span class="red_star_span"> *</span>
					</label>	
					<?php echo $this->Form->input('firstname', array('type' => 'text', 'class' => 'form-control', 'placeholder' => __('Enter First Name'))); ?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label>
						<?php echo __('Last Name'); ?><span class="red_star_span"> *</span>
					</label>	
					<?php echo $this->Form->input('lastname', array('type' => 'text', 'class' => 'form-control', 'placeholder' => __('Enter Last Name'))); ?>
				</div>
			</div>
		</div>
		<div class="row dobrow form-group-container">
			<div class="col-lg-4">
				<div class="form-group">
					<label>
						<?php echo __('Date of Birth'); ?><span class="red_star_span"> *</span>
					</label>	
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
			</div>
			<div class="col-lg-4">
				<div class="form-group">
					<label>
						&nbsp;
					</label>	
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
			</div> 
			<div class="col-lg-4">
				<div class="form-group">
					<label>
						&nbsp;
					</label>	
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

		<div class="row">
			<div class="col-lg-6 gender_row">
				<div class="form-group">
					<label>
						<?php echo __('Gender'); ?><span class="red_star_span"> *</span>
					</label>	
					<?php
					echo $this->Form->input('gender', array(
						'options' => array('M' => 'Male', 'F' => 'Female'),
						'empty' => __('Select Gender'),
						'class' => 'form-control',
					));
					?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label>
						<?php echo __('Country'); ?><span class="red_star_span"> *</span>
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
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label>
						<?php echo __('State/Province'); ?><span class="red_star_span"> *</span>
					</label>	
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
			<div class="col-lg-6">
				<div class="form-group">
					<label>
						<?php echo __('City'); ?><span class="red_star_span"> *</span>
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
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label>
						<?php echo __('Zip'); ?><span class="red_star_span"> *</span>
					</label>	
					<?php
					echo $this->Form->input('zip', array(
						'class' => 'form-control zip',
						'data-rel' => 'country#state#city#zip',
						'placeholder' => __('Please enter a valid zip')
					));
					?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">        
				</div>
			</div>
		</div>
	</div>

	<div class="profile_picture">
		<div class="form_sections"><?php echo __('Profile Picture (Optional)'); ?></div>
		<div class="upload_photo">
			<div id="uploadPreview">
				<img src="/theme/App/img/user_default_1_medium.png" alt="Patient" class="img-circle profile_brdr_5 border_patient">
			</div>
			<div id="bootstrapped-fine-uploader">
				<div class="qq-upload-button-selector qq-upload-button btn" style="width: auto;">
					<div><?php echo __('Upload'); ?></div>
				</div>
			</div>
			<input type="hidden" value="" name="cropfileName" id="cropfileName" />
			<input type="hidden" value="0" name="x1" id="x1" />
			<input type="hidden" value="0" name="y1" id="y1" />
			<input type="hidden" value="200" name="w" id="w" />
			<input type="hidden" value="200" name="h" id="h" />
		</div>
		<div id="uploadmessages"></div>
		<div class="form-group checkbox_style">

                    <div class="checkbox">
                        <input class="chck_box" type="checkbox" id="UserNewsletter" name="data[User][newsletter]" value="1" checked />
                        <label for="UserNewsletter"><?php echo __('Sign up for newsletter'); ?></label>			
                    </div>
                    <div class="checkbox">
                        <input class="chck_box" type="checkbox" id="UserVolunteer" name="data[User][volunteer]" value="1"  />
                        <label for="UserVolunteer"><?php echo __('Sign me up to volunteer to join a support team'); ?></label>			
                    </div>			
	</div>

	<div class="condition_div">
		<div class="form_sections condition_heading"><?php echo __('Condition Details'); ?></div>
		<div id="conditions_container">
			<?php echo $this->element('User.Register/condition_row', array('index' => 0)); ?>
		</div>
		<div class="medication_add">
			<input type="hidden" id="last_condition_index" value="0" />
			<?php echo $this->element('User.Register/condition_row', array('id' => 'sample_condition_row', 'hide' => true, 'index' => 'index')); ?>
			<button type="button" class="upload_btn" id="add_condition_btn"><?php echo __('Add More'); ?></button>
		</div>
		<div class="form-group checkbox_style">
<!--			<label>
				<input class="chck_box" id="agree_check" name="data[User][agree]" type="checkbox" />
				<span id="terms_conditions"><?php echo __('I agree to the'); ?>&nbsp;<a target="_blank" href="/pages/terms_of_service"><?php echo __('Terms & Conditions'); ?></a>&nbsp;<?php echo __('and'); ?>&nbsp;<a target="_blank" href="/pages/terms_of_service"><?php echo __('Privacy Policy'); ?>.</a></span>
			</label>-->
                    <div class="checkbox">
                        <input class="chck_box" type="checkbox" id="agree_check" name="data[User][agree]"  />
                        <label for="agree_check"><span for="agree_check" id="terms_conditions"><?php echo __('I agree to the'); ?>&nbsp;<a target="_blank" href="/pages/terms_of_service"><?php echo __('Terms & Conditions'); ?></a>&nbsp;<?php echo __('and'); ?>&nbsp;<a target="_blank" href="/pages/terms_of_service"><?php echo __('Privacy Policy'); ?>.</a></span></label>			
                    </div>
		</div>
		<div class="signup_finish">
			<button type="button" class="btn btn_active" id="signup_finish_btn"><?php echo __('Finish'); ?></button>
			<button type="button" class="btn btn_clear" id="signup_cancel_btn" ><?php echo __('Cancel'); ?></button>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
</div>
    </div>