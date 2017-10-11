<div class="container signup_section">
	<div class="row">
		<?php echo $this->element('User.Register/lhs'); ?>
		<?php echo $this->element('User.Register/rhs_form'); ?>
	</div>
</div>
<?php
echo $this->jQValidator->validator();
$this->jQValidator->printCountryZipRegexScriptBlock();
echo $this->AssetCompress->css('facelist');
echo $this->AssetCompress->script('register_view.js');
?>
<script type="text/javascript">
	$(document).ready(function() {
		initRegistration();
	});
</script>