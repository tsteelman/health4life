<?php
    $postingPlaceholder = "Post a new blog, photo, video, or e-card";
    $blogTab = FALSE;
    if(isset($isBlogPage) && ($isBlogPage === true)) {
        $blogTab = true;
    }
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
                        echo $this->Form->hidden('post_type', array('value' => Post::POST_TYPE_BLOG));

                        ?>


                        <?php echo $this->element('Post.post_blog_form'); ?>

                        <?php echo $this->element('Post.post_ecard_form'); ?>


                        <div class="block">
                            <div class="posting_items">
                                <div class="pull-left" id="posting_errors_container">
                                        <div id="posting_errors"> </div>
                                </div>
                                <div class="pull-right">
                                    <ul class="post_options">
                                        <?php 
                                        if($blogTab):
                                        ?>    
                                            <li data-toggle="tooltip" title="Add a Blog" data-posttype="<?php echo Post::POST_TYPE_BLOG; ?>" 
                                                    data-elem="posting_blog" class="posting_blog active pull-right"></li>

                                            <li data-toggle="tooltip" title="Send an e-Card" data-posttype="<?php echo Post::POST_TYPE_ECARD; ?>" 
                                                    data-elem="posting_ecard" class="posting_ecard pull-right"></li>                                                                                    
                                            
                                        <?php
                                        endif;
                                        ?>

                                    </ul>

                                    <div class="clearfix"></div>
                                    
                                    <div id="posting_more_action" class="block">

                                            <button id="cancel_post_btn" type="button" data-style="expand-right" data-spinner-color="#3581ED" class="btn btn_clear pull-right ladda-button">
                                                    <span class="ladda-label"><?php echo __('Cancel'); ?></span>
                                                    <span class="ladda-spinner"></span>
                                            </button>   
                                            <button id="share_post_btn" type="button" data-style="expand-right" data-spinner-color="#3581ED" class="btn btn_active pull-right ladda-button">
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
endif;

echo $this->Html->scriptBlock(
		"var form = '#{$formId}';"
);
$this->AssetCompress->script('blog_posting', array('block' => 'scriptBottom'));
