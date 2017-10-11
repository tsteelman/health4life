<div class="form-group">
    <?php
	if(isset($disease)) {
		echo $this->Form->input("EventDisease.{$index}.disease_name", array_merge($options, array(
			'class' => 'form-control disease_search',
			'placeholder' => __('Type to select'),
			'value' => $disease['name']
		)));
		echo $this->Form->hidden("EventDisease.{$index}.disease_id", array('class' => 'disease_id_hidden', 'value' => $disease['id']));
		echo $this->Form->hidden("EventDisease.{$index}.id", array('value' => $disease['id']));
	} else {
		echo $this->Form->input("EventDisease.{$index}.disease_name", array_merge($options, array(
			'class' => 'form-control disease_search',
			'placeholder' => __('Type to select')
		)));
		echo $this->Form->hidden("EventDisease.{$index}.disease_id", array('class' => 'disease_id_hidden'));
		echo $this->Form->hidden("EventDisease.{$index}.id");
	}
    ?>
    <div class="no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
</div>