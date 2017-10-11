<?php
$teamCount = count($teamDetails);

if($teamCount > 0) {
?>

<div id="myteam_list" class="member_list disease_rhs">
    <h4>My Team (<?php echo $teamCount; ?>)</h4>
    <div class="row content details_container">
        <?php        
        foreach($teamDetails as $teamInfo) {
        ?>
        <div class="event col-xs-12">
            <div class="pull-left">
                <a href="/myteam/<?php echo $teamInfo['Team']['id']; ?>">
                    <img class="myteam_thumb_listing_small" src="<?php echo Common::getTeamThumb($teamInfo['Team']['id'], $teamInfo['Team']['patient_id'], 'medium'); ?>">
                </a>
            </div>
            <div class="myteam_list_profile indvdl_list name_details pull-left">
                <?php $name = Common::truncate($teamInfo['Team']['name'], 25); ?>
                <a href="/myteam/<?php echo $teamInfo['Team']['id']; ?>" title="<?php echo __(h($name['title'])); ?>">
                    <?php echo __(h($name['name']));?>
                </a>                
            </div>
        </div>
        
        <?php        
        }
        ?>
    </div>
</div>



<?php
}