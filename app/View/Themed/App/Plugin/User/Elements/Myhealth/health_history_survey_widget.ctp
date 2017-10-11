<div class="modal fade" id="healthHistorySurveyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Health History Survey</h4>
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
					echo $this->Form->create('HealthHistorySurveyForm', array(
						'id' => 'healthHistorySurveyForm',
						'method' => 'POST',
						'enctype' => 'multipart/form-data',
						'inputDefaults' => $inputDefaults
					));
					?>
					<div class="wizard surveyWizard" id="healthHistorySurveyWizard" data-target="#healthHistorySurveyWizardContent">
						<div class="step-content" id="healthHistorySurveyWizardContent">
							<div class="step-pane active" id="health_history_survey_step1">
								<div class="form-group">
									<label>Have you been diagnosed with any of the following diseases ?</label>
									<span>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Acquired Immunodeficiency Syndrome (AIDS)or HIV Positive</span></p> 
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Arthritis</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Asthma</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Bronchitis</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Cancer</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Chlamydia</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Diabetes</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Dizziness</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Emphysema</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Epilepsy</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Eye Problem</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][diseases]" class="form-control" /><span>Fainting </span></p>
									</span>	
								</div>
							</div>
							<div class="step-pane" id="health_history_survey_step2">
								<div class="form-group">
									<label>Do you have any of the following infectious diseases ?</label>
									<span>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Chicken Pox</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Hepatitis</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Measles</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Mumps</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Pertussis / Whooping Cough</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Pneumonia</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Polio</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Rubella</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][infectious_diseases]" class="form-control" /><span>Scarlet Fever</span></p>
									</span>
								</div>
							</div>
							<div class="step-pane" id="health_history_survey_step3">
								<div class="form-group">
									<label>Have you been immunized ? Select the one’s that you have been:</label>
									<span>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Diphtheria</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Hepatitis B</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Measles</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Mumps</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Pertussis/Whooping Cough</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Polio</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Rubella</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Smallpox</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Tetanus</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Tuberculosis</span></p>
										<p><input type="checkbox" name="data[HealthHistorySurveyForm][immunized]" class="form-control" /><span>Typhoid</span></p>
									</span>	
								</div>								
							</div>
							<div class="step-pane" id="health_history_survey_step4">
								<div class="form-group">
									<label>Do you have allergies to any drugs/medications? If yes, please list them out below</label>
									<?php echo $this->Form->input('allergies'); ?>
								</div>
								<div class="form-group">
									<label>Do you consume alcohol ? If yes, how often ?</label>
									<?php echo $this->Form->input('alcohol_consumption_rate'); ?>
								</div>
								<div class="form-group">
									<label>Do you smoke any substance ?</label>
									<?php echo $this->Form->input('smoke'); ?>
								</div>	
							</div>
							<div class="step-pane" id="health_history_survey_step5">
								Thank you for completing this section !!
							</div>
						</div>
						<div class="wizard_steps_container">
							<button type="button" class="btn btn-default btn-prev flt_lft"><img src="/theme/App/img/back_arow.png" alt="Back"></button>
							<ul class="steps flt_lft">
								<?php
								for ($step = 1; $step <= 5; $step++) {
									echo $this->Html->tag('li', '', array(
										'data-target' => "#health_history_survey_step{$step}",
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