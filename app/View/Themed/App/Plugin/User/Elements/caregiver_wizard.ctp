<div class="wizard hide" id="registrationWizard_<?php echo $type ?>">
	<ul class="steps">
	<li data-target="#step_<?php echo $type ?>_1" data-title="Caregiver SignUp" class="active"></li>
	<li data-target="#step_<?php echo $type ?>_2" data-title="Complete your profile" ></li>
	<li data-target="#step_<?php echo $type ?>_3" data-title="Share Patient Details" ></li>
	<li data-target="#step_<?php echo $type ?>_4" data-title="Share Diagnosis Details"></li>	
	</ul>
	<div class="step-content signup_fields">
		<div class="step-pane active" id="step_<?php echo $type ?>_1">
			<?php
			echo $this->element('User.basic_form');
			?>
		</div>

		<div class="step-pane" id="step_<?php echo $type ?>_2">
			<?php
			echo $this->element('User.profile_form', array(
				'profilePhotoClass' => 'border_caregiver',
				'defaultProfilePhoto' => 'user_default_3_medium.png',
				'type' => $type
				));
			?>
			<div class=" flt_lft btn_area">
				<button type="button" class="btn btn-prev"><?php echo $this->Html->image('back_arow.png', array('alt' => 'Back')); ?>&nbsp;Back</button>
				<button type="button" class="btn btn-next">Next &nbsp;</button>
			</div>	
		</div>

		<div class="step-pane" id="step_<?php echo $type ?>_3">
			<?php
			echo $this->element('User.caregiver_patient_form');
			?>
		</div>
		
		<div class="step-pane" id="step_<?php echo $type ?>_4">
         <?php echo $this->element('User.Register/diagnosis_form_list'); ?>
         <div class=" flt_lft btn_area">
             <button type="button" class="btn  btn-prev"><?php echo $this->Html->image('back_arow.png', array('alt' => 'Back')); ?>&nbsp;Back</button>
             <button type="button" class="btn btn-finish btn-next">Finish</button>
         </div>
		</div>		
	</div>

</div>