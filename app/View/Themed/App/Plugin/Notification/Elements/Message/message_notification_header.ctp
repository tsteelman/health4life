<img src="/theme/App/img/notifictn_arrow.png" class="notfctn_arrow">
<ul class="notification_scroll_header">
    <li class="notfctn_header keep_open" >
        <?php echo __("Messages"); ?>
        <a class="pull-right more" href="/message" ><?php echo __("Go to Inbox"); ?></a>
    </li>
</ul>
<ul class="message_notification_scroll">
    <?php if (isset($messages) && $messages != NULL) { ?>
        <?php
        foreach ($messages as $message) {
            if (!isset($message['message']['is_read']) || $message['message']['is_read'] == 0) {
                $li_class = 'unread_message_ntfcn message_ntfcn_content';
            } elseif (isset($message['message']['is_read']) && $message['message']['is_read'] == 1) {
                $li_class = 'read_message_ntfcn message_ntfcn_content';
            }
            ?>
            <li class="<?php echo $li_class; ?>" >
                <input id="other_user_id_hidden" type="hidden" value="<?php echo $message['message']['user_id']; ?>" />
                <div class="media keep_open">
                    <a class="pull-left keep_open" href="/message/details/index/<?php echo $message['message']['user_id']; ?>">
                        <?php echo Common::getUserThumb($message['message']['user_id'], $message['message']['user_type'], 'x_small', 'media-object'); ?>                    
                    </a>
                    <div class="media-body keep_open">
                        <div class="pull-left message_notification">
                            <h5 class="keep_open">
                                <a class="pull-left keep_open owner" href="/message/details/index/<?php echo $message['message']['user_id']; ?>"><?php echo $message['message']['username']; ?></a>
                                <?php //echo Common::getUserProfileLink($message['message']['username'], FALSE, 'pull-left keep_open owner'); ?>
                                <span class="pull-right"><?php echo __(CakeTime::nice($message['message']['created'], $timezone, '%B %e, %Y at %l:%M%P')); ?></span>

                            </h5>
                            <span class="keep_open message_body notification_content"><?php echo h($message['message']['message']); ?></span>
                        </div>
                    </div>
                </div>
            </li>
            <?php
        }
    } else {
        ?>
        <div class="col-lg-12 keep_open no_message_in_inbox">
            <?php
            echo __('No messages in inbox.');
            ?>
        </div>
    <?php }
    ?>
</ul>