<?php if (!empty($lastLikedUsers)) : ?>
    <?php
    $lastLikedUsersCount = count($lastLikedUsers);
    foreach ($lastLikedUsers as $key => $lastLikedUserName) :
        ?>
        <span class="liker">
            <?php
            if ($lastLikedUserName == $currentUsername) {
                echo 'You';
            } else {
                ?>
                <a href="<?php echo Common::getUserProfileLink($lastLikedUserName, true); ?>"
                   class="owner" data-hovercard="<?php echo $lastLikedUserName; ?>">
                       <?php echo $lastLikedUserName; ?>
                </a>
                <?php
            }
            ?>
        </span>
        <?php
        if ($key === 0) {
            if ($lastLikedUsersCount > 1) {
                echo ($othersLikeCount > 0) ? '<span class="comma">,</span>' : __('<span class="and">and</span>');
            }
        }
    endforeach;
    ?>
    <?php
    if ($othersLikeCount > 0):
        echo __('<span class="and">and</span>');
        $pluralText = ($othersLikeCount > 1) ? __('s') : '';
        $othersText = $othersLikeCount . ' ' . __('other') . $pluralText;
        ?> 
        <a data-post_id="<?php echo $postId; ?>" class="owner view_likes"><?php echo $othersText; ?></a> 
        <?php
    endif;
    echo __('<span>like this</span>');
endif;
?>