var defaultPhotoSrc = '/theme/App/img/dashboard_default.jpg';
var isSlideShowEnabled = false;
var removeImgIcon = '<img src="/theme/App/img/close.gif" alt="X" class="remove_img hide" />';
$(document).ready(function() {
	if ($('#UserIsDashboardSlideshowEnabled').is(':checked')) {
		isSlideShowEnabled = true;
		showSlideShow();
	}
	initDashboardPhotoUploader();
});

$(document).on('change', '#UserIsDashboardSlideshowEnabled', function() {
	handleSlideShowEnabledStatusChange($(this).is(':checked'));
});

$(document).on('click', '#dashboard_image_list li', function() {
	if (!$('#dashboard_image_list').hasClass('all_selected')) {
		$('#dashboard_image_list li').removeClass('selected');
		$(this).addClass('selected');
	}
});

var $uploadBtn;
var uploader;
function initDashboardPhotoUploader() {
	$uploadBtn = $('#bootstrapped-fine-uploader');
	$saveBtn = $('#save_image_settings');
	uploader = new qq.FineUploaderBasic({
		button: $uploadBtn[0],
		debug: false,
		multiple: false,
		request: {
			endpoint: '/user/dashboard/uploadPhoto'
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
		},
		callbacks: {
			onError: function(id, name, reason, xhr) {
				showDashboardPhotoUploadError(reason);
				$uploadBtn.show();
			},
			onSubmit: function(id, fileName) {
				$saveBtn.prop('disabled', true);
				$uploadBtn.hide();
			},
			onUpload: function(id, fileName) {
				showDashboardPhotoUploadLoadingMessage('Uploading ' + '“' + fileName + '” ');
			},
			onProgress: function(id, fileName, loaded, total) {
				if (loaded < total) {
					var progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
					showDashboardPhotoUploadLoadingMessage('Uploading ' + '“' + fileName + '” ' + progress);
				}
			},
			onComplete: function(id, fileName, responseJSON) {
				$uploadBtn.show();
				$saveBtn.prop('disabled', false);
				if (responseJSON.success) {
					showImageCropDialog(id, responseJSON);
				} else {
					if (responseJSON.error) {
						showDashboardPhotoUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
					}
					else {
						showDashboardPhotoUploadError('Failed to upload photo.');
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
						hideDashboardPhotoUploadMessage();
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
		var aspectRatio = '58:32';
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
			url: '/user/dashboard/cropPhoto',
			data: {
				'x1': scaledSelection.x1,
				'y1': scaledSelection.y1,
				'w': scaledSelection.width,
				'h': scaledSelection.height,
				'fileName': response.fileName
			},
			success: function(responseJSON) {
				hideDashboardPhotoUploadMessage();

				// avoid cached image
				var timestamp = new Date().getTime();
				var imageSrc = responseJSON.fileUrl + '?' + timestamp;

				$('#hidden_images_container').append('<input type="hidden" id="hidden_img_' + id + '" name="data[User][images][]" value="' + responseJSON.fileName + '" />');
				$('#dashboard_image_list').append('<li class="tmp" data-id="' + id + '"><img src="' + imageSrc + '" />' + removeImgIcon + '</li>');
				$('#dashboard_image_list').animate({
					scrollTop: $('#dashboard_image_list li:last').offset().top
				}, 100);
				handleSlideShowEnabledStatusChange();
			}
		});
	}

}
$messages = $('#uploadmessages');
$loadingImg = '<img src="/img/loading.gif" /> ';
function showDashboardPhotoUploadError(message) {
	$messages.html(message);
	$messages.removeClass('success').addClass('error').show();
}
function showDashboardPhotoUploadLoadingMessage(message) {
	$messages.html($loadingImg + message);
	$messages.removeClass('error').addClass('success').show();
}
function hideDashboardPhotoUploadMessage() {
	$messages.html('').hide();
}

var dashboardSlider = null;
var slideShowOptions = {
	auto: false,
	minSlides: 1,
	maxSlides: 1,
	pager: false,
	speed: 500,
	pause: 3000,
	autoHover: true,
	mode: 'fade',
	controls: false
};

function showSlideShow() {
	$('#dashboard_image_container').addClass('hide');
	$('#dashboard_slideshow_container').removeClass('hide');
        var numberOfImages = $('.bxslider img').length;
        if( numberOfImages > 1) {
            slideShowOptions['auto'] = true;
        } else {
            slideShowOptions['auto'] = false;
        }
        dashboardSlider = $('.bxslider').bxSlider(slideShowOptions);

}

$(document).on('click', '.my_photos .setting', function() {
	$('#dashboard_image_container').removeClass('hide');
	$('#dashboard_slideshow_container').addClass('hide');
	$('#settings_container').removeClass('hide');
	handleSlideShowEnabledStatusChange(isSlideShowEnabled);
	styleImageListScrollBar();
});

function styleImageListScrollBar() {
	$('.slim-scroll').slimScroll({
		color: '#838886',
		railColor: '#646a68',
		size: '8px',
		height: '130px',
		railVisible: true,
		disableFadeOut: true
	});
}

function resetDashboardImageSettingsForm() {
	$('#hidden_images_container').html('');
	$('#deleted_photos_container').html('');
	if (isSlideShowEnabled === true) {
		$('#UserIsDashboardSlideshowEnabled').prop('checked', true);
	}
	else {
		$('#UserIsDashboardSlideshowEnabled').prop('checked', false);
	}
	handleSlideShowEnabledStatusChange(isSlideShowEnabled);

	$('#dashboard_image_list li').removeClass('selected').removeClass('hidden');
	if ($('#UserDefaultPhotoId').val() > 0) {
		var selectedPhotoId = $('#UserDefaultPhotoId').val();
		$('#dashboard_image_list li').each(function() {
			if ($(this).find('img').attr('data-photo_id') === selectedPhotoId) {
				$(this).addClass('selected');
			}
		});
	}
	hideDashboardPhotoUploadMessage();
}

function handleSlideShowEnabledStatusChange(isEnabled) {
	if (isEnabled === undefined) {
		var isEnabled = $('#UserIsDashboardSlideshowEnabled').is(':checked');
	}

	if (isEnabled === true) {
		$('#select_default_img_msg').addClass('hide');
		$('#dashboard_image_list').addClass('all_selected');
	}
	else {
		if ($('#dashboard_image_list li').not('.hidden').length > 0) {
			$('#select_default_img_msg').removeClass('hide');
		}
		else {
			$('#select_default_img_msg').addClass('hide');
		}
		$('#dashboard_image_list').removeClass('all_selected');
	}
}

$(document).on('reset', '#user_dashboard_images_form', function() {
	$('#dashboard_image_list li.tmp').remove();
	resetDashboardImageSettingsForm();
	reloadMyPhotos();
	uploader.cancelAll();
	hideDashboardPhotoUploadMessage();
	$('#save_image_settings').prop('disabled', false);
	$uploadBtn.show();
});

$(document).on('click', '#save_image_settings', function() {
	var saveBtn = $(this);
	var loading = Ladda.create(this);
	var selectedPhoto = $('#dashboard_image_list li.selected img');
	var selectedPhotoId = selectedPhoto.attr('data-photo_id');
	if (selectedPhotoId > 0) {
		$('#UserDefaultPhotoId').val(selectedPhotoId);
		$('#UserDefaultPhoto').val('');
	}
	else {
		$('#UserDefaultPhoto').val(selectedPhoto.attr('src'));
		$('#UserDefaultPhotoId').val(0);
	}
	$.ajax({
		method: 'POST',
		url: '/user/dashboard/saveImageSettings',
		data: $('#user_dashboard_images_form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			saveBtn.prop('disabled', true);
			loading.start();
		},
		success: function(result) {
			saveBtn.prop('disabled', false);
			loading.stop();
			if (result.success === true) {
				isSlideShowEnabled = $('#UserIsDashboardSlideshowEnabled').is(':checked');

				if (result.deletedPhotos) {
					$('#dashboard_image_list li.hidden').remove();
				}

				resetDashboardImageSettingsForm();

				if (result.photos) {
					$('#dashboard_image_list li.tmp').remove();
					$(result.photos).each(function(index, photo) {
						var liClass = (result.defaultPhotoId > 0 && (result.defaultPhotoId === photo.id)) ? 'selected' : '';
						$('#dashboard_image_list').append('<li class="' + liClass + '"><img class="photo" data-photo_id="' + photo.id + '" src="' + photo.src + '" />' + removeImgIcon + '</li>');
					});
				}

				if (result.photos || result.deletedPhotos) {
					var imageList = $('#dashboard_image_list').clone();
					if (imageList.find('li').length > 0) {
						imageList.find('li').removeClass('selected');
						imageList.find('img.remove_img').remove();
						$('.bxslider').html(imageList.html());
					}
					else {
						var tmpPhoto2 = '<li><img src="/theme/App/img/dashboard_default2.jpg" /></li>';
						$('.bxslider').html('<li><img src="' + defaultPhotoSrc + '" /></li>' + tmpPhoto2);
					}
				}

				var defaultPhoto;
				if ($('#dashboard_image_list li').length > 0) {
					if ($('#dashboard_image_list li.selected').length > 0) {
						defaultPhoto = $('#dashboard_image_list li.selected img').attr('src');
					}
					else {
						defaultPhoto = $('#dashboard_image_list li:first img').attr('src');
					}
				}
				else {
					defaultPhoto = defaultPhotoSrc;
				}
				$('#dashboard_image_container img').attr('src', defaultPhoto);
				
				// if chat notification is displayed, display blurred images
				if ($('#chat_notification_container').is(':visible')) {
					showBlurDashboardImages();
				}
				
				reloadMyPhotos();
			}
		}
	});
});

function reloadMyPhotos() {
	if (isSlideShowEnabled === true) {
		$('#dashboard_image_container').addClass('hide');
		$('#dashboard_slideshow_container').removeClass('hide');
                var numberOfImages = $('.bxslider img').length;
                if( numberOfImages > 1) {
                    slideShowOptions['auto'] = true;
                } else {
                    slideShowOptions['auto'] = false;
                }
                
		if (dashboardSlider === null) {
			dashboardSlider = $('.bxslider').bxSlider(slideShowOptions);
		}
		else {
			dashboardSlider.reloadSlider();
		}
	}
	else {
		$('#dashboard_slideshow_container').addClass('hide');
		$('#dashboard_image_container').removeClass('hide');
	}

	$('#settings_container').addClass('hide');
}

$(document).on('mouseover', '#dashboard_image_list li', function() {
	$(this).find('img.remove_img').removeClass('hide');
});

$(document).on('mouseout', '#dashboard_image_list li', function() {
	$(this).find('img.remove_img').addClass('hide');
});

$(document).on('click', '#dashboard_image_list li img.remove_img', function() {
	var imgBox = $(this).parent('li');
	if (imgBox.hasClass('tmp')) {
		var imgBoxId = imgBox.attr('data-id');
		$('#hidden_img_' + imgBoxId).remove();
		imgBox.remove();
	}
	else {
		imgBox.addClass('hidden');
		var photoId = imgBox.find('img.photo').attr('data-photo_id');
		$('#deleted_photos_container').append('<input type="hidden" name="data[User][deleted_photos][]" value="' + photoId + '" />');
	}
	handleSlideShowEnabledStatusChange();
});

$(document).on('click', '.my_photos #chat_notification_container', function() {
	var userId = $(this).find('.name').attr('data-id');
	jqac.arrowchat.chatWith(userId);
});