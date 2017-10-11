$(document).ready(function() {
    // post video uploader
    initPostVideoUploader();
});

function initPostVideoUploader() {
    var $uploadBtn = $('#post_video_uploader');
    var $videoPreviewBlock = $('#post_video_preview');
	var $messages = $('#video_posting_error');
    var $videoUploadUrl = '/post/api/uploadVideos';
    var $videoPreviewElement = $videoPreviewBlock.find('#video_preview');
    var $videoProgressBar = $videoPreviewElement.find(".m_u_p_bar");
    var $noVideosBlock = $videoPreviewBlock.find('#no_videos');
    var uploader = new qq.FineUploaderBasic({
        button: $uploadBtn[0],
        debug: false,
        multiple: false,
        request: {
            endpoint: $videoUploadUrl
        },
        validation: {
            allowedExtensions: ['avi', 'mp4', '3gp', 'mpeg', 'mov', 'flv', 'wmv', 'mpg'],
            minSizeLimit: '1024',
            sizeLimit: '100000000'// 100 MB
        },
        callbacks: {
            onError: function(id, name, reason, xhr) {
                showVideoUploadError(reason);
            },
            onSubmit: function(id, fileName) {
                $noVideosBlock.hide();
				$('#video_form_bottom').addClass('hide');
				$('#post_video_preview').addClass('no_border');
                $videoPreviewElement.removeClass('hide');
            },
            onUpload: function(id, fileName) {
                $videoProgressBar.show();
            },
            onComplete: function(id, fileName, responseJSON) {
                if (responseJSON.success) {
					$('.preview_video_name_container').removeClass('hide');
					$('#preview_video_name').html(fileName).removeClass('hide');
                    isVideoPresent = true;
                    $videoProgressBar.hide();
                    $('#PostVideoFileName').val(responseJSON.fileName);
                    hideVideoUploadMessage();
                    changeShareBtnStatus();
                } else {
                    if (responseJSON.error) {
                        showVideoUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
                    }
                    else {
                        showVideoUploadError('Failed to upload video.');
                    }
                }
            }
        }
    });

    function showVideoUploadError(message) {
        $messages.html('<span class="help-block">' + message + '</span>');
        $messages.show();
    }

    function hideVideoUploadMessage() {
        $messages.html('').hide();
    }

    $(document).on('click', '#closeVideoPreview', function() {
        resetPostVideoForm();
    });
    $(document).on('mouseover', '#video_preview', function() {
        if (!$(this).find('.m_u_p_bar').is(':visible')) {
            $('#closeVideoPreview').removeClass('hide');
        }
    });
    $(document).on('mouseout', '#video_preview', function() {
        $('#closeVideoPreview').addClass('hide');
    });
}

/**
 * Function to reset the post video form
 */
function resetPostVideoForm() {
    $('#video_preview').addClass('hide');
    $('#video_preview').find('.m_u_p_bar').hide();
	$('.preview_video_name_container').addClass('hide');
	$('#preview_video_name').html('').addClass('hide');
	$('#video_form_bottom').removeClass('hide');
	$('#post_video_preview').removeClass('no_border');
    $('#no_videos').show();
    $('#post_video_uploader').show();
    $('#PostVideoFileName').val('');
    isVideoPresent = false;
    changeShareBtnStatus();
}