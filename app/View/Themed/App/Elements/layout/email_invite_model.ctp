
<!-- Modal -->
<div class="modal email_invitation" id="emailInviteFriends" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
		        
		        <h4 class="modal-title"><?php echo __('Invite your friends'); ?></h4>
		    </div>
            <div class="modal-body">
                <div class="row">
                	<p><?php echo __('Enter the email of your friends' ); ?></p>
                	<div class="form-group"> 
                	<?php
                		echo $this->Form->create('InviteFriendsByEmailForm',  array(										
										'inputDefaults' => array(
											'label' => false,
											'div' => false,
										)));
                	 	echo $this->Form->input('email', array('class' => 'form-control','id' => 'InviteFriendsByEmailInput', 'placeholder' => __('Type email and press spacebar or enter key')));
                	 	?>
                               <p class="placeholder"><?php echo __('Type email and press spacebar or enter key');?></p>
                	 	
                	 	<div id="emailInviteFriendsResponse">
                       
                    	</div>
                    <?php echo $this->Form->end(); ?>
                    </div>                        
                </div>
            </div>
            <div class="modal-footer">
                <button id="email_close_invite_button" type="button" class="btn btn_add" data-dismiss="modal">Close</button>
                <button id="email_invite_button" type="button" class="btn btn_active  ladda-button" data-style="expand-right" ><span class="ladda-label">Invite</span><span class="ladda-spinner"></span></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 

<?php
	echo $this->jQValidator->validator();
	echo $this->AssetCompress->script('tag-it.min');
	echo $this->AssetCompress->css('jquery.tagit');
?>               	 	
<script type="text/javascript">
$('#InviteFriendsByEmailInput').tagit();

$(document).on('click', '#email_invite_button', function() {
	
    $('#emailInviteFriendsResponse').html("");
    
    if ($('#emailInviteFriends').is(':visible')) {
        var valid = true;
        var value = $('#InviteFriendsByEmailInput').val().split(',');
        if(value ==""){
        	addDataTo_InviteFriendsByEmail_response_model("<div class ='alert alert-error'>Please enter an email address.</div>");
        }else{
            if ( value.length > 1 ) {
            	var multiple = true;
            } else {
            	var multiple = false;
            }
	        $(value).each(function(index, email ) { 
				if(!isEmail(email)){
					valid = false;
				}
	        });
	        if (valid === true) {
	        	rotate = Ladda.create(this);
			    if (rotate != null) {
			        rotate.start();
			    }
			    $("#email_close_invite_button").attr("disabled", "disabled");
	        	$.ajax({
	                url: '/user/profile/inviteFriendsByEmail',
	                dataType: 'json',
	                data: {
	                    email_list :$('#InviteFriendsByEmailInput').val() 
	                },
	                success: function(data) {
	                rotate.stop();
	                $("#email_close_invite_button").removeAttr("disabled");
	                $('#InviteFriendsByEmailInput').tagit("removeAll");
	                show_InviteFriendsByEmail_response(data, multiple);
	                  
	                }
	            });
	        }else{
	        	addDataTo_InviteFriendsByEmail_response_model("<div class ='alert alert-error'>You have entered an invalid email address</div>");
	        }
        }
    }
   
});

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
	
function show_InviteFriendsByEmail_response(data, multiple){
	$('#emailInviteFriendsResponse').html("");
	
	if(typeof(data['mailed_list']) != "undefined" && data['mailed_list'] !== null){
		var count = data['mailed_list'].length;
		var user = "person";
		if(count>1){
			user = "people";
		}		
		var response = "<div class='invitaion_report'><h4> You have successfully sent friend request to "+ count +" "+ user + ":</h4>";
		response += addMailLists(data['mailed_list']) + "</div>";
		addDataTo_InviteFriendsByEmail_response_model(response);
	}
	
	if(typeof(data['couldnotmailed']) != "undefined" && data['couldnotmailed'] !== null){		
		if(multiple){
			var response = "<div class='invitaion_report'><h4> " + data['couldnotmailed'].length + " of the email addresses you entered could not be sent an invitation:</h4>";			
		}else{
			var response = "<div class='invitaion_report'><h4> The email address that you entered could not be sent an invitation:</h4>";
		}
		response += addMailLists(data['couldnotmailed']) + "</div>";
		addDataTo_InviteFriendsByEmail_response_model(response);		
	}
	
	if(typeof(data['friends']) != "undefined" && data['friends'] !== null){
		if(multiple){
			var response = "<div class='invitaion_report'><h4> " + data['friends'].length + " of the email addresses you entered matched someone you are already friends with on " + app.appName + ":</h4>";
		}else{
			var response = "<div class='invitaion_report'><h4>The user of email address that you entered is already your friend on " + app.appName + ":</h4>";
		}	
		
		response += addMailLists(data['friends']) + "</div>";
		addDataTo_InviteFriendsByEmail_response_model(response);
	}
	
	if(typeof(data['request_sent']) != "undefined" && data['request_sent'] !== null){	
		if(multiple){
			var response = "<div class='invitaion_report'><h4> " + data['request_sent'].length + " of the email addresses you entered matched someone you have already sent request on " + app.appName + ":</h4>";
		}else{
			var response = "<div class='invitaion_report'><h4> The email address that you have entered matches someone you have already sent a request on " + app.appName + ":</h4>";
		}	
		
		response += addMailLists(data['request_sent']) + "</div>";
		addDataTo_InviteFriendsByEmail_response_model(response);
	}
}

function addMailLists(data){
	var list = "";
	var i = 0;

	for(; i < (data.length - 1) ; i++){
		list += "<span>" + data[i] + ",</span>";
	}
	list += "<span>" + data[i] + "</span>";
	return (list);
}

function addDataTo_InviteFriendsByEmail_response_model(data){
	$('#emailInviteFriendsResponse').append(data);
}

$(document).on('click', '#email_close_invite_button', function() {
	$('#emailInviteFriendsResponse').html("");
	$('#InviteFriendsByEmailInput').tagit("removeAll");
	
});


</script>