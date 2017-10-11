	<div class="page-header">
		<h2>
			<span><?php echo __('Edit Profile'); ?></span>&nbsp;
		</h2>
	</div>	
	<div class="clearfix form-group">
		 <div class="col-lg-3 col-sm-3"><label><?php echo __('Username:'); ?></label></div>
		 <div class="col-lg-7 col-sm-7">
			<label><?php echo __($username); ?></label>
		 </div>

	</div>
  
<div class="clearfix form-group" id="email_show">
		<div class="col-lg-3 col-sm-3"><label><?php echo __('Email:'); ?></label></div>
		<div class="col-lg-7 col-sm-7">
      <label id="email_label"><?php echo __($email); ?></label> 
      <a class="contact_by_mail" title="Click to change your email address" data-toggle="modal" data-target="#verify_user"  href="#"><?php echo __('Change'); ?></a>
		</div>
	</div>
    <?php
    echo $this->Form->create($model, array(
        'id' => $formId,
        'inputDefaults' => $inputDefaults
    ));
	echo $this->Form->hidden('type', array('value' => $loggedin_user_type));
    ?>
    <div class="clearfix form-group" style="display: none;" id="email_edit">
        <div class="col-lg-3 col-sm-3"><label><?php echo __('Email'); ?></label></div>
        <div class="col-lg-7 col-sm-7">
        	<?php echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control')); ?>
        	<?php echo $this->Form->input('verified', array('type' => 'hidden')); ?>
                <?php echo $this->Form->input('oldEmail', array('type' => 'hidden', 'value' => $email)); ?>
        </div>
    </div>
    
   
    <?php echo $this->element('User.Edit/edit_profile_form'); ?>
    
    
    <div class="clearfix form-group">
        <div class="col-lg-3 col-sm-3"><label>&nbsp;</label></div>
        <div class="col-lg-7 col-sm-7">
        	<?php echo $this->Form->input('id', array('type' => 'hidden'));?>
            <button type="submit" class="btn btn-next"><?php echo __('Save'); ?></button>
			<button type="button" class="btn btn_clear settings_cancel">Cancel</button>
        </div>
    </div>
    <?php echo $this->Form->end();?> 
<?php echo $this->jQValidator->validator();?>

<!-- Modal -->
<div class="modal email_invitation" id="verify_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog password_verify">
        <div class="modal-content">
        	<div class="modal-header">
		        
		        <h4 class="modal-title"><?php echo __('Confirm Password'); ?></h4>
		    </div>
            <div class="modal-body">
                <div class="row">
                	<p><?php echo __('Enter your password to change the email address' ); ?></p>
                	<div class="form-group"> 
                    
                    <input type="password" id="password" name="password" class='form-control'/>
                    
                	 	<div class="alert alert-error hide">
                      <div class="password-error-message"></div>
                    </div>
                  </div>                        
                </div>
            </div>
            <div class="modal-footer">
                <button id="email_close_invite_button" type="button" class="btn btn_add" data-dismiss="modal">Close</button>
                <button id="email_invite_button" type="button" class="btn btn_active  ladda-button" data-style="expand-right" >
                  <span class="ladda-label">Submit</span><span class="ladda-spinner"></span>
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  $(document).ready(function() {
    $('#email_invite_button').click(function() {
      if ($('#password').val() == '') {
        $('.password-error-message').html('Please Enter a valid password');
        $('.alert-error').removeClass('hide');
        return false;
      }
      $.ajax({
        async: true,
        data: {'password': $('#password').val()},
        dataType: 'json',
        type: 'POST',
        url: '/user/edit/verify_user',
        success: function(data) {
          if (data.status == 'success') {
            $('#email_show').hide();
            $('#UserVerified').val('1');
            $('#email_edit').show();
            $('#email_close_invite_button').click();
            $('#UserEmail').focus();
          }
          else {
            $('.password-error-message').html(data.message);
            $('.alert-error').removeClass('hide');
          }
        }
      })
    })
  });
  $('#verify_user').keypress(function(event) {
    if ((event.keyCode == 13)) {
      $('#email_invite_button').click();
    }
  });
</script>
