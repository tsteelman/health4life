<div class="signup_container">
    <div class="thumbnail">
        <div class="page-header">
            <h1><?php echo __('Change Password'); ?></h1>                   
        </div>
        <div class="signup_fields">  
            <?php
            echo $this->Session->flash();
            echo $this->Form->create('ResetPasswordForm', array(
                'inputDefaults' => array(
                    'label' => false,
                    'div' => false
                )
            ));
            ?>
            <div class="form-group row" id="password">
                    <label><?php echo __('New Password'); ?></label>
                    <?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => __('six or more characters'))); ?>
                </div>
                <div class="form-group">
                    <label><?php echo __('Confirm Password'); ?></label>
                    <?php echo $this->Form->input('password', array('id' => 'confirm-password', 'class' => 'form-control', 'placeholder' => __('six or more characters'), 'name' => 'data[ResetPasswordForm][confirm-password]')); ?>
                </div>
                <div class=" flt_lft form-group">
                    <button type="submit" class="btn btn_green"><?php echo __('Save New Password'); ?></button>
                </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php
echo $this->jQValidator->validator();
?>

<script>
    jQuery(document).ready(function () {            
        var options = {};
        $('#ResetPasswordFormPassword').pwstrength(options);
    });

    setHideShowPlugin('#ResetPasswordFormPassword');
    setHideShowPlugin('#confirm-password');
</script>