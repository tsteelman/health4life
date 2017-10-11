/**
 * Community types
 */
var COMMUNITY_TYPE_OPEN = 1;
var COMMUNITY_TYPE_CLOSE = 2;
var COMMUNITY_TYPE_SITE = 3;

$(document).ready(function() {

	/**
	 * Community type pop over
	 */
	$('#community_type_help').popover({
		placement: 'right',
		trigger: 'hover focus',
		title: 'Community type',
		html: true,
		container: '#community_type_popover'
	});

	/**
	 * Tags input
	 */
	$('input#CommunityTags').tagsinput({
		confirmKeys: [13, 32]
	});

	/*
	 * Initialize the community photo uploader
	 */
	initCommunityPhotoUploader();

	/*
	 * Wizard process
	 */
	var communityWizard = $('#communityWizard').wizard();

	communityWizard.on('change', function(e, data) {
		if (typeof(data) != "undefined" && data.direction == "next") {
			if (data.step === 2) {
				handleCountryZipValidation($('#CommunityCountry'));
			}
			var formValid = $(form).valid();
			if (!formValid) {
				return false;
			}
			else {
				$('#diagnosis_form_container .no_result_msg').hide();
			}
		}
	});

	communityWizard.on('finished', function(e, data) {
		if ($(form).valid()) {
			$(form).submit();
			communityWizard.find('button').attr('disabled', 'disabled');
		}
	});

	/**
	 * Scroll to top on moving to next page in the wizard for better user experience
	 */
	communityWizard.on('changed', function() {
		window.scrollTo(0, 0);
	});

});

/**
 * On clicking cancel button or close icon, 
 * redirect to community listing page from 'create community' page, or 
 * redirect to community detail page from 'edit community' page.
 */
$(document).on('click', '#cancel_community_wizard, #close_community_wizard', function() {
	var backUrl = $(form).data('backurl');
	window.location.href = backUrl;
});

/**
 * Add more diagnosis
 */
$(document).on('click', '#add_diagnosis_btn', function() {
    var diagnosisLastIndex = $('#diagnosis_last_index').val();
    var diagnosisNewIndex = parseInt(diagnosisLastIndex) + 1;
    var record = $('#sample_diagnosis_record').clone();
    record.removeClass('hide').removeAttr('id');
    record.find('input').each(function() {
        var inputName = $(this).attr('name');
        var inputId = $(this).attr('id');
        inputName = inputName.replace('index', diagnosisNewIndex);
        inputId = inputId.replace('Index', diagnosisNewIndex);
        $(this).attr('name', inputName);
        $(this).attr('id', inputId);
    });
    $('#diagnosis_form_container').append(record);
    $('#diagnosis_last_index').val(diagnosisNewIndex);
});

/**
 * Search disease names
 */
$(document).on('focus', '.disease_search', function() {
	var minLength = 2;
	initDiseaseAutoComplete(this, minLength);
});

/**
 * Delete community.
 */
$(document).on('click', '#delete_community_button', function(e) {
	e.preventDefault();
	var location = $(this).attr('href');
	bootbox.confirm("On deleting the community, all the posts and events will be deleted. Are you sure you want to delete?", function(confirmed) {
		if (confirmed) {
			window.location.replace(location);
		}
	});
});

/**
 * Initialise the photo uploader
 */
function initCommunityPhotoUploader() {
	$uploadBtn = $('#bootstrapped-fine-uploader');
	$previewImg = $('#uploadPreview img#preview_image');
	$previewLoadingImg = $('#uploadPreview img#loading_img');

	var uploader = new qq.FineUploaderBasic({
		button: $uploadBtn[0],
		debug: false,
		multiple: false,
		request: {
			endpoint: '/community/add/uploadPhoto'
		},
		validation: {
			acceptFiles: 'image/*',
			allowedExtensions: app.imageExtensions,
			minSizeLimit: '1024',
			sizeLimit: '5242880'
		},
		callbacks: {
			onError: function(id, name, reason, xhr) {
				showUploadError(reason);
				$uploadBtn.show();
                                $('#btn_community_wizard_stop1').prop('disabled', false);
			},
			onSubmit: function(id, fileName) {
				$uploadBtn.hide();
			},
			onUpload: function(id, fileName) {
				showUploadLoadingMessage('Uploading ' + '“' + fileName + '” ');
                                $('#btn_community_wizard_stop1').prop('disabled', true);
			},
			onProgress: function(id, fileName, loaded, total) {
				if (loaded < total) {
					var progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
					showUploadLoadingMessage('Uploading ' + '“' + fileName + '” ' + progress);
				}
			},
			onComplete: function(id, fileName, responseJSON) {
                                $('#btn_community_wizard_stop1').prop('disabled', false);
				$uploadBtn.show();
				if (responseJSON.success) {
					hideUploadMessage();
					showImageCropDialog(id, responseJSON);
				} else {
					if (responseJSON.error) {
						showUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
					}
					else {
						showUploadError('Failed to upload photo.');
					}
				}
			}
		}
	});
	
	var cropBox;
	function showImageCropDialog(id, responseJSON) {
		var imgHTML = '<div class="crop_area"><img src="' + responseJSON.fileUrl + '"' +
				' alt="Uploaded photo" /></div>';
		imgHTML += '<div>To make adjustments, please drag around the white rectangle.' +
				' When you are happy with the photo, click "Accept" button.</div>';
		cropBox = bootbox.dialog({
			closeButton: false,
			message: imgHTML,
			title: "Crop Image",
			animate: false,
			buttons: {
				success: {
					label: "Accept",
					className: "accept btn-success",
					callback: function() {
						previewCroppedPhoto(id, responseJSON);
					}
				},
				cancel: {
					label: "Cancel",
					className: "btn-default",
					callback: function() {
						uploader.cancelAll();
						hideUploadMessage();
					}
				}
			}
		});

		cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");

		setTimeout(function() {
			createCropper(cropBox, responseJSON);
		}, 500);
	}

	var ias;
	function createCropper(cropBox, responseJSON) {
		var uploadedImg = cropBox.find(".bootbox-body img");
		srcWidth = responseJSON.imageWidth;
		srcHeight = responseJSON.imageHeight;
		var aspectRatio = '240 : 106';
		var cropMinDim = getCropMinDimensions(srcWidth, srcHeight, aspectRatio);
		ias = uploadedImg.imgAreaSelect({
			parent: '.bootbox',
			autoHide: false,
			mustMatch: true,
			handles: true,
			instance: true,
			imageWidth: srcWidth,
			imageHeight: srcHeight,
			aspectRatio: aspectRatio,
			x1: 0, y1: 0,
			x2: cropMinDim.width, y2: cropMinDim.height,
			onInit: function() {
				cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
			},
			onSelectStart: function() {
				cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
			},
			onCancelSelection: function() {
				cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");
			}
		});
	}

	function getCropMinDimensions(srcWidth, srcHeight, aspectRatio) {
		var aspectRatioArr = aspectRatio.split(':');
		var aspectWidth = aspectRatioArr[0];
		var aspectHeight = aspectRatioArr[1];
		var maxCropWidth = Math.floor(srcWidth / aspectWidth);
		var maxCropHeight = Math.floor(srcHeight / aspectHeight);
		var scale = Math.min(maxCropWidth, maxCropHeight);
		var cropMinDim = {
			width: scale * aspectWidth,
			height: scale * aspectHeight
		};
		return cropMinDim;
	}

	function previewCroppedPhoto(id, response) {
		var scaledSelection = ias.getSelection(false);
		$.ajax({
			dataType: 'json',
			type: 'POST',
			url: '/community/add/cropImage',
			beforeSend:function(){
				$previewLoadingImg.removeClass('hide');
				$previewImg.hide();
			},
			data: {
				'x1': scaledSelection.x1,
				'y1': scaledSelection.y1,
				'w': scaledSelection.width,
				'h': scaledSelection.height,
				'fileName': response.fileName
			},
			success: function(responseJSON) {
				$('#CommunityImage').val(responseJSON.fileName);
				var timestamp = new Date().getTime();
				var imageSrc = responseJSON.fileUrl + '?' + timestamp;
				$previewImg.attr('src', imageSrc);
				$previewImg.load(function() {
					$previewLoadingImg.addClass('hide');
					$previewImg.fadeIn();
				});
			}
		});
	}
}
$messages = $('#uploadmessages');
$loadingImg = '<img src="/img/loading.gif" /> ';
function showUploadError(message) {
	$messages.html(message);
	$messages.removeClass('alert-info').addClass('alert-error').show();
	$messages.parent().removeClass('upload_progress');
}
function showUploadLoadingMessage(message) {
	$messages.html($loadingImg + message);
	$messages.removeClass('alert-error').addClass('alert-info').show();
	$messages.parent().addClass('upload_progress');
}
function hideUploadMessage() {
	$messages.html('').hide();
	$messages.parent().removeClass('upload_progress');
}

function setUserStatus(communityId, userId, status) {
	if(status == 1) {
		var confirmMessage = "Are you sure you want to leave the community?";
	} else {
		var confirmMessage = "Are you sure you want to join the community?";
	}
	bootbox.confirm( confirmMessage, function(confirmed) {
	if (confirmed) {
				var l = Ladda.create(document.querySelector('#status'));  
				l.start();
				$.ajax({
					type: 'POST',
					url: '/community/details/setUserStatus',
					data: {'communityId': communityId, 'userId': userId, 'status': status},
					success: function(data) {
						location.reload();
					}
				});
			}
	});
}

/**
 * Function to approve a community invitation by the invitee
 */
$(document).on('click', '#approve_invitation_btn', function() {
	var l = Ladda.create(this);
	l.start();
	var communityId = $(this).closest('form').find('#community_id').val();
	$('#reject_invitation_btn').attr('disabled', 'disabled');
	$.ajax({
		type: 'POST',
		url: '/community/details/approveInvitation',
		data: {'communityId': communityId},
		success: function(data) {
			location.reload();
		}
	});
});

/**
 * Function to reject a community invitation by the invitee
 */
$(document).on('click', '#reject_invitation_btn', function() {
	var l = Ladda.create(this);
	l.start();
	var communityId = $(this).closest('form').find('#community_id').val();
	$('#approve_invitation_btn').attr('disabled', 'disabled');
	$.ajax({
		type: 'POST',
		url: '/community/details/rejectInvitation',
		data: {'communityId': communityId},
		success: function(data) {
			location.reload();
		}
	});
});

/**
 * On changing community type, if the type is site wide, hide invite friends from step3.
 */
$(document).on('change', '#CommunityType', function() {
	var communityType = parseInt($(this).val());
	if (communityType === COMMUNITY_TYPE_SITE) {
		$('#diagnosis_error').addClass('hide').hide();
		$('#diagnosis_error').closest('.form-group').removeClass('error');
		$('#community_wizard_step3_common').addClass('hide');
		$('#community_wizard_step3_sitewide').removeClass('hide');
	}
	else {
		$('#community_wizard_step3_common').removeClass('hide');
		$('#community_wizard_step3_sitewide').addClass('hide');
	}
});