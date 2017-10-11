<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Communities');

?>
<?php $this->extend('Profile/view'); ?>
<div class="content">
    <div class="row">
        <div id="myGroupsList" class="group_list">
            <?php echo $this->element('Community.community_row'); ?>
        </div>                           
    </div>
</div>