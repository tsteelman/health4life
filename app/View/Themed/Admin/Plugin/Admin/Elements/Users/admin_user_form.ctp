<?php
echo $this->Form->create($modelName, array(
	'class' => 'form-horizontal',
	'inputDefaults' => array(
		'label' => false,
		'div' => false
	)
));
echo $this->Form->hidden('id');
echo $this->Form->hidden('old_email');
echo $this->element('Admin.Users/list_form_fields');
?>
<hr/>

<div class="form-actions">
	<button class="btn btn-info" type="submit">
		<i class="icon-ok bigger-110"></i>
		<?php echo __('Save'); ?>
	</button>

	&nbsp; &nbsp; &nbsp;
	<a href="/admin/users/admins">
		<button class="btn" type="button">
			<?php echo __('Cancel'); ?>
		</button>
	</a>
</div>

<?php
echo $this->Form->end();
echo $this->AssetCompress->css('chosen');
echo $this->AssetCompress->script('chosen');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
	});
</script>