<div class="posting_area poll_post_area poll_<?php echo $poll_details['Poll']['id']; ?>" id="post_<?php echo $postId; ?>">
    <?php if (isset($iconClass)) echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php if (isset($postedUserThumb)) echo $this->element('Post.profile_img'); ?>
         <?php if (isset($canDeletePost)) echo $this->element('Post.delete_post_btn'); ?>
        <div class="media-body">
            <div class="poll_rates poll_popup">
               
                <?php echo $this->element('Post.username'); ?>
                <input id="poll_id_hidden" type="hidden" value="<?php echo $poll_details['Poll']['id']; ?>">
                
        </div>
    </div>
    </div>
    <div class="comment_posting">
        <h4 class="poll_name">
            <?php if (isset($poll_name)) {
//            echo h($poll_name); 
                echo $this->element('Post.description');
            
            } ?>
        </h4>
                <h4><?php if (isset($poll_details['Poll']['title'])) echo h($poll_details['Poll']['title']); ?></h4>
                <?php
                if ($is_user_voted === true || $can_vote != TRUE) {
                    $totalVotes = $vote_details['totalVotes'];
                    $totalChoices = $vote_details['totalChoices'];
                    $width = 0;
                    foreach ($poll_details['PollChoices'] as $choices) {
                        $width = 0;
                        if ($totalVotes > 0) {
                            $width = round(($choices['votes'] / $totalVotes) * 100);
                        }
                        ?>
                        <!--<div class="poll_options">-->                                        
                        <div class="poll_results">
                            <div class="poll_question"> <?php  echo h($choices['option']); ?></div>
                            <div class="poll_details no_of_polls">
                                <div class="polled_option" style="width:<?php echo $width . '%'; ?>">
                                </div>
                                <span class="poll_votes_count"><?php echo $choices['votes'] . ' vote(s)'; ?></span>
                                <span class="poll_percentage"> <?php echo $width . '%'; ?></span>
                            </div>
                        </div>
                        <!--</div>-->                                                                                 
                        <?php
                    }
                } elseif($can_vote) {
                    foreach ($poll_details['PollChoices'] as $choices) {
                        ?>
                        <div class="radio">
                            <label>
                                <input class="poll_options_radios" type="radio" name="optionsRadios" id="<?php echo $choices['id'] ?>">
                                <?php echo h($choices['option']); ?>
                            </label>
                        </div>

                        <?php
                    }
                }
                ?>
                <?php if ($is_user_voted != true) {
                    ?>
                    <div class="poll_vote"><button type="button" class="vote_for_poll btn pull-left btn_leave ladda-button" disabled="disabled" ><span class="ladda-label">Vote</span></button></div>
                    <?php
                }
                ?>
         
            <?php echo $this->element('Post.like_comment'); ?>
                       </div>
    </div>
