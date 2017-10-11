<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Account Settings');
?>
<div class="container">
    <div class="row edit">
        <div class="col-lg-3 edit_lhs">
             <div class="respns_header"><h2>Account Settings</h2></div>
            <?php echo $this->element('User.Edit/lhs'); ?>
        </div>
		<div class="col-lg-9">
			<div class="account_settings page-header">
				<h2 class="sub-head"><?php echo __('Account Settings'); ?></h2>
			</div>
            <?php
            echo $this->Form->create($model, array(
                'id' => $changeTimezoneFormId,
                'inputDefaults' => $inputDefaults
            ));
            ?>
            <div class="form-group clearfix">
				<div class="col-lg-3 col-sm-3">
					<label><?php echo __('Timezone'); ?> </label>
				</div>
				<div class="col-lg-7 col-sm-7">
                    <?php echo $this->Form->input('timezone', array('options' => $timeZones, 'class' => 'form-control chosen-select', 'empty' => __('Select Timezone'))); ?>
                </div>
			</div>
			<div class="form-group clearfix">
				<div class="col-lg-3 col-sm-3">
					<label><?php echo __('Language'); ?> </label>
				</div>
				<div class="col-lg-7 col-sm-7">
                    <?php echo $this->Form->input('language', array('options' => $languages, 'class' => 'form-control chosen-select', 'empty' => __('Select Language'))); ?>
                </div>
			</div>

            <?php //echo $this->Form->end(); ?>
            <div class="measeurement_units">
                <?php
                //echo $this->Form->create($unitSettingsModel, array('id' => $unitSettingsModel, 'default' => FALSE));
                ?>
                <div class="page-header"><h2 class="sub-head"><?php echo __('Unit Settings'); ?></h2></div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('Height'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7 active_label">
						<div id="height" class="btn-group">
                        	<?php
                            	echo $this->Form->input('height', array(
									'type' => 'radio',
									'legend' => false,
									'label' => array('class' => 'btn btn-default changeUnit'),
									'div' => false,
									'name' => 'data[NotificationSetting][height]',
									'options' => array(1 =>'Imperial',2 => 'Metric'),
									'value' => $unit_settings['height_unit']
								));                            
                            ?>
                           
                        </div>
					</div>
				</div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('Weight'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7 active_label">
						<div id="weight" class="btn-group">
                            <?php
                            	echo $this->Form->input('weight', array(
									'type' => 'radio',
									'legend' => false,
									'label' => array('class' => 'btn btn-default changeUnit'),
									'div' => false,
									'name' => 'data[NotificationSetting][weight]',
									'options' => array(1 =>'Imperial',2 => 'Metric'),
									'value' => $unit_settings['weight_unit']
								));                            
                            ?>
                        </div>
					</div>
				</div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('Temperature'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7 active_label">
						<div id="temp" class="btn-group">
                            <?php
                            echo $this->Form->input('temp', array(
                            		'type' => 'radio',
                            		'legend' => false,
                            		'label' => array('class' => 'btn btn-default changeUnit'),
                            		'div' => false,
                            		'name' => 'data[NotificationSetting][temp]',
                            		'options' => array(1 =>'&deg;C',2 => '&deg;F'),
                            		'value' => $unit_settings['temp_unit']
                            ));
                         
                            ?>
						</div>
					</div>
				</div>
				<!--<button type="submit" class="btn btn-next"><?php echo __('Save'); ?></button>-->
			</div>
			<div class="measeurement_units">
                            <div class="page-header"><h2 class="sub-head"><?php echo __('Notification Settings'); ?></h2></div>
				<div class="form-group clearfix">
					<div class="col-lg-3 col-sm-3">
						<label><?php echo __('Play sound on new notification'); ?> </label>
					</div>
					<div class="col-lg-7 col-sm-7 active_label">
						<div id="music" class="btn-group">
                        	<?php
                            	echo $this->Form->input('music', array(
									'type' => 'radio',
									'legend' => false,
									'label' => array('class' => 'btn btn-default changeUnit'),
									'div' => false,
									'name' => 'data[NotificationSetting][music]',
									'options' => array(1 =>'On',2 => 'Off'),
									'value' => $unit_settings['sound_settings']
								));                            
                            ?>
                           
                        </div>
					</div>
				</div>
				
				<div class="form-group clearfix">
                    <div class="col-lg-3 col-sm-3"><label>&nbsp;</label></div>
						<div class="col-lg-5 col-sm-5">
							<?php
							echo $this->Form->button(__('Save'), array('type' => 'submit',
								'class' => 'btn btn-next')); ?>
							<button type="button" class="btn btn_clear settings_cancel">Cancel</button>
							<?php echo $this->Form->end();
							?>
						</div>
                </div>		
			</div>
		</div>
	</div>
</div>
<script>
	$('.measeurement_units label').attr('for', function(i, attr) { 
		if(typeof(attr) != 'undefined'){
			return attr.replace(/UserUser/, 'User');
		}
		
	});

   
</script>

<?php
echo $this->jQValidator->validator();
?>