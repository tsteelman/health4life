<div class="row">
    <?php 
    if (!empty($events)) {
    	$i = 0;
        foreach ($events as $event) {
            // add row for each 3 events
            if($i == 3){
                echo "</div><div class=\"row\">";
                $i = 0;
            }
        	
            if ($event['Event']['created_by'] == $user['id']) {
                $is_my_events = true;
            } else {
                $is_my_events = false;
            }
            $event_more_option_id = "event_more_option_" . $event['Event']['id'];
            $is_upcoming_event = (($event['Event']['end_date'] >= $now) || ($event['Event']['repeat'] == 1 && $event['Event']['end_date'] == '0000-00-00 00:00:00'));
            $not_in_community = (($event['Event']['community_id'] == NULL) ||
                    (in_array($event['Event']['community_id'], $user_communities)));
            ?>
            <div class="col-sm-4">
                <div class="indvdl_event">
                    <div class="event_image hover_element">
                        <a href="/event/details/index/<?php echo $event['Event']['id']; ?>">
                            <?php echo $this->Html->image(Common::getEventThumb($event['Event']['id'])); ?>
                        </a>
                        <?php if (!$is_upcoming_event) { ?>
                        <div class="past_event_banner">
                            <p class="past_event_message">This is a past event</p>
                         </div>    
                        <?php } ?>
                       
                    </div>
                    
                    <div class="individual_event_details">
                        <a href="/event/details/index/<?php echo $event['Event']['id']; ?>">
                            <h4><?php echo h($event['Event']['name']); ?></h4>
                        </a>
                        <span>
                            <?php
                            if (isset($event['Event']['start_date'])) {
                                echo __(CakeTime::nice($event['Event']['start_date'], $timezone, '%a, %b %e, %Y, %l:%M %p'));
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
//                                    case Event::REPEAT_MODE_WEEKLY:
//                                        $repeat_mode = 'Weekly';
//                                        break;
//                                    case Event::REPEAT_MODE_MONTHLY:
//                                        $repeat_mode = 'Monthly';
//                                        break;
//                                    case Event::REPEAT_MODE_YEARLY:
//                                        $repeat_mode = 'Yearly';
//                                        break;
//                                    case Event::REPEAT_MODE_WEEKDAY:
//                                        $repeat_mode = 'Every weekday (Monday to Friday)';
//                                        break;
//                                    case Event::REPEAT_MODE_MON_WED_FRI:
//                                        $repeat_mode = 'Every Monday, Wednesday and Friday';
//                                        break;
//                                    case Event::REPEAT_MODE_TUE_THU:
//                                        $repeat_mode = 'Every Tuesday, And Thursday';
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
            $i++;
            
        }
        ?>
    </div>
    <?php
    if ($event_type != Event::UPCOMING_COMMUNITY_EVENTS && $event_type != Event::PAST_COMMUNITY_EVENTS) {
//        if ($this->Paginator->param('nextPage') && $this->Paginator->param('nextPage') != 1) {
        if ($this->Paginator->param('nextPage')) {
            if(empty($nextPage) || !isset($nextPage)) { 
                 if(isset($pageCount) && $pageCount > 1) {
                   $nextPage = 2; 
                }
            }
            if(!empty($nextPage)) {
            ?>
                <div id="more_button<?php echo $event_type . $nextPage; ?>" class="block">
                    <a href="javascript:load_events_list(<?php echo $event_type; ?>,<?php echo $nextPage; ?>);" id="load-more<?php echo $event_type; ?>" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED">
                        <span class="ladda-label"><?php echo __('More'); ?></span>
                    </a>
                </div>
            <?php
            }
        }
   }
} else {
    if ($event_type == Event::MY_EVENTS) {
        ?>
        <div class="row" id="blank_area">
            <p class="pull-left">It seems that you have not added any event yet</p>
            <a href="/event/add"  class="pull-left btn create_button"><?php echo __('Create new event'); ?></a>
        </div>
        <?php
    } else if ($event_type == Event::UPCOMING_COMMUNITY_EVENTS) {
        if (!is_null($user_status)) {
            ?>
            <div class="row" id="blank_area">
                <p>It seems that you have not added any event yet</p>
                <a href="/community/<?php echo $community_id; ?>/event/add"  class="pull-left btn create_button"><?php echo __('Create new event'); ?></a>
            </div>
        <?php } else {
            ?>
            <div id="blank_area">
                <div class="alert alert-warning">
                    <p>It seems there is no events for this community</p>
                </div>
            </div>
            <?php
        }
    } else if ($event_type == Event::UPCOMING_USER_EVENTS) {
        ?>
        <div class="content">
            <div class="row">
                <div id="myGroupsList" class="group_list">
                    <div class="text-center noresult_padding">
                        <p class="alert alert-error">No events found.</p>
                    </div>
                </div>                           
            </div>
        </div>
        <?php
    }
    else { ?>
        <div id="blank_area">
            <div class="alert alert-warning">
                <p>It seems there is no events for this community</p>
            </div>
        </div>
    <?php } ?>
    </div>
<?php 
 }
?>