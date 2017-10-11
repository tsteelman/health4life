<div class="profile_cover_setting hide" id="profile_cover_settings"  >
    <?php
	echo $this->Form->create($coverModel, array(
		'id' => 'cover_images_form',
		'enctype' => 'multipart/form-data',
	));
        echo $this->Form->hidden('id', array('id' => 'Id', 'value' => $roomId));
	echo $this->Form->hidden('default_photo_id', array('id' => 'DefaultPhotoId'));
	echo $this->Form->hidden('default_photo', array('id' => 'DefaultPhoto'));
	?>
    <div id="hidden_images_container" data-model="<?php echo $coverModel; ?>"></div>
	<div id="deleted_photos_container"></div>

	<div class="row slideshow_enable"><!-- col-lg-12  -->
		<div class="col-lg-7 col-sm-6 col-md-6">
			<?php
			echo $this->Form->input('is_cover_slideshow_enabled', array(
                            'id' => 'IsCoverSlideshowEnabled',
                            'type' => 'checkbox', 
                            'label' => __('Enable slideshow'), 
                            'div' => false,
                            'checked' => $isSlideShowEnabled
                            ));
			?>
		</div>
		<div class ="col-lg-4 col-sm-4 col-md-4 pull-right">
                        <div id="fine-coverUploader-cover">
				<div class="qq-upload-button-selector qq-upload-button btn btn-success  pull-right" style="width: 100px;">
					<div><?php echo __('Upload'); ?></div>
				</div>
			</div>
		</div>
		<div class="col-lg-8" id="select_default_img_msg">Select an image to make it your default image</div>
	</div>
	
	<ul id="cover_image_list" class="col-lg-12 slim-scroll <?php echo $coverModel;?>">
		<?php if (!empty($photos)) : ?>
			<?php foreach ($photos as $photo): ?>
				<li class="<?php echo ($defaultPhotoId === $photo['id']) ? 'selected' : ''; ?>">
					<img src="<?php echo $photo['src']; ?>" class="photo" data-photo_id="<?php echo $photo['id']; ?>" />
					<img src="/theme/App/img/close.gif" alt="X" class="remove_img hide" />
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

	<div class="col-lg-12" id="upload_btn_container">		
		<div id="uploadmessages" style="display: none;"></div>
	</div>

	<div class="col-lg-12">
		<button  type="button" class="btn btn-primary" id="save_image_settings">Save</button>
		<button type="reset" class="btn btn-default" id="cancel_image_settings">Cancel</button>
	</div>

	<?php
	echo $this->Form->end();
	?>
</div>

<script>    
    var isSlideShowEnabled = false;
    var removeImgIcon = '<img src="/theme/App/img/close.gif" alt="X" class="remove_img hide" />';
    var model = $('#hidden_images_container').data('model').trim();
    var slim_scroll_height = (model === 'User') ? '95px' : '240px' ;
    var defaultPhotoSrc = (model === 'User') ?  '/theme/App/img/cover_bg.png' : '/theme/App/img/event_cover_bg.png';
    var aspcetRatio = (model === 'User') ? '111:52' : '28:13';
    var coverUploader;
    var $coverUploadBtn;
    
    $(document).ready(function() {
        
	if ($('#IsCoverSlideshowEnabled').prop('checked')) {
		isSlideShowEnabled = true;
	}
	initCoverPhotoUploader();
    });
    
    $(document).on('change', '#IsCoverSlideshowEnabled', function() {
	handleSlideShowEnabledStatusChange($(this).is(':checked'));
    });

    $(document).on('click', '#cover_image_list li', function() {
            if (!$('#cover_image_list').hasClass('all_selected')) {
                    $('#cover_image_list li').removeClass('selected');
                    $(this).addClass('selected');
            }
    });

   
    function initCoverPhotoUploader() {
            $coverUploadBtn = $('#fine-coverUploader-cover');
            $coverSaveBtn = $('#save_image_settings');
            coverUploader = new qq.FineUploaderBasic({
                    button: $coverUploadBtn[0],
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
                                    showCoverPhotoUploadError(reason);
                                    $coverUploadBtn.show();
                                    if ( typeof  $profileBgUploadBtn !== 'undefined') {
                                        $profileBgUploadBtn.show();
                                    }                                    
                            },
                            onSubmit: function(id, fileName) {
                                    $coverSaveBtn.prop('disabled', true);
                                    $coverUploadBtn.hide();
                                    if ( typeof  $profileBgUploadBtn !== 'undefined') {
                                        $profileBgUploadBtn.show();
                                    }
                            },
                            onUpload: function(id, fileName) {
                                    if ( fileName.length > 36) {
                                        fileName = fileName.substring(0, 40) + '...';
                                    }
                                    showCoverPhotoUploadLoadingMessage('Uploading ' + '“' + fileName + '” ');
                                    
                            },
                            onProgress: function(id, fileName, loaded, total) {
                                    if (loaded < total) {
                                            if ( fileName.length > 36) {
                                                fileName = fileName.substring(0, 40) + '...';
                                            }
                                            var progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                                            showCoverPhotoUploadLoadingMessage('Uploading ' + '“' + fileName + '” ' + progress);
                                    }
                            },
                            onComplete: function(id, fileName, responseJSON) {
                                
                                    $coverSaveBtn.prop('disabled', false);
                                    $coverUploadBtn.show();
                                    if ( typeof  $profileBgUploadBtn !== 'undefined') {
                                        $profileBgUploadBtn.show();
                                    }
                                    if (responseJSON.success) {
                                            showImageCropDialog(id, responseJSON);
                                    } else {
                                            if (responseJSON.error) {
                                                    showCoverPhotoUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
                                            }else if (responseJSON.image_type_error) {                                                
                                                    showCoverPhotoUploadError( fileName + ' has an invalid image content.');
                                            } else {
                                                    showCoverPhotoUploadError('Failed to upload photo.');
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
                                                    coverUploader.cancelAll();
                                                    hideCoverPhotoUploadMessage();
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
                    var aspectRatio = aspcetRatio;
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
                                    hideCoverPhotoUploadMessage();

                                    // avoid cached image
                                    var timestamp = new Date().getTime();
                                    var imageSrc = responseJSON.fileUrl + '?' + timestamp;
                                    
                                    $('#hidden_images_container').append('<input type="hidden" id="hidden_img_' + id + '" name="data['+ model + '][images][]" value="' + responseJSON.fileName + '" />');
                                    $('#cover_image_list').append('<li class="tmp" data-id="' + id + '"><img src="' + imageSrc + '" />' + removeImgIcon + '</li>');
                                    $('#cover_image_list').animate({
                                            scrollTop: $('#cover_image_list li:last').offset().top
                                    }, 100);
                                    handleSlideShowEnabledStatusChange();
                            }
                    });
            }

    }
    $coverMessages = $('#uploadmessages');
    $loadingImg = '<img src="/img/loading.gif" /> ';
    
    function showCoverPhotoUploadError(message) {
            $coverMessages.html(message);
            $coverMessages.removeClass('success').addClass('error').show();
    }
    function showCoverPhotoUploadLoadingMessage(message) {
            $coverMessages.html($loadingImg + message);
            $coverMessages.removeClass('error').addClass('success').show();
    }
    function hideCoverPhotoUploadMessage() {
            $coverMessages.html('').hide();
    }
    
    
    /*
     * On click change cover button
     * 
     * @param {boolean} isEnabled
     */
    $(document).on('click', '#btn_changeCover', function() {
            $('#cover_image_container').removeClass('hide');
            $('#cover_slideshow_container').addClass('hide');
            $('#profile_cover_settings').removeClass('hide');
            $('.cover_message_container').addClass('hide');
            $('.profile_video').addClass('hide');
            hideCoverVideo();
            if ( $('.profile_video').length ) {
                $('.profile_video').addClass('hide');
            }
            handleSlideShowEnabledStatusChange(isSlideShowEnabled);
            styleImageListScrollBar();
    });

    function styleImageListScrollBar() {
            $('.slim-scroll').slimScroll({
                    color: '#838886',
                    railColor: '#646a68',
                    size: '8px',
                    height: slim_scroll_height, 
                    railVisible: true,
                    disableFadeOut: true
            });
    }

    function resetCoverImageSettingsForm() {
            $('#hidden_images_container').html('');
            $('#deleted_photos_container').html('');
            if (isSlideShowEnabled === true) {
                    $('#IsCoverSlideshowEnabled').prop('checked', true);
            }
            else {
                    $('#IsCoverSlideshowEnabled').prop('checked', false);
            }
            handleSlideShowEnabledStatusChange(isSlideShowEnabled);

            $('#cover_image_list li').removeClass('selected').removeClass('hidden');
            if ($('#DefaultPhotoId').val() > 0) {
                    var selectedPhotoId = $('#DefaultPhotoId').val();
                    $('#cover_image_list li').each(function() {
                            if ($(this).find('img').attr('data-photo_id') === selectedPhotoId) {
                                    $(this).addClass('selected');
                            }
                    });
            }
            hideCoverPhotoUploadMessage();
    }

    function handleSlideShowEnabledStatusChange(isEnabled) {
            if (isEnabled === undefined) {
                    var isEnabled = $('#IsCoverSlideshowEnabled').is(':checked');
            }

            if (isEnabled === true) {
                    $('#select_default_img_msg').addClass('hide');
                    $('#cover_image_list').addClass('all_selected');
            }
            else {
                    if ($('#cover_image_list li').not('.hidden').length > 0) {
                            $('#select_default_img_msg').removeClass('hide');
                    }
                    else {
                            $('#select_default_img_msg').addClass('hide');
                    }
                    $('#cover_image_list').removeClass('all_selected');
            }
    }

    $(document).on('reset', '#cover_images_form', function() {
            $('#cover_image_list li.tmp').remove();
            resetCoverImageSettingsForm();
            reloadMyPhotos();
            coverUploader.cancelAll();
            $coverUploadBtn.show();
            if ( typeof  $profileBgUploadBtn !== 'undefined') {
                $profileBgUploadBtn.show();
            }
            hideCoverPhotoUploadMessage();
            $('#save_image_settings').prop('disabled', false);
            hideCoverVideo();
    });

    /*
     * Save new image settings 
     */
    $(document).on('click', '#save_image_settings', function() {
            var saveBtn = $(this);
            var selectedPhoto = $('#cover_image_list li.selected img');
            var lastSelectedPhoto =  $('#cover_image_container img');
            var coverImageContainer =  $('#cover_image_container');
            var selectedPhotoId = selectedPhoto.attr('data-photo_id');
            if (selectedPhotoId > 0) {
                    $('#DefaultPhotoId').val(selectedPhotoId);
                    $('#DefaultPhoto').val('');
            }
            else {
                    $('#DefaultPhoto').val(selectedPhoto.attr('src'));
                    $('#DefaultPhotoId').val(0);
            }
            $.ajax({
                    method: 'POST',
                    url: '/api/saveImageSettings',
                    data: $('#cover_images_form').serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                            saveBtn.prop('disabled', true);
                            showCoverPhotoUploadLoadingMessage('Saving settings');
                            if ( selectedPhoto.length && (selectedPhoto.html() !== lastSelectedPhoto.html())) {
                                    coverImageContainer.html(selectedPhoto);
                            }
                    },
                    success: function(result) {
                            
                            if (result.success === true) {
                                    isSlideShowEnabled = $('#IsCoverSlideshowEnabled').is(':checked');

                                    if (result.deletedPhotos) {
                                            $('#cover_image_list li.hidden').remove();
                                    }

                                    if (result.photos) {
                                            $('#cover_image_list li.tmp').remove();
                                            $(result.photos).each(function(index, photo) {
                                                    var liClass = (result.defaultPhotoId > 0 && (result.defaultPhotoId === photo.id)) ? 'selected' : '';
                                                    $('#cover_image_list').append('<li class="' + liClass + '"><img class="photo" data-photo_id="' + photo.id + '" src="' + photo.src + '" />' + removeImgIcon + '</li>');
                                            });
                                    }

                                    if (result.photos || result.deletedPhotos) {
                                            var imageList = $('#cover_image_list').clone();
                                            if (imageList.find('li').length > 0) {
                                                    imageList.find('li').removeClass('selected');
                                                    imageList.find('img.remove_img').remove();
                                                    $('.bxslider').html(imageList.html());
                                            }
                                            else {
                                                    var tmpPhoto2 = '<li><img src="'+$('#cover_slide_2').val()+'" /></li>';
                                                    $('.bxslider').html('<li><img src="' + defaultPhotoSrc + '" /></li>' + tmpPhoto2);
                                            }
                                    }

                                    var defaultPhoto;
                                    if ($('#cover_image_list li').length > 0) {
                                            if ($('#cover_image_list li.selected').length > 0) {
                                                    defaultPhoto = $('#cover_image_list li.selected img').attr('src');
                                            }
                                            else {
                                                    defaultPhoto = $('#cover_image_list li:first img').attr('src');
                                            }
                                    }
                                    else {
                                            defaultPhoto = defaultPhotoSrc;
                                    }
                                    
                                    $('#cover_image_container img').attr('src', defaultPhoto);
                                   
                                    reloadMyPhotos();
                                    saveBtn.prop('disabled', false);
                                    resetCoverImageSettingsForm();

                                  
                                   
                            }
                    },
                    error: function() {
                        coverImageContainer.html(lastSelectedPhoto);
                    }
            });
    });

    function reloadMyPhotos() {
            if (isSlideShowEnabled === true) {
                    $('#cover_image_container').addClass('hide');
                    $('#cover_slideshow_container').removeClass('hide');
                    var numberOfImages = $('.bxslider img').length;
                    if (coverSlider === null) {
                            if( numberOfImages > 1) {
                                    coverSlider = $('.bxslider').bxSlider(slideShowOptions);
                            }
                    }
                    else {
                            if( numberOfImages > 1) {
                                coverSlider.reloadSlider();
                            } else {
                                coverSlider.destroySlider();
                            }
                    }
            }
            else {
                    $('#cover_slideshow_container').addClass('hide');
                    $('#cover_image_container').removeClass('hide');
            }
            $('.cover_message_container').removeClass('hide');
            $('.profile_video').removeClass('hide');
            $('#profile_cover_settings').addClass('hide');
    }

    $(document).on('mouseover', '#cover_image_list li', function() {
            $(this).find('img.remove_img').removeClass('hide');
    });

    $(document).on('mouseout', '#cover_image_list li', function() {
            $(this).find('img.remove_img').addClass('hide');
    });

    $(document).on('click', '#cover_image_list li img.remove_img', function() {
            var imgBox = $(this).parent('li');
            if (imgBox.hasClass('tmp')) {
                    var imgBoxId = imgBox.attr('data-id');
                    $('#hidden_img_' + imgBoxId).remove();
                    imgBox.remove();
            }
            else {
                    imgBox.addClass('hidden');
                    var photoId = imgBox.find('img.photo').attr('data-photo_id');
                    var model = $('#hidden_images_container').data('model').trim();
                    $('#deleted_photos_container').append('<input type="hidden" name="data['+ model + '][deleted_photos][]" value="' + photoId + '" />');
            }
            handleSlideShowEnabledStatusChange();
    });

    
        
        
</script>