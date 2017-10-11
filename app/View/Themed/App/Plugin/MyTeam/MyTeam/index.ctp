<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
}
$this->Html->addCrumb('My Team', $module_url);
?>
<div class="container">
    <div class="myteam">		
        <div class="row">
            <div class="col-lg-9">

             
		<div id="team_supporting_me" class="team_invitations">
                        <div class="page-header">
                            <h3>
                                <div class="myteam_response">
                                    <span class="pull-left">My Teams</span><span class="header_icon pull-left" title="These teams do the care for you"></span>
                                
                                </div>
                                <?php
                                if  ($teams_count['user_supporting'] > 0) {
                                ?>
                                    <a href="/myteam/create"  class="pull-right btn create_button"><?php echo __('Create New Team'); ?></a>
                                <?php
                                }
                                ?>
                            </h3> 
                        </div>		
                <?php
                if  ($teams_count['user_supporting'] > 0) { ?>
                    
                        <div id="myTeamList">  
                            <div class="text-center loader"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
                        </div>                       
                    
                    <?php
                } else {
                ?>
                <div id="blank_area">
                    <p class="pull-left">It looks like you are not part of a team yet</p>
                    <a href="/myteam/create" class="pull-left btn create_button">Create new team</a>
                </div>
                <?php
                }
                ?>
                </div>
				<?php if (($teams_count['supported_by_user'] > 0) 
						|| ($teams_count['user_invited'] > 0)
						|| ($isVolunteer)): ?>
                <hr class="hr_divider" />
				<?php endif; ?>
                <?php
                if ($teams_count['supported_by_user'] > 0) {
                    ?>
                    <div id="team_user_support" class="team_invitations">
                        <div class="page-header">                            
                            <h3>
                                <div class="myteam_response">
                                   <span class="pull-left">Teams I belong to</span><span class="header_icon " title="You are a member of the following teams"></span>
                                   
                                </div>                                
                            </h3> 
                        </div>
                        <div id="mySupportTeamList">
							 <div class="text-center loader"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
                        </div>
                        
                    </div>
					<?php if (($teams_count['user_invited'] > 0)
						|| ($isVolunteer)): ?>
                     <hr class="hr_divider" />
					 <?php endif; ?>
                <?php } ?>
               
                <?php
                if ($teams_count['user_invited'] > 0) {
                    ?>
                    <div id="team_invitation" class="team_invitations">
                        <div class="page-header">
                            <h3>
                                <div class="myteam_response">
                                    <span class="pull-left">Team Invitations</span><span class="header_icon pull-left" title="The following teams are waiting for your approval. This invitation can be for a new team creation or to join a team"></span>
                                
                                </div>
                               
                            </h3> 
                        </div>
                        <div id="team_invitation_list">
							<div class="text-center loader"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
                        </div>                        
                    </div>                    
                    <?php
                }				
				if ($isVolunteer) {
                ?>
					 <hr class="hr_divider" />
				<div id="team_recommendation" class="team_recommendation">
						<div class="page-header hide">
							<h3>
                                                            <div class="myteam_response">
                                                                <span class="pull-left">Teams I might be interested in</span> <span class="header_icon pull-left" title="Teams in which your friends are members"></span>
								
                                                            </div>
                                                            
							</h3> 
						</div>
						<div id="myRecommendedTeamList">
							<div class="text-center loader"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
						</div>						
				</div>
				<?php } ?>
            </div>
            <?php echo $this->element('volunteer_tile'); ?>

        </div>
    </div> 
</div><!-- /.container -->
<script type="text/javascript">
$(document).ready(function() {
    load_myteam();
    load_user_supported_team();
    load_team_invitation();
    <?php if ($isVolunteer) : ?>
    load_recommended_teams();
    <?php endif; ?>
});
</script>

<?php
echo $this->AssetCompress->script('team', array('block' => 'scriptBottom'));


