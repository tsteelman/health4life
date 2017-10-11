<div class="form-group">
    <?php
    echo $this->Form->input("keyword_{$index}_location", array_merge($options, array(
        'class' => 'complete_location added_field pull-left form-control',
	'id' => "keyword_{$index}_location"
//        'placeholder' => __('Type to select')
    )));
    echo $this->Form->hidden("PatientDiseased{$index}location_id", array('class' => 'location_id_hidden'));
    echo $this->Form->hidden("PatientDiseased{$index}location_id");
    ?>
    <div class="clearb no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
</div>