	<?php if ( isset ( $symptoms ) && !empty ( $symptoms ) ) { ?>
        <div class="symptom_table_container">            
           <div class="">
    
		    <table class="table" id="symptom_severity">
		      <thead>
		        <tr>          
		           	<th class="table_symptos text-center"><label>Symptom</label></th>
		        	<th class="table_symptos text-center" colspan="4"><label>Severity</label></th>
		          	
		            <th class="conditions text-center">Conditions</th>		            
		        </tr>
		      </thead>
		      <tbody>
      	
      			<?php
                        
                        foreach ( $symptoms as $symptom ){ 
                          
                          ?>
                          <tr>        
    <td class="table_symptos"><label><a href="/mysymptom/<?php echo $user_details['username']; ?>/<?php echo urlencode(($symptom['id'])) ?>"><?php echo __(h($symptom['name'])) ?></a></label></td>
    <td class="condition_indicator">
        <label class="condition_none <?php if ($symptom['value'] == "1") {
    echo "on";
} ?> ">
            <span class="name">None</span>

            <input  name="data[mySymptoms][symptoms][<?php echo $symptom['id']; ?>]" type="radio" value="1">
        </label>
    </td>
    <td class="condition_indicator">
        <label class="condition_mild  <?php if ($symptom['value'] == "2") {
    echo "on";
} ?> ">
            <span class="name">Mild</span>
            <input  name="data[mySymptoms][symptoms][<?php echo $symptom['id']; ?>]" type="radio" value="2">
        </label></td>
    <td class="condition_indicator">
        <label class="condition_moderate <?php if ($symptom['value'] == "3") {
    echo "on";
} ?> ">
            <span class="name">Moderate</span>
            <input  name="data[mySymptoms][symptoms][<?php echo $symptom['id']; ?>]" type="radio" value="3">
        </label>
    </td>
    <td class="condition_indicator">
        <label class="condition_severe <?php if ($symptom['value'] == "4") {
    echo "on";
} ?> ">
            <span class="name">Severe</span>
            <input  name="data[mySymptoms][symptoms][<?php echo $symptom['id']; ?>]" type="radio" value="4">
        </label></td>
    <td class="conditions"><?php echo __(h($symptom['conditions'])); ?></td>          
</tr>
       <?php                     
                        }?>
      		  </tbody>
    	     </table>
    	
  			</div>
        </div>  
        
        <div class="row">
	        <?php
	        	
	         	$options = array('div' => false, 'label' => 'Save', 'type'=>'submit', 'class' => 'pull-left btn btn_active '); 
	    		echo $this->Form->end($options); 
	    	?>
    	</div>
    
    	
    
	    <?php 
	      	} else {
		?> 
				
			<?php echo __('No symptoms found'); ?>
						
		<?php
			}
	    ?>
