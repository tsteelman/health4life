<div id="diagnosis_form_container">
		<div class="form-group">
		<label>
  What disease does your family member or friend have?
		</label>
	</div>
    <div class="default-diagnoisis">
        <?php
        $inputDefaults = array('label' => false, 'div' => false,);
        echo $this->element('User.diagnosis_addsupport_form', array('index' => 0, 'options' => $inputDefaults));
        ?>
    </div>
</div>
<button type="button" class="btn upload_btn" id="add_diagnosis_support_btn">+ Add diagnosis</button>
<input type="hidden" id="diagnosis_last_index" value="0" />