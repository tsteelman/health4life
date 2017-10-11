<div class="main-content">
	<div class="row-fluid">
		<div class="span12">
			<div class="login-container">
				<div class="row-fluid">
					<div class="center">
						<h1>
                            <span class="black"><?php echo __('Patients'); ?></span><span class="green"><?php echo __('4'); ?></span><span class="black"><?php echo __('Life'); ?></span>
						</h1>
					</div>
				</div>

				<div class="space-6"></div>

				<div class="row-fluid">
					<div class="position-relative">
						<div id="resetPassword-box" class="resetPassword-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header blue lighter bigger">
										<i class="icon-twitter green"></i>
										<?php echo __('Reset Password'); ?>
									</h4>
									<div class="space-6"></div>
									<?php
									echo $this->Form->create('ResetPasswordForm', array(
										'inputDefaults' => array(
											'label' => false,
											'div' => false
										)
									));
									?>
										<fieldset>
											<?php
											echo $this->Session->flash('success', array(
                                                                                                'element' => 'success'
                                                                                            ));
                                                                                        echo $this->Session->flash('error', array(
                                                                                                'element' => 'error'
                                                                                            ));
											?>
											<label class="control-group">
												<span class="block input-icon input-icon-right">

													<?php echo $this->Form->input('password', array('class' => 'span12', 'placeholder' => __('Password'))); ?>
													<i class="icon-lock"></i>   
												</span>
											</label>

											<label class="control-group">
												<span class="block input-icon input-icon-right">
													<?php echo $this->Form->input('password', array('id' => 'ResetPasswordFormConfirmPassword','class' => 'span12', 'placeholder' => __('Confirm Password'), 'name' => 'data[ResetPasswordForm][confirm-password]')); ?>
													<i class="icon-lock"></i>
												</span>
											</label>

											<div class="space"></div>

											<div class="clearfix">
												<button type="submit" class="width-35 pull-right btn btn-small btn-primary">
													<i class="icon-key"></i>
													<?php echo __('Reset'); ?>
												</button>
											</div>

											<div class="space-4"></div>
										</fieldset>
									<?php echo $this->Form->end(); ?>
								</div><!--/widget-main-->
                                                        </div>
					</div><!--/position-relative-->
				</div>
			</div>
		</div><!--/.span-->
	</div><!--/.row-fluid-->
</div>
<?php
echo $this->jQValidator->validator();
?>
