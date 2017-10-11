<!-- Modal -->
<div class="poll_result_modal modal fade" id="pollPopupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="likes_modal">
                <div class="modal-header">
                    <button type="button" class="bootbox-close-button close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Poll Results</h4>
                </div>
            </div>
            <div id="pollPopupModalBody" class="modal-body">
                <div class="posting_area poll_post_area" id="post_<?php echo $postId; ?>">
                    <div class="media">
                        <div class="media-body">
                            <div class="poll_rates poll_popup">
                                <input id="poll_id_hidden" type="hidden" value="<?php echo $poll_details['Poll']['id']; ?>">
                                <?php $totalVotes = $vote_details['totalVotes']; ?>
                                <div class="clearfix">
                                    <?php if (isset($poll_name)) { ?>
                                        <h4 class="poll_name pull-left">
                                            <?php echo h($poll_name); ?>
                                        </h4>
                                    <?php } ?>

                                    <span class="pull-right voted_no">
                                        <?php
                                        echo '( ' . $totalVotes . ' ';
                                        echo ($totalVotes == 1) ? 'person voted )' : 'people voted )';
                                        ?>
                                    </span>
                                    <h4 class="pull-left poll_title"><?php if (isset($poll_details['Poll']['title'])) echo h($poll_details['Poll']['title']); ?></h4>
                                </div>


                                <?php
//                                if ($is_user_voted === true) {

                                $totalChoices = $vote_details['totalChoices'];
                                $width = 0;
                                foreach ($poll_details['PollChoices'] as $choices) {
                                    $width = 0;
                                    if ($totalVotes > 0) {
                                        $width = round(($choices['votes'] / $totalVotes) * 100);
                                    }
                                    ?>
                                    <!--<div class="poll_options1">-->                                        
                                    <div class="poll_results">
                                        <div class="poll_question"> <?php echo h($choices['option']); ?></div>
                                        <div class="poll_details no_of_polls">
                                            <div class="polled_option" style="width:<?php echo $width . '%'; ?>">
                                            </div>
                                            <span><?php echo $choices['votes'] . ' vote(s)'; ?></span>
                                            <span class="poll_percentage"> <?php echo '(' . $width . '%)'; ?></span>
                                        </div>

                                        <!--</div>-->                                                                                 
                                        <?php if (isset($voted_user_details) && !empty($voted_user_details)) { ?>
                                            <div class = "voted_users_list row">
                                                <?php
                                                foreach ($voted_user_details as $voted_user) {
                                                    if ($voted_user['PollVote']['choice_id'] == $choices['id']) {
                                                        ?>
                                                        <div class = "col-lg-1 voted_user_thumb_container">
                                                            <a href="<?php echo Common::getUserProfileLink(  $voted_user['User']['username'], TRUE); ?>" title="<?php echo $voted_user['User']['username'] ?>">
                                                                <?php echo Common::getUserThumb($voted_user['User']['id'], $voted_user['User']['type'], 'x_small', 'media-object voted_user_thumb'); ?>
                                                            </a>
                                                        </div> 

                                                        <?php
                                                    }
                                                    ?>

                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>

                                <?php
//                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--            <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->