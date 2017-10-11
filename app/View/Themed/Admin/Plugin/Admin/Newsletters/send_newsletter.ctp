<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Newsletters', '/admin/Newsletters');
$this->Html->addCrumb(__('Send Newsletters'));
?>
<div class="page-content">
    <section class="grid_12">
        <div class="block-border">
	    <?php echo $this->Form->create('Newsletter', array('class' => 'block-content form', 'id' => 'SubscriberForm',
	    		'url'=>array('action'=>'sendNewsletterEmails'), 'inputDefaults' => array('div' => false, 'label' => false))); ?>
	    <?php echo $this->Form->hidden('insertId', array('value' => $insertId)); ?>
            <h1><?php echo __('Send Newsletters'); ?></h1>
            <table class="table sortable no-margin" cellspacing="0" width="100%" style="margin-left: 0.33em; margin-bottom:-0.67em; ">



                <tr>
                    <td align="left" width="20%" valign="top">
                        <p> <b><?php echo __('Choose Users: '); ?></b> </p> </td>
                    <td align="left" width="80%" valign="top" class="control-group">
                       
			<?php echo $this->Form->input('subscribers', array('type' => 'select', 'options' => $type,'required' => TRUE, 'empty' => array('' => 'Select subscriber'))) ?>

                    </td>
                </tr>

		<tr class="multi-email-section" style="display:none">
                    <td align="left" width="20%" valign="top"><b><?php echo __('Email Address: '); ?></b></td>
                
                    <td  class="" width="80%">
			<div class="control-group control-group">
			    <?php echo $this->Form->input('email_address', array('type' => 'textarea', 'id' => 'email_address', 'class' => 'form-control','style'=>'width:450px;', 'required' => FALSE)); ?>
                        </div>
			<span class="help-inline" style='margin-left: 10px; color: #009fff;'>Comma separated values for multiple email address. eg: email@domain.com,email2@domain.com</span>
                    </td>
                </tr>



            </table>
            <table cellspacing="0" width="100%" style="margin-left: 0.33em; margin-top: 40px;">
                <tr>
                    <td width="100" align="left">
			<?php
			echo $this->Form->submit(__('Send', true), array(
                            'div' => false, 
                            'onclick' => 'return validateNewsletterForm();',
                            'class' => 'btn btn-small btn-success', 
                            'style' => 'float: right; margin-right: 45px; min-width: 90px; height: 35px !important;'));

			echo $this->Form->button(
				'Cancel', array(
			    'type' => 'button',
			    'onclick' => "window.location='/admin/Newsletters'",
			    'class' => 'btn btn-small',
			    'style' => 'float: right; margin-right: 10px;min-width: 90px; height: 35px !important;'
				)
			);
			?>  
                    </td>               
                </tr>
            </table>

	    <?php echo $this->Form->end(); ?>
        </div>

    </section>    	

    <?php echo $this->jQValidator->validator(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
	$("#newsletters-li a").trigger('click');
	$("ul.nav-list li").removeClass('active');
	$("#newsletters-manage-li").addClass('active');
	$("#email_address").removeClass("required");

	$('#NewsletterSubscribers').change(function() {
            resetNewsletterForm();  
	    $("#email_address").removeAttr("required");
	    $(".multi-email-section").hide();
	    if ($(this).val() == "multi") {
		$(".multi-email-section").show();
		$('#email_address').prop('required', true);
	    }
	});
    });

    function validateNewsletterForm(){
        
         var selector = $('#NewsletterSubscribers');
         var subscribers = selector.val();
        
        if ( subscribers == '') {
            showSelectSubscriberError(selector);
            return false ;
        } else if ( subscribers == 'all' ) {
            return true;
        } else if ( subscribers == 'multi'){
            
            var value = $('#email_address').val().split(',');
            var valid = true;
             $(value).each(function(index, email ) { 
                        if(!isEmail(email.trim())){
                                valid = false;
                        }
            });

            if(!valid) {
                showEmailValidationError(value.length);
            }
            return valid;
        }
        return false;
    }
    
    function showEmailValidationError(count) {       
       
        if(count == 0 || count == 1){
            var message = "Please enter a valid email address.";
        } else  {
            var message = "Please check the email addresses that you have entered";
        } 
        
       $('#email_address').parent().find('span').remove();
        $('#email_address').parent().addClass('error');        
        $('#email_address').parent().append('<span for="email_address" class="help-block">' +message+ '</span>');
    }
    
    function showSelectSubscriberError(selector){
        var message = "please select users";
        selector.parent().addClass('error');  
        selector.parent().append('<span for="email_address" class="help-block">' +message+ '</span>');
    }
    function resetNewsletterForm(){
        $('#NewsletterSubscribers').parent().removeClass('error');
        $('#NewsletterSubscribers').parent().find('span').remove();
        $('#email_address').parent().removeClass('error');
        $('#email_address').parent().find('span').remove();
        $('#email_address').val('');
    }
    
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

</script>