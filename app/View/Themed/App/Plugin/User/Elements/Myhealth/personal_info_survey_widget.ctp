<div class="modal fade" id="personalInfoSurveyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Personal Information Survey</h4>
            </div>
            <div class="modal-body">
				<div class="surveyWizardContainer">
					<?php
					$inputDefaults = array(
						'label' => false,
						'div' => false,
						'legend' => false,
						'class' => 'form-control'
					);
					echo $this->Form->create('PersonalSurveyForm', array(
						'id' => 'personalSurveyForm',
						'method' => 'POST',
						'enctype' => 'multipart/form-data',
						'inputDefaults' => $inputDefaults
					));
					?>
					<div class="wizard surveyWizard" id="personalSurveyWizard" data-target="#personalSurveyWizardContent">
						<div class="step-content" id="personalSurveyWizardContent">
							<div class="step-pane active" id="personal_survey_step1">
								<div class="form-group">
									<label>Greetings! Could you tell us your first name?</label>
									<?php echo $this->Form->input('firstname'); ?>
								</div>
								<div class="form-group">
									<label>And now your last name please? :)</label>
									<?php echo $this->Form->input('lastname'); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step2">
								<div class="form-group">
									<label>Where do you live? Enter the first two lines of your address below!</label>
									<?php echo $this->Form->input('address'); ?>
								</div>
								<div class="form-group">
									<label>You might be living in a city that you really love, care to tell us which one? ;)</label>
									<?php echo $this->Form->input('city'); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step3">
								<div class="form-group">
									<label>Under which state does your city fall?</label>
									<?php echo $this->Form->input('state'); ?>
								</div>
								<div class="form-group">
									<label>Please type in your Zipcode.</label>
									<?php echo $this->Form->input('zipcode'); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step4">
								<div class="form-group">
									<label>Which Country do you reside in currently?</label>
									<?php echo $this->Form->input('country'); ?>
								</div>
								<div class="form-group">
									<label>Everyone has a mobile these days, we believe you have one too, care to tell us your number?</label>
									<?php echo $this->Form->input('mobile'); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step5">
								<div class="form-group">
									<label>Enter your email-id as well please.</label>
									<?php echo $this->Form->input('email'); ?>
								</div>
								<div class="form-group">
									<label>Please select your gender.</label>
									<span>
										<input type="hidden" name="data[PersonalSurveyForm][gender]" id="SurveyGender_" value=""/>
										<p><input type="radio" name="data[PersonalSurveyForm][gender]" id="SurveyGenderF" class="form-control" value="M" /><span>Male</span></p>
										<p><input type="radio" name="data[PersonalSurveyForm][gender]" id="SurveyGenderF" class="form-control" value="F" /><span>Female</span></p>
									</span>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step6">
								<div class="form-group">
									<label>In which year were you born ?</label>
									<?php echo $this->Form->input('dob_year'); ?>
								</div>
								<div class="form-group">
									<label>Great, now please enter the month in which you were born.</label>
									<?php echo $this->Form->input('dob_month'); ?>
								</div>
								<div class="form-group">
									<label>Excellent, now please enter the day you were born.</label>
									<?php echo $this->Form->input('dob_day'); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step7">
								<div class="form-group">
									<label>Please enter your race/ethnicity below.</label>
									<?php echo $this->Form->input('race'); ?>
								</div>
								<div class="form-group">
									<label>Do you have any birthmarks or scars? If you do, please describe, else skip to the next question</label>
									<?php echo $this->Form->input('scars', array('type' => 'textarea')); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step8">
								<div class="form-group">
									<label>What is your Blood/RH type?</label>
									<?php echo $this->Form->input('blood_rh_type'); ?>
								</div>
								<div class="form-group">
									<label>Do you have any special conditions? If yes, describe it below, else you can finish the survey.</label>
									<?php echo $this->Form->input('conditions'); ?>
								</div>
							</div>
							<div class="step-pane" id="personal_survey_step9">
								Thank You!
							</div>
						</div>
						<div class="wizard_steps_container">
							<button type="button" class="btn btn-default btn-prev flt_lft"><img src="/theme/App/img/back_arow.png" alt="Back"></button>
							<ul class="steps flt_lft">
								<?php
								for ($step = 1; $step <= 9; $step++) {
									echo $this->Html->tag('li', '', array(
										'data-target' => "#personal_survey_step{$step}",
										'class' => ($step === 1) ? 'active' : ''
									));
								}
								?>
							</ul>
							<button type="button" class="btn btn-next flt_rt"><img src="/theme/App/img/nxt_arow.png" alt="Next"></button>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
				<br clear="all" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>			
            </div>
        </div>
    </div>
</div>