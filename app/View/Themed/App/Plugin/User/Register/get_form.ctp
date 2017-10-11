<?php
      $inputDefaults = array(
            'label' => false,
            'div' => false,
        );
		echo $this->Form->create(null, array(
				'url' => array('plugin' => null, 'controller' => 'user', 'action' => 'register'),
				'type' => 'file',
                'id' => $formId,
                'inputDefaults' => $inputDefaults
       ));
       echo $this->Form->hidden('type', array('value' => $type));
?>
	<div id="registrationWizards">
	<?php
        $wizardData = array('type' => $type);
        switch ($type) {
            case 1:
                echo $this->element('User.patient_wizard', $wizardData);
                break;

            case 2:
                echo $this->element('User.family_wizard', $wizardData);
                break;

            case 3:
                echo $this->element('User.caregiver_wizard', $wizardData);
                break;

            case 4:
                echo $this->element('User.other_user_wizard', $wizardData);
                break;
        }
	?>
	</div>
<?php 
echo $this->Form->end(); 
echo $this->jQValidator->validator();
?>

<script>
function createUploader() {
	$upload_btn = $('#bootstrapped-fine-uploader');
	$messages = $('#uploadmessages');

	$preview_div = $("#uploadPreview");
	$preview_img = $("#uploadPreview img");  


	function previewPhoto(response) {
 	     $.ajax({
 	    	dataType: 'json',
 	        type: 'POST',
 	        url: '/user/register/photo',
 	        data: {'crop_image' : true, 
 	 	        'x1': $('#x1').val(),  
 	 	      	'y1': $('#y1').val(),
 	 	    	'w': $('#w').val(),   	
 	 	    	'h': $('#h').val(),   	 	 	    	
 	 	        'cropfileName': response.fileName
 	 	    },
 	        beforeSend: function() {
 	        	$preview_img.attr("src", "<?php echo Configure::read('App.SITE_URL') ?>/img/loader.gif");
 	        },
 	        success: function(data) {
 	        	$('#cropfileName').val(data.fileName);
 	        	$preview_img.attr("src", data.fileUrl);
 	        }
 	    });
	}

	function createCropper(cropBox)
	{
		var ias = cropBox.find(".bootbox-body img").imgAreaSelect({ 
			/* remove:true, */
			parent: '.bootbox',
			autoHide: false,
			mustMatch: true,
    		handles: true,
    		instance: true,
    		aspectRatio: '1:1', 
    		x1: 0, y1: 0, x2: 200, 
    		y2: 200, 
    		minHeight : 200, 
    		minWidth : 200,
    		onInit: function () {
    			cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
        	},
    		onSelectStart: function () {
    			cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
        	},
    		onCancelSelection: function () {
    			cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");
        	},
        	
    		onSelectEnd: function (img, selection) {
                $('#x1').val(selection.x1);
                $('#y1').val(selection.y1);
                $('#w').val(selection.width);  
                $('#h').val(selection.height);  
            }
    	});
	}
	
    var uploader = new qq.FineUploaderBasic({
    	button: $upload_btn[0],
    	debug: false,
    	multiple: false,	
        request: {
            endpoint: '/user/register/photo'
        },
        validation: {
        	acceptFiles: "image/*",
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'bmp'],
			/* itemLimit: "1", */
            minSizeLimit: "1024",
            sizeLimit: "5242880"
        },
        callbacks: {
        	onError: function(a, b, c, d) {
        		$messages.html('<div class="alert alert-error">'+c+'</div>');
        		$messages.show();
        		$upload_btn.show();
            },
            
            onSubmit: function(id, fileName) {
            	$upload_btn.hide();
            	$messages.show();
                $messages.html('<div class="alert alert-info">onSubmit</div>');
            },
            
            onUpload: function(id, fileName) {
            	var upload_msg = '<img src="<?php echo Configure::read("App.SITE_URL") ?>/img/loading.gif" alt="Initializing. Please hold."> ' +
                'Initializing ' + '“' + fileName + '”';
            	$messages.html('<div class="alert alert-info">'+upload_msg+'</div>');
            },
            
            onProgress: function(id, fileName, loaded, total) {
                if (loaded < total) {
	                progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
	                $messages.html('<div class="alert alert-info"><img src="<?php echo Configure::read("App.SITE_URL") ?>/img/loading.gif" alt="In progress. Please hold."> ' +
                                     'Uploading ' +
                                     '“' + fileName + '” ' +
                                     progress+'</div>');
                } else {
                	$messages.show();
                }
            },
            
            onComplete: function(id, fileName, responseJSON) {
            	$upload_btn.show();
                if (responseJSON.success) {
                	$messages.hide();
                	var cropBox = bootbox.dialog({
                		  closeButton: false,
                		  message: "test",
                		  title: "Profile Photo",
                		  buttons: {
                		    success: {
                		      label: "Accept",
                		      className: "btn-success",
                		      callback: function() {
                		    	  previewPhoto(responseJSON);
                		      }
                		    },
                		    cancel: {
                		      label: "Cancel",
                		      className: "btn-default",
                		      callback: function() {
                		    	  /*
                		    	  * TODO : Remove the uploaded temp image
                		    	  */
                		    	  uploader.cancelAll();
                		      }
                		    }
                		  }
                	});

                	cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");
                	var imgHTML = "<div class='upload_area'><img src='" +responseJSON.fileurl+"' alt='Uploaded photo' /></div>";
                	imgHTML += "<div>To make adjustments, please drag around the white rectangle below. When you are happy with the photos, click \"Accept\" button. Your beautiful, clean, non-pixelated image should be at minimum 200x200 pixels.</div>"
                	cropBox.find(".bootbox-body").html(imgHTML);

                	setTimeout(function(){createCropper(cropBox);}, 500)
                	
                    
                } else {
                	$messages.show();
                	$('#uploadmessages .alert').removeClass('alert-info')
                                .addClass('alert-error')
                                .html('Error with ' +
                                        '“' + fileName + '”: ' +
                                        responseJSON.error);
                }
            }
        }        
    });
}
$(function() {

	/*
	 * Initialize the uploader functionality
	 */
	createUploader();
	
	
	/*
	 * Role unselect function
	 */
	$("#div_role_choosen .change_role").click(function(){
		$("#div_role_select").show();
        $("#div_role_choosen").hide();
        return false;
	});


	//$('input[type=file]').bootstrapFileInput();
	
	var selected_role = <?php echo $type; ?>; 
	/*
	 * Wizard process
	 */
	 var registration_wizard = $('#registrationWizard_'+selected_role);
	 
	 	registration_wizard.on('change', function(e, data) {
		 	if(typeof(data) != "undefined" && data.direction == "next") {
				if(!$('#User').valid()) return false;
		 	}
		});
		
	 	registration_wizard.on('changed', function(e, data) {
	 		var item = registration_wizard.wizard('selectedItem');
			var step_id = "#step_"+selected_role+"_"+item.step;
			var step_li = registration_wizard.find("li[data-target='"+step_id+"']");
			var wiz_title = step_li.data("title");
			$("#div_role_choosen .page-header h3 span").html(wiz_title);
		});
		
	 	registration_wizard.on('finished', function(e, data) {
			if($('#User').valid()){
				$("#User").submit();
			}
			
		});

	 	$(document).on('click', '#add_diagnosis_btn', function() {
	 	    var diagnosisLastIndex = $('#diagnosis_last_index').val();
	 	    var diagnosisNewIndex = parseInt(diagnosisLastIndex) + 1;
	 	    $.ajax({
	 	        type: 'POST',
	 	        url: '/user/register/getDiagnosisForm',
	 	        data: {'index': diagnosisNewIndex},
	 	        beforeSend: function() {
	 	        },
	 	        success: function(result) {
	 	            $('#diagnosis_form_container').append(result);
	 	            $('#diagnosis_last_index').val(diagnosisNewIndex);
                    runRelatedFormsValidationScript();
	 	        }
	 	    });
	 	});

	 	$(document).on('click', '.is_diagnosed_control', function() {
	 	    var diagnosisDateControls = $(this).parents('.diagnosis_form').find('.diagnosis_date_controls');
	 	    if ($(this).val() === 'Y') {
	 	        diagnosisDateControls.show();
	 	    }
	 	    else {
	 	        diagnosisDateControls.hide();
	 	    }
	 	});

	 	$(document).on('click', '.diagnosis_form .close', function() {
	 		$(this).closest( ".diagnosis_form" ).remove();
	 		var diagnosisLast = $('#diagnosis_last_index');
	 		diagnosisLast.val(parseInt(diagnosisLast.val()) - 1);
	 	});	 	

	 	/**
	 	 * Search disease names
	 	 */
	 	$(document).on('focus', '.disease_search', function() {
            var minLength = 1;
	 	    initDiseaseAutoComplete(this, minLength);
	 	});
        
        $.validator.addMethod('isValidDiagonisedDate', function(value, element) {
            var diagnosisForm = $(element).closest('.diagnosis_form');
            var symptom_year = diagnosisForm.find('.diagnosis-year').val();
            var symptom_month = diagnosisForm.find('.diagnosis-month').val();
            var diagnosed_year = diagnosisForm.find('.diagnosed-year').val();
            var diagnosed_month = diagnosisForm.find('.diagnosed-month').val();

            // check for validity
            var valid = false;
            if((symptom_year>0) && (symptom_month>0) && (diagnosed_year>0) && (diagnosed_month>0)){
                var symptom_date = new Date(symptom_year, symptom_month);
                var diagnosed_date = new Date(diagnosed_year, diagnosed_month);
                valid = (symptom_date <= diagnosed_date);
            }
            else {
                valid = true;
            }

            // error class placement
            var elementGroup = $(element).closest('.form-group-container').find('.form-group');
            if(!valid){
                elementGroup.addClass('error');
            }
            else{
                elementGroup.removeClass('error');
            }

            return valid;
        });
});
</script>
