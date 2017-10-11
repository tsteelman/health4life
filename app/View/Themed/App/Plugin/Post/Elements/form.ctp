<?php
    $postingPlaceholder = "Start a discussion or share something ...";
?>
<?php if (isset($loggedin_userid) && ($hasPostPermission === true)): ?>
<div class="start_area">
    <div class="event_wraper">
        <div class="media">
            <a class="pull-left cursor-default" href="javascript:void(0)"> 
                    <?php
                    echo Common::getUserThumb($loggedin_userid, $loggedin_user_type, 'small', 'media-object normal_post_thumb');
                    echo Common::getAnonymousUserThumb('small', 'media-object hidden anonymous_post_thumb');
                    ?>
            </a>
            <div class="media-body">
                <div class="start_discussion">
                    <?php echo $postingPlaceholder; ?>
                </div>
                <div class="discussion_matter">
                    <div class="pull-left discussion_form">

                            <div class="posting_frm_box">

                                    <?php
                                    echo $this->Form->create('Post', array(
                                            'id' => $formId,
                                            'inputDefaults' => $inputDefaults,
                                            'method' => 'POST',
                                            'enctype' => 'multipart/form-data',
                                    ));
                                    echo $this->Form->hidden('posted_in_room');

                                    if (isset($newsFeedUrl)) {
                                            echo $this->Form->hidden('newsFeedUrl', array('value' => $newsFeedUrl));
                                    }

                                    echo $this->Form->hidden('posted_in');
                                    echo $this->Form->hidden('posted_in_type');
                                    echo $this->Form->hidden('post_type', array('value' => Post::POST_TYPE_TEXT));

                                    // hidden fields to store link data
                                    echo $this->Form->hidden('link_title');
                                    echo $this->Form->hidden('link_url');
                                    echo $this->Form->hidden('link_page_url');
                                    echo $this->Form->hidden('link_cannonical_url');
                                    echo $this->Form->hidden('link_description');
                                    echo $this->Form->hidden('link_image');
                                    echo $this->Form->hidden('link_video');
                                    echo $this->Form->hidden('link_video_iframe');

                                    // hidden field to store video file name
                                    echo $this->Form->hidden('video_file_name');
                                    ?>
                                    <div id="posting_text_form" class="form-group posting_options_form posting_test">
                                            <?php if ($isCommunityPost === true) : ?>
                                                    <div class="form-group">
                                                            <?php echo $this->Form->input('title', array('placeholder' => 'Topic or Title')); ?>
                                                    </div>
                                            <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                            <?php echo $this->Form->textarea('description', array('class' => 'post_no_border_bottom form-control', 'placeholder' => $placeHolderText)); ?>
                                    </div>


                                    <?php echo $this->element('Post.post_video_form'); ?>

                                    <?php echo $this->element('Post.photo_upload_form'); ?>

                                    <?php echo $this->element('Post.post_url_form'); ?>				

                                    <?php echo $this->element('Post.poll_form'); ?>	                    		
                            </div>



                            <div class="block">
                                    <div class="posting_items">
                                            <div class="pull-left" id="posting_errors_container">
                                                    <div id="posting_errors"> </div>
                                                    <div id="video_posting_error"> </div>
                                            </div>
                                            <div class="pull-right">
                                                    <ul class="post_options">
 
                                                            <li data-toggle="tooltip" title="Share video" data-posttype="<?php echo Post::POST_TYPE_VIDEO; ?>" 
                                                                    data-elem="posting_video" class="posting_video"></li>

                                                            <li data-toggle="tooltip" title="Share photos" data-posttype="<?php echo Post::POST_TYPE_IMAGE; ?>" 
                                                                    data-elem="posting_image" class="posting_image"></li>

                                                            <li data-toggle="tooltip" title="Share link" data-posttype="<?php echo Post::POST_TYPE_LINK; ?>" 
                                                                    data-elem="posting_url" class="posting_url"></li>

                                                            <li data-toggle="tooltip" title="Get opinions" data-posttype="<?php echo Post::POST_TYPE_POLL; ?>" 
                                                                    data-elem="posting_poll" class="posting_poll"></li>

                                                            <li data-toggle="tooltip" title="Start a discussion" data-posttype="<?php echo Post::POST_TYPE_TEXT; ?>" 
                                                                    data-elem="posting_text" class="posting_post active"></li>
                                                    </ul>
                                                    <?php
                                                    $showAnonymousCheckbox = fasle;
                                                    if ($showAnonymousCheckbox === true):
                                                    ?>
                                                        <div class="post_anonymous_box clearfix">
                                                                <div class="pull-right">
                                                                        <?php
                                                                        echo $this->Form->checkbox('is_anonymous', array('class' => 'pull-left'));
                                                                        echo $this->Form->label('is_anonymous', __('Post anonymously'), array('class' => 'pull-left'));
                                                                        ?>
                                                                </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div id="posting_more_action" class="block">

                                                            <button id="cancel_post_btn" type="button" data-style="expand-right" data-spinner-color="#3581ED" class="btn btn_clear pull-right ladda-button">
                                                                    <span class="ladda-label"><?php echo __('Cancel'); ?></span>
                                                                    <span class="ladda-spinner"></span>
                                                            </button>   
                                                            <button id="share_post_btn" disabled="disabled" type="button" data-style="expand-right" data-spinner-color="#3581ED" class="btn btn_active pull-right ladda-button">
                                                                    <span class="ladda-label"><?php echo __('Share'); ?></span>
                                                                    <span class="ladda-spinner"></span>
                                                            </button>                                                     
                                                    </div>
                                            </div>
                                    </div>
                            </div>



                            <?php echo $this->Form->end(); ?>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</div>
	<?php
else: //case when user is not login.
	echo $this->Form->create('Post');
	echo $this->Form->hidden('posted_in');
	echo $this->Form->hidden('posted_in_type');
	echo $this->Form->hidden('posted_in_room');
	echo $this->Form->end();
endif;
?>

<?php
echo $this->jQValidator->validator();
echo $this->Html->scriptBlock(
		"var form = '#{$formId}';"
);
?>
<script type="text/javascript">
	$.validator.setDefaults({
		errorLabelContainer: 'div#posting_errors'
	});
</script>