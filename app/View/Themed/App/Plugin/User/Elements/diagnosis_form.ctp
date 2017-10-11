<div class="diagnosis_form">
  
    <div class="form-group">
        <div><label><?php echo __('Diagnosis'); ?><span class="red_star_span"> *</span></label></div>
        <div class="row">
            <div class="col-lg-2 pull-right">
                <button type="button" style="font-size:20px;" class="close" aria-hidden="true">&times;</button>
            </div>
            <div class="col-lg-6 form-group">
                <?php
                echo $this->Form->input("PatientDisease.{$index}.disease_name", array_merge($options, array(
                    'class' => 'form-control disease_search',
                    'placeholder' => __('Type to select')
                )));
                echo $this->Form->hidden("PatientDisease.{$index}.disease_id", array('class' => 'disease_id_hidden'));
                ?>
                <div class="no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
            </div>
        </div>
    </div>
    <div class="form-group-container">
        <div><label><?php echo __('Date of diagnosis'); ?></label></div>
        <div class="row">
            <div class="col-lg-3 form-group">
                <?php
                echo $this->Form->input("PatientDisease.{$index}.diagnosis_date_year", array_merge($options, array(
                    'options' => $dob['diagnosisYear'],
                    'empty' => __('Year'),
                    'class' => 'form-control diagnosed-year'
                )));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div><label><?php echo __('Medication(s)'); ?></label></div>
        <div class="row">
            <div class="col-lg-12 form-group-col">
                <ul class="facelist">
                    <li class="token-input">
                        <?php
                        echo $this->Form->input("PatientDisease.{$index}.user_treatments", array_merge($options, array(
                            'type' => 'text',
                            'class' => 'medication_input',
                            'placeholder'=>'Multiple medications can be added'
                        )));
                        echo $this->Form->hidden("PatientDisease.{$index}.treatment_id", array('class' => 'treatment_id_hidden'));
                        ?>
                    </li>
                </ul>
                <div class="result_list" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>