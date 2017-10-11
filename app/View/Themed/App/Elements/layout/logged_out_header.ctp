<!-- Logged Out header -->
<div class="top_header logout_header">
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container header_section">
            <div class="navbar-header">
                <?php echo $this->element('layout/logo'); ?>
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target=".navbar-collapse">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                        class="icon-bar"></span>
                </button>

            </div>

            <?php if ($show_header_login) { ?>
                <div class="navbar-collapse collapse ">
                    <?php
                    echo $this->Form->create('User', array(
                        'class' => 'navbar-form navbar-right',
                        'url' => '/login',
                        'inputDefaults' => array(
                            'label' => false,
                            'div' => false,
                            'required' => false
                        )
                    ));
                    ?>
                    <div class="login_form_container">
                        <div class="loggedout_header pull-left">
                            <div class="checkbox_form row">
                                <div class="form-group col-lg-6">
                                    <p>Username or Email</p>  
                                </div>
                                <div class="form-group col-lg-6">
                                    <p>Password</p>                            
                                </div>
                            </div>
                            <div class="login_form row">
                                <div class="form-group col-lg-6">
                                    <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => __('Username or Email'))); ?>
                                </div> 
                                <div class="form-group col-lg-6">
                                    <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => __('Password'))); ?>
                                </div>
                            </div>
                            <div class="checkbox_form row">
                                <div class="form-group col-lg-6">
                                    <p><?php echo $this->Form->input('rememberMe', array('type' => 'checkbox')); ?><span for="UserRememberMe"><?php echo __('Remember Me'); ?></span> </p>  
                                </div>
                                <?php if (!(isset($isForgotpasswordForm) || isset($isResetpasswordForm))) { ?>
                                    <div class="form-group col-lg-6">
                                        <p><a href="<?php echo Configure::read('Url.forgotPassword'); ?>" tabindex="-1"><?php echo __('Forgot Password?'); ?></a></p>                            
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <ul class="pull-right">
                            <li><button type="submit" class="btn login_btn "><?php echo __('Log In'); ?></button></li>
                            <?php if ($this->here != "/") { ?>
                                <li><a  href="<?php echo Configure::read('Url.register'); ?>" class="btn join_btn "><?php echo __('Join Us'); ?></a></li>
                                <?php } ?>
                        </ul>


                    </div></div>


                <?php echo $this->Form->end();
            } ?>
            <!--/.navbar-collapse -->
            
            <!--Show login button in Registration page -->
            <?php if (trim($this->request->here, "/") == "register") { ?>
            <form class="navbar-form navbar-right">
                <div class="login_form_container">
                <ul class="pull-right">
                    <li> <a href="/" class="btn login_btn "><?php echo __('Home'); ?></a></li>
                </ul>
            </div>
            </form>            
            <?php } ?>
            
        </div>
    </div></div>
<?php echo $this->AssetCompress->script('password_plugin.js'); ?>
<?php if ($show_header_login) { ?>
    <script>
        setHideShowPlugin('#UserPassword');
    </script>
<?php } ?>

