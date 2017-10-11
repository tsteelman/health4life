<div class="form-group">
    <?php
    echo $this->Form->input("keyword_{$index}_symptoms", array_merge($options, array(
        'class' => 'complete_symptom added_field pull-left form-control',
	'id' => "keyword_{$index}_symptoms"
//        'placeholder' => __('Type to select')
    )));
    echo $this->Form->hidden("PatientDiseased{$index}symptoms_id", array('class' => 'symptoms_id_hidden'));
    echo $this->Form->hidden("PatientDiseased{$index}symptoms_id");
    ?>
    <div class="clearb no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
</div>