<!-- Modal -->
<div class="modal fade" id="medical_summary_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_print" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Print your Medical Summary</h4>
            </div>
			<div class="select_graph">
				Select the details of your Health to take a print
				<div class="selectAll" style="padding-top: 10px;">
					<a href='javascript:void(0)' class="select_all_graphs">Select all</a>
					<span>|</span>
					<a href='javascript:void(0)' class="clear_all_graphs">Clear</a>
				</div>
				<div class="modal-body">
					<?php   echo $this->Form->create('Print', array(
                                    'id' => 'printForm',
									'type' => 'GET'
//									'target' => '_blank',
					)); ?> 
					<div class="graph_print_list slim-scroll">
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span>
										<input type="checkbox" name="graph_title_list[]" id="select_print_title0">
										<label for="select_print_title0">Current Health</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="0" id="select_graph_option0" class="health_options">
										<label for="select_graph_option0" class="print_options">Weight</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="1" id="select_graph_option1" class="health_options">
										<label for="select_graph_option1" class="print_options">Blood Pressure</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="2" id="select_graph_option2" class="health_options">
										<label for="select_graph_option2" class="print_options">Temperature</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="3" id="select_graph_option3" class="health_options">
										<label for="select_graph_option3" class="print_options">BMI</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="4" id="select_graph_option4" class="health_options">
										<label for="select_graph_option4" class="print_options">Health Status</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span>
										<input type="checkbox" name="graph_title_list[]" id="select_print_title1">
										<label for="select_print_title1">Trackers</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="5" id="select_graph_option5" class="tracker_options">
										<label for="select_graph_option5" class="print_options">Pain Tracker</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="6" id="select_graph_option6" class="tracker_options">
										<label for="select_graph_option6" class="print_options">Quality Of Life</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="7" id="select_graph_option7" class="tracker_options">
										<label for="select_graph_option7" class="print_options">Sleeping Habits</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span>
										<input type="checkbox" name="graph_title_list[]" id="select_print_title2">
										<label for="select_print_title2">Body Pain Tracker</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="8" id="select_graph_option8" class="body_options">
										<label for="select_graph_option8" class="print_options">Head Area</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="9" id="select_graph_option9" class="body_options">
										<label for="select_graph_option9" class="print_options">Chest Area</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="10" id="select_graph_option10" class="body_options">
										<label for="select_graph_option10" class="print_options">Abdomen</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="11" id="select_graph_option11" class="body_options">
										<label for="select_graph_option11" class="print_options">Pelvic Area</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="12" id="select_graph_option12" class="body_options">
										<label for="select_graph_option12" class="print_options">Back Area</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="13" id="select_graph_option13" class="body_options">
										<label for="select_graph_option13" class="print_options">Arm</label>
									</span>
								</div>
							</div>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span class="print_select_options">
										<input type="checkbox" name="graph_options_list[]" value="14" id="select_graph_option14" class="body_options">
										<label for="select_graph_option14" class="print_options">Legs</label>
									</span>
								</div>
							</div>
							<?php if(!empty($dailyHealthIndicator)) { ?>
								<div class="clearfix form-group graph_option">
									<div class="col-lg-12">
										<span>
											<input type="checkbox" name="graph_title_list[]" id="select_print_title3">
											<label for="select_print_title3">Daily Health Indicators</label>
										</span>
									</div>
								</div>
								<?php foreach($dailyHealthIndicator as $symptom) { ?>
									<div class="clearfix form-group graph_option">
										<div class="col-lg-12">
											<span class="print_select_options">
												<input type="checkbox" name="graph_options_list[]" value="16" id="select_graph_option<?php echo $symptom['id']; ?>" class="symptom_options" data-symptom = "<?php echo $symptom['id']; ?>">
												<label for="select_graph_option<?php echo $symptom['id']; ?>" class="print_options"><?php echo $symptom['name']; ?></label>
											</span>
										</div>
									</div>
							<?php } } ?>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span>
										<input type="checkbox" name="graph_options_list[]" value="15" id="select_graph_option15">
										<label for="select_graph_option15">Medication Scheduler</label>
									</span>
								</div>
							</div>
					</div>
				</div>
				<div style="margin-bottom : 10px;">For these dates</div>
				<div class="modal-body">
					<div class="clearfix form-group">
						<div class="row">
							<div class="col-lg-6">
								<label class="radio-inline">
									<input type="radio" name="dateSelect" id="dateSelectType1" value="0"  />
									<?php echo __('Period'); ?>
								</label>
								<label class="radio-inline">
									<input type="radio" name="dateSelect" id="dateSelectType2" value="1"   />
									<?php echo __('Date range'); ?>
								</label>
							</div>
						</div>	
					</div>
					<?php $periodOptions = array(2 => 'All dates', 3 => 'Last week', 4 =>  'Last month', 5 =>  'Last year',  6 => 'Year till date'); ?>
					<div class="hidden periodSelection rangeSelector">
						<div class="row">
							<div class="col-lg-4">
							   <?php echo $this->Form->input('', array('class'=> 'periodSelectionValue form-control', 'type' => 'select','options' => $periodOptions)); ?> 
						   </div>
						</div>
					</div>
					<div class="hidden dateSelection rangeSelector">
						<div class="row">
							<div class="col-lg-4">
							   <?php echo $this->Form->input('From', array('type' => 'text', 'readonly' => 'readonly', 'class' => 'dateInterval form-control')); ?> 
						   </div>
							<div class="col-lg-4">
								<?php echo $this->Form->input('To', array('type' => 'text', 'readonly' => 'readonly', 'class' => 'dateInterval form-control')); ?> 
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="print_error_message" class="alert alert-error health_reading_error_message" style="display: none;"></div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;"> 
				<button id="print_summary" type="button" class="btn btn_active" data-style="expand-right"><span class="ladda-label">Ok</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn_clear close_print" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$(document).ready(function() {
	$('.dateInterval').datepicker({            
    });
});
	
</script>

