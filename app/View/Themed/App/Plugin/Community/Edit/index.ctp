<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Community', '/community');
$this->Html->addCrumb($communityName, "/community/details/index/{$communityId}");
$this->Html->addCrumb('Edit');
?>
<?php
echo $this->element('Community.Wizard/form');