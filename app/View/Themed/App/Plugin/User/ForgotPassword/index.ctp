<div class="signup_container" id="forgotpwd_container">
    <div class="thumbnail">
        <div class="page-header">
            <h1><?php echo __('Forgot Password'); ?></h1>                   
        </div>
        <div class="signup_fields">
            <p><?php echo __("Forgotten your password or username? We'll send you password reset instructions."); ?></p>
            <?php
            echo $this->Session->flash();
            echo $this->Form->create('ForgotPasswordForm', array(
                'id' => $formId,
                'inputDefaults' => array(
                    'label' => false,
                    'div' => false,
                )
            ));
            ?>
            <div class="form-group">
                <?php echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => __('Enter your email address'))); ?>
            </div>
            
             <div class="form-group">
           		 <button type="submit" class="btn btn_green"><?php echo __('Submit'); ?></button>
             </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php
echo $this->jQValidator->validator();
?>

<script>
    $(document).ready(function(){
        var flag = $('.alert-success');
        if(flag.length > 0) {
            $(".signup_fields p").remove();
            $('.signup_fields input').remove();
            $('.signup_fields button').remove();
        }
    });
</script>