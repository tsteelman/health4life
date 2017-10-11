<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
} else {
	$this->Html->addCrumb('My Team', $module_url);
}
$this->Html->addCrumb($team['name'], $module_url .'/'. $team['id']);
if( $isOrganizer ) {
    $this->Html->addCrumb('Members');
} else {
    $this->Html->addCrumb('Manage Members');
}
$role = array('0' => 'Member', '1' => 'Patient', '2' => 'Team Lead', '3' => 'Patient');
?>
<div class="container">
    <div class="row team_discussion">       
		<?php echo $this->element('lhs'); ?>
        <div class="col-lg-9 ">
            <?php echo $this->element('MyTeam.approve_decline_privacy_chane_box', array('teamId' => $team['id'])); ?>
			<?php if ($showRoleRequest) : ?>
				<div class="approval_container role_approval">
					<h4 class="pull-left"><?php echo __('You are invited as Team Lead for this team'); ?></h4>
					<div class="pull-right">
						<?php echo $this->element('MyTeam.approve_decline_role_buttons', array('teamId' => $team['id'])); ?>
					</div>
				</div>
			<?php endif; ?>
            <div id="team_member_list" class="team_members_list">
                <div class="page-header">
                    <h3>Members (<?php echo count($memberDetails['approved']); ?>)</h3>
                    <button data-team-id="<?php echo $teamId; ?>" id="leave_team_btn" class=" btn btn_active pull-right" type="button">Leave Team</button>
                </div>
                <div class="existing_team">
					<?php 
					foreach ($memberDetails['approved'] as $key => $member) {
						if ($key % 3 == 0) {
							?>
							<div class="row"> <?php } ?>
							<div id="team_member_<?php echo $member['TeamMember']['id'];?>"class="col-lg-4 col-md-4 col-sm-4">
								<div class="team_member">
									<div class="media">
										<a href="<?php echo Common::getUserProfileLink($member['User']['username'], true);?>" class="pull-left">
	<?php echo Common::getUserThumb($member['TeamMember']['user_id'], $member['User']['type']); ?>
										</a>
										<div class="media-body">
											<h5 class="owner">
                                                                                            <?php echo Common::getUserProfileLink($member['User']['username'], FALSE); ?>
                                                                                        </h5>
											<?php if ($member['TeamMember']['role'] == TeamMember::TEAM_ROLE_MEMBER) { ?>
												<p class="memebr_member">Member</p>
											<?php } else if ($member['TeamMember']['role'] == TeamMember::TEAM_ROLE_PATIENT 
													|| $member['TeamMember']['role'] == TeamMember::TEAM_ROLE_PATIENT_ORGANIZER) { ?>
												<p class="memebr_patient">Patient</p>
											<?php } else if ($member['TeamMember']['role'] == TeamMember::TEAM_ROLE_ORGANIZER) { ?>
												<p class="memebr_organizer">Team Lead</p>
										<?php } ?>
										</div>
										<?php if ($isOrganizer 
												&& ($member['TeamMember']['user_id'] != $loginUserId)
												&& ($member['TeamMember']['role'] != TeamMember::TEAM_ROLE_PATIENT)
												&& ($member['TeamMember']['role'] != TeamMember::TEAM_ROLE_PATIENT_ORGANIZER)) { ?>
											<div class="btn-group pull-right member_edit">
												<div class="filter dropdown-toggle" data-toggle="dropdown">                                          

												</div>
												<ul id="manage-user-menu" class="dropdown-menu" data-username="<?php echo $member['User']['username'];  ?>" data-member-id="<?php echo $member['TeamMember']['id']; ?>">
													<?php if (!empty($member['TeamMember']['new_role'])) { ?>
													<li class="approval-wait-option"><a>Waiting for Approval</a></li>
													<?php } else if ($member['TeamMember']['role'] == TeamMember::TEAM_ROLE_MEMBER) { //role type member ?>
														<li class="promote-option"><a>Promote as Team Lead</a></li>
													<?php } ?>
													<li class="remove-option"><a>Remove from team</a></li>
													<?php if($member['TeamMember']['role'] == TeamMember::TEAM_ROLE_ORGANIZER ){ ?>
													<li class="demote-option"><a>Demote from Team Lead</a></li>
													<?php } ?>
												</ul>
											</div>
									<?php } ?>
									</div>
								</div>                    
							</div>
							<?php
						$keyValue = $key;
						if ($key % 3 == 2) {
							?> </div> <?php } ?>
<?php } ?>
                 <?php if($keyValue%3 != 2) { ?> </div> <?php } ?>
			</div>
		</div>
        <?php if( $isPatient || $isOrganizer) { ?>
		<div class="team_members_list">
			<div class="page-header">
				<h3>Invited (<?php echo count($memberDetails['invited']); ?>)</h3>
                        <?php if($isOrganizer) { ?>
					<button class=" btn continue_btn pull-right" type="button" data-toggle="modal" data-target="#friend-invite" data-backdrop="static" data-keyboard="false">Invite Friends</button>
				<?php } ?>
			</div>
			<div id="invited_members" class="existing_team">
                    <?php if(empty($memberDetails['invited'])) { ?>
                            <div class="media" style="margin-top: 10px">No users invited to this team.</div>
                    <?php } ?>
                    <?php foreach ($memberDetails['invited'] as $key => $member) {
                        if($key%3 == 0) { ?>
						<div class="row"> <?php } ?>
						<div class="col-lg-4 ">
							<div class="team_member">
								<div class="media">
									<a href="<?php echo Common::getUserProfileLink($member['User']['username'], true);?>" class="pull-left">
										<?php echo Common::getUserThumb($member['TeamMember']['user_id'], $member['User']['type']); ?>
									</a>
									<div class="media-body">
										<h5 class="owner" style="margin-bottom: 10px;">
													<?php echo Common::getUserProfileLink($member['User']['username'], FALSE); ?>
											</h5>
									</div>
                                               <?php if($isOrganizer) { ?>
										<div class="btn-group pull-right member_edit">
											<div class="filter dropdown-toggle" data-toggle="dropdown">                                             

											</div>
											<ul class="dropdown-menu">
												<!--<li><a href="#"></a></li>-->
												<li id="cancel-join-request" data-member-id="<?php echo $member['TeamMember']['id']; ?>"><a >Cancel request</a></li>
											</ul>
										</div>
						<?php } ?>
								</div>
							</div>                    
						</div>
	<?php if ($key % 3 == 2) { ?> </div> <?php } ?>
<?php } ?>
			</div>
		</div>
            <?php } ?>
		<?php if ($showJoinRequest): ?>
			<div class="team_members_list">
				<div class="page-header">
					<h3>Team Join Request (<?php echo count($joinRequests); ?>)</h3>

				</div>
				<div class="public_join_team">
					<?php 
					if (empty($joinRequests)) { ?>
						<div class="media" style="margin-top: 10px">No team join requests.</div>
					<?php } ?>
					<?php foreach ($joinRequests as $key => $member) {
						if ($key % 3 == 0) {
							?>
							<div class="row"> <?php } ?>
							<div class="col-lg-4 ">
								<div id="public_join_<?php echo $member['TeamMember']['id']; ?>"class="team_member">
									<div class="media">
										<a href="<?php echo Common::getUserProfileLink($member['User']['username'], true); ?>" class="pull-left">
		<?php echo Common::getUserThumb($member['TeamMember']['user_id'], $member['User']['type']); ?>
										</a>
										<div class="media-body">
											<h5 class="owner" style="margin-bottom: 10px;">
		<?php echo Common::getUserProfileLink($member['User']['username'], FALSE); ?>
											</h5>
										</div>

										<div class="btn-group pull-right member_edit">
											<div class="filter dropdown-toggle" data-toggle="dropdown">                                             

											</div>
											<ul class="dropdown-menu">												
												<li class="approve-join-request" data-member-id="<?php echo $member['TeamMember']['id']; ?>"><a>Approve request</a></li>
												<li class="decline-join-request" data-member-id="<?php echo $member['TeamMember']['id']; ?>"><a>Decline request</a></li>
											</ul>
										</div>

									</div>
								</div>                    
							</div>
		<?php if ($key % 3 == 2) { ?> </div> <?php } ?>
			<?php } ?>
				</div>
			</div>
<?php endif; ?>
	</div>
</div>
</div>

<!-- Modal for inviting friends to team-->
<div class="modal fade" id="friend-invite" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close invite_cancel" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Invite Friends</h4>
			</div>
			<div class="modal-body">
<?php echo $this->element('invite_friend_team', array('type' => 1)); ?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal for transferring role-->
<div class="modal fade" id="transfer-organizer" data-team-id="<?php echo $teamId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Transfer Team Lead</h4>
			</div>
			<div class="modal-body">
				You need to choose another user as the Team Lead to leave this team.
				<br/>
				<?php if (!empty($memberDetails['approved'])) { ?>
					<select id="assign_organizer_dropdown" class="form-control">
						<?php
						foreach ($memberDetails['approved'] as $key => $member) {
							if ($member['TeamMember']['user_id'] != $loginUserId) { // donot show login user
							?>
							<option value="<?php echo $member['TeamMember']['id']; ?>" <?php if($member['TeamMember']['role'] == 1) { echo "selected='selected'"; } ?>>
								<?php echo $member['User']['username'].' ('.$role[$member['TeamMember']['role']].')' ?>
							</option>
						<?php } } ?>
					</select>
				<?php } ?>
			</div>
			<div id="assign_message" class="alert" style="display: none;"></div>
			<div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
				<button id="assign_organizer_button" type="button" class="btn btn_active ladda-button" data-style="expand-right" ><span class="ladda-label">Ok</span><span class="ladda-spinner"></span></button>    
				<button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal for remove a user-->
<div class="modal fade" id="remove-user-reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Remove User</h4>
			</div>
			<div class="modal-body">
				Please leave reason for removing a user
				<br/>
				<textarea id="remove-reason" class="form-control"></textarea>
			</div>
			<div id="assign_message" class="alert" style="display: none;"></div>
			<div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
				<button id="remove_user_button" data-member-id="" type="button" class="btn btn_active ladda-button" data-style="expand-right" ><span class="ladda-label">Ok</span><span class="ladda-spinner"></span></button>    
				<button id="close_remove_user_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
        var friendsToInvite = <?php echo $myFriendsListJson; ?>
</script>
<?php
echo $this->AssetCompress->script('team', array('block' => 'scriptBottom'));