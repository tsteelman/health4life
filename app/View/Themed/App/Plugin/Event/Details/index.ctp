<?php

$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
	
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
} elseif (substr($this->request->referer(1), 0, 9) == '/calendar') {
    $this->Html->addCrumb('Calendar', $this->request->referer(1));
} else if (isset($refer) && $refer == 'Calendar') {
    $this->Html->addCrumb('Calendar', '/calendar');
} else if (isset($refer) && $refer == 'Condition') {
	$diseaseUrl = '/condition/index/' . $eventDisease['Disease']['id'];
    $this->Html->addCrumb($eventDisease['Disease']['name'], $diseaseUrl);
	$this->Html->addCrumb('Events', $diseaseUrl . '/events');
} else if (substr($this->request->referer(1), 0, 10) == '/condition') {
	$diseaseUrl = '/condition/index/' . $eventDisease['Disease']['id'];
	$this->Html->addCrumb($eventDisease['Disease']['name'], $diseaseUrl);
	$this->Html->addCrumb('Events', $diseaseUrl . '/events');
} else if (isset($communityId)) {
	if (substr($this->request->referer(1), 0, 6) == '/event') {
		$this->Html->addCrumb('Events', '/event');
	} else {
		$this->Html->addCrumb('Community', '/community');
		$this->Html->addCrumb(htmlspecialchars_decode($communityName, true), "/community/details/index/{$communityId}");
	}
} else {
    $this->Html->addCrumb('Events', '/event');
}
$this->Html->addCrumb(h($event['Event']['name']));
?>
<div class="container">
    <div class="event">
        <div class="row">
            <div class="col-lg-12">
                <div class="event_list ">
                    <input id="event_id_hidden" type="hidden" value="<?php echo $event['Event']['id'] ?>">
                    <input id="event_type_hidden" type="hidden" value="<?php echo $event['Event']['event_type'] ?>">
                    <input id="user_attendance_hidden" type="hidden" value="<?php echo $currentAttendanceOfUser ?>">
                    <input id="is_group_event" type="hidden" value="<?php echo $isCommunityEvent; ?>">
                    <input id="is_group_member" type="hidden" value="<?php echo $isCommunityMember; ?>">
                    <input id="is_approved_group_member" type="hidden" value="<?php echo $isApprovedCommunityMember; ?>">
                    <input id="is_invited" type="hidden" value="<?php echo $isInvited; ?>">

                    <div class="event_wraper detail_page">
                        <div class="profile_container">
                            <!--                            <div class="row">
                                                                                                                            </div>-->
                            <div class="row">
                                <div class="col-lg-4 profile_info">
                                    <div class="date_and_conformation">
                                        <div class="date_div">
                                            <?php
                                                if (isset($event['Event']['start_date'])) {
                                                    echo CakeTime::nice($event['Event']['start_date'], $timezone, '%a, %B %e, %Y %l:%M %p');
                                                    if (isset($event['Event']['end_date']) && $event['Event']['repeat'] == 0) {
                                                        echo ' - ' . CakeTime::nice($event['Event']['end_date'], $timezone, '%l:%M %p');
                                                    }
                                                    echo "&nbsp;" . $timeZoneOffset;
                                                }
                                            ?>
                                        </div>                                          
                                    </div>
                                    <div class="response_tile">
                                    <div class="image_container"><?php echo $this->Html->image(Common::getEventThumb($event['Event']['id'], "large"), array('class' => 'img-responsive default_img')); ?></div>
                                    <div class="event_name">
                                        <h3><?php echo h($event['Event']['name']); ?></h3>    
                                        
                                        <?php 
                                            $eventDescription = Common::truncate($event['Event']['description'], 97);
                                        ?>
                                        <div title="<?php echo h($eventDescription['title']); ?>" class="event_decrptionn">
                                        
                                        <?php                     
                                        echo h($eventDescription['name']); ?>
                                    </div>
                                      <?php
                                        if (isset($event['Event']['created_by']) && $event['Event']['created_by'] != $user['user_id']) {
                                            if ((isset($event['Event']['start_date']) && $event['Event']['start_date'] > $now && $event['Event']['repeat'] == 0) ||
                                                    ($event['Event']['end_date'] == '0000-00-00 00:00:00' && $event['Event']['repeat'] == 1) ||
                                                    ($event['Event']['end_date']  > $now  && $event['Event']['repeat'] == 1)) {
                                                ?>
                                        <div class="event_type_notifier"> 
                                                <div>
                                                        <button id="yes_<?php echo $event['Event']['id']; ?>"
                                                                class="btn btn_more rsvp_button ladda-button pull-left"
                                                                data-style="slide-right" data-event="<?php echo $event['Event']['id']; ?>"
                                                                data-id="rsvp_yes_button">
                                                            <span class="ladda-label">Yes</span>
                                                            <span class="ladda-spinner"></span>
                                                        </button>                                                        
                                                         <button id="maybe_<?php echo $event['Event']['id']; ?>" 
                                                                class="btn btn_more rsvp_button ladda-button pull-left" 
                                                                data-style="slide-right" data-event="<?php echo $event['Event']['id']; ?>" 
                                                                data-id="rsvp_maybe_button">
                                                            <span class="ladda-label">Maybe</span>
                                                            <span class="ladda-spinner"></span>
                                                        </button>
                                                        <button id="no_<?php echo $event['Event']['id']; ?>" 
                                                                class="btn btn_more rsvp_button ladda-button pull-left" data-style="slide-right" data-event="<?php echo $event['Event']['id']; ?>" 
                                                                data-id="rsvp_no_button">
                                                            <span class="ladda-label">No</span>
                                                            <span class="ladda-spinner"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } ?>  
                                        <?php // if ( $event['Event']['end_date'] < $now ) {  ?> 
                                        <?php  if ((isset($event['Event']['start_date']) && $event['Event']['start_date'] < $now && $event['Event']['repeat'] == 0) ||
                                                    ($event['Event']['end_date'] != '0000-00-00 00:00:00' && $event['Event']['repeat'] == 1 && $event['Event']['end_date']  < $now)) {  ?> 
                                                        <p  class="past_event_message pull-left">
                                                            <?php echo __('This is a past event'); ?>
                                                        </p>
                                        <?php } ?>
                                    </div>
                                </div>
                                     <div class="event_detail_icon">
                                         
                                    <?php 
                                        $repeatModes = $event['Event']['repeat_mode'];
										$repeatInterval = $event['Event']['repeat_interval'];
                                        if (isset($event['Event']['end_date']) && $event['Event']['repeat'] == 1) {
											if($repeatInterval == 1) {
												$eventRepeatDetails = $repeatModeTextArray[$repeatModes];
											} else {
												$eventRepeatDetails = 'Every ' .$repeatInterval.' '.$repeatIntervalTextArray[$repeatModes];
												if($repeatModes == 3) { //Monthly
													if($repeatInterval == 3) {
														$eventRepeatDetails = 'Quarterly';
													} else if($repeatInterval == 6) {
														$eventRepeatDetails = 'Bi-annually';
													}
												}
											}
                                        }
                                    
                                    ?>    
                                    <?php if($event['Event']['repeat'] == 0) { ?>    
                                        <span class="event_type oneday" > One-time</span>
                                    <?php } else { ?>
                                        <span class="event_type everyday" data-placement="top"  data-toggle="popover" data-content="<?php echo $eventRepeatDetails; ?>">Recurring Event</span>
                                   <?php } ?>
                                    <?php
                                        
                                        /*
                                         * Edit gear icon
                                         */
                                        if ($hasManagePermission === true) {
                                    ?>
                                            <span class="btn-toolbar pull-right">    
                                                <span class="btn-group pull-right">
                                                    <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown" style="margin:-5px 0px 0px;">
                                                        <div class="edit_common edit_common_default"></div>
                                                    </button> 
                                                    <ul class="dropdown-menu">
                                                        <li><a href="/event/edit/<?php echo $event['Event']['id']; ?>">Edit event</a></li>
                                                        <li><a id="delete_event_button"href="/event/details/delete/<?php echo $event['Event']['id']; ?>">Delete Event</a></li>                                                    
                                                    </ul>
                                                </span>
                                            </span>
                                    <?php 
                                        }
                                        
                                        
                                        /*
                                         * If it is a community event
                                         * Show community name and link
                                         */
                                        if ($isCommunityEvent === true && isset ( $communityId )) { ?>
                                            <a href="/community/details/index/<?php echo $communityId; ?>" class="community_name pull-right" 
                                               data-placement="top"  data-toggle="popover" data-content="<?php echo $communityName; ?>"></a>
                                    <?php
                                        }
                                        
                                        
                                        /*
                                         * If sponsor is viewing the event
                                         */
                                        if ( $event['Event']['created_by'] == $user['user_id'] ) {
                                    ?>
                                        <a href="javascript:void(0);" class="contact_sponsor pull-right" data-placement="top"  data-toggle="popover" data-content="You are the sponsor of this event"></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0);" 
                                           data-placement="top"                                             
                                           data-content="Contact <?php echo __(h($eventCreatedBy['user_name'])); ?> (Sponsor)"
                                           class="contact_sponsor message_button pull-right" 
                                           data-toggle="modal"
                                            data-target="#composeMessage"
                                           data-username="<?php echo $eventCreatedBy['user_name']; ?>"
                                           data-user-id="<?php echo $eventCreatedBy['user_id']; ?>" 
                                           data-backdrop="static" data-keyboard="true"></a>
                                        
                                    <?php }
                                    
                                        /**
                                         * Invite friends button
                                         */
                                        if ((intval($event['Event']['event_type']) !== EVENT::EVENT_TYPE_SITE) && 
                                        (($event['Event']['event_type'] == 2 && $currentAttendanceOfUser != NULL && $event['Event']['guest_can_invite'] == 1) || 
                                        ($event['Event']['created_by'] == $user['user_id']) || 
                                        ($event['Event']['event_type'] == 1 && $event['Event']['guest_can_invite'] == 1))) {
                                        
                                            if ((isset($event['Event']['start_date']) && $event['Event']['start_date'] > $now && $event['Event']['repeat'] == 0) ||
                                                        ($event['Event']['end_date'] == '0000-00-00 00:00:00' && $event['Event']['repeat'] == 1) ||
                                                        ($event['Event']['end_date']  > $now  && $event['Event']['repeat'] == 1)) { 
                                        ?>
                                            <script type="text/javascript">
                                                var friendList = <?php echo $friendsListJson; ?>
                                            </script>
                                            <a href="#" class="invite_members pull-right" 
                                               data-toggle="modal" 
                                               data-target="#inviteFriends" 
                                               data-backdrop="static" 
                                               data-keyboard="false" 
                                               data-placement="top"  data-toggle="popover" data-content="Invite Friends"
                                                onclick="inviteButtonStatus()">                                                   
                                            </a>
                                            
                                     <?php
                                            }
                                        }
                                        
                                        
                                        
                                        /**
                                         * Event location icon
                                         */
                                        if (isset($event['Event']['virtual_event']) && $event['Event']['virtual_event'] != 1) {
                                            $eventLocation =  $eventLocation['location'] . ', ' . $eventLocation['city'] . ', ' . $eventLocation['state'] . ', ' . $eventLocation['country'];
                                        } else {
                                            $eventLocation = __('Online event');
                                        }

                                        if ($onlineEventDetails) {
                                            $eventLocation .= " : ";
                                            $eventLocation .= __(h($onlineEventDetails));

                                        }
                                    ?> 
                                    <a href="javascript:void(0);" class="event_location_icon pull-right" data-placement="top"  data-toggle="popover" data-content="<?php echo $eventLocation; ?>"></a>
                                    
                                </div>
                                </div>
                               

                                <div class="col-lg-8 profile_cover_photo" id="cover">                                    
                                    <?php if ( $hasManagePermission === true ) {echo $this->element('profile_cover_settings');} ?>
                                    <?php echo $this->element('cover_slideshow'); ?>
                                    <div id="cover_image_container" >
                                        <img src="<?php echo $defaultPhoto; ?>" />
                                    </div>
                                    <?php if ( $hasManagePermission === true) { ?>
                                        <a href="javascript:void(0);" class="change_coverpage" id="btn_changeCover"></a>
                                    <?php } ?>
                                    <?php // if ( $event['Event']['end_date'] < $now ) {  ?> 
                                        <!--<p  class="past_event_message pull-right">-->
                                            <?php // echo __('This is a past event'); ?>
                                        <!--</p>-->
                                    <?php // } ?>
                                </div>
                            </div>
                        </div>
                                               

                </div>
                </div>
            </div>
        </div>
        <div class="row mr_0">            
            <div class="col-lg-9">



                <?php if ($embedCode) { ?>
                <!-- Video  -->
                <div id="event_video" class="event_video">
                        <?php echo $embedCode; ?>                			
                </div>
                <?php } ?>

                <div class="event_details" id="post_content">
                    <?php
                    echo $this->element('Post.post_content');
                    ?>
                </div>
            </div>
            <div class="col-lg-3" id="rhs"> 
                <div class="event_lhs">   

                    <div id="all_attendess_list_container"></div>
                    <?php echo $this->element('ads'); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade inviteFriend" id="inviteFriends" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close invite_close_button" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Invite Friends</h4>
            </div>
            <div class="modal-body">
                <div class="import_contact_step_1">
                    <?php echo $this->element('invite_friends'); ?>
                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Invitation has been sent
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e1e1e1 !important;">
                <button id="close_invite_button" type="button" class="btn btn-default invite_close_button" data-dismiss="modal">Close</button>
                <button id="invite_button" type="button" class="btn btn-primary ladda-button" data-style="expand-right" onclick = "inviteFriends(<?php echo $event['Event']['id'] . ',' . $event['Event']['created_by'] . ',1'; ?>);" disabled><span class="ladda-label">Invite</span><span class="ladda-spinner"></span></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="event_popover" class="default-popover"></div>
<?php
if ($isCommunityEvent === true) {
    $this->AssetCompress->script('community_events.js', array('block' => 'scriptBottom'));
    $joinCommunityModalBody = $this->element('Event.join_community_modal');
    echo $this->element('modal', array('id' => 'join_community', 'titleId' => 'join_community_title', 'title' => __('Join Community'), 'body' => $joinCommunityModalBody));
    echo $this->element('modal', array('id' => 'waiting_community_approval',
        'titleId' => 'waiting_community_approval_modal_title',
        'title' => __('Waiting Community Admin Approval'),
        'body' => 'In order to attend this event, you need to be a member of this community. 
            The Community admin has been notified about your interest in joining the Community.'
    ));
} else {
    $this->AssetCompress->script('events', array('block' => 'scriptBottom'));
}
?>
<script>
    var isCommunityEvent = Boolean($('#is_group_event').val());
    var isCommunityMember = Boolean($('#is_group_member').val());
    var isApprovedCommunityMember = Boolean($('#is_approved_group_member').val());
    var isInvited = Boolean($('#is_invited').val());
    $(function() {
        /**
         * delete event
         */
        $("#delete_event_button").click(function(e) {
            e.preventDefault();
            var location = $(this).attr('href');
            bootbox.confirm("Are you sure you want to delete?", function(confirmed) {
                if (confirmed) {
                    window.location.replace(location);
                }
            });
        });

        var event_id = $("#event_id_hidden").val();
        updateList(event_id);
        var status = $('#user_attendance_hidden').val();
        if (status != null && status == 1) {
            $('#yes_' + event_id).css({
                'border': '1px solid #004f7f',
                'background-color': '#2c589e'
            });
            $('#yes_' + event_id).addClass('active');
            $('#yes_' + event_id + ' span.ladda-label').css({'color': '#fff'});
            $('#yes_' + event_id).attr('disabled', 'disabled');
        }
        else if (status == 2) {
            $('#no_' + event_id).css({
                'border': '1px solid #004f7f',
                'background-color': '#2c589e'
            });
            $('#no_' + event_id).addClass('active');
            $('#no_' + event_id + ' span.ladda-label').css({'color': '#fff'});
            $('#no_' + event_id).attr('disabled', 'disabled');
        }
        else if (status == 3) {
            $('#maybe_' + event_id).css({
                'border': '1px solid #004f7f',
                'background-color': '#2c589e'
            });
            $('#maybe_' + event_id).addClass('active');
            $('#maybe_' + event_id + ' span.ladda-label').css({'color': '#fff'});
            $('#maybe_' + event_id).attr('disabled', 'disabled');
        }
    });
    /*
     * Update attendees list.
     */
    function updateList(event_id) {
        $.ajax({
            url: '/event/details/setAttendees/' + event_id,
            beforeSend: function() {
            },
            success: function(data) {
                $("#all_attendess_list_container").html(data);
                applySticky();
            }
        });
    }

    $(document).ready(function() {
       $('.event_detail_icon a').popover({trigger: "hover",  container :'#event_popover'});
       $('.event_detail_icon span.everyday').popover({trigger: "hover",  container :'#event_popover'});
    });
</script>
<div class="col-lg-3 ">

</div>


