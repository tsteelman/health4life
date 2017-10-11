<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if(isset($searchStr) || isset($searchStrName) || isset($searchStrGender) || isset($searchStrAge) 
	|| isset($searchStrLocation) || isset($searchStrDisease) || isset($searchStrSymptoms) || isset($searchStrTreatment)){
	$this->Html->addCrumb('Search');
}else{
	$this->Html->addCrumb('Invitations');
}
?>
<div class="container">
    <input type="hidden" name="search_type" id="search_type" value="<?php echo isset($type) ? $type : ''; ?>" />
	<input type="hidden" name="search_string" id="search_string" value="<?php echo isset($searchStr)? htmlspecialchars($searchStr) : ''; ?>" />
	<input type="hidden" name="search_class" id="search_class" value="<?php echo isset($searchClass) ? $searchClass : ''; ?>" />
	
	<?php
		
		echo $this->element($layout_file);

	?>
</div>
<?php 
	$this->AssetCompress->script('search', array('block' => 'scriptBottom'));
?>
