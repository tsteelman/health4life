<?php
$headingText = __('Please list any past accidents, severe falls, major injuries, as well as fractures and dislocations');
echo $this->element('heading', array('headingText' => $headingText));
echo $this->Form->create($modelName, $formOptions);
echo $this->Form->hidden('id');
echo $this->Form->hidden('born_year', array('id' => 'born_year', 'value' => $bornYear));
?>

<div  class="records_container <?php echo $recordsContainerClass; ?>">
	<div class="form-group">
		<div class="col-lg-2">
			<label><?php echo __('Year'); ?></label>
		</div>
		<div class="col-lg-5">
			<label><?php echo __('Type'); ?></label>
		</div>
		<div class="col-lg-5">
			<label><?php echo __('Residual problem'); ?></label>
		</div>
	</div>

	<?php
	$lastIndex = '';
	if (isset($recordsCount) && ($recordsCount > 0)) {
		for ($index = 0; $index < $recordsCount; $index++) {
			$lastIndex = $index;
			echo $this->element('Records/injury_record', array('index' => $index));
		}
	}
	?>
</div>
<div class="form-group">
	<?php
	echo $this->element('Records/no_records_msg', array(
		'noRecordsMsg' => __("It seems that you haven't added any information about your injuries/accidents. Please click on the '+' button to add injuries/accidents information.")
	));
	?>
	<div class="col-lg-1 pull-right select_plus"><button type="button" id="add_record" class="btn"><img src="/theme/App/img/plus_icon.png" alt=""></button></div>
</div>
<?php
echo $this->Form->hidden('last_record_index', array('id' => 'last_record_index', 'value' => $lastIndex));
echo $this->element('Records/injury_record', array('id' => 'sample_record', 'hide' => true, 'index' => 'index'));
echo $this->element('buttons_row', array('isLast' => true));
echo $this->Form->end();
echo $this->jQValidator->validator();