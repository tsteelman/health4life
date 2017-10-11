<div class="form-group">
    <?php
    echo $this->Form->input("keyword_{$index}_treatment", array_merge($options, array(
        'class' => 'complete_treatment added_field pull-left form-control',
	'id' => "keyword_{$index}_treatment"
//        'placeholder' => __('Type to select')
    )));
    echo $this->Form->hidden("PatientDiseased{$index}treatment_id", array('class' => 'treatment_id_hidden'));
    echo $this->Form->hidden("PatientDiseased{$index}treatment_id");
    ?>
    <div class="clearb no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
</div>