<div class="posting_area" id="post_<?php echo $postId; ?>">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">

        <?php echo $this->element('Post.profile_img'); ?>
        <?php  echo $this->element('Post.remove_from_library_btn'); ?>
        <div class="media-body">
            <?php echo $this->element('Post.username'); ?>
            <?php echo $this->element('Post.posted_in_details'); ?>
            <?php if (isset($title) && ($title !== '')) : ?>
                <h4 class="wordwrap"><?php echo $title; ?></h4>
            <?php endif; ?>
            <?php echo $this->element('Post.description'); ?>
                
            <?php if (isset($additional_info) && !empty($additional_info)) : ?>
                <?php if ($additional_info['link_video_iframe'] !== '') : ?>
                    <?php echo html_entity_decode(str_replace('display: none;', '', $additional_info['link_video_iframe'])); ?>
                <?php elseif ($additional_info['link_image'] !== '') : ?>
                    <a href="<?php echo $additional_info['link_url']; ?>" target="_blank">
                        <img src="<?php echo $additional_info['link_image']; ?>" class="url_img"/>
                    </a> 
                <?php endif; ?>
                <p><?php echo $additional_info['link_cannonical_url']; ?></p>
                <?php if ($additional_info['link_title'] !== '') : ?>
                    <p>
                        <a href="<?php echo $additional_info['link_url']; ?>" target="_blank">
                            <?php echo $additional_info['link_title']; ?>
                        </a>
                    </p>
                <?php endif; ?>
                <?php if ($additional_info['link_description'] !== '') : ?>
                    <p><?php echo html_entity_decode($additional_info['link_description']); ?></p>
                <?php endif; ?>
            <?php endif; ?>
            <?php // echo $this->element('Post.like_comment'); ?>
        </div>
    </div>
</div>