var ROLE_PATIENT = 1;
var ROLE_FAMILY = 2;
var ROLE_CAREGIVER = 3;
var ROLE_OTHER = 4;

/**
 * Function to remove validation from dob fields
 */
function removeDobValidations() {
	$('.dobrow select').each(function() {
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
		var selectedUserType = parseInt($('#UserType').val());
		var isGroupRequired = (selectedUserType === ROLE_PATIENT) ? true : false;
		$(this).rules('add', {
			groupRequired: isGroupRequired,
			validDOB: true,
			messages: {
				groupRequired: 'Enter your date of birth',
				validDOB: 'Minimum age limit is 13 years'
			}
		});
	});
}

/**
 * Remove dob validation on selecting each drop downs
 */
$(document).on('change', '.dobrow select', function() {
	removeDobValidations();
});

/**
 * Add dob validation on submitting edit user form
 */
$(document).on('click', '#userEditForm button[type="submit"]', function() {
	handleCountryZipValidation($('#UserCountry'));
	addDobValidations();
	handleGenderValidation();
});

/**
 * Function to handle gender validation based on user type
 */
function handleGenderValidation(){
	var selectedUserType = parseInt($('#UserType').val());
	if (selectedUserType === ROLE_PATIENT) {
		$('#UserGender').rules('add', {
			required: true,
			messages: {
				required: 'Please select a Gender'
			}
		});
	}
	else {
		$('#UserGender').rules('remove', 'required');
	}
}

/**
 * Function to add form validation rule for diagnosis date
 */
function addDiagnosisDateValidationRule() {
	$.validator.addMethod('isValidDiagonisedDate', function(value, element) {
		var dobYear = $('#UserDob-year').val();
		var diagnosedYear = $(element).val();

		// check for validity
		var valid = false;
		if ((dobYear > 0) && (diagnosedYear > 0)) {
			valid = (dobYear <= diagnosedYear);
		}
		else {
			valid = true;
		}
		return valid;
	});
}

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

/**
 * Function to initialize the registration functions
 */
function initRegistration() {
	detectTimeZone();
	applyChosen();
	$(".chosen-select").chosen().change(function() {
		$(this).valid();
	});
	setHideShowPlugin('#UserPassword');
	setHideShowPlugin('#UserConfirm-password');
	$('#UserPassword').pwstrength();
	createUploader();
	addDiagnosisDateValidationRule();
	initFaceList("#PatientDisease0UserTreatments");
}

/**
 * Function to detect user timezone
 */
function detectTimeZone() {
	$.ajax({
		url: '/api/detected_timezone_id_JSON',
		data: getTimeZoneData(),
		method: 'POST',
		dataType: 'JSON'
	}).done(function(data) {
		$('#UserTimezone').val(data);
	});
}

/**
 * Function to remove validation from the disease name fields 
 */
function removeDiseaseNamesValidation() {
	$('[name*="[disease_name]"]').each(function() {
		$(this).rules('remove', 'required');
		$(this).valid();
	});
}

/**
 * Function to change RHS form fields based on selected role
 */
$(document).on('click', '.roles', function() {
	$('.role_container .roles').removeClass('active');
	$(this).addClass('active');
	var selectedUserType = parseInt($(this).data('user_type'));
	$('#UserType').val(selectedUserType);
	var selectedRoleName = $(this).find('h3').text();
	var formHeading = selectedRoleName + ' SignUp';
	$('#form_heading').text(formHeading);
	var defaultProfileImg = '/theme/App/img/user_default_' + selectedUserType + '_medium.png';
	var profileImgBorderClass;
	var conditionHeading;
	var conditionMandatoryStar = $('.condition_row .red_star_span');
	var dobMandatoryStar = $('.dobrow .red_star_span');
	var genderMandatoryStar = $('.gender_row .red_star_span');
	var patientOnlyConditionCols = $('.diagnosis_date_row, .medication_row');
	if (selectedUserType === ROLE_PATIENT) {
		patientOnlyConditionCols.show();
		conditionMandatoryStar.show();
		dobMandatoryStar.show();
		genderMandatoryStar.show();
		conditionHeading = 'Condition Details';
		profileImgBorderClass = 'border_patient';
	}
	else {
		patientOnlyConditionCols.hide();
		patientOnlyConditionCols.each(function() {
			$(this).find('select, input').val('');
		});
		$('.medication_row .facelist li.token').remove();
		removeDiseaseNamesValidation();
		conditionMandatoryStar.hide();
		dobMandatoryStar.hide();
		genderMandatoryStar.hide();
		var conditionOf;
		switch (selectedUserType) {
			case ROLE_OTHER:
				conditionOf = 'friend';
				profileImgBorderClass = 'border_other';
				break;
			case ROLE_CAREGIVER:
				conditionOf = 'patient';
				profileImgBorderClass = 'border_caregiver';
				break;
			case ROLE_FAMILY:
				conditionOf = 'family member';
				profileImgBorderClass = 'border_family';
				break;
		}
		conditionHeading = 'What condition does your ' + conditionOf + ' have?';
	}
	$('.condition_heading').text(conditionHeading);
	var profileImg = $('#uploadPreview img');
	if ($('#cropfileName').val() === '') {
		profileImg.attr('src', defaultProfileImg);
	}
	var profileImgClass = 'img-circle profile_brdr_5 ' + profileImgBorderClass;
	profileImg.attr('class', profileImgClass);
	removeValidationErrors();
});

/**
 * Function to remove validation errors from the registration form
 */
function removeValidationErrors() {
	removeDobValidations();
	var validator = $('#User').validate();
	validator.resetForm();
	$('.form-group').removeClass('error');
}

/**
 * Function to clear disease field if no valid condition is selected
 */
$(document).on('blur', '.disease_search', function() {
	if ($(this).next('.disease_id_hidden').val().trim() === "") {
		$(this).val('');
	}
});

/**
 * Function to add new condition
 */
$(document).on('click', '#add_condition_btn', function() {
	var lastConditionRowIndex;
	var newConditionRowIndex;
	lastConditionRowIndex = $('#last_condition_index').val();
	newConditionRowIndex = parseInt(lastConditionRowIndex) + 1;
	var conditionRow = $('#sample_condition_row').clone();
	conditionRow.removeClass('hide').removeAttr('id');
	conditionRow.find('input').each(function() {
		var inputName = $(this).attr('name');
		var inputId = $(this).attr('id');
		inputName = inputName.replace('index', newConditionRowIndex);
		inputId = inputId.replace('Index', newConditionRowIndex);
		$(this).attr('name', inputName);
		$(this).attr('id', inputId);
	});
	$('#conditions_container').append(conditionRow);
	$('#last_condition_index').val(newConditionRowIndex);
	initFaceList('#PatientDisease' + newConditionRowIndex + 'UserTreatments');
});

/**
 * Function to remove condition
 */
$(document).on('click', '.condition_row .close', function() {
	$(this).closest('.condition_row').remove();
});

/**
 * Hide disease name validation error for non-patient roles, 
 * if treatment and date of diagnosis if empty
 */
$(document).on('change', '[name*="[diagnosis_date_year]"], [name*="[treatment_id]"]', function() {
	var selectedUserType = parseInt($('#UserType').val());
	if (selectedUserType !== ROLE_PATIENT) {
		var conditionRow = $(this).closest('.condition_row');
		var diseaseNameField = conditionRow.find('[name*="[disease_name]"]');
		var diagnosisDateField = conditionRow.find('[name*="[diagnosis_date_year]"]');
		var treatmentField = conditionRow.find('[name*="[treatment_id]"]');
		if (diagnosisDateField.val() === '' && treatmentField.val() === '') {
			diseaseNameField.rules('remove', 'required');
			diseaseNameField.valid();
		}
	}
});

/**
 * Function to add validation to diagnosis fields
 */
function addDiagnosisValidations() {
	$('.condition_row').each(function() {
		var diseaseNameField = $(this).find('[name*="[disease_name]"]');
		var diagnosisDateField = $(this).find('[name*="[diagnosis_date_year]"]');
		var selectedUserType = parseInt($('#UserType').val());
		if (selectedUserType === ROLE_PATIENT) {
			diseaseNameField.rules('add', {
				required: true,
				messages: {
					required: 'Please enter a valid diagnosis'
				}
			});
		}
		else {
			var treatmentField = $(this).find('[name*="[treatment_id]"]');
			if (diagnosisDateField.val() === '' && treatmentField.val() === '') {
				diseaseNameField.rules('remove', 'required');
			}
			else {
				diseaseNameField.rules('add', {
					required: true,
					messages: {
						required: 'Please select a valid diagnosis'
					}
				});
			}
		}
		diagnosisDateField.rules('add', {
			isValidDiagonisedDate: true,
			messages: {
				isValidDiagonisedDate: 'Please enter a valid date'
			}
		});
	});
}

/**
 * Function to make the agree checkbox checked on clicking the terms links
 */
$(document).on('click', '#terms_conditions', function() {
	$('#agree_check').click();
});

/**
 * Function to finish the sign up process
 */
$(document).on('click', '#signup_finish_btn', function() {
	handleGenderValidation();
	addDobValidations();
	addDiagnosisValidations();
	handleCountryZipValidation($('#UserCountry'));
	if ($('#User').valid() && (!$('#registrationWizards .form-group').hasClass('error'))) {
		$('#User').submit();
		$(this).prop('disabled', true);
	}
	else {
		return false;
	}
});

/**
 * Function to cancel the sign up process
 */
$(document).on('click', '#signup_cancel_btn', function() {
	window.location.href = '/';
});

/**
 * Function to play signup video in a pop up
 */
$(document).on('click', '.signup_video', function() {
    showVideoPopup(this);
});