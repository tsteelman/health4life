<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');

if ($is_owner) {
    $this->Html->addCrumb('My Health', '/profile/myhealth');
} else {
    $this->Html->addCrumb($user_details['username'] . "'s Health", Common::getUserProfileLink($user_details['username'], true) . '/myhealth');
}

if ($is_owner) {
    $this->Html->addCrumb('My Symptoms','/profile/mysymptom');
} else {
    $this->Html->addCrumb($user_details['username'] . "'s Symptoms",  Common::getUserProfileLink($user_details['username'], true) . '/mysymptom');
}


$this->Html->addCrumb(h($symptomdetails['Symptom']['name']));
?>
<div class="container">
    <input type="hidden" name="graphUpdatedInRoom" value="<?php echo $graphRoom; ?>" id="graphUpdatedInRoom">
    <div class="mysymptoms">
	<?php if ($is_owner): ?>
		<button class="btn print_btn pull-right print_button" data-toggle="modal" data-target="#printGraph" data-backdrop="static" data-keyboard="false">Print</button>
    <?php endif; ?>
	<h2><?php echo __(h($symptomdetails['Symptom']['name'])) ?></h2>
    <div class="graph_container">
        
    <div class="history_container">    
         <div class="row"> 
        <div class="col-lg-6">
            <h3>
                <?php if($is_owner): ?>
                My <?php echo __(h($symptomdetails['Symptom']['name'])) ?> History
                <?php else:
                    echo ucfirst($user_details['username']) .' '.__(h($symptomdetails['Symptom']['name'])). " History";
                    endif; ?>
            </h3>
        </div>
             <div class="col-lg-6">
                 <input type="hidden" name="data[mySymptoms][SymptomDate]" id="symptomHistoryDatepicker" class="pull-right btn create_button">
                 <?php if ($is_owner): ?>
                     <button <?php if ($userSymptomCount == 0) { ?> style="display:none;"<?php } ?> onClick="return false" id="add_new_history_button" class="pull-right btn create_button " ><?php echo __('Add Severity'); ?></button>


                     <button <?php if ($userSymptomCount == 0) { ?> style="display:none;"<?php } ?>data-symptom-id="<?php echo $symptomdetails['Symptom']['id']; ?>" id="delete_user_symptom" class="pull-right btn btn_active " >Remove this Symptom</button>
                     <button <?php if ($userSymptomCount > 0) { ?> style="display:none;"<?php } ?> data-symptom-id="<?php echo $symptomdetails['Symptom']['id']; ?>" id="add_user_symptom" class="pull-right btn create_button " >Add this Symptom</button>
                 <?php endif; ?>  
             </div>      
      	</div> 
        <br/>
        <div class="row">
            <div class="col-lg-1 col-sm-1 col-xs-1 col-md-1 symptom_severity_list">
                <ul>
                    <li>Severe</li>                    
                    <li>Moderate</li>
                    <li>Mild</li>
                    <li>None</li>   
                </ul>
            </div>
        <div class="col-lg-11 col-sm-11 col-xs-11 col-md-11" id="symptomSeverityDetailGraph" data-username="<?php echo $user_details['username']; ?>"  data-symptom_name="<?php echo __(h($symptomdetails['Symptom']['name'])) ?>" style="height: 298px;">

        </div>    
        </div>
	
    <div class="symptom_detail  row">    	 
          <div class="col-lg-12"> 
              <h5>My <?php echo __(h($symptomdetails['Symptom']['name'])) ?> history</h5>
              <input type="hidden" id="symptom_id" value="<?php echo $symptomdetails['Symptom']['id'];  ?>"/>
              <input type="hidden" id="symptom_filter_year" value=""/>              
          </div>
     
        <div id="symptom_history_row" >
            
        </div>
            
      </div>
        
    </div>
  
    </div>
      
    </div>
    </div>

  <div id="symptom_conditions" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Symptoms severity tracker</h4>
      </div>
      <div class="modal-body">

          <h4>How was your <?php echo __(h($symptomdetails['Symptom']['name'])); ?> on <span id="date-selected"></span> ?</h4>
          <input type="hidden" id="selectedSymptomDate">

          <div class="btn-toolbar" role="toolbar">
              <div class="condition_popup_container">

<div class="condition_indicator condition_none_header">
    <label class="condition_none ">
            <span class="name">None</span>

            <input name="symptomHistoryRadio" type="radio" value="1">
        </label>
</div>
<div class="condition_indicator condition_mild_header">
    <label class="condition_mild ">
            <span class="name">Mild</span>

            <input name="symptomHistoryRadio" type="radio" value="2">
        </label>
</div>
<div class="condition_indicator condition_moderate_header">
    <label class="condition_moderate ">
            <span class="name">Moderate</span>

            <input name="symptomHistoryRadio" type="radio" value="3">
        </label>
</div>
<div class="condition_indicator condition_severe_header">
    <label class="condition_severe ">
            <span class="name">Severe</span>

            <input name="symptomHistoryRadio" type="radio" value="4">
        </label>
</div>
                  <span id="symptom_history_error_message" style="display: none; color: red;"> Please select valid severity.</span>
              </div>
    </div>
      </div>
      <div class="modal-footer">
          
        <button type="button" id="symptom_history_save" class="btn btn_active" >Save</button>
        <button type="button" id="symptom_history_cancel" class="btn btn_clear" data-dismiss="modal">Cancel</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php echo $this->element('User.Myhealth/graph_printer', array('printData' => array($symptomdetails['Symptom']['id'] => $symptomdetails['Symptom']['name']), 'isSymptomPrint' => true, 'isSymptomDetail' => true)); ?>
<?php
echo $this->AssetCompress->script('chart.js');
echo $this->AssetCompress->css('graph');
?>

<script type="text/javascript">
    $(document).ready(function(e) { 
    
    load_symptom_history();
    getSymptomSeverityGraph('symptomSeverityDetailGraph');

});


function load_symptom_history(page) {
sym_id = $('#symptom_id').val();
    $.ajax({
        url: '/symptom/history/list',
        cache: false,
        type: 'POST',
        data: {'sym_id': sym_id},
        success: function(result) {
            console.log
            $('#symptom_history_row').append(result);


        }
    });
}

$(document).on('click', '#add_new_history_button', function() { 
        $("#symptomHistoryDatepicker").datepicker('show'); 
     });

    </script>
    
  
