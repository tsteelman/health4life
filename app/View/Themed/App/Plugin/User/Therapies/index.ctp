<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
$this->Html->addCrumb('My Profile', '/profile');
}
else {
$this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Therapies');
?>
<?php $this->extend('Profile/view'); ?>

<div class="content">
  <div class="row">
    <div class="group_list">
      <div class="event_list">
        <div class="row">
          <div class="group_list">
            <iframe src="https://www.breakthrough.com/" width="800" height="1065"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

