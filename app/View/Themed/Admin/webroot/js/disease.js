$(document).ready(function() {
    createUploader();

    if ($('#disease_logos div').hasClass('active')) {
        $("#save_logo").attr('disabled', false);
    } else {
        $("#save_logo").attr('disabled', 'disabled');
    }
});


/**
 * Cancel edit profile and redirect to profile page
 */
$(document).on('click', '#cancel_btn', function() {
    window.location.href = '/admin/diseases';
});

/**
 * Add button to add more videos in disease mylibrary.
 */
$(document).on('click', '#add_more_videos_btn', function() {
    var count = parseInt($('.library_videos_input').length) + 1;
    var element = "<div class='control-group library_videos_input'>" +
            "<label class='control-label'>Video " + count + "</label>" +
            "<div class='controls span6'>" +
            "<div id='library_video_url_form_" + count + "' class='add_video_url' style='display:block;'>" +
            "<div class='form-group' id='link_input_section'>" +
            "<input name='data[Disease][url][" + count + "][src] type='url' class='form-control' value='' placeholder='Enter a video url' data-videourl='true' id='DiseaseLibraryVideo" + count + "'>" +
            "<input name='data[Disease][url][" + count + "][image]' type='url' class='hidden' id='DiseaseUrl1Image' style='display:none;'>" +
            "<button type='button' id='disease_grab_video_url_btn' class='add_video btn btn_add pull-right event_video_add_btn' disabled='disabled' value='Add' data-videoUrl='true'>Add</button>" +
            "</div>" +
            "<div id='preview_library_" + count + "' style='display: none;'>" +
            "<div id='previewImages' class='pull-left'>" +
            "<div id='previewImage'></div>" +
            "<div id='previewImagesNav' style='display: none;'>" +
            "</div>" +
            "</div>" +
            "<div id='closeLibraryVideoPreview" + count + "' title='Remove' class='close_video_preview pull-right hidden' data-close='true'>" +
            "<button class='close' type='button'>×</button>" +
            "</div>" +
            "<div id='previewContent' class='pull-left' >" +
            "<div id='previewTitle' class='owner'></div> " +
            "<div id='previewUrl'></div>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";

    if (count >= 10) {
        $("#add_more_videos_btn").remove();
    }
    var last_element = $('.library_videos_input').last();
    last_element.after(element);
});
/**
 * Profile photo uploader
 */
function createUploader() {
    $upload_btn = $('#upload_avatar');
    $messages = $('#uploadmessages');

    $preview_div = $("#uploadPreview");
    $preview_img = $("#uploadPreview img");


    function previewPhoto(response) {
		var scaledSelection = ias.getSelection(false);
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '/admin/diseases/photo',
            data: {'crop_image': true,
				'x1': scaledSelection.x1,
				'y1': scaledSelection.y1,
				'w': scaledSelection.width,
				'h': scaledSelection.height,
                'cropfileName': response.fileName
            },
            beforeSend: function() {
                $preview_img.attr("src", app.site_url + 'img/loading.gif');
                $preview_img.removeClass('hidden');
            },
            success: function(data) {
                $('#cropfileName').val(data.fileName);
                $preview_img.attr("src", data.fileUrl);
            }
        });
    }

	var ias;
	function createCropper(cropBox, responseJSON) {
		var uploadedImg = cropBox.find(".bootbox-body img");
		srcWidth = responseJSON.imageWidth;
		srcHeight = responseJSON.imageHeight;
		var aspectRatio = '16 : 7';
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

    var uploader = new qq.FineUploaderBasic({
        button: $upload_btn[0],
        debug: false,
        multiple: false,
        request: {
            endpoint: '/admin/diseases/photo'
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
                        title: "Disease Image",
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

/*URL Grabbing*/
var videoLink = [];
var videoUrlRegex = /(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i;
var isCrawlingEventUrl = false;
var isVideoLinkDataPresent = false;
var preview_div_id;
var preview_image;
var preview_title;
var preview_url;
var preview_description;
var close_button;

$(document).ready(function() {
    var array = [];
    $('input').each(function() {
        if ($(this).attr('data-videoUrl')) {
            array.push(this);
        }
    });
    grabVideoUrl(array);
});

$(document).on('keydown', 'input', function(e) {
    var input = this;
    if ($(this).val() == '') {
        $("#" + $(this).parent().parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").remove();
        $("#save_button").removeAttr('disabled');
    }
    var video_input = $(this).attr('data-videoUrl');
    if (video_input) {
        setPreviewDetails(input);
        checkEventLink();
    }
});

$(document).on('paste', 'input', function(e) {
    var input = this;
    var video_input = $(this).attr('data-videoUrl');
    if (video_input) {
        // Short pause to wait for paste to complete
        setTimeout(function() {
            setPreviewDetails(input);
            grabVideoUrl(videoLink);
        }, 100);
    }
});

$(document).on('click', '*[data-close="true"]', function() {
    var div = this;
    var preview_div = $(div).parent();
    var link_div = $(div).parent().prev();
    var parent_div = $(preview_div).parent();
    setPreviewDetails($("#" + $(parent_div).attr('id') + " #" + $(link_div).attr('id') + " input"));
    $(preview_div).fadeOut("fast", function() {
        clearVideoPreview();
        $(link_div).removeClass('hide');
        $("#" + $(parent_div).attr('id') + " #" + $(link_div).attr('id') + " input").val('').removeClass('textbox_loader').removeAttr('disabled');
        $(parent_div).removeClass('event_video_url_review');
        $(this).addClass('hidden');
    });
});

$(document).on('click', 'button', function() {
    var button_status = $(this).attr('data-videoUrl');
    var input = $(this).prev();
    if (button_status) {
        setPreviewDetails(input);
        grabVideoUrl(videoLink);
    }
});
function setPreviewDetails(element) {
    videoLink = [];
    preview_div_id = $(element).parent().next().attr('id');
    preview_image = $('#' + preview_div_id + ' #previewImages');
    preview_title = $('#' + preview_div_id + ' #previewTitle');
    preview_url = $('#' + preview_div_id + ' #previewUrl');
    preview_description = $('#' + preview_div_id + ' #previewDescription');
    close_button = $("#" + preview_div_id + " .close_video_preview");
    videoLink.push(element);
}

function clearVideoPreview() {
    $('#' + preview_div_id).hide();
    $(preview_image).html("");
    $(preview_title).html("");
    $(preview_url).html("");
    $(preview_description).html("");
    $(videoLink).next().attr('disabled', 'disabled');
}

function grabVideoUrl(array) {
    $.each(array, function(index, value) {
        var link = $(value).val();

        /*Condition to check if the link is of youtube for ad videos*/
        if (preview_div_id == 'preview_advertisement') {
            var matches = link.match(/watch\?v=([a-zA-Z0-9\-_]+)/);
            if (!matches)
            {
                $(value).val('');
                showYoutubeError();
                return;
            }
        }
        /****************************************************************/

        if (link.length > 0) {
            $.ajax({
                type: 'POST',
                url: '/admin/diseases/textCrawler',
                data: {
                    'link': link
                },
                dataType: 'json',
                beforeSend: function() {
                    $(value).next().attr('disabled', 'disabled');
                    $(value).addClass("textbox_loader").attr('disabled', 'disabled');
                    $("#" + preview_div_id).hide();
                    isCrawlingEventUrl = true;
                },
                success: function(response) {
                    setPreviewDetails(value);
                    $(value).next().removeAttr('disabled');
                    if (response.url && (response.video !== 'no')) {
                        $("#" + $(value).parent().parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").remove();
                        $("#save_button").removeAttr('disabled');
                        $(value).removeClass('textbox_loader').removeAttr('disabled');
                        $(value).parent().parent().addClass('event_video_url_review');
                        $(value).parent().addClass('hide');
                        $("#" + preview_div_id).show();
                        $("#" + preview_div_id).removeClass('hidden');
                        $(preview_title).html(response.title);
                        try {
                            if (response.images !== '' && response.images !== false && response.images !== null) {
                                images = (response.images).split("|");
                                response.images = images;
                                $(preview_image).show();
                                $(close_button).removeClass('hidden');
                                images.length = parseInt(images.length);
                                previewImagesCount = images.length;
                                $(videoLink).next().val(images[0]);
                                var appendImage = "";
                                for (i = 0; i < images.length; i++) {
                                    if (i === 0)
                                        appendImage += "<img id='imagePreview" + i + "' src='" + images[i] + "' style='width: 130px; height: auto' ></img>";
                                    else
                                        appendImage += "<img id='imagePreview" + i + "' src='" + images[i] + "' style='width: 130px; height: auto; display: none' ></img>";
                                }
                                $(preview_image).html("<a href='" + response.pageUrl + "' target='_blank'>" + appendImage + "</a><div id='whiteImage' style='width: 130px; color: transparent; display:none;'>...</div>");
                            }
                            else {
                                $(preview_image).hide();
                            }
                        } catch (err) {
                            $(preview_image).hide();
                        }

                        isVideoLinkDataPresent = true;
                        isCrawlingEventUrl = false;
                    } else {
                        isCrawlingEventUrl = false;
                        showEventVodeoLinkError();
                        $(value).removeClass('textbox_loader').removeAttr('disabled');
                        $(value).val('');
                        $(value).attr('placeholder', 'Please enter a valid url');
                    }
                }
            });
        }
    });
}

function showEventVideoLinkError(message) {
    if ($("#" + $(videoLink).parent().parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").length > 0) {
        $("#" + $(videoLink).parent().parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").html(
                'Please enter a valid video URL. Supports youtube, vimeo, ustream and istream videos only.'
                ).show();
    } else {
        var PostLinkErrorHTML = '<span for="' + $(videoLink).attr('id') + '" class="help-block error">' +
                'Please enter a valid video URL. Supports youtube, vimeo, ustream and istream videos only.'
                + '</span>';
        $(videoLink).parent().append(PostLinkErrorHTML);
    }
//    $("#save_button").attr('disabled', 'disabled');
    $(videoLink).parent().addClass('error');
}

function showYoutubeError() {
//    $("#save_button").attr('disabled', 'disabled');
    if ($("#" + $(videoLink).parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").length > 0) {
        $("#" + $(videoLink).parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").html(
                'Please enter a valid video URL. Supports youtube videos only.'
                ).show();
    } else {
        var PostLinkErrorHTML = '<span for="' + $(videoLink).attr('id') + '" class="help-block error">' +
                'Please enter a valid video URL. Supports youtube videos only.'
                + '</span>';
        $(videoLink).parent().append(PostLinkErrorHTML);
    }
//    $("#save_button").attr('disabled', 'disabled');
    $(videoLink).parent().addClass('error');
}

function checkEventLink() {
    isVideoLinkDataPresent = false;
    if ($.trim($(videoLink).val()) !== "") {
        var text = $(videoLink).val();
        // add http if not present
        if (text.substr(0, 7) !== 'http://') {
            if (text.substr(0, 8) != 'https://') {
                text = 'http://' + text;
            }
        }
        if (text.substr(text.length - 1, 1) !== '/') {
            text = text + '/';
        }

        if (videoUrlRegex.test(text)) {
            $(videoLink).next().removeAttr('disabled');
            $(videoLink).next().next().removeAttr('disabled');
        } else {
            if (preview_div_id == 'preview_advertisement') {
                showYoutubeError();
            } else {
                showEventVideoLinkError();
            }
//            $("#save_button").attr('disabled', 'disabled');
            $(videoLink).next().attr('disabled', 'disabled');
            $('#' + preview_div_id).fadeOut("fast", function() {
                clearVideoPreview();
            });
        }
    }
    else {
        $(videoLink).next().attr('disabled', 'disabled');
        $("#" + $(videoLink).parent().parent().attr('id') + " span[for='" + $(videoLink).attr('id') + "']").remove();
    }
}

$(document).on('click', '.padding_block', function() {
    $('.image_div').removeClass('active');
    $(this).children().addClass('active');
    $("#save_logo").attr('disabled', false);
});

$("#save_logo").click(function() {
    var value = $('.padding_block .active').attr('value');
    var key = $('.padding_block .active').attr('key');
    if (typeof(value) != 'undefined') {
        $("#DiseaseProfileImage").prev().attr('src', app.site_url + '/uploads/disease_logos/' + value);
        $("#disease_logos").modal('hide');
        $("#DiseaseProfileImage").val(key);
    }
});

$("#cancel_logo").click(function() {
    $('.image_div').removeClass('active');
});




$(document).on('click', '#save_button', function(e) {
    var form = $(this).parents('form:first');
    $(form).validate();
    e.preventDefault();
    var elements = $('.form-control');
    elements.each(function(index, element) {
        var input = $(element);
        if ($(element).parent().hasClass('error')) {
            var required = $(input).attr('required');
            if (typeof required == 'undefined') {
                $(input).val('');
                $(input).parent().removeClass('error');
                $("#" + $(input).parent().attr('id') + " span").remove();
            }
        }
    });
    $(form).submit();
});