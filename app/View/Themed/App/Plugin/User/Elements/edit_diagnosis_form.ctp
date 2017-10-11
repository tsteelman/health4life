<?php 
$is_disease_set = (isset($disease_list))? true : false; 
?>
<?php foreach($disease_list as $disease): ?>
<div <?php if($type == 1 || $type == 3){ ?>class="diagnosis_form" <?php } else { ?> class="diagnosis_support_form" <?php } ?> >
  <div id="disease-<?php echo $disease['PatientDisease']['id']; ?>">
    <div class="form-group">
        <div><label><?php echo __('Diagnosis'); ?>
				<?php if ($type == 1) { ?>
				<span class="red_star_span"> *</span>
				<?php } ?>
			</label></div>
        <div class="row">
            <div class="col-lg-2 pull-right">
                <button type="button" style="font-size:20px;" 
						class="close_add_form" id="<?php echo $disease['PatientDisease']['id']; ?>"	data-diseaseId="<?php echo $disease['PatientDisease']['disease_id']; ?>"
						aria-hidden="true">&times;
				</button>
            </div>
            <div class="col-lg-6 form-group">
                <?php 
                echo $this->Form->input("PatientDisease.{$index}.disease_name", array_merge($options, array(
                    'class' => 'form-control disease_search',
                    'default' => ($is_disease_set)? $disease['Disease']['name'] : "",
                    'placeholder' => __('Type to select')
                )));
                echo $this->Form->hidden("PatientDisease.{$index}.disease_id", 
                  array('class' => 'disease_id_hidden',
                    'value' => ($is_disease_set)? $disease['Disease']['id'] : ""));
                echo $this->Form->hidden("PatientDisease.{$index}.id", 
                  array('class' => 'patient_disease_id_hidden',
                    'value' => ($is_disease_set)? $disease['PatientDisease']['id'] : ""));    
                ?>
                <div class="no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
            </div>
        </div>
    </div>
	  <?php if ($type == 1 || $type == 3):?>
    <div class="form-group-container">
        <div><label><?php echo __('Date of diagnosis'); ?></label></div>
        <div class="row">
            <div class="col-lg-7 form-group">
                <?php
                $year = $disease['PatientDisease']['diagnosis_date']; 
                echo $this->Form->input("PatientDisease.{$index}.diagnosis_date_year", array_merge($options, array(
                    'options' => $dob['year'],
                    'default' => (($year == "0000-00-00 00:00:00") || (empty($year)))? "" : strftime("%Y", strtotime($year)),
                    'empty' => __('Year'),
                    'class' => 'form-control diagnosed-year'
                )));
                ?>
            </div>
        </div>
    </div>
	  <?php endif; ?>
    <div class="form-group">
        <div><label><?php echo __('Medication(s)'); ?></label></div>
        <div class="row">
            <div class="col-lg-12 form-group-col">
                <ul class="facelist">
                    <?php $treatment_value = ""; ?>
                    <?php if (!empty($treatments)): foreach ($treatment_list as $key => $treatment) : ?>
                    <?php if ($disease['PatientDisease']['id'] == $key): ?>
                    <?php $treatment_value = implode(',', $treatment).",";?>
                        <?php foreach ($treatment as $id): ?>
                        <?php if ($id != 0): ?>
                        <li class="token" id="bit-<?php echo $id; ?>">
                          <span>
                            <span>
                              <span><span><?php echo $treatments[$id]; ?></span></span>
                            </span>
                          </span>
                          <span class="x"> .x</span>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php endforeach; endif;?>
                    <li class="token-input">
                        <?php
                        echo $this->Form->input("PatientDisease.{$index}.user_treatments", array_merge($options, array(
                            'type' => 'text',
                            'class' => 'medication_input',
                            'value' => ''
                        )));
                        
                        echo $this->Form->hidden("PatientDisease.{$index}.treatment_id", array(
                          'class' => 'treatment_id_hidden',
                          'value'=> $treatment_value
                          ));
                          $index++;
                        ?>
                    </li>
                </ul>
                <div class="result_list" style="display:none;"></div>
                <span class="plus_disabled">Type a medication to show suggestion. Multiple medication can be added</span>
            </div>
        </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
<input type="hidden" id="diagnosis_last_index" value="<?php echo $index-1; ?>" />

