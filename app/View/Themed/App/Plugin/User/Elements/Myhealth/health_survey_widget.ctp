<div class="col-lg-6 surveyWizardContainer wizard_myhealth">
	
	<?php
	$inputDefaults = array(
		'label' => false,
		'div' => false,
		'legend' => false,
		'class' => 'form-control'
	);
	echo $this->Form->create('HealthSurveyForm', array(
		'id' => 'healthSurveyForm',
		'method' => 'POST',
		'enctype' => 'multipart/form-data',
		'inputDefaults' => $inputDefaults
	));
	?>
	<div class="wizard surveyWizard" id="healthSurveyWizard" data-target="#healthSurveyWizardContent">
		<div class="step-content" id="healthSurveyWizardContent">
			<div class="step-pane active" id="step1">
				<div class="form-group">
					<label>Hiya ! Please select your Gender.</label>
					<span>
						<p><input type="radio" name="data[HealthSurveyForm][gender]"  class="form-control" value="M" /><span>Male</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][gender]"  class="form-control" value="F" /><span>Female</span></p>
					</span>
				</div>
				<div class="form-group">
					<label>That’s great, can you tell us how old you are ? :)</label>
					<?php
					echo $this->Form->input('age', array(
						'options' => array(
							'Under 18 years',
							'18 to 24 years',
							'25 to 34 years',
							'35 to 39 years',
							'40+ years'
						)
					));
					?>
				</div>
			</div>
			<div class="step-pane" id="step2">
				<div class="form-group" id="diagnosis_field_group">
					<label>Have you been diagnosed with Crohn’s Disease yet ?</label>
					<span>
                                            <p><input type="radio" name="data[HealthSurveyForm][is_diagnosed]" class="form-control" value="Y" /><span>Yes</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][is_diagnosed]" class="form-control" value="N" /><span>No</span></p>
						
					</span>
				</div>
				<div id="sorry_message" class="hide">Sorry this survey is for people who have been diagnosed with Crohn’s Disease</div>
			</div>
			<div class="step-pane" id="step3">
				<div class="form-group">
					<label>You might be having a few symptoms related with Crohn’s, please check all that are valid:</label>
					<span>
                                              <p><input type="checkbox" name="data[HealthSurveyForm][symptoms]" class="form-control" /><span>Feeling Sick</span></p>
                                                <p><input type="checkbox" name="data[HealthSurveyForm][symptoms]" class="form-control" /><span>Stomach pain/cramps</span></p>
                                                  <p><input type="checkbox" name="data[HealthSurveyForm][symptoms]" class="form-control" /><span>Frequent, watery diarrhea</span></p>
                                                    <p><input type="checkbox" name="data[HealthSurveyForm][symptoms]" class="form-control" /><span>Fatigue</span></p>
                                                      <p><input type="checkbox" name="data[HealthSurveyForm][symptoms]" class="form-control" /><span>High Fever</span></p>
						</span>
				</div>
			</div>
			<div class="step-pane" id="step4">
				<div class="form-group">
					<label>In general, how sick have you felt ?</label>
					<span>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>Terrible</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>Very bad</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>Bad</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>Little worse than I usually do</span></p>
					</span>					
				</div>
				<div class="form-group">
					<label>In the past week, have you worried that you would..</label>
					<span>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>be unable to reach a restroom in time ?</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>need to avoid places without a restroom ?</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][severity]" class="form-control" /><span>need to cancel an outing ?</span></p>
					</span>
				</div>
			</div>
			<div class="step-pane" id="step5">
				<div class="form-group">
					<label>Have you felt the following in the past week..</label>
					<span>
						<p><input type="checkbox" name="data[HealthSurveyForm][feeling]" class="form-control" /><span>Depressed ?</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][feeling]" class="form-control" /><span>Irritable ?</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][feeling]" class="form-control" /><span>Almost burst into tears?</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][feeling]" class="form-control" /><span>Frustrated ?</span></p>
					</span>	
				</div>
				<div class="form-group">
					<label>Do you have any of the following symptoms that usually occur with Crohn’s ?</label>
					<span>
						<p><input type="checkbox" name="data[HealthSurveyForm][symptoms_with]" class="form-control" /><span>Arthritis</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][symptoms_with]" class="form-control" /><span>Colon Cancer</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][symptoms_with]" class="form-control" /><span>Kidney Stones</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][symptoms_with]" class="form-control" /><span>Uveitis</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][symptoms_with]" class="form-control" /><span>Mouth sores</span></p>
					</span>					
				</div>
			</div>
			<div class="step-pane" id="step6">
				<div class="form-group">
					<label>Are you taking any of the following medicines to manage Crohn’s Symptoms ?</label>
					<span>
						<p><input type="checkbox" name="data[HealthSurveyForm][medicines]" class="form-control" /><span>Anti-Diarrhea (Lomotil, Imodium etc)</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][medicines]" class="form-control" /><span>Antibiotics (Cipro, Flagyl etc)</span></p>
						<p><input type="checkbox" name="data[HealthSurveyForm][medicines]" class="form-control" /><span>Narcotic pain relievers (Vicodin, Percocet etc)</span></p>
					</span>	
				</div>
				<div class="form-group">
					<label>Are you having trouble with your weight ?</label>
					<span>
						<p><input type="radio" name="data[HealthSurveyForm][weight_trouble]" class="form-control" /><span>No, my weight is normal</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][weight_trouble]" class="form-control" /><span>Yes, I’m overweight.</span></p>
						<p><input type="radio" name="data[HealthSurveyForm][weight_trouble]" class="form-control" /><span>Yes, I’m underweight.</span></p>
					</span>	
				</div>
			</div>			
			<div class="step-pane" id="step7">
				Thank You!
			</div>
		</div>
		<div class="wizard_steps_container">
			<button type="button" class="btn btn-default btn-prev flt_lft"><img src="/theme/App/img/wizard/wizard_prev1.png" alt="Back"></button>
			<div class="text-center">
				<ul class="steps">
					<?php
					for ($step = 1; $step <= 7; $step++) {
						echo $this->Html->tag('li', '', array(
							'data-target' => "#step{$step}",
							'class' => ($step === 1) ? 'active' : ''
						));
					}
					?>
				</ul>
			</div>
			<button type="button" class="btn btn-next flt_rt"><img src="/theme/App/img/wizard/wizard_next1.png" alt="Next"></button>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
	/*
	 * Health Survey Wizard handling
	 */
	var healthSurveyWizard = $('#healthSurveyWizard').wizard();
	healthSurveyWizard.on('change', function(e, data) {
		if (typeof(data) !== "undefined" && data.direction === "next" && (data.step === 2)) {
			if ($('input[name="data[HealthSurveyForm][is_diagnosed]"]:checked').val() === 'N') {
				$('#diagnosis_field_group').addClass('hide');
				$('#sorry_message').removeClass('hide');
				return false;
			}
		}
		else if (typeof(data) !== "undefined" && data.direction === "previous" && (data.step === 2)) {
			if ($('#diagnosis_field_group').hasClass('hide')) {
				$('#diagnosis_field_group').removeClass('hide');
				$('#sorry_message').addClass('hide');
				return false;
			}
		}
	});
</script>