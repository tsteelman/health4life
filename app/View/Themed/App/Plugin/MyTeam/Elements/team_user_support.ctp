<?php
$i = 0;
$role = array('0' => 'Member', '1' => 'Patient', '2' => 'Team Lead', '3' => 'Patient');
if (!empty($teams)) {
    foreach ($teams as $team) {

        if ($i % 3 == 0) {
            ?>
            <div class="row team_list">
            <?php } ?>
            <?php if ($i != 2) { ?>
                <div class="team_container col-lg-4">
                <?php } else { ?>
                    <div class="team_container col-lg-4 ml_0">
                    <?php } ?>
                    <div class="team_details">
                        <a class="team_image" href="/myteam/<?php echo $team['Team']['id']; ?>">
                            <img class="img-responsive " src="<?php echo Common::getTeamThumb($team['Team']['id'], $team['Team']['patient_id'], 'medium'); ?>">
                        </a>
                        <h4>
                            <?php $name = Common::truncate($team['Team']['name'], 18); ?>
                            <a href="/myteam/<?php echo $team['Team']['id']; ?>"
                               title="<?php echo __(h($name['title'])); ?>">
                                   <?php echo __(h($name['name'])); ?>
                            </a>
                        </h4>
                        <p>Created By : <?php echo __(h($team['Organizer']['username'])) ?></p> 
<!--                        <p>supporting</p>
                        <h5><a href="<?php echo Common::getUserProfileLink($team['Patient']['username'], true); ?>"><?php echo $team['Patient']['username']; ?></a></h5>
                        <p>Created on : <?php echo CakeTime::nice($team['Team']['created'], $timezone, '%B %e, %Y'); ?></p>        -->
                    </div>


                    <div class="team_members_details">
                        <?php if ($team['Team']['status'] == Team::STATUS_APPROVED): ?>
                            <span class="no_of_members pull-left">
                                <?php echo $team['Team']['member_count']; ?> Member(s)
                            </span>
                            <span class="memebr_patient pull-right">
                                <?php
                                echo $role[$team['TeamMember']['role']];
                                ?></span> 
                        <?php else: ?>
                            <a href="/myteam/<?php echo $team['Team']['id']; ?>"><button  class=" btn btn_normal" type="button" >Waiting for approval </button></a>				 
                        <?php endif; ?>					
                    </div>
                </div>  
                <?php
                $i++;
            }

            $page_start_record_no = ($this->Paginator->param('page') * $this->Paginator->param('limit')) - $this->Paginator->param('limit') + 1;

            if ((($this->Paginator->param('count') - $page_start_record_no) == 0) || $i % 3 == 0) {
                ?>
            </div>
            <?php
        }
    }
