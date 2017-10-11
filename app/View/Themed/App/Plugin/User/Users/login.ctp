<div class="signup_container">
    <div class="thumbnail">
        <div class="page-header text-center">
            <h1><?php echo __('Login'); ?></h1>
        </div>
        <div class="signup_fields">
            <?php
            $style = 'display: none;';
            echo $this->element('error', array('id' => 'login_flash_error', 'style' => $style));
            echo $this->element('success', array('id' => 'login_flash_success', 'style' => $style));
            echo $this->element('warning', array('id' => 'login_flash_warning', 'style' => $style));

            if ($this->Session->check('Message.flash')):
                echo html_entity_decode($this->Session->flash());
            elseif ($this->Session->check('Message.auth')):
                echo $this->Session->flash('auth', array(
                    'element' => 'warning'
                ));
            endif;

            echo $this->Form->create('User', array(
                'id' => $formId,
                'inputDefaults' => array(
                    'label' => false,
                    'div' => false
                )
            ));
            ?>
            <div class="form-group">
                <label><?php echo __('Username / Email'); ?></label>
                <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => __('Username or email'))); ?>
            </div>
            <div class="form-group">
                <label><?php echo __('Password'); ?></label>
                <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => __('six or more characters'))); ?>
            </div>

            <div class="form-group checkbox_style">                
                <div class="checkbox">
                    <?php echo $this->Form->input('rememberMe', array('type' => 'checkbox', 'class' => "chck_box",  'value' => 1)); ?>                       
                        <label for="UserRememberMe"><?php echo __('Remember Me'); ?></label>			
                    </div>
            </div>    

            <div class="form-group">
                <div class="pull-left join_link">
                    <button type="submit" class="btn btn_green"><?php echo __('Log In'); ?></button>
                    <span>or</span>  <a href="<?php echo Configure::read('Url.register'); ?>" class=""><?php echo __('Join '.Configure::read ( 'App.name' )); ?></a>
                </div>
                <div class="clearfix"></div>
            </div>    


            <div class="form-group" style="margin:30px 0px;">
                <div class="pull-left forgt_link">
                    <a href="<?php echo Configure::read('Url.forgotPassword'); ?>"><?php echo __('Forgot password?'); ?></a>
                </div>

                <div class="clearfix"></div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php echo $this->jQValidator->validator(); ?>
<script>
	setHideShowPlugin('#UserPassword');
</script>