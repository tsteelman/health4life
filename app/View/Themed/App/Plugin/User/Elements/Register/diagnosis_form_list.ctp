<div id="diagnosis_form_container">
    <div class="default-diagnoisis">
        <?php
        $inputDefaults = array('label' => false, 'div' => false,);
        echo $this->element('User.diagnosis_form', array('index' => 0, 'options' => $inputDefaults));
        ?>
    </div>
</div>
<button type="button" class="btn upload_btn" id="add_diagnosis_btn">+ Add diagnosis</button>
<input type="hidden" id="diagnosis_last_index" value="0" />