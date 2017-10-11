<div id="upcoming_events_list">
    <?php
        if (isset($userStatus['CommunityMember']['status'])) {
            if($creator['user_id'] === $user['id'] || 
                    $userStatus['CommunityMember']['user_type'] == CommunityMember::USER_TYPE_ADMIN) { ?>
                <div class="page-header hide">
                    <h3 class="pull-left"> &nbsp;</h3>
                    <?php  echo $this->Html->link('Create New Event', "/community/{$community['Community']['id']}/event/add", array('id' => 'createButton',
                'class' => 'pull-right btn create_button hidden')); ?>
                </div>
            <?php 
            }
        } ?>
    
    <div id="event_list">
        <div class="text-center"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
        <?php // echo $this->element('Event.events_row'); ?>
    </div>
</div>

<!--div id="past_events_list" class="hidden">
    <div class="page-header">
        <h3>Past Events</h3>
    </div>
    <div id="event_list">
        <div class="text-center"><?php //echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
    </div>
</div-->

<script>
    $(function() {
                load_events(<?php echo $community['Community']['id']; ?>, '6', '1', 'upcoming_events_list');
    });
</script>