<?php
//echo '<pre>';
//print_r($recommendedUsersDetails);
//exit;

if (!empty($recommendedUsersDetails)) {
    foreach ($recommendedUsersDetails as $member) {
        ?>
        <div class="event col-xs-12">
            <div class="pull-left">
                <?php echo Common::getUserThumb($member['user']['id'], $member['user']['type'], 'x_small', '', 'img' ,$member['user']['username'] ); ?>
            </div>
            <div class="indvdl_list name_details pull-left">
                <a href="<?php echo Common::getUserProfileLink($member['user'] ['username'], TRUE); ?>" 
                   data-hovercard="<?php echo $member['user'] ['username']; ?>" class="owner" title="<?php echo __(h($member['user'] ['username'])); ?>">
                       <?php echo __(h($member['user'] ['username'])); ?>
                </a>
                <div class="user_disease_list" title="<?php echo h($member['diseases']); ?>">
                    <?php echo h($member['diseases']); ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="event col-xs-12">
        <div class="indvdl_list name_details pull-left">
            <?php echo __('No Members Found.') ?>
        </div>
    </div>
    <?php
}
?>