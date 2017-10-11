<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');

$this->Html->addCrumb('My Health', '/profile/myhealth');

$this->Html->addCrumb('My Symptoms');
?>
<div class="container">
    <div class="mysymptoms">
    <h2>My Symptoms</h2>
    <div class="graph_container">

      
      	<?php
    		echo $this->Form->create('mySymptoms', array(
    			'url' => array(
						'controller' => 'mysymptom',
						'action' => 'addSymptomSeverity'
				),
				'div' => false)); 
    	?>
        <div class="row">             
	        <div class="col-lg-6"><h3>Update the severity of Symptoms for  <?php echo __( h( $titleDate ) ); ?></h3></div>                

	        <div class="col-lg-6">
	        	       	
	          	<button data-toggle="modal" data-target="#addSymptom" class="pull-right btn create_button " ><?php echo __('Create new Symptom'); ?></button>
	          	<input type="hidden" name="data[mySymptoms][SymptomDate]" id="symptomDatepicker" class="pull-right btn create_button " value ="<?php echo __($date); ?>" >	 
	          	<input type="hidden" id="symptomListPage">
				<button <?php if (empty($symptoms)):?> style="display:none;" <?php endif; ?>onClick="return false" id="add_new_score_button" class="pull-right btn create_button " ><?php echo __('Add Severity'); ?></button>
	          	</div>      
      	</div>
        <div id="symptom_user_list">
      	<?php        
        echo $this->element('User.Mysymptom/symptom_list',array('symptoms'=>$symptoms)); 
        
        ?>
        </div>
    </div>
   </div>
</div>

<?php echo $this->element('User.Mysymptom/add_symptom'); ?>
    
<script type="text/javascript">
    $(document).on('click', '#symptom_severity td label input', function(){        
        $( this ).closest('tr')	.find('label').removeClass('on');
        $( this ).parent().addClass('on');
        });
 
    $("#add_new_score_button").click(function() {
        $("#symptomDatepicker").datepicker('show'); 
     });
     
     $(document).ready( function(){
            $('#symptomDatepicker').datepicker({
                  minDate: "-2y",    
                  maxDate: getUserNow(),
                  defaultDate: getUserNow(),
                  dateFormat: "yy-mm-dd",
                  onSelect: function(dateText) {
    		window.location.href = "/profile/mysymptom?date=" + dateText  ;
    	      }
        });
     });

</script>
