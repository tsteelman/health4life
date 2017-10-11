<div class="post_btm">
	<span class="pull-left"><?php echo __('Like (%d)', $likeCount); ?></span>
	<span class="seperator pull-left"></span>
	<span class="pull-left"><?php echo __('Comment (%d)', $commentCount); ?></span>
	<span class="seperator pull-left"></span>
	<span class="pull-left timeago"><?php echo $postedTimeAgo; ?></span>		
</div>
<br clear="all" />
<div class="comment_details">
    <div>
		<?php echo $this->element('Admin.Post/comments_list'); ?>
    </div>	
</div>