<div class="signup_container">
    <div class="thumbnail">
        <div class="page-header text-center">
            <h1>Account Activation</h1>
        </div>
        <div class="alert <?php echo $activateSuccess ? 'alert-success' : 'alert-error'; ?>">
            <div class="message"><?php echo $activateMessge; ?></div>
        </div>
		<?php
		$style = 'display: none;';
		echo $this->element('error', array('id' => 'login_flash_error', 'style' => $style));
		echo $this->element('success', array('id' => 'login_flash_success', 'style' => $style));
		echo $this->element('warning', array('id' => 'login_flash_warning', 'style' => $style));
		?>
    </div>
</div>