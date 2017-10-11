<div class="row">
    <?php
    
    if (!empty($events)) {
        foreach ($events as $event) {
            if ((isset($user['id'])) &&($event['Event']['created_by'] == $user['id'])) {
                $is_my_events = true;
            } else {
                $is_my_events = false;
            }            
            $event_more_option_id = "event_more_option_" . $event['Event']['id'];
            $is_upcoming_event = (($event['Event']['end_date'] >= $now) || ($event['Event']['repeat'] == 1 && $event['Event']['end_date'] == '0000-00-00 00:00:00'));
            
            $not_in_community = (($event['Event']['community_id'] == NULL) ||
                    (!empty($user_communities) && in_array($event['Event']['community_id'], $user_communities)));
            ?>
        
            <div class="col-sm-4">
                <div class="indvdl_event">
                    <div class="event_image hover_element"><?php echo $this->Html->image(Common::getEventThumb($event['Event']['id'])); ?>
                        <?php if (!$is_upcoming_event) { ?>
                        <div class="past_event_banner">
                            <p class="past_event_message">This is a past event</p>
                         </div>    
                        <?php }  ?>
                    </div>
                    <div id="<?php echo $event['Event']['id']; ?>" class="contenthover">
                        <div class="member_count text-center" style="<?php echo ($not_in_community) ? "padding-top:25px;" : ""; ?>">
                            Attending (<?php echo $event['Event']['attending_count']; ?>) | Maybe (<?php echo $event['Event']['maybe_count']; ?>)</div>
                        <div class="text-center">
                            <?php
                            if ($is_upcoming_event && !$is_my_events && (isset($user['id']))) {
                                if ($not_in_community) {

                                    if ((isset($goingEventIds)) && in_array($event['Event']['id'], $goingEventIds)) {
                                        $yes_class = "active";
                                        $no_class = "";
                                        $maybe_class = "";
                                    } else if ((isset($maybeEventIds)) && in_array($event['Event']['id'], $maybeEventIds)) {
                                        $yes_class = "";
                                        $no_class = "";
                                        $maybe_class = "active";
                                    } else if ((isset($notAttendingEventIds)) && in_array($event['Event']['id'], $notAttendingEventIds)) {
                                        $yes_class = "";
                                        $no_class = "active";
                                        $maybe_class = "";
                                    } else {
                                        $yes_class = "";
                                        $maybe_class = "";
                                        $no_class = "";
                                    }
                                    ?>
                                    <button id="yes_<?php echo $event['Event']['id']; ?>" class="btn rsvp_button ladda-button <?php echo $yes_class; ?>" 
                                            data-style="slide-right" <?php
                                            if ($yes_class == 'active') {
                                                echo 'disabled';
                                            }
                                            ?>
                                            data-event="<?php echo $event['Event']['id']; ?>"
                                            data-id="rsvp_yes_button">
                                        <span class="ladda-spinner"></span>Yes
                                    </button>
                                    <button id="maybe_<?php echo $event['Event']['id']; ?>" class="btn rsvp_button ladda-button <?php echo $maybe_class; ?>" 
                                            data-style="slide-right" <?php
                                            if ($maybe_class == 'active') {
                                                echo 'disabled';
                                            }
                                            ?>
                                            data-event="<?php echo $event['Event']['id']; ?>"
                                            data-id="rsvp_maybe_button">
                                        <span class="ladda-spinner"></span>Maybe
                                    </button>
                                    <button id="no_<?php echo $event['Event']['id']; ?>" class="btn rsvp_button ladda-button <?php echo $no_class; ?>" 
                                            data-style="slide-right" <?php
                                            if ($no_class == 'active') {
                                                echo 'disabled';
                                            }
                                            ?>
                                            data-event="<?php echo $event['Event']['id']; ?>" 
                                            data-id="rsvp_no_button">
                                        <span class="ladda-spinner"></span>No
                                    </button>
                                    <?php
                                } else {
                                    ?>  
                                    <?php
                                    echo __('This event is in the community "' . $this->Text->truncate(
                                                    h($event['Community']['name']), 20, array(
                                                'ellipsis' => '...',
                                                'exact' => false
                                                    )
                                            ) . '"');
                                    ?><br/>
                                    <a href="/event/details/index/<?php echo $event['Event']['id']; ?>"
                                       class="btn rsvp_button ladda-button">More  details &raquo;
                                    </a>
                                    <?php
                                }
                            } else if ($is_my_events || !$is_upcoming_event || !(isset($user['id']))) { //last not login case
                                ?>
                                <a href="/event/details/index/<?php echo $event['Event']['id']; ?>"
                                   class="btn rsvp_button ladda-button">More  details &raquo;
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="individual_event_details">
                        <a href="/event/details/index/<?php echo $event['Event']['id']; ?>">
                            <h4><?php echo h($event['Event']['name']); ?></h4>                            
                        </a>
                        <span>
                            <?php
                            if ((isset($event['Event']['start_date'])) && (isset($timezone))) {
                                echo __(CakeTime::nice($event['Event']['start_date'], $timezone, '%a, %b %e, %Y, %I:%M %p'));
                            }
                            else {
                                echo CakeTime::format($event['Event']['start_date'],'%a, %b %e, %Y, %I:%M %p');
                            }
                            ?>
                        </span>
                        <?php
                        if (isset($event['Event']['repeat_mode'])) {
                            if ($event['Event']['repeat_mode'] == '0') {
                                ?>
                                <p><span class="event_type oneday pull-left"></span><?php echo __('One-time'); ?></p>
                                <?php
                            } else {

                                $repeat_mode = 'Recurring Event';    
//                                switch ($event['Event']['repeat_mode']) {
//                                    case Event::REPEAT_MODE_DAILY:
//                                        $repeat_mode = 'Daily';
//                                        break;
////                                    case Event::REPEAT_MODE_WEEKDAY:
////                                        $repeat_mode = 'Every weekday (Monday to Friday)';
////                                        break;
////                                    case Event::REPEAT_MODE_MON_WED_FRI:
////                                        $repeat_mode = 'Every Monday, Wednesday and Friday';
////                                        break;
////                                    case Event::REPEAT_MODE_TUE_THU:
////                                        $repeat_mode = 'Every Tuesday, And Thursday';
////                                        break;
//                                    case Event::REPEAT_MODE_WEEKLY:
//                                        $repeat_mode = 'Weekly';
//                                        break;
//                                    case Event::REPEAT_MODE_MONTHLY:
//                                        $repeat_mode = 'Monthly';
//                                        break;
//                                    case Event::REPEAT_MODE_YEARLY:
//                                        $repeat_mode = 'Yearly';
//                                        break;
//                                }
                                ?>
                                <p><span class="event_type everyday pull-left"></span><?php echo __($repeat_mode); ?></p>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
} else {
    ?>
		<div id="blank_area" class="group_list" style="padding-left: 7px;">
			<p class="pull-left">It seems that there are no events for this disease</p>
			<a href="/event/add"  class="pull-left btn create_button"><?php echo __('Create new event'); ?>&nbsp;</a>
		</div>
    </div>
    <?php
}
?>
