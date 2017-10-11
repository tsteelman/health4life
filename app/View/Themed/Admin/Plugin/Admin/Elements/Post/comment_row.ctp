<div class="comment_section <?php echo ($selectedCommentId === $commentId) ? 'selected' : ''; ?>" id="comment_<?php echo $commentId; ?>">
    <div class="media">
        <a class="pull-left cursor-default" href="javascript:void(0)"> 
			<?php echo $commentedUserOriginalThumb; ?>
        </a>
        <div class="media-body">
            <h5 class="pull-left">
				<?php echo $commentedUserAdminLink; ?>
            </h5>
            <span class="comments"><?php echo $commentText; ?></span>
            <span class="timeago"><?php echo $commentedTimeAgo; ?></span>
        </div>
    </div>
</div>