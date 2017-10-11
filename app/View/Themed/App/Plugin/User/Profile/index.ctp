<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
}
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
($is_same)? $this->Html->addCrumb('News Feed'): (($viewActivity)? $this->Html->addCrumb('Activity') : '');
?>
<?php $this->extend('Profile/view');
if ((isset($viewActivity) && ($viewActivity === true)) || !isset($viewActivity)) {
	echo $this->element('Post.post_content');
}