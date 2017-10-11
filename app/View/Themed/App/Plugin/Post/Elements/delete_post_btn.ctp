<?php if ($canDeletePost) : ?>
    <button type="button" data-post_id="<?php echo $postId; ?>" title="<?php echo $deleteButtonTitle; ?>" class="pull-right delete_post_btn hide">&times;</button>
<?php endif; ?>