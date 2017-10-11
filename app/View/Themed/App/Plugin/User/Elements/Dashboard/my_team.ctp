<div class="my_team">
    <a href="/myteam" class="dashboard_header_link">
        <div class="dashboard_header">Team</div>
    </a>
        <div class="tile_content my_communities_tile">
<?php
$teamCount = count($teamDetails);
if($teamCount > 0) {
    $i = 0;
    foreach($teamDetails as $teamInfo) {        
        $name = Common::truncate($teamInfo['Team']['name'], 25);
?>
            <div class="media">
                <a class="pull-left " href="/myteam/<?php echo $teamInfo['Team']['id']; ?>">
                    <img  src="<?php echo Common::getTeamThumb($teamInfo['Team']['id'], $teamInfo['Team']['patient_id'], 'medium'); ?>" class="media-object" height="40" alt="">                                    </a>
                <div class="media-body">
                    <h5><a href="/myteam/<?php echo $teamInfo['Team']['id']; ?>">
                        <?php echo __(h($name['name']));?>
                        </a></h5>
                    <p>Members (<?php echo $teamInfo['Team']['member_count']; ?>)                                            </p>
                </div>
            </div>
          

<?php
            $i++; 
            if($i == 3) { break; }
 }
    if($teamCount > 3) {
?> 
        <a href="/myteam" id="dashboard_team_more" class="dashboard_more pull-right">more</a>
<?php            
    }
} else {
?>            
    <div class="media">
        <h4> <?php echo __('You are not yet part of any team!');?> </h4>
    </div>
<?php } ?>                         
    </div>
</div>