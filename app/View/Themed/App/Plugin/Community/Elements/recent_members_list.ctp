<?php
if (!empty($recent_members)) {
    foreach ($recent_members as $member) {
        ?>
        <div class="event col-xs-12">
            <div class="pull-left">
                <?php echo Common::getUserThumb($member['User']['id'], $member['User']['type'], 'x_small'); ?>
            </div>
            <div class="indvdl_list name_details pull-left">
                <a href="<?php echo Common::getUserProfileLink($member['User'] ['username'], TRUE); ?>" 
                   data-hovercard="<?php echo $member['User'] ['username']; ?>" class="owner" title="<?php echo __(h($member['User'] ['username'])); ?>">
                       <?php echo __(h($member['User'] ['username'])); ?>
                </a>
                <div class="user_disease_list" title="<?php echo h($member['User']['diseases']); ?>">
                    <?php echo h($member['User']['diseases']); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>