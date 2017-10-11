<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
    $this->Html->addCrumb('My Health', '/profile/myhealth');
} else {
    $this->Html->addCrumb($user_details['username'] . "'s Health",  Common::getUserProfileLink($user_details['username'], true) . '/myhealth');
}
$this->Html->addCrumb('Pain Tracker');
$user = $this->Session->read('Auth');
$birth_year = strftime("%Y", strtotime($user['User']['date_of_birth']));

echo $this->AssetCompress->script('chart.js');
echo $this->AssetCompress->css('graph');
?>

<div class="container">
    <div class="row edit manage_health">
        <!--    <div class="col-lg-3">
        <?php // echo $this->element('User.manage_health_lhs'); ?>
            </div>-->
        <div class="col-lg-12">
            <div class="pain_tracking_data_table">
                <?php echo $this->element('User.pain_tracking_history'); ?>
            </div>
        </div>
    </div>
</div>