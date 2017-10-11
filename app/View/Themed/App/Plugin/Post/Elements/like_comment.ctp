<?php  
/*
 * Do not display comments and likes for the posts in hashtag page 
 */
if($displayPage != "hashtag") {
?>
<div class="po_activity">
	<?php if (isset($vote_details['totalVotes'])) { ?>

		<div class="no_of_votes_container poll_popup_area" style="display: none;">
                    <a class="liker no_of_votes">

                        <span id="user_count_<?php echo $poll_details['Poll']['id']; ?>">
                                <?php echo $vote_details['totalVotes'] . ' ';
                                          echo ($vote_details['totalVotes'] == 1) ? 'person voted' : 'people voted'; ?>
                        </span>
                    </a>
		</div>
		<?php
	}
	?>
    <div class="<?php echo $likedUsersClass; ?>" id="liked_users_list_<?php echo $postId; ?>">
        <span>
			<?php echo $this->element('Post.last_liked_users_list'); ?>
        </span>
    </div>

</div>
<div class="comment_details">
	<?php
	if (isset($loggedin_userid)):
		?>
		<div class="post_comment_section_form comment_section <?php echo $commentFormClass; ?>" id="comment_form_section_<?php echo $postId; ?>">
			<div class="media">
				<a class="pull-left cursor-default" href="javascript:void(0)"> 
					<?php echo Common::getUserThumb($loggedin_userid, $loggedin_user_type, 'x_small', 'media-object normal_thumb'); ?>
					<?php echo Common::getAnonymousUserThumb('x_small', 'media-object hidden anonymous_thumb'); ?>
				</a>
				<div class="media-body">
					<?php echo $this->element('Post.comment_form'); ?>
				</div>
			</div>
		</div>
		<?php
	endif;
	?>
        <div id="comment_list_<?php echo $postId; ?>">
                    <?php echo $this->element('Post.comments_list', array('comments' => $latestComments)); ?>
        </div>
		<?php if ($showSeeAllComments === true): ?>
			<div class="load_more_comments">
				<a data-post_id="<?php echo $postId; ?>">
					<?php echo __('Load More Comments'); ?>
				</a>
			</div>
		<?php endif; ?>    
	</div>




<?php
if (isset($loggedin_userid)):
	?>
	<div class="like">
		<?php if ($showLikeBox === true) : ?>
			<?php
			echo $this->Html->link($likeBtnText, 'javascript:void(0);', array(
				'data-post_id' => $postId,
				'class' => "pull-left $likeBtnClass",
			));
			?>
            <span class="like_dot seperator pull-left"></span>
			<a class="pull-left"><?php echo __('Comment'); ?>&nbsp;(<label class="comment_count" id="comment_count_<?php echo $postId; ?>"><?php echo $commentCount; ?></label>)</a>
			<span class="seperator pull-left"></span>
                        <div id="favorite_<?php echo $postId; ?>" data-post_id ="<?php echo $postId; ?>" title="<?php echo $favoritebtnTitle; ?>" class="favorite_icon <?php echo $favoritebtnClass; ?>"> 
                           <span class="favoriteBtnText"> <?php echo $favoritebtnTitle; ?></span>
                        </div>
                        <?php if ($canReportAbusePost) { ?>
                            <span class="seperator pull-left"></span>
                        <?php 
                        
                        }
                        ?>
		<?php endif; ?>

		<?php if ($canReportAbusePost): ?>                    
                    <a data-post_id ="<?php echo $postId; ?>" class="report_abuse report_abuse_post">
                        <span class="reportAbuseText"><?php echo __('Report Abuse'); ?></span>
                    </a>
		<?php endif; ?>
	</div>
	<?php
endif;
}
?>
