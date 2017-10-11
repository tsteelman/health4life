<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Name'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-7 col-md-7 col-sm-7">
		<?php echo $this->Form->input('name'); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3  col-sm-3"><label><?php echo __('Description'); ?></label></div>
    <div class="col-lg-7 col-md-7 col-sm-7">
		<?php echo $this->Form->textarea('description', array('class' => 'form-control')); ?>
    </div>
</div>

<?php if (isset($eventTypes)): ?>
	<div class="form-group">
		<div class="col-lg-3 col-md-3  col-sm-3"><label><?php echo __('Event type'); ?> </label></div>
		<div class="col-lg-7 col-md-7  col-sm-7 col-xs-10 type_tooltip">
			<?php
			echo $this->Form->input('event_type', array('options' => $eventTypes));
			?>
		</div>
		<?php
		echo $this->Html->image('/img/symptom_info.png', array(
			'alt' => '?',
			'id' => 'event_type_help',
			'data-content' => $this->Html->nestedList($eventTypeHintList)
		));
		?>
		<div id="event_type_popover"></div>
	</div>
	<?php
else:
	echo $this->Form->hidden('event_type', array('value' => $eventType));
endif;
?>

<div class="form-group">
    <div class="col-lg-3 col-md-3  col-sm-3">
        <div>
            <label> <?php echo __('Event Photo'); ?> </label>
			<?php echo $this->Form->hidden('image'); ?>
        </div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8">
        <div id="uploadPreview">
			<?php echo $this->Html->image($eventImage, array('alt' => 'Default Event Photo', 'style' => 'width:262px; height: 114px;', 'class' => 'img-responsive img-thumbnail', 'id' => 'preview_image')); ?>
			<?php echo $this->Html->image('loading_big.gif', array('alt' => 'Loading image...', 'style' => 'width:262px; height: 114px;', 'class' => 'img-responsive img-thumbnail hide', 'id' => 'loading_img')); ?>
        </div>
        <br/>		
        <div class="row">
            <label>  </label>
            <div class="col-lg-5 col-md-8 col-xs-10 col-sm-5">
                <div id="bootstrapped-fine-uploader" class="width_auto">
                    <div class="qq-upload-button-selector qq-upload-button btn btn-success" style="width: auto;">
                        <div><?php echo __('Upload Image'); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div id="uploadmessages"><div class="alert" style="display: none;"></div></div>
            </div>	
        </div>	
    </div>
</div>
<div class="form-group <?php echo $diagnosisVisibilityClass; ?>" id="disease_field_group">
    <div class="col-lg-3 col-md-3  col-sm-3"><label> <?php echo __('Diagnosis'); ?></label></div>
    <div id="diagnosis_form_container" class="col-lg-4 col-md-4 col-sm-4 col-xs-8">
		<?php
		if (isset($eventDiseasesCount) && ($eventDiseasesCount > 0)) {
			for ($index = 0; $index < $eventDiseasesCount; $index++) {
				$lastIndex = $index;
				echo $this->element('Event.diagnosis_form', array('index' => $index, 'options' => $inputDefaults));
			}
		} else {
			$lastIndex = 0;
			if(isset($disease)) {
				echo $this->element('Event.diagnosis_form', array('index' => $lastIndex, 'options' => $inputDefaults , 'disease' => $disease));
			} else {
				echo $this->element('Event.diagnosis_form', array('index' => $lastIndex, 'options' => $inputDefaults));
			}
		}
		?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 select_plus"><button type="button" id="add_diagnosis_btn" class="btn pull-left"><img src="/theme/App/img/plus_icon.png" alt=""></button></div>
    <input type="hidden" id="diagnosis_last_index" value="<?php echo $lastIndex; ?>" />
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3  col-sm-3"><label><?php echo __('Tags'); ?></label></div>
    <div class="col-lg-7 col-md-7  col-sm-7 tags">
		<?php echo $this->Form->input('tags', array('placeholder' => 'Type tags and press spacebar or enter key')); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3  col-sm-3"><label></label></div>
    <div class="col-lg-7 col-md-7  col-sm-7">
        <div class=" flt_lft btn_area">
            <button type="button" id="btn_event_wizard_step1" class="btn btn-next">Next  <img src="/theme/App/img/nxt_arow.png" alt="Next"></button>
        </div>
    </div>
</div>