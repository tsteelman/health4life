var ROLE_PATIENT = 1;
var ROLE_FAMILY = 2;
var ROLE_CAREGIVER = 3;
var ROLE_OTHER = 4;

/**
 * Function to remove validation from dob fields
 */
function removeDobValidations() {
	$('.dobrow select, .patient_dob_row select').each(function() {
		$(this).rules('remove', 'groupRequired');
		$(this).rules('remove', 'validDOB');
		$(this).parents('.form-group-container').removeClass('error');
		$(this).valid();
	});
}

/**
 * Function to add validation to dob fields
 */
function addDobValidations() {
	$('.dobrow select').each(function() {
		$(this).rules('add', {
			groupRequired: true,
			validDOB: true,
			messages: {
				groupRequired: 'Enter your date of birth.',
				validDOB: 'Minimum age limit is 13 years.'
			}
		});
	});
}

/**
 * Remove dob validation on selecting each drop downs
 */
$(document).on('change', '.dobrow select, .patient_dob_row select', function() {
	removeDobValidations();
});

/**
 * Add dob validation on submitting edit user form
 */
$(document).on('click', '#userEditForm button[type="submit"]', function() {
	handleCountryZipValidation($('#UserCountry'));
	addDobValidations();
});

/**
 * Function to add validation to patient dob fields
 */
function addPatientDobValidations() {
	$('.patient_dob_row select').each(function() {
		$(this).rules('add', {
			groupRequired: true,
			validDOB: true,
			messages: {
				groupRequired: 'Enter Patient date of birth.',
				validDOB: 'Minimum age limit is 13 years.'
			}
		});
	});
}

$(function() {
    $.validator.addMethod('isValidDiagonisedDate', function(value, element) {
        var diagnosisForm = $(element).closest('.diagnosis_form');
        var dob_year;
        var userType = parseInt($('#UserType').val());
        if (userType === ROLE_PATIENT) {
            dob_year = $('#UserDob-year').val();
        }
        else if (userType === ROLE_CAREGIVER) {
            dob_year = $('#UserPatient-dob-year').val();
        }

        var diagnosed_year = diagnosisForm.find('.diagnosed-year').val();

        // check for validity
        var valid = false;
        if ((dob_year > 0) && (diagnosed_year > 0)) {
            valid = (dob_year <= diagnosed_year);
        }
        else {
            valid = true;
        }

        // error class placement
        var elementGroup = $(element).closest('.form-group-container').find('.form-group');
        if (!valid) {
            elementGroup.addClass('error');
        }
        else {
            elementGroup.removeClass('error');
        }

        return valid;
    });

    // set the different wizards html in temp variables
    var registrationWizard_1_html = $('#registrationWizard_1').html();
    var registrationWizard_2_html = $('#registrationWizard_2').html();
    var registrationWizard_3_html = $('#registrationWizard_3').html();
    var registrationWizard_4_html = $('#registrationWizard_4').html();
    $('#wizard_provider').remove();

    /*
     * Role selection function
     */
    $(".role_btn").click(function() {
        var selectedRole = parseInt($(this).data("role"));
        $("#div_role_choosen .page-header h1").html($("#role_" + selectedRole).data("title"));
        $("#role_sel_desc").html($("#role_" + selectedRole).html());

        $("#div_role_select").hide();
        $("#div_role_choosen").removeClass("hide").show();

        $('#UserType').val(selectedRole);
		
		removeDobValidations();

        window.scrollTo(0, 0);

        switch (selectedRole) {
            case ROLE_PATIENT:
                $('#registrationWizards').html('<div class="wizard">' + registrationWizard_1_html + '</div>');
                break;
            case ROLE_FAMILY:
                $('#registrationWizards').html('<div class="wizard">' + registrationWizard_2_html + '</div>');
                break;
            case ROLE_CAREGIVER:
                $('#registrationWizards').html('<div class="wizard">' + registrationWizard_3_html + '</div>');
                break;
            case ROLE_OTHER:
                $('#registrationWizards').html('<div class="wizard">' + registrationWizard_4_html + '</div>');
                break;
        }

        //initialize hideShow plugin
        setHideShowPlugin('#UserPassword');
        setHideShowPlugin('#UserConfirm-password');
        
        // initialize the password strength indicator
        $('#UserPassword').pwstrength();

        // initialize the uploader functionality
        createUploader();

        // run related forms validation
//        if ((selectedRole === ROLE_PATIENT) || (selectedRole === ROLE_CAREGIVER)) {
            initFaceList("#PatientDisease0UserTreatments");
            runRelatedFormsValidationScript();
//        }

        /**
         * Wizard functionality
         */
        var registration_wizard = $('#registrationWizards .wizard');

        registration_wizard.on('change', function(e, data) {
            if (typeof(data) != "undefined" && data.direction == "next") {
				if ((data.step === 2) && ((selectedRole === ROLE_PATIENT) || (selectedRole === ROLE_CAREGIVER))) {
					addDobValidations();
					handleCountryZipValidation($('#UserCountry'));
				}
				else if ((data.step === 3) && (selectedRole === ROLE_CAREGIVER)) {
					addPatientDobValidations();
					handleCountryZipValidation($('#UserPatient-country'));
				}
				
                if (!$('#User').valid() || ($('#registrationWizards .form-group').hasClass('error'))){
					var id = $('#registrationWizards .form-group.error').parent().attr("id");
					$('[data-target=#'+id+']').trigger("click");
					return false;					
				}                    
            }
        });

        registration_wizard.on('changed', function(e, data) {
            var item = registration_wizard.wizard('selectedItem');
            var step_id = "#step_" + selectedRole + "_" + item.step;
            var step_li = registration_wizard.find("li[data-target='" + step_id + "']");
            var wiz_title = step_li.data("title");
            $("#div_role_choosen .page-header h1").html(wiz_title);
            applyChosen();
        });

        registration_wizard.on('finished', function(e, data) {
			if ((selectedRole === ROLE_FAMILY) || (selectedRole === ROLE_OTHER)) {
				addDobValidations();
				handleCountryZipValidation($('#UserCountry'));
			}

			
			if ($('#User').valid() 
					&& (!$('#registrationWizards .form-group').hasClass('error'))) {
				$("#User").submit();
			}		
		});
        
    });

});

function initFaceList(input) {
    var result_field = $(input).next('.treatment_id_hidden').attr('id');
    $(input).facelist('/api/searchTreatments', properties = {
        matchContains: true,
        minChars: 2,
        selectFirst: false,
        intro_text: 'Type Name',
        no_result: 'No Names',
        result_field: result_field
    });
}

var isUploading = false;
/*
 * Role unselect function
 */
$(document).on('click', '#div_role_choosen .change_role', function(event) {
	if ( !isUploading ) {
		$("#div_role_select").show();
		$("#div_role_choosen").hide();
	}
    return false;
});

$(document).on('click', '#add_diagnosis_btn', function() {
    $('#add_diagnosis_btn').attr('disabled', 'disabled');
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
            initFaceList('#PatientDisease' + diagnosisNewIndex + 'UserTreatments');
            runRelatedFormsValidationScript();
            $('#add_diagnosis_btn').removeAttr('disabled');
        }
    });
});

$(document).on('click', '#add_diagnosis_support_btn', function() {
    $('#add_diagnosis_support_btn').attr('disabled', 'disabled');
    var diagnosisLastIndex = $('#diagnosis_last_index').val();
    var diagnosisNewIndex = parseInt(diagnosisLastIndex) + 1;
    $.ajax({
        type: 'POST',
        url: '/user/register/getSupportDiagnosisForm',
        data: {'index': diagnosisNewIndex},
        beforeSend: function() {
        },
        success: function(result) {	
            $('#diagnosis_form_container').append(result);
            $('#diagnosis_last_index').val(diagnosisNewIndex);
            initFaceList('#PatientDisease' + diagnosisNewIndex + 'UserTreatments');
            runRelatedFormsValidationScript();
            $('#add_diagnosis_support_btn').removeAttr('disabled');
        }
    });
});

$(document).on('click', '.diagnosis_form .close', function() {
    $(this).closest(".diagnosis_form").remove();
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

/*
 * Function to validate and generate the DOB dropdowns
 */
function generate_day_select_box(elem)
{
    var related = $(elem).attr("data-rel");
    related = new String(related).split("#");
    var parent_row = $(elem).closest(".form-group-container");
    var day_option = $(parent_row).find("." + related[2]);
    var month_option = $(parent_row).find("." + related[1]);
    var year_option = $(parent_row).find("." + related[0]);

    if ($(elem).attr("id") == year_option.attr("id")) {
        month_option.prop('selectedIndex', 0);
        day_option.prop('selectedIndex', 0);
    }
    if ($(elem).attr("id") == month_option.attr("id")) {
        day_option.prop('selectedIndex', 0);
    }

    var day_selected_value = day_option.val();
    var month_value = month_option.val();
    var year_value = year_option.val();
    var day_value = new Date(year_value, month_value, 0).getDate();
    var month_loop_value = 12;


    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var date = new Date();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var year = date.getFullYear();

    if (year_value >= year)
    {
        month_loop_value = month;
    }

    if (year_value >= year && month_value >= month)
    {
        day_value = day - 1;
    }

    if (year_value != '' && month_value != '')
    {
        day_option.empty();
        day_option.append($('<option></option>').val('').html("Day"));
        for (var i = 1; i <= day_value; i++)
        {
            day_option.append($('<option>Day</option>').val(i).html(i));
        }
    }


    month_option.empty();
    month_option.append($('<option></option>').val('').html("Month"));
    for (var j = 1; j <= month_loop_value; j++)
    {
        var d = j - 1;
        month_option.append($('<option>Month</option>').val(j).html(months[d]));
    }

    day_option.val(day_selected_value);
    month_option.val(month_value);

}
/**
 * Profile photo uploader
 */
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
            data: {'crop_image': true,
                'x1': $('#x1').val(),
                'y1': $('#y1').val(),
                'w': $('#w').val(),
                'h': $('#h').val(),
                'cropfileName': response.fileName
            },
            beforeSend: function() {
                $preview_img.attr("src", app.site_url + 'theme/App/img/loading.gif');
            },
            success: function(data) {
                $('#cropfileName').val(data.fileName);
                $preview_img.attr("src", data.fileUrl);
            }
		});
	}

	function createCropper(cropBox, responseJSON)
	{
		var minDimension = 200;
		if (responseJSON.imageWidth < minDimension) {
			minDimension = responseJSON.imageWidth - 2;
		}
		else if (responseJSON.imageHeight < minDimension) {
			minDimension = responseJSON.imageHeight - 2;
		}

		var ias = cropBox.find(".bootbox-body img").imgAreaSelect({
			/* remove:true, */
			parent: '.bootbox',
            autoHide: false,
            mustMatch: true,
            handles: true,
            instance: true,
			aspectRatio: '1:1',
			x1: 0, y1: 0, x2: minDimension,
			y2: minDimension,
			minHeight: minDimension,
            minWidth: minDimension,
            onInit: function() {
                cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
            },
            onSelectStart: function() {
                cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
            },
            onCancelSelection: function() {
                cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");
            },
            onSelectEnd: function(img, selection) {
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
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
            /* itemLimit: "1", */
            minSizeLimit: "1024",
            sizeLimit: "5242880"
        },
        callbacks: {
            onError: function(a, b, c, d) {
                $messages.html('<div class="alert alert-error">' + c + '</div>');
                $messages.show();
                $upload_btn.show();
				$(".btn-prev").removeAttr("disabled");
				$(".btn-next").removeAttr("disabled");
				isUploading = false;
            },
            onSubmit: function(id, fileName) {
                $upload_btn.hide();
                $messages.show();
                $messages.html('<div class="alert alert-info">onSubmit</div>');
            },
            onUpload: function(id, fileName) {
                var upload_msg = '<img src="' + app.site_url + '/img/loading.gif" alt="Initializing. Please hold."> ' +
                        'Initializing ' + '"' + fileName + '"';
                $messages.html('<div class="alert alert-info">' + upload_msg + '</div>');  
				$(".btn-prev").attr("disabled", "disabled");
				$(".btn-next").attr("disabled", "disabled");
				isUploading = true;
            },
            onProgress: function(id, fileName, loaded, total) {
                if (loaded < total) {
                    progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                    $messages.html('<div class="alert alert-info"><img src="' + app.site_url + '/img/loading.gif" alt="In progress. Please hold."> ' +
                            'Uploading ' +
                            '"' + fileName + '" ' +
                            progress + '</div>');
                } else {
                    $messages.show();
                }
            },
            onComplete: function(id, fileName, responseJSON) {               
                isUploading = false;
                $upload_btn.show();
                if (responseJSON.success) {
                    $messages.hide();
                    var cropBox = bootbox.dialog({
                        closeButton: false,
                        message: "test",
                        title: "Profile Photo",
                        animate: false,
                        buttons: {
                            success: {
                                label: "Accept",
                                className: "accept btn-success",
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
                    var imgHTML = '<div class="upload_area"><img src="' + responseJSON.fileurl + '"' +
                            ' alt="Uploaded photo" /></div>';
                    imgHTML += '<div>To make adjustments, please drag around the white rectangle below.' +
                            ' When you are happy with the photos, click "Accept" button. Your beautiful, clean,' +
                            ' non-pixelated image should be at minimum 200x200 pixels.</div>';
                    cropBox.find(".bootbox-body").html(imgHTML);

                    setTimeout(function() {
                        createCropper(cropBox, responseJSON);
						$(".btn-prev").removeAttr("disabled");
						$(".btn-next").removeAttr("disabled");
                    }, 500);
					

                } else {
                    $messages.show();
                    $('#uploadmessages .alert').removeClass('alert-info')
                            .addClass('alert-error')
                            .html('Error with ' +
                            '"' + fileName + '": ' +
                            responseJSON.error);
                }
            }
        }
    });
}