$(document).ready(function() {
	createUploader();
	$('#UserDateOfBirth').datepicker().on('changeDate', function(ev) {
		$(this).datepicker('hide');
		$(this).valid();
	});
	$(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
});

/**
 * Display date picker on clicking the calendar symbol
 */
$(document).on('click', '.icon-calendar', function() {
	$('#UserDateOfBirth').datepicker('show');
});

/**
 * Cancel edit profile and redirect to profile page
 */
$(document).on('click', '#cancel_btn', function() {
	window.location.href = '/admin/users/profile';
});

/**
 * Hide datepicker on validation display
 */
$(document).on('click', 'button[type="submit"]', function() {
	if (!$('#AdminProfileEditForm').valid()) {
		window.setTimeout("hideDatePicker();", 1);
	}
});
function hideDatePicker() {
	$('#UserDateOfBirth').blur();
	$('#UserDateOfBirth').datepicker('hide');
}



/**
 * Profile photo uploader
 */
function createUploader() {
	$upload_btn = $('#upload_avatar');
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
				$preview_img.attr("src", app.site_url + 'img/loading.gif');
			},
			success: function(data) {
				$('#cropfileName').val(data.fileName);
				$preview_img.attr("src", data.fileUrl);
			}
		});
	}

	function createCropper(cropBox, responseJSON)
	{
		var aspectRatio = '1:1';
		var minDimension = 200;
		if (responseJSON.imageWidth < minDimension) {
			minDimension = responseJSON.imageWidth - 2;
		}
		else if (responseJSON.imageHeight < minDimension) {
			minDimension = responseJSON.imageHeight - 2;
		}

		var ias = cropBox.find(".bootbox-body img").imgAreaSelect({
			parent: '.bootbox',
			autoHide: false,
			mustMatch: true,
			handles: true,
			instance: true,
			aspectRatio: aspectRatio,
			x1: 0, y1: 0,
			x2: minDimension, y2: minDimension,
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
			endpoint: '/admin/users/photo'
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
			},
			onSubmit: function(id, fileName) {
				$upload_btn.hide();
				$messages.show();
				$messages.html('<div class="alert alert-info">onSubmit</div>');
			},
			onUpload: function(id, fileName) {
				var upload_msg = '<img src="' + app.site_url + '/img/loading.gif" alt="Initializing. Please hold."> ' +
						'Initializing ' + '"' + getTruncatedString(fileName, 38) + '"';
				$messages.html('<div class="alert alert-info">' + upload_msg + '</div>');
			},
			onProgress: function(id, fileName, loaded, total) {
				if (loaded < total) {
					progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
					$messages.html('<div class="alert alert-info"><img src="' + app.site_url + '/img/loading.gif" alt="In progress. Please hold."> ' +
							'Uploading ' +
							'"' + getTruncatedString(fileName, 60) + '" ' +
							progress + '</div>');
				} else {
					$messages.show();
				}
			},
			onComplete: function(id, fileName, responseJSON) {
                                
				$upload_btn.show();
				if (responseJSON.success) {
					$messages.hide();
					var imgHTML = '<div class="upload_area"><img src="' + responseJSON.fileurl + '"' +
							' alt="Uploaded photo" /></div>';
					imgHTML += '<div>To make adjustments, please drag around the white rectangle below.' +
							' When you are happy with the photos, click "Accept" button. Your beautiful, clean,' +
							' non-pixelated image should be at minimum 200x200 pixels.</div>';
					var cropBox = bootbox.dialog({
						closeButton: false,
						message: imgHTML,
						title: "Profile Photo",
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
									uploader.cancelAll();
								}
							}
						}
					});

					cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");

					setTimeout(function() {
						createCropper(cropBox, responseJSON);
					}, 500);


				} else {
					$messages.show();
					$('#uploadmessages .alert').removeClass('alert-info')
							.addClass('alert-error')
							.html('Error with ' +
							'"' + getTruncatedString(fileName, 38) + '": ' +
							responseJSON.error);
				}
			}
		}
	});
}

/**
 * Function to get a truncated string
 * 
 * @param {string} str
 * @param {int} limit
 * @returns {string}
 */
function getTruncatedString(str, limit) {
	if (str.length > limit) {
		var truncatedStr = str.substring(0, limit);
		truncatedStr += '...';
	}
	else {
		truncatedStr = str;
	}

	return truncatedStr;
}