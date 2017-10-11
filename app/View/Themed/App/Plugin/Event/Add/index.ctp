<?php

$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if (substr($this->request->referer(1), 0, 9) == '/calendar') {
    $this->Html->addCrumb('Calendar', $this->request->referer(1));
    $refer = $this->request->referer(1);
} else if(substr($this->request->referer(1), 0, 10) == '/condition') {
	$this->Html->addCrumb($eventDisease['Disease']['name'], $this->request->referer(1));
    $refer = $this->request->referer(1);
	$eventForDisease = true; // If adding event from disease page
} else {
    $this->Html->addCrumb('Events', '/event');
}
$this->Html->addCrumb('Create');

if (isset($refer)) {
	if(isset($eventForDisease)) {
		echo $this->element('Event.Wizard/form', array(
			'title' => __('Create Events'),
			'refer' => $refer,
			'disease' => $eventDisease['Disease']
		));
	} else {
		echo $this->element('Event.Wizard/form', array(
			'title' => __('Create Events'),
			'refer' => $refer,
		));
	}
} else {
    echo $this->element('Event.Wizard/form', array(
        'title' => __('Create Events')
    ));
}
?>