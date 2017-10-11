var postPhotoUploader = null;
var totalSelectedPhotos = 0;
var totalPhotos = 0;

$(document).ready(function() {
    // post photo uploader
    initPostPhotoUploader();
});

function initPostPhotoUploader() {

    var $no_photo_div = $("#no_post_photos");
    var $uploadBtn = $('#post_photos_uploader');
    var $previewImg = $('#post_photos_preview');
    var $messages = $('#posting_errors');
    var $loadingImg = '<img src="/img/loading.gif" /> ';
    var $photot_upload_url = '/post/api/previewPhoto';
    var $media_progress_html = $("#m_u_p_t .photo_holder");
    
    postPhotoUploader = new qq.FineUploaderBasic({
        itemLimit: 5,
        button: $uploadBtn[0],
        debug: false,
        multiple: true,
        request: {
            endpoint: $photot_upload_url
        },
        validation: {
            acceptFiles: 'image/*',
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'bmp'],
            minSizeLimit: '1024',
            sizeLimit: '5242880' // 5MB
        },
        callbacks: {
            onError: function(id, name, reason, xhr) {
                showUploadError(reason);
                totalSelectedPhotos --;
            },
            onSubmit: function(id, fileName) {
                totalSelectedPhotos ++;
                
                $no_photo_div.removeClass("hide").hide();
                
                $clone_elem = $media_progress_html.clone();//.appendTo($previewImg);
                $clone_elem.attr("id", "photofile-"+id);
                
                $previewImg.append($clone_elem);    
                $previewImg.removeClass("hide").show();
                
            },
            
            onUpload: function(id, fileName) {
                $("#photofile-"+id).find(".m_u_p_bar").show();                
            },
            
            onProgress: function(id, fileName, loaded, total) {
                if (loaded < total) {
                    var progress = Math.round(loaded / total * 100) + "%";                    
                     $("#photofile-"+id).find(".m_u_p_bar").css("width", progress);
                }
                isPhotoPresent = false;
                changeShareBtnStatus();
            },
            onComplete: function(id, fileName, responseJSON) {
                cloneElemId =  "#photofile-"+id;
                if (responseJSON.success) {
                    $no_photo_div.hide();
                    $(cloneElemId).find(".m_u_p_bar").css("width", '100%');
                    hideUploadMessage();
                    $(cloneElemId).html(responseJSON.filehtml);
                    $(cloneElemId).removeClass("post_tmp_photo");
                    $previewImg.removeClass("hide");
                    
                    totalPhotos ++;
                    isPhotoPresent = true;
                    changeShareBtnStatus();
                    
                } else {
                    $(cloneElemId).remove();
                    if (responseJSON.error) {
                        showUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
                    }
                    else {
                        showUploadError('Failed to upload photo.');
                    }
                    
                    if(parseInt(totalPhotos) >= 0) {
                        isPhotoPresent = true;
                        changeShareBtnStatus();
                    }
                }
            }
        }
    });
    
    function resetPhotoCount() {
        totalPhotos = 0;
        totalSelectedPhotos = 0;
    }

   function showUploadError(message) {
       $messages.html("<span class='help-block'>"+message+"</span>");
       $messages.show();
   }
   
   function hideUploadMessage() {
       $messages.html('').hide();
   }
   
    $(document).on('click', '#posting_image_form .remove_photo', function() {
        $(this).closest( ".photo_holder" ).remove();
        totalPhotos--;
        if(parseInt(totalPhotos) <= 0) {
            resetPostPhotoForm();
            
        }
    });   
    
    $(document).on('mouseover', '#post_photos_preview .photo_holder', function() {
        if (!$(this).find('.m_u_p_bar').is(':visible')) {
            $(this).find('.remove_photo').removeClass('hide');
        }
    });    
    
    $(document).on('mouseout', '#post_photos_preview .photo_holder', function() {
       $(this).find('.remove_photo').addClass('hide');
    });    
}

/**
 * Function to reset the post video form
 */
function resetPostPhotoForm() {
    $('#post_photos_preview').addClass('hide');
    $('#post_photos_preview').html('');
    $('#posting_errors').html('');
    totalPhotos = 0;
    totalSelectedPhotos = 0;
    $("#no_post_photos").show();
    
    postPhotoUploader.cancelAll();
    
    isPhotoPresent = false;
    changeShareBtnStatus();
}   

