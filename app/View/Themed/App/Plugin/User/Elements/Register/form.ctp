<?php
$inputDefaults = array(
    'label' => false,
    'div' => false,
);
echo $this->Form->create(null, array(
    'url' => array('plugin' => null, 'controller' => 'user', 'action' => 'register'),
    'type' => 'file',
    'id' => $formId,
    'inputDefaults' => $inputDefaults
));
echo $this->Form->hidden('type');
echo $this->Form->hidden('timezone');
?>
<div id="registrationWizards">
    
</div>
<?php echo $this->Form->end(); ?>

<?php
echo $this->Form->create(null, array(
    'type' => 'file',
    'id' => 'wizard_provider',
    'inputDefaults' => $inputDefaults
));
?>
<?php
echo $this->element('User.patient_wizard', array('type' => 1));
echo $this->element('User.family_wizard', array('type' => 2));
echo $this->element('User.caregiver_wizard', array('type' => 3));
echo $this->element('User.other_user_wizard', array('type' => 4));
?>

<?php echo $this->Form->end(); 
echo $this->jQValidator->validator();
?>
<script>
$(document).ready(function() {
	$.ajax({
		url: '/api/detected_timezone_id_JSON',
		data: getTimeZoneData(),
		method: 'POST',
		dataType: 'JSON'
	}).done(function(data) {
		$('#UserTimezone').val(data);
	});
});
</script>