<?php
if (!empty($hashtags)) {
    foreach ($hashtags as $hashtag) {
        ?>
        <div class="friends_list hashtag_search_list">
            <div class="media">
                <div class="row">
                    <div class="pull-left">
                        <h5>
                            <a class="owner" href="<?php echo '/hashtag?tag='.$hashtag['Hashtag']['tag_name']; ?>">
                                <?php echo __(h("#".$hashtag['Hashtag']['tag_name'])); ?>
                            </a>
                        </h5>
                        <?php if ($hashtag['Hashtag']['total_posts'] != '') { ?>
                            <span class="disease_list_user_row"><?php echo __($hashtag['Hashtag']['total_posts']. ' posts.'); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div></div>
			<?php 
		}
	}else{ 
		?>
			<div class="friends_list">
				<div class="text-center friends_noresult_padding">
					<?php echo __('Sorry, no results containing all your search terms were found.');?>
				</div>
			</div>
		<?php 
	}
?>
