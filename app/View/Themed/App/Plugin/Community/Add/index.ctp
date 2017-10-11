<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
    if (substr($this->request->referer(1), 0, 10) == '/condition') {
        $this->Html->addCrumb($communityDisease['Disease']['name'], $this->request->referer(1));
        $refer = $this->request->referer(1);
    } else {
        $this->Html->addCrumb('Community', '/community');
    }
    $this->Html->addCrumb('Create');
?>
<?php
if(isset($refer)) {
    echo $this->element('Community.Wizard/form', array('refer' => $refer, 
        'disease' => $communityDisease['Disease']));
} else {
    echo $this->element('Community.Wizard/form');
}