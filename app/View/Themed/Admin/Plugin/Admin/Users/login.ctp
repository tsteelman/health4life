<div class="main-content">
	<div class="row-fluid">
		<div class="span12">
			<div class="login-container">
				<div class="row-fluid">
					<div class="center">
						<h1>
							<span class="black"><?php echo Configure::read('App.name'); ?></span>
						</h1>
					</div>
				</div>

				<div class="space-6"></div>

				<div class="row-fluid">
					<div class="position-relative">
						<div id="login-box" class="login-box <?php echo $login_box_visiblity; ?> widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header blue lighter bigger">
										<i class="icon-twitter green"></i>
										<?php echo __('Administrator Login'); ?>
									</h4>
									<div class="space-6"></div>
									<?php
									echo $this->Form->create('User', array(
										'inputDefaults' => array(
											'label' => false,
											'div' => false
										)
									));
									?>
										<fieldset>
											<?php
											echo $this->Session->flash('flash', array(
												'element' => 'warning'
											));
											echo $this->Session->flash('auth', array(
												'element' => 'warning'
											));
                                                                                        echo $this->Session->flash('success', array(
                                                                                                'element' => 'success'
                                                                                        ));
                                                                                        echo $this->Session->flash('error', array(
                                                                                                'element' => 'error'
                                                                                        ));
											?>
											<label class="control-group">
												<span class="block input-icon input-icon-right">
													<?php echo $this->Form->input('username', array('class' => 'span12', 'placeholder' => __('Username'))); ?>
													<i class="icon-user"></i>
												</span>
											</label>

											<label class="control-group">
												<span class="block input-icon input-icon-right">
													<?php echo $this->Form->input('password', array('class' => 'span12', 'placeholder' => __('Password'))); ?>
													<i class="icon-lock"></i>
												</span>
											</label>

											<div class="space"></div>

											<div class="clearfix">
												<label class="inline">
													<?php echo $this->Form->input('rememberMe', array('type' => 'checkbox')); ?>
													<span class="lbl"> <?php echo __('Remember Me'); ?></span>
												</label>

												<button type="submit" class="width-35 pull-right btn btn-small btn-primary">
													<i class="icon-key"></i>
													<?php echo __('Login'); ?>
												</button>
											</div>

											<div class="space-4"></div>
										</fieldset>
									<?php echo $this->Form->end(); ?>
								</div><!--/widget-main-->

								<div class="toolbar clearfix">
									<div>
										<a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
											<i class="icon-arrow-left"></i>
                                                                                        <?php echo __('I forgot my password'); ?>
										</a>
									</div>
								</div>
							</div><!--/widget-body-->
						</div><!--/login-box-->

						<div id="forgot-box" class="forgot-box <?php echo $forgot_box_visiblity; ?> widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header red lighter bigger">
										<i class="icon-key"></i>
										<?php echo __('Retrieve Password'); ?>
									</h4>
									<div class="space-6"></div>
									<p>
										<?php echo __('Enter your Email to receive instructions'); ?>
									</p>
                                                                        
                                                                        <?php
									echo $this->Form->create('ForgotPasswordForm', array(
										'inputDefaults' => array(
											'label' => false,
											'div' => false,
										),
                                                                                'url' => 'forgotpassword',
                                                                                'id' => 'ForgotPasswordForm'
									));
									?>
										<fieldset>
                                                                                    <?php
                                                                                        echo $this->Session->flash('forgotpasswordsuccess', array(
                                                                                                'element' => 'success'
                                                                                        ));
                                                                                        echo $this->Session->flash('forgotpassworderror', array(
                                                                                                'element' => 'error'
                                                                                        ));
                                                                                    ?>
											<label class="control-group">
												<span class="block input-icon input-icon-right">
													<?php echo $this->Form->input('email', array('class' => 'span12', 'placeholder' => __('Email'))); ?>
													<i class="icon-envelope"></i>
												</span>
											</label>

											<div class="clearfix">
												<button type="submit" class="width-35 pull-right btn btn-small btn-danger">
													<i class="icon-lightbulb"></i>
													<?php echo __('Send Me'); ?>!
												</button>
											</div>
										</fieldset>
									<?php echo $this->Form->end(); ?>
								</div><!--/widget-main-->

								<div class="toolbar center">
									<a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
										<?php echo __('Back to login'); ?>
										<i class="icon-arrow-right"></i>
									</a>
								</div>
							</div><!--/widget-body-->
						</div><!--/forgot-box-->
					</div><!--/position-relative-->
				</div>
			</div>
		</div><!--/.span-->
	</div><!--/.row-fluid-->
</div>

<?php
echo $this->jQValidator->validator();
$this->AssetCompress->script('login' , array('block' => 'scriptBottom'));