<div class="signup_container">
    <div class="thumbnail">
        <div class="page-header text-center">
            <h1><?php echo __('Account Blocked'); ?></h1>
        </div>
        <br />
		<?php echo $this->element('warning', array('message' => __('Your account has been blocked by the super admin.'), 'hideCloseBtn' => true)); ?>
    </div>
</div>