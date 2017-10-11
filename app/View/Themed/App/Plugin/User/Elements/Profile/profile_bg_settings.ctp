<div class="profile-bg-settings">
    <div id="profileBgUploadMessages" class="profile-bg-message" style="display: none;"></div>
    <div id="btn_profile_bg_list" class="hide">
            <button type="button" class="btn btn-default pull-right" id="cancel_bg_settings">Cancel</button>
            <button  type="button" class="btn btn-primary pull-right" id="save_bg_settings">Save</button>
            <input type="hidden" name="profile-cover-bg" class="profile-cover-bg">
            <input type="hidden" name="profile-cover-bg-default" class="profile-cover-bg-default" value="<?php echo $defaultProfileTileBg; ?>">
    </div>
</div>
<script>
    
    $(document).ready(function(){
        initProfileBgUploader();
    });
    
    
    var profileBgaspcetRatio = '111:52';
    var profileBgUploader;
    var $profileBgUploadBtn;
    
    function initProfileBgUploader() {
            $profileBgUploadBtn = $('#btn_profile_bg_gear');
            $coverSaveBtn = $('#save_bg_settings');
            profileBgUploader = new qq.FineUploaderBasic({
                    button: $profileBgUploadBtn[0],
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
                                    ProfileBgUploadError(reason);
                                    $coverUploadBtn.show();
                                    $profileBgUploadBtn.show();
                                    
                            },
                            onSubmit: function(id, fileName) {
                                    $coverSaveBtn.prop('disabled', true);                                    
                                    $coverUploadBtn.hide();
                                    $profileBgUploadBtn.hide();
                            },                           
                            onUpload: function(id, fileName) {
                                    ProfileBgUploadLoadingMessage('Uploading ' + '“' + fileName + '” ');
                                    
                            },
                            onProgress: function(id, fileName, loaded, total) {
                                    if (loaded < total) {
                                            var progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                                            ProfileBgUploadLoadingMessage('Uploading ' + '“' + fileName + '” ' + progress);
                                    }
                            },
                            onComplete: function(id, fileName, responseJSON) {
                                   
                                    $coverSaveBtn.prop('disabled', false);
                                    $coverUploadBtn.show();
                                    $profileBgUploadBtn.show();
                                    if (responseJSON.success) {
                                            showImageCropDialog(id, responseJSON);
                                    } else {
                                            if (responseJSON.error) {
                                                    ProfileBgUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
                                            }else if (responseJSON.image_type_error) {                                                
                                                    ProfileBgUploadError( fileName + ' has an invalid image content.');
                                            } else {
                                                    ProfileBgUploadError('Failed to upload photo.');
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
                                                    profileBgUploader.cancelAll();
                                                    hideProfileBgUploadMessage();
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
                    var aspectRatio = profileBgaspcetRatio;
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
                    var model = $('#hidden_images_container').data('model').trim();
                    $.ajax({
                            dataType: 'json',
                            type: 'POST',
                            url: '/api/cropPhoto',
                            data: {
                                    'x1': scaledSelection.x1,
                                    'y1': scaledSelection.y1,
                                    'w': scaledSelection.width,
                                    'h': scaledSelection.height,
                                    'fileName': response.fileName,
                                    'model' : model
                            },
                            success: function(responseJSON) {
                                    hideProfileBgUploadMessage();

                                    // avoid cached image
                                    var timestamp = new Date().getTime();
                                    var imageSrc = responseJSON.fileUrl + '?' + timestamp;
                                    
                                    setProfileBg(imageSrc);
                                    $('.profile-cover-bg').val(responseJSON.fileName);
                                    $('.profile_settings_gear').addClass('hide');
                                    $('#btn_profile_bg_list').removeClass('hide');
                            }
                    });
            }

    }
    
    $profileUploadMessages = $('#profileBgUploadMessages');
    $profileLoadingImg = '<img src="/img/loading.gif" /> ';
    
    function ProfileBgUploadError(message) {
            $profileUploadMessages.html(message);
            $profileUploadMessages.removeClass('success').addClass('error').show();
    }
    function ProfileBgUploadLoadingMessage(message) {
            $profileUploadMessages.html($profileLoadingImg + message);
            $profileUploadMessages.removeClass('error').addClass('success').show();
    }
    function hideProfileBgUploadMessage() {
            $profileUploadMessages.html('').hide();
    }
    
    function setProfileBg( imageSrc ){
        $('.profile_tile .profile_tile_l').css('background','url("'+ imageSrc +'") 0px 0px no-repeat');
    }
    
    $(document).on('click', '#save_bg_settings', function() {
            var saveBtn = $(this);            
            var img = $('.profile-cover-bg').val();
            $.ajax({
                    method: 'POST',
                    url: '/api/saveProfileCoverBg',
                    data: {'image' : img},
                    dataType: 'json',
                    beforeSend: function() {
                            saveBtn.prop('disabled', true);
                            ProfileBgUploadLoadingMessage('Saving settings');                            
                    },
                    success: function(result) {
                            
                            if (result.success === true) {
                                    hideProfileBgUploadMessage();
                                    $('#btn_profile_bg_list').addClass('hide');
                                    saveBtn.prop('disabled', false);
                                    $('.profile-cover-bg-default').val(result.fileUrl);                                    
                                    $('.profile_settings_gear').removeClass('hide');
                            }
                    },
                    error: function() {
                            var imageSrc = $('.profile-cover-bg-default').val();
                            setProfileBg(imageSrc);
                    }
            });
    });  
    $(document).on('click', '#cancel_bg_settings', function() {  
                hideProfileBgUploadMessage();
                $('#btn_profile_bg_list').addClass('hide');
                $('#save_bg_settings').prop('disabled', false);
                var imageSrc = $('.profile-cover-bg-default').val();
                setProfileBg(imageSrc);
                $('.profile_settings_gear').removeClass('hide');
    });  
</script>