<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Friends');
$this->extend('Profile/view');
echo $this->element('User.Profile/friends');