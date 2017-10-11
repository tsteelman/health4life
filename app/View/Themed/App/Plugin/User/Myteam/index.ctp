<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Team');

?>
<?php $this->extend('Profile/view'); ?>



<div id="mySupportTeamList">
    
        
<?php

if($is_same && isset($teamDetails)) { 

$role = array('0' => 'Member', '1' => 'Patient', '2' => 'Team Lead', '3' => 'Patient');
$teamCount = count($teamDetails);
$teamNum = 0;
if ( !empty($teamDetails)) {
?>
	<div class="my_team_print">
		<button class="btn pull-right print_btn team_print"><?php echo __('Print'); ?></button>
	</div>
        <div class="row team_list">
        <?php       
        foreach($teamDetails as $teamInfo) {
            if($teamNum  == 3) {
                $teamNum = 0;
        ?> 
                </div>
                <div class="row team_list">
        <?php
            }
        ?>
        <div class="team_container col-lg-4">

            <div class="team_details">
                <a href="/myteam/<?php echo $teamInfo['Team']['id']; ?>" class="team_image">
                    <img class="img-responsive"
                         src="<?php echo Common::getTeamThumb($teamInfo['Team']['id'], $teamInfo['Team']['patient_id'], 'medium'); ?>">
                </a>
                <h4>
                    <?php $name = Common::truncate($teamInfo['Team']['name'], 20); ?>
                    <a href="/myteam/<?php echo $teamInfo['Team']['id']; ?>" title="<?php echo __(h($name['title'])); ?>">
                        <?php echo __(h($name['name']));?>
                    </a>                          
                   
                </h4>
                <p>&nbsp;</p>
<!--                <p>supporting</p>
                <h5><a href="/myteam/<?php echo $teamInfo['Team']['id']; ?>/members">patient</a></h5>-->
                <p>Created on : <?php echo CakeTime::nice($teamInfo['Team']['created'], $timezone, '%B %e, %Y'); ?></p>        
            </div>


            <div class="team_members_details">
                <span class="no_of_members pull-left">
                    <?php echo $teamInfo['Team']['member_count']; ?> Member(s)
                </span>
                <span class="memebr_patient pull-right">
                    <?php
                    if (isset($teamInfo['TeamMember']['role'])) {
                    echo $role[$teamInfo['TeamMember']['role']];
                    }
                    ?>
                </span> 

            </div>
        </div> 
                
        <?php        
        $teamNum++;
        }
        ?>
        </div>
        

<?php
} else {
?>
<div class="text-center noresult_padding">
    <p class="alert alert-error">No teams found</p>
</div>        
<?php
    }
} else {
?>
<div class="text-center noresult_padding">
    <p class="alert alert-warning">No access to this page</p>
</div>       
<?php    
}
?>  
</div>