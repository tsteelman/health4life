<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Edit Profile');
?>
<div class="container">
    <div class="row edit">
        <div class="col-lg-3 edit_lhs">
            <div class="respns_header"><h2>Edit profile</h2></div>
            <?php echo $this->element('User.Edit/lhs'); ?>
        </div>
        <div class="col-lg-9">
            
                <?php echo $this->element('User.Edit/edit_form'); ?>
            
        </div>
    </div>
</div>