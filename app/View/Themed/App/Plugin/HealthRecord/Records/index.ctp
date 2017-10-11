<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Health', '/profile/myhealth');
$this->Html->addCrumb(__('My Health Record'));
?>
<div class="container">
    <div class="health_record">
        <div class="row edit">
			<div class="col-lg-3 edit_lhs">
				<?php echo $this->element('lhs'); ?>
			</div>
			<div class="col-lg-9">
				<?php echo $this->element("Records/$element"); ?>
			</div>
		</div>
	</div>
</div>

<?php
$this->AssetCompress->script('health_history', array('block' => 'scriptBottom'));