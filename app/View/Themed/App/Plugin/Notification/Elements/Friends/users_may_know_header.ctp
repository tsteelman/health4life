<?php
$peopleMayKnowCounter = 0;
$peopleMayKnowCount = count($recommendedUsersDetails);
?>
<?php
if (isset($recommendedUsersDetails) && $recommendedUsersDetails != NULL) {
    ?>
    <li class="notfctn_header keep_open people_mayknow_notification_header" >
        <?php echo __("People You May Know"); ?>
        <?php if ($peopleMayKnowCount > 3) { ?>
            <a class="pull-right more keep_open" href="/search?type=people" >
                <?php
                echo __('more');
                ?>
            </a>
        <?php } ?>
    </li>
    <?php
    foreach ($recommendedUsersDetails as $recommendedUser) {
        $peopleMayKnowCounter++;
        $peopleMayKnow_li_classes = 'keep_open people_mayknow_notification';
        if ($peopleMayKnowCounter > 3) {
            $peopleMayKnow_li_classes = $peopleMayKnow_li_classes . ' hidden';
        }
        ?>
        <li class="<?php echo $peopleMayKnow_li_classes; ?>">
            <div class="media keep_open">
                <a class="pull-left keep_open" href="<?php echo Common::getUserProfileLink( $recommendedUser['user']['username'], TRUE); ?>" data-hovercard="<?php echo $recommendedUser['user']['username']; ?>" >
                    <?php echo Common::getUserThumb($recommendedUser['user']['id'], $recommendedUser['user']['type'], 'x_small', 'media-object'); ?>
                </a>
                <div class="media-body keep_open">
                    <div class="pull-left not_descrptn keep_open">
                        <h5 class="username_in_notification">
                            <?php echo Common::getUserProfileLink($recommendedUser['user']['username'], FALSE, 'pull-left keep_open', TRUE); ?>
                        </h5>
                        <span class="notification_content user_diseses_in_notification" title="<?php echo h($recommendedUser['diseases']); ?>"><?php echo h($recommendedUser['diseases']); ?></span></div>
                    <div class="pull-right confrm_btns keep_open">
                        <button id="notification_add_button_<?php echo $recommendedUser['user']['id']; ?>" type="button" class="keep_open btn btn_normal pull-right ladda-button"
                                data-style="slide-left"
                                data-spinner-color="#3581ED"
                                onclick="addFriend('<?php echo $recommendedUser['user']['id']; ?>', true, true)">
                            <span class="ladda-spinner"></span><?php echo __('Add Friend') ?>
                        </button>
                    </div>
                </div>
            </div>
        </li>

        <?php
    }
}
?>
