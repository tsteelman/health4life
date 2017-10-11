<?php
$currentUser = $currentUserDetails['userDetails'][0]['User'];
$locationArr = array(
	$currentUserDetails['userDetails'][0]['City']['description'],
	$currentUserDetails['userDetails'][0]['State']['description'],
	$currentUserDetails['userDetails'][0]['Country']['short_name']
);
$location = join(',	', array_filter($locationArr));
$locLength = strlen($location);
$userLength = strlen($currentUser['username']);
$diseaseLength = strlen($currentUserDetails['userDiseaseDetails']);
if (isset($isDashboard) && ($isDashboard === true)) {
    $locMaxLength =  23;
    $userNameMaxLength = 14;
    $diseaseMaxLength = 21;
} else {
    $locMaxLength =  58;
    $userNameMaxLength = 23;
    $diseaseMaxLength = 46;
}
$user = $this->Session->read('Auth');
$urlVar = explode('/', $this->here);
$current_url = array_pop($urlVar);
$settings_url = "/user/edit";
if ($current_url == Configure::read('Url.health')) {
    if ($user['User']['type'] == User::ROLE_PATIENT || $user['User']['type'] ==  User::ROLE_CAREGIVER) {
		$settings_url = "/user/manage_diagnosis";
	}
}
$userName = $currentUser['username'];
$userProfileLink = Common::getUserProfileLink($userName, true);
$userProfileThumb = Common::getUserThumb($currentUser['id'], $currentUser['type'], 'medium', 'user_medium_thumb profile_brdr_5');
$userRole = Common::getUserRoleName($currentUser['type']);
$userRoleBg = strtolower($userRole)."_profile";

?>

<div class="col-lg-5 col-md-5">
    <div class="dashboard_profile <?php echo $userRoleBg; ?>">                            
        <div class="profile_details">
            <div class="media">
                <div class="pull-left">
                    <a href="/profile" class="">
                    <?php echo $userProfileThumb; ?>                   
                </a>                   
                </div>                
                <div class="media-body">
                    <h3 class="owner">
                        <a href="/profile" class=""><?php echo $userName; ?></a>
                    </h3>                                       
                    <?php
                        if (isset($showFeeling) && ($showFeeling === true)) :
                            //echo $this->element('User.Myhealth/feeling_indicator');
                        endif;
                    ?>
                    <h5><?php echo $userRole; ?><span style="display: inline" class="feeling_condition <?php echo $user_details['feeling'];
            echo ($is_same) ? ' my_health_add ' : ''; ?>"></span></h5>
                    <span class="dashboard_profile_disease" <?php if($diseaseLength > $diseaseMaxLength) { ?> title="<?php echo h($currentUserDetails['userDiseaseDetails']); } ?> "
                     ><?php echo h($currentUserDetails['userDiseaseDetails']); ?>
                    </span>
                    <span class="dashboard_profile_disease">
                    <?php echo h($currentUserDetails['userTreatmentDetails']); ?>
                    </span>                
                    <p title="<?php echo h($currentUser['about_me']); ?>">
                        <?php echo h($currentUser['about_me']); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="home_profile_location clearfix">
            <?php
            if(isset($weather)):                
            ?>
            <h3 class="weather pull-left">
                <span class="pull-left"><img title="<?php echo $weather['weatherDesc'] ?>"
                src="/theme/App/img/weather_icons/<?php echo $weather['weatherIcon'] ?>_lg.png"></span> <?php echo $weather['currentTemperature'] ?>&deg;<?php if($tempUnit == 1) { echo __('C'); } else { echo __('F'); } ?>
            </h3>
            <?php
            endif;
            ?>
             <h4 class="pull-right" >
                <span  <?php if($locLength > $locMaxLength) { ?> title="<?php echo $location; } ?> "><?php echo $location; ?></span>
            </h4>            
        </div>
    </div>
</div>
