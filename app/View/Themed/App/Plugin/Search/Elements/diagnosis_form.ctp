<div class="form-group">
    <?php
    echo $this->Form->input("keyword_{$index}_diagnosis", array_merge($options, array(
        'class' => 'complete_diagnosis added_field pull-left form-control',
	'id' => "keyword_{$index}_diagnosis"
//        'placeholder' => __('Type to select')
    )));
    echo $this->Form->hidden("PatientDiseased{$index}disease_id", array('class' => 'disease_id_hidden'));
    echo $this->Form->hidden("PatientDiseased{$index}disease_id");
    ?>
    <div class="clearb no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
</div>