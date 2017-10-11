<?php
if (isset($communityId)) {
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
    $this->Html->addCrumb('Community', '/community');
    $this->Html->addCrumb($communityName, "/community/details/index/{$communityId}");
} else {
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
    $this->Html->addCrumb('Events', '/event');
}
$this->Html->addCrumb($eventName, "/event/details/index/{$eventId}");
$this->Html->addCrumb('Edit');
echo $this->element('Event.Wizard/form', array(
    'title' => __('Update Event')
));