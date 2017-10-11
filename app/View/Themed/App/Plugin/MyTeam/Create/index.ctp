<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Team', $module_url);
$this->Html->addCrumb('Create'); 
?>
<div class="container">
    <div class="myteam create_form">
        <div class="row">
            <div class="col-lg-9">
                
                <!-- Include the step -->
                <?php echo $this->element("create/".$element); ?>
                
                
            </div>
             <?php echo $this->element('volunteer_tile'); ?>
        </div>
    </div>
</div>   

<?php
echo $this->AssetCompress->script('team', array('block' => 'scriptBottom'));