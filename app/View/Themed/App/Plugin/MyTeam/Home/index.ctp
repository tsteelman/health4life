<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
} else {
	$this->Html->addCrumb(__('My Team'), $module_url);
}
$this->Html->addCrumb(h($team['name']));
?>
<div class="container">
    <div class="row team_discussion">
        <?php echo $this->element('lhs'); ?>
        <div class="col-lg-9">
			<?php if (isset($showTeamJoinButtons) && $showTeamJoinButtons) : ?>
			 <div class="approval_container">
                    <h4 class="pull-left"><?php echo __('You are welcome to join this team'); ?></h4>
                    <div class="pull-right">
                        <?php echo $this->element('MyTeam.send_team_join_request', array('teamId' => $team['id'])); ?>
                    </div>
             </div>
			<?php endif; ?>
            <?php if (isset($showTeamMemberApproveDeclineButtons)) : ?>
                <div class="approval_container">
                    <h4 class="pull-left"><?php echo __('You have been invited to join this team on %s', $invitedDate); ?></h4>
                    <div class="pull-right">
                        <?php echo $this->element('MyTeam.accept_decline_team_join_invitation', array('teamId' => $team['id'])); ?>
                    </div>
                </div>
            <?php elseif (isset($showTeamApproveDeclineButtons)) : ?>
                <div class="approval_container">
                    <h4 class="pull-left"><?php echo __('%s has created this team to support you', $organizerName); ?></h4>
                    <div class="pull-right">
                        <?php echo $this->element('MyTeam.approve_decline_team_buttons', array('teamId' => $team['id'])); ?>
                    </div>
                </div>
            <?php elseif (isset($showNewRoleApproveDeclineButtons)) : ?>
                <div class="approval_container role_approval">
                    <h4 class="pull-left"><?php echo __('You are invited as Team Lead for this team'); ?></h4>
                    <div class="pull-right">
                        <?php echo $this->element('MyTeam.approve_decline_role_buttons', array('teamId' => $team['id'])); ?>
                    </div>
                </div>
            <?php elseif (isset($showTeamAwaitingApproval)) : ?>
                <div class="approval_container">
                    <h4 class="pull-left"><?php echo __('Awaiting approval from %s', $patientName); ?></h4>
					<div class="pull-right">
                        <button type="button" class="btn btn_active ladda-button cancel_team_request" data-style="slide-right"	data-team_id="<?php echo $team['id']; ?>">
							<?php echo __('Cancel Request'); ?>
						</button>
						<input type="hidden" class="patient_id" value="<?php echo $patientId ; ?>" />
                    </div>
                </div>
			<?php elseif (isset($showTeamJoinApprovalWaiting) && $showTeamJoinApprovalWaiting) : ?>
                <div class="approval_container text-center">
                    <h4><?php echo __('Your team join request is awaiting approval'); ?></h4>
                </div>
            <?php endif; ?>
            <?php echo $this->element('MyTeam.approve_decline_privacy_chane_box', array('teamId' => $team['id'])); ?>
            
            <div class="page-header">
                <h3><?php echo __('Welcome to %s!', h($team['name'])); ?></h3> 
                <p><?php echo h($team['about']); ?></p>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <?php
                    if (isset($patient) && !empty($patient)):
                        echo $this->element('MyTeam.Home/about_patient');
                    endif;
                    if (isset($tasks)):
                        echo $this->element('MyTeam.Home/tasks');
                    endif;
                    if (isset($patient) && !empty($patient)):
                        echo $this->element('MyTeam.Home/view_medical_data_requests');
                    endif;
                    ?>
                </div>
                <div class="col-lg-4" id="members_container">
                    <?php
                    if (isset($members) && !empty($members)):
                        echo $this->element('MyTeam.Home/members');
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->AssetCompress->script('team', array('block' => 'scriptBottom'));
