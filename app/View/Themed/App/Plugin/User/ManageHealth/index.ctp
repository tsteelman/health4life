<?php
$user = $this->Session->read('Auth');
$birth_year = strftime("%Y", strtotime($user['User']['date_of_birth']));

$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Health', '/profile/myhealth');
$this->Html->addCrumb('Current Health', '/profile/'.$user['User']['username'].'/healthtracker');
$this->Html->addCrumb('Manage Health');
?>

<div class="container">
  <div class="row edit manage_health">
    <div class="col-lg-3 edit_lhs">
      <?php echo $this->element('User.manage_health_lhs'); ?>
    </div>
    <div class="col-lg-9">
      <div class="health_data_table">
        <?php echo $this->element('User.health_data_table'); ?>
      </div>
    </div>
  </div>
</div>

<script src="/theme/app/js/vendor/jquery.dataTables.min.js"></script>