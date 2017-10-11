<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb(__('My Team'), '/myteam');
$this->Html->addCrumb($team['name'], $teamUrl);
$this->Html->addCrumb(__('Settings'));
?>
<div class="container" id="email_settings">
    <div class="myteam">
        <div class="row">     
			<?php echo $this->element('lhs'); ?>
			<div class="col-lg-9">
                                <?php echo $this->element('MyTeam.approve_decline_privacy_chane_box', array('teamId' => $team['id'])); ?>
				<div class="page-header">
					<h3><?php echo __('Team Settings'); ?></h3> 
				</div>                            
				<div class="discussion_area create_team_section " >
					<?php
					echo $this->Form->create('TeamSetting', array(
						'inputDefaults' => $inputDefaults,
						'method' => 'POST',
						'id' => 'team_setting_form'
					));
					echo $this->Form->hidden('id');
					?>
					<div class="row">
						<div class=" col-lg-7">
							<div class="team_form">
								<div class="form-group clearfix">
									<div class="col-lg-4 privacy_settings_left"><label><?php echo __('Team notification'); ?> </label></div>
									<div class="col-lg-6 privacy_settings_right">
										<div class="span3">
											<label>
												<?php
												echo $this->Form->checkbox('enable_notification', array(
													'hiddenField' => false,
													'class' => 'ace-switch ace-switch-3',
												));
												?>
												<span class="lbl"></span>
											</label>
										</div>
									</div>
								</div>
								<div class="<?php echo $emailSiteVisibilityClass; ?>" id="team_site_email_notifications">
									<div class="form-group clearfix">
										<div class="col-lg-4 privacy_settings_left"><label><?php echo __('Email notification'); ?> </label></div>
										<div class="col-lg-6 privacy_settings_right">
											<div class="span3">
												<label>
													<?php
													echo $this->Form->checkbox('email_notification', array(
														'hiddenField' => false,
														'class' => 'ace-switch ace-switch-3',
													));
													?>
													<span class="lbl"></span>
												</label>
											</div>
										</div>
									</div>
									<div class="form-group clearfix">
										<div class="col-lg-4 privacy_settings_left"><label><?php echo __('Site notification'); ?> </label></div>
										<div class="col-lg-6 privacy_settings_right">
											<div class="span3">
												<label>
													<?php
													echo $this->Form->checkbox('site_notification', array(
														'hiddenField' => false,
														'class' => 'ace-switch ace-switch-3',
													));
													?>
													<span class="lbl"></span>
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group clearfix btns_row">
									<button class="btn continue_btn" type="submit"><?php echo ('Save'); ?></button>  
									<button class="btn btn_clear" type="button" id="cancel_edit" data-href="<?php echo $teamUrl; ?>">
										<?php echo ('Cancel'); ?>
									</button> 
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php
echo $this->AssetCompress->script('team', array('block' => 'scriptBottom'));