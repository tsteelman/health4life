<?php
$i = 0;
if (!empty($teams)) {
    foreach ($teams as $team) {
        if ($i % 3 == 0) {
            ?>
            <div class="row team_list">
                <?php
            }
            if ($authUser == $team['Team']['patient_id']) { // new team create invite request
                ?>
                <div id="team_invite_<?php echo $team['Team']['id']; ?>" class="team_container col-lg-4">
                    <div class="team_details create_bteam">
                        <a class="team_image" href="/myteam/<?php echo $team['Team']['id'] ?>">
                            <img class="img-responsive" src="<?php echo Common::getTeamThumb($team['Team']['id'], $team['Team']['patient_id'], 'medium', 'team_add'); ?>">                           
                        </a>
                        <h4><span class="owner"><a href="<?php echo Common::getUserProfileLink($team['Organizer']['username'], true); ?>"><?php echo $team['Organizer']['username'] ?></a></span></h4>
                        <p> wants to create a team for you</p>
                        <h5>
                            <?php $name = Common::truncate($team['Team']['name'], 18); ?>
                            <a href="/myteam/<?php echo $team['Team']['id']; ?>" title="<?php echo __(h($name['title'])); ?>">
                                <?php echo __(h($name['name'])); ?>
                            </a>
                        </h5>                        
                        <p>Created on : <?php echo CakeTime::nice($team['Team']['created'], $timezone, '%B %e, %Y'); ?></p> 
                    </div>
                    <div class="team_members_details">
                        <div id="team_approv_div_<?php echo $team['Team']['id']; ?>" >
                            <?php echo $this->element('MyTeam.approve_decline_team_buttons', array('teamId' => $team['Team']['id'])); ?>     
                        </div>
                        <a href="/myteam/<?php echo $team['Team']['id'] ?>"><button id="view_team_btn_<?php echo $team['Team']['id']; ?>" style="display: none;" class=" btn btn_normal" type="button" >View Team</button></a>
                    </div>
                </div>

                <?php
            } else { // invitation to join team				
                ?>
                <div id="team_invite_<?php echo $team['Team']['id']; ?>" class="team_container col-lg-4">
                    <div class="team_details join_team">
                        <a class="team_image" href="/myteam/<?php echo $team['Team']['id'] ?>">
                            <img class="img-responsive" src="<?php echo Common::getTeamThumb($team['Team']['id'], $team['Team']['patient_id'], 'medium', 'team_invite'); ?>">
                        </a>
                        <h4>
							<span class="owner">
						<a href="<?php echo Common::getUserProfileLink($team['TeamMember']['InvitedBy']['username'], true); ?>"><?php echo $team['TeamMember']['InvitedBy']['username'] ?></a>
							</span>
						</h4>
                        <p>invited you to join</p>
                        <h5>
                            <?php $name = Common::truncate($team['Team']['name'], 18); ?>
                            <a href="/myteam/<?php echo $team['Team']['id']; ?>" title="<?php echo __(h($name['title'])); ?>">
                                <?php echo __(h($name['name'])); ?>
                            </a>
                        </h5>
                        <p>as a team member on <?php echo CakeTime::nice($team['Team']['created'], $timezone, '%B %e, %Y'); ?></p>
                    </div>
                    <div class="team_members_details">
                        <div id="team_approv_div_<?php echo $team['Team']['id']; ?>" >
                            <?php echo $this->element('MyTeam.accept_decline_team_join_invitation', array('teamId' => $team['Team']['id'])); ?>
                        </div>
                        <a href="/myteam/<?php echo $team['Team']['id'] ?>"><button id="view_team_btn_<?php echo $team['Team']['id']; ?>" style="display: none;" class=" btn btn_normal" type="button" >View Team</button></a>
                    </div>
                </div> 
                <?php
            }
			$i++;
        }
        
        $page_start_record_no = ($this->Paginator->param('page') * $this->Paginator->param('limit')) - $this->Paginator->param('limit') + 1;

        if ((($this->Paginator->param('count') - $page_start_record_no) == 0) || $i % 3 == 0) {
            ?>
        </div>
        <?php
    }
}
?>
