<div id="user_element_container" class="profile_page mutual_frndlist">
    <div class="community_members mutual_list">
        <div class="event_wraper">
            <div class="row">
                <div class="clearfix form-group" style="border-bottom: 1px solid #ebebeb;">
                    <div id="mutual-friends" class="members_list">
                        <?php
                        if (!empty($mutual_friends)) {
                            foreach ($mutual_friends as $friend) {
                                ?>
                                <div id="<?php echo $friend['User']['id']; ?>" class="col-lg-6 ">
                                    <div class="friends_list">
                                        <div class="media">
                                            <a class="pull-left" href="<?php echo Common::getUserProfileLink( $friend['User']['username'], TRUE); ?>"> 
                                                <?php echo Common::getUserThumb($friend['User']['id'], $friend['User']['type'], 'small'); ?>
                                            </a>
                                            <div class="media-body">
                                                <div class="pull-left">
                                                    <h5>
                                                        <?php echo Common::getUserProfileLink($friend['User']['username'], FALSE, 'owner'); ?>
                                                    </h5>
                                                    <p><?php echo $friend[0]['diseases']; ?></p>
                                                    <span><?php echo $friend[0]['location']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div id="none_found" class="col-lg-12 alert alert-warning hidden">No mutual friends found</div>
                            <?php
                        } else {
                            ?>
                            <div id="none_found" class="col-lg-12 alert alert-warning">No mutual friends found</div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>