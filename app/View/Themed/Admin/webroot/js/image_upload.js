/* 
 * Contains function that handles image upload using fineuploader
 */

function initEventPhotoUploader() {
    $uploadBtn = $('#bootstrapped-fine-uploader');
    $messages = $('#uploadmessages');
    $previewImg = $('#uploadPreview img');

    var uploader = new qq.FineUploaderBasic({
        button: $uploadBtn[0],
        debug: false,
        multiple: false,
        request: {
            endpoint: '/admin/diseases/upload_image'
        },
        validation: {
            acceptFiles: 'image/*',
            allowedExtensions: ['jpeg', 'jpg', 'gif', 'png', 'bmp'],
            minSizeLimit: '1024',
            sizeLimit: '5242880'
        },
        callbacks: {
            onError: function(a, b, c, d) {
                $messages.html('<div class="alert alert-info" style="margin: 20px 0 0">' + c + '</div>');
                $messages.show();
            },
            onSubmit: function(id, fileName) {
                $messages.html('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
            },
            onUpload: function(id, fileName) {
            },
            onProgress: function(id, fileName, loaded, total) {
                if (loaded < total) {
                    progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                    $('#file-' + id).removeClass('alert-info')
                            .html('<img src="<?php echo Configure::read("App.SITE_URL") ?>/img/loading.gif" alt="In progress. Please hold."> ' +
                            'Uploading ' +
                            '“' + fileName + '” ' +
                            progress);
                } else {
                    $messages.show();
                }
            },
            onComplete: function(id, fileName, responseJSON) {
                if (responseJSON.success) {
                    $messages.hide();
                    $('#DiseaseDiseaseImage').val(responseJSON.fileName);
                    $previewImg.attr('src', responseJSON.fileurl);
                } else {
                    $messages.show();
                    $('#file-' + id).removeClass('alert-info')
                            .addClass('alert-error')
                            .html('<i class="icon-exclamation-sign"></i> ' +
                            'Error with ' +
                            '“' + fileName + '”: ' +
                            responseJSON.error);
                }
            }
        }
    });
}