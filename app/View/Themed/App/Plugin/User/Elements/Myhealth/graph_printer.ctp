<!-- Modal -->
<div class="modal fade" id="printGraph" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_print" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Select informations to print</h4>
            </div>
			<div class="select_graph">
				Select the types of data to include
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
						<?php foreach ($printData as $key => $value) { ?>
							<div class="clearfix form-group graph_option">
								<div class="col-lg-12">
									<span>
										<input type="checkbox" name="graph_options_list[]" value="<?php echo $key; ?>" id="select_graph_option<?php echo $key; ?>" <?php if(isset($isSymptomDetail)) { ?> checked <?php } ?> >
										<label for="select_graph_option<?php echo $key; ?>" class="print_options"><?php echo $value; ?></label>
									</span>
								</div>
							</div>
						<?php } ?>
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
				<button id="print_button" type="button" class="btn btn_active" data-style="expand-right" data-graphtype= "<?php echo (isset($isSymptomPrint)) ? 'symptom' : 'normal' ; ?>" ><span class="ladda-label">Ok</span><span class="ladda-spinner"></span></button>
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

