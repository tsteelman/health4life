<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Settings');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(' Settings'); ?>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>
    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->
            <div class="row-fluid">
                <?php
                    echo $this->Form->create('Setting', array(
                        'action' => 'editSettings',
                        'class' => 'form-horizontal',
                        'label' => false,
                        'div' => false,
                        'type' => 'file',
                        'onsubmit' => ''
                    ));
                ?>
                
                <?php foreach ($configurations as $config) { 
                    if($config['Configuration']['name'] == 'contact_email') { ?>
                        <div class="control-group">
                            <label class="control-label" align="left">Contact Email Address</label>
                            <div class="controls">
                                <?php
                                        echo $this->Form->input('contact_email', array('class' => 'form-control',
                                            'label' => false,
                                            'value' => $config['Configuration']['value'],
                                            'style' => 'width: 250px;',
                                            'div' => false));
                                ?>
                            </div>
                        </div>
                    <?php } else if($config['Configuration']['name'] == 'new_users_friend_id') { ?>
                        <div class="control-group">
                            <label class="control-label" align="left">Support Person</label>
                            <div class="controls">
                                <?php
                                        echo $this->Form->input('new_users_friend_id', array(
                                           // 'type' => 'select',
                                            'options' => $adminList,
                                            'class' => 'form-control chosen-select',
                                            'value' => $config['Configuration']['value'],
                                            'empty' => 'select an admin user',
                                            'div' => false,
                                            'label' => false,
                                            'style' => 'width:265px'
                                            ));
                                ?>
                            </div>
                         </div>
                    <?php } ?>
                <?php } ?>
                <div class="modal-footer">
                    <?php $options = array('id' => 'save_button', 'label' => 'Save', 'class' => 'btn btn-primary', 'style' => 'height: 40px'); ?>
                    <?php echo $this->Form->end($options); ?>
                </div>
                <hr/>
                
            </div>
        </div><!--/.span-->
    </div><!--/.page-content-->
</div> 

<script type="text/javascript">
//     $("ul.nav-list li").removeClass('active');
//     $("#settings-li").addClass('active');
</script>

