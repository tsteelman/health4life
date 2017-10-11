This event belongs to the community <a href="/community/details/index/<?php echo $communityId; ?>"><?php echo $communityName; ?></a>.
In order to attend this event, you need to be a member of this community.
Please click on the 'Join' button to join the community.
<br /><br />
<button id="status" class="btn btn_leave ladda-button" data-style="expand-right" 
    data-spinner-color="#3581ED" 
    onclick="setUserStatus(<?php echo $communityId; ?>, <?php echo $user['user_id']; ?>)">
    <span class="ladda-label"><?php echo __('Join'); ?></span>
    <span class="ladda-spinner"></span>
</button>