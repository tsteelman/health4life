<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
if($type == 1 || $type == 3) { //patient or caregiver
	$this->Html->addCrumb('Manage Diagnosis');
	$pageHeading = 'Manage Diagnosis';
} else {
	$this->Html->addCrumb('Support Diagnosis');
	$pageHeading = 'Support Diagnosis';
}
$user = $this->Session->read('Auth');
$birth_year = strftime("%Y", strtotime($user['User']['date_of_birth']));
?>

<div class="container">
  <div class="row edit">
    <div class="col-lg-3 edit_lhs">
         <div class="respns_header"><h2><?php echo $pageHeading; ?></h2></div>
      <?php echo $this->element('User.Edit/lhs'); ?>
    </div>
    <div class="col-lg-9">


      <div class="page-header manage-diagnosis">           
        <h2>
		  <button class="btn pull-right print_btn conditions_print"><?php echo __('Print'); ?></button>
          <span><h2><?php echo $pageHeading; ?></span>&nbsp;
        </h2>         
      </div>
      <?php
      echo $this->Form->create('', array(
      'inputDefaults' => $inputDefaults,
      'id' => $formId,
      ));
      ?>
      <div id="diagnosis_form_container" class="edit_diagnosis_form">
          <?php
          $inputDefaults = array('label' => false, 'div' => false,);
          echo $this->element('User.edit_diagnosis_form',  array('index' => 0,
			  'options' => $inputDefaults));
          ?>
      </div>
      
      <div>&nbsp;</div>
      <div class="clearfix form-group">
        <div>
          <button type="button" class="btn upload_btn add-button" id="diagnosis_add_btn">+ Add diagnosis</button>
          <button id="submit_form"class="btn btn-next" type="button">Save</button>
		  <button type="button" class="btn btn_clear settings_cancel">Cancel</button>
        </div>
      </div>
      
    </div>
    <input type="hidden" id="birth-year" value="<?php echo $birth_year; ?>"/>
    <input type="hidden" id="user-type" value="<?php echo $type; ?>"/>
    <?php echo $this->Form->end(); ?>
</div>   
</div>       
    <?php echo $this->jQValidator->validator(); ?>
<script type="text/javascript">
  /**
   * Delete confirmation
   */
  function confirm_diagnosis_delete(message) {
    $('#message_box').html(message);
    $('#close-modal').hide();
    $('#confirm-delete').show();
    $('#delete_failed').click();
  }
  
  $(document).ready(function() {
      
    $(document).on('focusout', '.medication_input', function() {
        $(this).val('');;
      });
      
      $('.medication_input').each(function() {
        initFaceList('#'+$(this).attr('id'));
      });
      
      $(document).on('blur','.disease_search', function() {
          
        if ($(this).next('.disease_id_hidden').val().trim() == "") {
          $(this).val('');
        }
        is_valid_disease(this);
      });

    $('#diagnosis_add_btn').click(function() {
      var diagnosisLastIndex = $('#diagnosis_last_index').val();	  
      var diagnosisNewIndex = parseInt(diagnosisLastIndex) + 1;
	  var userType = parseInt($('#user-type').val());
	  if (userType === 1 || userType === 3){
		 url =  '/user/register/getDiagnosisForm';
	  } else {
		 url =  '/user/register/getSupportDiagnosisForm';
	  }
      $.ajax({
        type: 'POST',
        url: url,
        data: {'index': diagnosisNewIndex},
        beforeSend: function() {
        },
        success: function(result) {
          $('#diagnosis_form_container').append(result);
          $('#diagnosis_last_index').val(diagnosisNewIndex);
          initFaceList('#PatientDisease' + diagnosisNewIndex + 'UserTreatments');
          $('.disease_search').on('blur', function() {
            if ($(this).next('.disease_id_hidden').val().trim() == "") {
              $(this).val('');
            }
            is_valid_disease(this);
          });
          $('.diagnosed-year').blur(function() {
            is_valid_year(this);
          });
          
        }
      });
    });
	
	/**
	* Function to close the diagnosis forms
	*/
   $(document).on('click', '.diagnosis_form .close', function() {
	   $(this).closest('.diagnosis_form').remove();
	   var diagnosisLast = $('#diagnosis_last_index');
	   diagnosisLast.val(parseInt(diagnosisLast.val()) - 1);
   });
   
	/**
	* Function to close the diagnosis support forms
	*/
   $(document).on('click', '.diagnosis_support_form .close', function() {
	   $(this).closest('.diagnosis_support_form').remove();
	   var diagnosisLast = $('#diagnosis_last_index');
	   diagnosisLast.val(parseInt(diagnosisLast.val()) - 1);
   });

	$(document).on('click', '.close_add_form', function() {

      var id = $(this).attr('id');
	  var diseaseId = $(this).attr("data-diseaseId");

      $('#selected-diagnosis').val(id);
      confirm_diagnosis_delete("Are you sure you want to delete this ? This cannot be undone.");

	  $.ajax({
		url: '/user/api/checkDiseaseFollowStatus',
		cache: false,
		type: 'POST',
		data: {
			'diseaseId': diseaseId
		},
		success: function(result) {
		followcount = parseInt(result);
		if (followcount > 0) {
			$('#delete_confirm').unbind('click').click(function() {
		  bootbox.dialog({
        message: "Do you want to unfollow this disease?",
        title: "Unfollow",
        closeButton: true,
        backdrop: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {			
				
                    if (confirmed) {
						
					var unfollow = true;					
                      removeUserDisease(unfollow);  
                    } 
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear",
				callback: function(declined) {
					if (declined) {
                     var unfollow = false;
                      removeUserDisease(unfollow);
                    } 
				}
            }
        }
    });
	  
       
      });
		}
		else {	
		
			$('#delete_confirm').unbind('click').click(function() {
			var unfollow = false;
			removeUserDisease(unfollow);
			});
		}
		}
	});
      
    });
	
	function removeUserDisease(unfollow){	
		$.ajax({
          async: true,
          data: {
			  'id': $('#selected-diagnosis').val(),
			  'unfollow' : unfollow
		  },
          type: 'post',
          success: function(data) {
            if (data == 'failed') {
              $('#close-modal').show();
              var msg = "Sorry, you must have at-least one diagnosis detail.";
              $('#message_box').html(msg);
              $('#confirm-delete').hide();
              $('#delete_failed').click();
            }
            else {
              $('#close-modal').show();
              var msg = "Diagnosis removed Successfully.";
              $('#message_box').html(msg);
              $('#confirm-delete').hide();
              $('#delete_failed').click(); 
              $('#disease-' + $('#selected-diagnosis').val()).parent().remove();
            }
          },
          url: '/user/manageDiagnosis/delete'
        }); 
	}
	
    $('.x').click(function() {
      var id = $(this).parent().attr('id');
      var id_to_remove = id.split("-");
      id_to_remove = id_to_remove[1];

      var selected = $(this).parents('.facelist').find('.treatment_id_hidden').val();
      ids = selected.split(",");
      var new_value = "";
      for (id in ids) {
        if (ids[id] !== id_to_remove && ids[id] != "") {
          new_value += ids[id] + ','
        }
      }
      $(this).parents('.facelist').find('.treatment_id_hidden').val(new_value);
      $(this).parent().remove();
    });
    
    $('.diagnosed-year').blur(function() {
      is_valid_year(this);       
    });
    
    $('#submit_form').click(function() {
      var flag = 0;
      if ($('.disease_id_hidden').length != 1) {
        $('.disease_id_hidden').each(function(index, element) {
          if ($(element).val().trim() == "" ) {
//            $(element).parent().parent().parent().parent().remove();
          }
        });
      }
      $('.diagnosed-year').each(function( index, element ) {
        if (!is_valid_year(element)) {
          flag = 1;
        }
      });
      $('.disease_search').each(function( index, element ) {
        if (!is_valid_disease(element)) {
          flag = 1;
        }
      });
      if (flag == 0) {
        $('#patient_disease_form').submit();
      }
    });
  });
    
function is_valid_year(obj) {
  if ($('option:selected', obj).val() != "" && $('option:selected', obj).val() < $('#birth-year').val()) {
    if (!$(obj).parent('.form-group').hasClass('error')) {console.log('has class false');
      $(obj).parent('.form-group').addClass('error');
      $(obj).parent('.col-lg-7').append('<span class="help-block">The year should be greater than the year of birth.</span>');
    }
    return false;
  }
  else {
    $(obj).parent('.form-group').removeClass('error');
    $(obj).parent('.col-lg-7').find('span').remove();
    return true;
  }
}
  
 function is_valid_disease(obj) {
    
    var val = $(obj).val();
    var id = $(obj).attr('id');
	//undefined check to make sure it ( same class used at other place ) is from form
	if (val.length === 0 && (typeof id != 'undefined')) {
		if (!$(obj).hasClass('error')) {
		 $(obj).addClass('error');
            $(obj).parent('.form-group').addClass('error');
            $(obj).parent('.col-lg-6').append('<span class="help-block">Diagnosis cannot be empty</span>');
			}
			$(obj).parent('.form-group').addClass('error');
        return false;
	}
    else if ( val.length > 100 ) {
        if (!$(obj).hasClass('error')) {
            console.log('in');
            $(obj).addClass('error');
            $(obj).parent('.form-group').addClass('error');
            $(obj).parent('.col-lg-6').append('<span class="help-block">Cannot be more than 100 characters long.</span>');
        }  
        $(obj).parent('.form-group').addClass('error');
        return false;
    } else {
        $(obj).removeClass('error');
        $(obj).parent('.form-group').removeClass('error');
        $(obj).parent('.col-lg-6').find('span').remove();
        return true;
    }
 }
 
 /*
 * Printing Disese details
 */
 $(document).on('click', '.conditions_print', function() {
	 window.open('/healthinfo/print?graphIds=17' , '_blank');
 });
 
 
</script>

<style>
  .close_add_form {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 0 none;
    cursor: pointer;
    padding: 0;
    color: #000000;
    float: right;
    font-size: 21px;
    font-weight: bold;
    line-height: 1;
    opacity: 0.2;
    text-shadow: 0 1px 0 #FFFFFF;
  }
  .edit_diagnosis_form .col-lg-7 {
    padding-left: 15px !important;
    padding-right: 15px !important;
  }
  
  .edit_diagnosis_form .col-lg-7 select{width: 90px;}
  
  .add-button {
    height: 40px;
    padding: 6px 14px !important;
  }
  
</style>

<a id="delete_failed" style="display:none;" title="Click to change your email address" data-toggle="modal" data-target="#delete_success"  href="#"><?php echo __('Change'); ?></a>
<!-- Modal -->
<div class="modal email_invitation" id="delete_success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog password_verify">
        <div class="modal-content">
        	<div class="modal-header">
		        
		        <h4 class="modal-title"><?php echo __('Manage Diagnosis'); ?></h4>
		    </div>
            <div class="modal-body">
                <div class="row">
                  <p id="message_box"><?php echo __('Sorry, you must have at-least one diagnosis detail.' ); ?></p>
                	<div class="form-group"> 
                  </div>                        
                </div>
            </div>
            <div class="modal-footer">
              <div id="confirm-delete" style="display:none">
                <input type="hidden" id="selected-diagnosis" value=""/>
                <button id="delete_confirm" type="button" class="btn btn_add" data-dismiss="modal">Yes</button>
                <button id="no_delete" type="button" class="btn btn_add" data-dismiss="modal">No</button>
              </div>
              <div id="close-modal" style="display:none;">
                <button id="email_close_invite_button" type="button" class="btn btn_add" data-dismiss="modal">Close</button>
              </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->