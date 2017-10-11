<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Change Password');
?>
<div class="container">
	<div class="row edit">
		<div class="col-lg-3 edit_lhs">
                     <div class="respns_header"><h2>Change Password</h2></div>
			<?php echo $this->element('User.Edit/lhs'); ?>
		</div>
		<div class="col-lg-9">
                    
				<div class="page-header">
					<h2>
						<span><?php echo __('Change Password'); ?></span>&nbsp;
					</h2>
				</div>
			    <?php
			    echo $this->Form->create($model, array(
			        'id' => $changePasswordFormId,
			        'inputDefaults' => $inputDefaults,
			        'url' => array('controller' => 'password', 'action' => 'changePassword')
			    ));
			    ?>
			    <div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('Current Password'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7">
			            <?php echo $this->Form->password('current_password', array('class' => 'form-control')); ?>
			        </div>
				</div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('New Password'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7" id="password">
			            <?php echo $this->Form->password('new_password', array('class' => 'form-control')); ?>
			        </div>
				</div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('Confirm Password'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7">
			            <?php echo $this->Form->password('confirm_password', array('class' => 'form-control')); ?>
			        </div>
				</div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label>&nbsp;</label>
					</div>
					<div class="col-lg-7 col-sm-7">
						<button type="submit" class="btn btn-next"><?php echo __('Change Password'); ?></button>
						<button type="button" class="btn btn_clear settings_cancel">Cancel</button>
					</div>
				</div>
			    <?php echo $this->Form->end(); ?>
			
		</div>
	</div>
</div>
<?php
	echo $this->jQValidator->validator();
	echo $this->AssetCompress->script('password_plugin.js');
?>
<script>
    jQuery(document).ready(function () {            
        var options = {};
        $('#UserNewPassword').pwstrength(options);
    });

    setHideShowPlugin('#UserCurrentPassword');
    setHideShowPlugin('#UserNewPassword');
    setHideShowPlugin('#UserConfirmPassword');
</script>