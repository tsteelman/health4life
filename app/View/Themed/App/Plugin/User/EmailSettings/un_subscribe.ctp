<div class="signup_container" id="forgotpwd_container" style="width: 640px;">
    <div class="thumbnail">
        <div class="page-header">
            <h1 style="font-size: 31px;"><?php echo __("Unsubscribe from ". $description  . " notification"); ?></h1>                   
        </div>
        <div class="signup_fields">
            <p><?php echo __("You have un-subscribed from ". $description  . " notification emails."); ?></p>
			<p><?php echo __("You May still be subscribed to other messages from " .Configure::read ( 'App.name' ). "."); ?></p>
            <div class="form-group">
				 <a href="/user/email_settings"><?php echo __("View and update your email settings."); ?></a>
            </div>
        </div>
    </div>
</div>

