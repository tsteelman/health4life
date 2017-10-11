<form id="UserAddForm" enctype="multipart/form-data"
      method="post" action="/user/edit/changeProfilePic">
    <div class="form-group">
        <div id="uploadPreview" class="col-lg-12">
            <?php
            echo $this->Html->image($userImage, array('alt' => 'User Thumbnail',
                'style' => 'width: 140px; height: 140px;', 'class' => "img-responsive profile_brdr_5
                     img-thumbnail {$profilePhotoClass}", 'fullBase' => true));
            ?>
        </div>
           <p> <?php echo __('Change Profile Picture'); ?></p>
       	
        <div class="row">
            <div class="col-lg-12">
                <div id="uploadmessages"></div>
            </div>	
        </div>
        <input type="hidden" value="cropfileName" name="cropfileName" id="cropfileName" />
        <input type="hidden" value="0" name="x1" id="x1" />
        <input type="hidden" value="0" name="y1" id="y1" />
        <input type="hidden" value="150" name="w" id="w" />
        <input type="hidden" value="150" name="h" id="h" />
    </div>
    <div class="upload_button">
    <div class="clearfix form-group">
        <div>
            <div id="bootstrapped-fine-uploader" class="width_auto">
                <div class="qq-upload-button-selector qq-upload-button btn" style="width: auto;">
                    <div>Upload</div>
                </div>
            </div>
            <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $userId)); ?>
            <button id="save_profile_picture" type="submit" class="btn btn_green pull-left hidden"><?php echo __('Save'); ?></button>
            <button id="cancel_profile_picture" type="reset" class="btn btn-prev hidden  pull-right col-lg-offset-1"><?php echo __('Cancel'); ?></button>
        </div>
        </div>
    </div>
</form>
<?php echo $this->AssetCompress->script('register', array('block' => 'scriptBottom')); ?>
<script>
    $(document).ready(function() {
        createUploader();
    });

    $(document).on('click', '.accept', function() {
        $('#bootstrapped-fine-uploader').addClass('hidden');
        $('#save_profile_picture').removeClass('hidden');
        $('#cancel_profile_picture').removeClass('hidden');
    });

    $(document).on('click', '.btn-prev', function() {
        $('#uploadPreview img').attr('src', '<?php echo $userImage; ?>');
        $('#bootstrapped-fine-uploader').removeClass('hidden');
        $('#save_profile_picture').addClass('hidden');
        $('#cancel_profile_picture').addClass('hidden');
    });

</script>
<?php echo $this->Js->writeBuffer(); ?>