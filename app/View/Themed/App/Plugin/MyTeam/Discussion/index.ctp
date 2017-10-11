<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Team', $module_url);
$this->Html->addCrumb($team['name'], $module_url .'/'. $team['id']);
$this->Html->addCrumb('Discussion');
?>
<div class="container">
    <div class="row team_discussion">       
		<?php echo $this->element('lhs'); ?>
        <div class="col-lg-9">
            <?php echo $this->element('MyTeam.approve_decline_privacy_chane_box', array('teamId' => $team['id'])); ?>
            <div class="page-header">
				<h3>Discussion</h3> 
			</div>
			<?php echo $this->element('Post.post_content'); ?>
        </div>
    </div>
</div>