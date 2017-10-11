<div id="posting_video_form" class="posting_options_form"  style="display:none;">
    <div id="post_video_preview" class="post_media_preview" class="col-lg-12">
        <span class="post_video" id="no_videos">No video uploaded!</span>
        <div class="hide" id="video_preview">
            <img class="img-responsive media-object" src="/theme/App/img/video_preview.png" alt="...">
            <?php
            echo $this->Html->image('close_preview.png', array(
                'alt' => 'X',
                'id' => 'closeVideoPreview',
                'title' => 'Remove Video',
                'class' => 'hide'
            ));
            ?>
            <div class="m_u_p_bar"></div>
        </div>
		<div class="preview_video_name_container hide"><span id="preview_video_name" class="hide"></span></div>
    </div>
    <div class="row" id="video_form_bottom">
        <div class="col-lg-12">
            <div id="post_video_uploader">
                <a class="post_video_upload_btn owner" href="javascript:void(0);"><span class="owner glyphicon glyphicon-plus"></span> Add New Video</a>
            </div>
        </div>
    </div>
</div>