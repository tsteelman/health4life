<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
} elseif (isset($refer) && (is_int($refer) || ($refer == 'c'))) {
	$diseaseUrl = '/condition/index/' . $communityDisease['Disease']['id'];
	$this->Html->addCrumb($communityDisease['Disease']['name'], $diseaseUrl);
	$this->Html->addCrumb('Communities', $diseaseUrl . '/communities');
} else {
    $this->Html->addCrumb('Community', '/community');
}
$this->Html->addCrumb(h($community['Community']['name']));
?>
<div class="container">
    <div class="group">
        <div class="row">
            <div class="col-lg-12">
                <div class="event_list">
                      <?php
                if ($isInvited) {
                    echo $this->element('approve_reject_invitation');
                }
                ?>
			
                    <div class="event_wraper detail_page">
                        <div class="profile_container">
                            <!--                            <div class="row">
                                                                                                                            </div>-->
                            <div class="row">
                                <div class="col-lg-4 profile_info">
                                    <div class="date_and_conformation">
                                        <div class="date_div">
                                            Founded On <?php echo CakeTime::nice($community['Community']['created'], $timezone, '%B %e, %Y'); ?>
                                        </div>                                        
                                    </div>
                                    <div class="response_tile">
                                    <div class="image_container"><?php echo $this->Html->image(Common::getCommunityThumb($community['Community']['id'], 'large'), array('class' => 'img-responsive')); ?></div>
                                    <div class="event_name">
                                        <h3><?php echo h($community['Community']['name']); ?></h3>
                                    <div class="community_leader">Community Leader <span class="owner">
                                                                                    <a href="<?php echo Common::getUserProfileLink($creator['user_name'], TRUE); ?>" 
                                                                                   accesskey="" data-hovercard="<?php echo $creator['user_name']; ?>">
                                                                                    <?php echo __(h($creator['user_name'])); ?>
                                                                                    </a> </span>
                                    </div>
                                    <?php
                                        if(trim($community['Community']['description']) != "") {
                                        $communityDescription = Common::truncate($community['Community']['description'], 95);
                                    ?>                        
                                        <div class="event_decrptionn" title="<?php echo h($communityDescription['title']); ?>">
                                            <?php 
                                                echo  h($communityDescription['name']); 
                                            ?>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                        
                                         <div class="event_type_notifier"> 
                                        <div class="join_community"> 
                                            <?php
                                            if (!($user['id'] === $creator['user_id'])) {
                                                if (isset($userStatus['CommunityMember']['status'])) {

                                                    switch ($userStatus['CommunityMember']['status']) {
                                                        case 1:
                                                            ?>

                                                            <button id="status" class="btn pull-left btn_leave ladda-button" data-style="expand-right" data-spinner-color="#3581ED" onclick="setUserStatus(<?php echo $community['Community']['id']; ?>,<?php echo $user['id']; ?>, 1)"><span class="ladda-label"><?php echo __('Leave'); ?></span><span class="ladda-spinner"></span></button>

                                                            <?php
                                                            break;
                                                        case 2:
                                                            ?>

                                                            <button id="status" class="btn pull-left btn_active" disabled><?php echo __('Waiting for approval'); ?></button>

                                                            <?php
                                                            break;
                                                    }
                                                } else {
                                                    ?>

                                                    <button id="status" class="btn pull-left btn_leave ladda-button" data-style="expand-right" data-spinner-color="#3581ED" onclick="setUserStatus(<?php echo $community['Community']['id']; ?>,<?php echo $user['id']; ?>, '')"><span class="ladda-label"><?php echo __('Join'); ?></span></button>

                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                               
                                    </div>                           
                                                                   
                                    
                                </div>
                                    </div>
                                     <?php
                                        if ($creator['user_id'] === $user['id']) {
                                    ?>
                                       
                                    
                                    
                                        <div class="event_detail_icon"> 

                                            <span class="btn-toolbar pull-right">
                                                <span class="btn-group pull-right">
                                                    <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown" style="margin: -5px 0px 0px;">                                                            
                                                        <div class="edit_common edit_common_default"></div>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li><a href="/community/edit/<?php echo $community['Community']['id']; ?>"><?php echo __('Edit Community'); ?></a></li>
                                                        <li><a id="delete_community_button" href="/community/details/delete/<?php echo $community['Community']['id']; ?>"><?php echo __('Delete Community'); ?></a></li>                                                    
                                                    </ul>
                                                </span>
                                            </span>
                                        </div>
                                    <?php
                                    }
                                    ?> 
                            </div>

                                <div class="col-lg-8 profile_cover_photo" id="cover">
                                    <?php if ( $creator['user_id'] === $user['id'] ) {echo $this->element('profile_cover_settings');} ?>
                                    <?php echo $this->element('cover_slideshow'); ?>
                                    <div id="cover_image_container" >
                                        <img src="<?php echo $defaultPhoto; ?>" />
                                    </div>
                                    <?php if ( $creator['user_id'] === $user['id'] ) { ?>
                                        <a href="javascript:void(0);" class="change_coverpage" id="btn_changeCover"></a>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                        </div>
                        <?php
                            if (((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1')) || ($community['Community']['type'] == '1')) {
                        ?>

                            <div class="group_options">
                                <nav class="navbar navbar-default" role="navigation">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>

                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                                        <div class="group_catagories pull-left">
                                            <ul class="subtabs_list">
                                                <?php
                                                if (!empty($section_list)) {
                                                    foreach ($section_list as $sec_url => $sec_name) {
                                                        $sel_class = $section == $sec_url ? "current" : "";
                                                        echo '<li class="' . $sel_class . '"><a href="' . $community_detail_url . '/' . $sec_url . '">' . $sec_name . '</a></li>';
                                                        $sel_class = "";
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>

                                    </div><!-- /.navbar-collapse -->
                                </nav>
                            </div>
                        <?php } ?>

                </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
<!--                    <div class="event_wraper">
                        <div class="profile_container">
                            <div class="row">
                                <div class="col-lg-4 profile_info">                                   
                                    <?php // echo $this->Html->image(Common::getCommunityThumb($community['Community']['id']), array('class' => 'img-responsive')); ?>
                                    <h3><?php // echo h($community['Community']['name']); ?></h3>
                                    <div> 
                                        <span class="group_info">Members <span id="group_members_count" class="group_members"><?php // echo __('(' . $community['Community']['member_count'] . ')'); ?></span></span>
                                        <span class="group_info">Events <span class="group_members"> <?php // echo __('(' . $community['Community']['event_count'] . ')'); ?></span></span>
                                        
                                    </div>
                                    
                                </div>
                                 <div class="col-lg-8 profile_cover_photo" id="cover">
                                    <?php // if ( $creator['user_id'] === $user['id'] ) {echo $this->element('profile_cover_settings');} ?>
                                    <?php // echo $this->element('cover_slideshow'); ?>
                                    <div id="cover_image_container" >
                                        <img src="<?php // echo $defaultPhoto; ?>" />
                                    </div>
                                      <div class="cover_message_container">
                                        <div class="event_type_container">
                                            <div class="col-lg-9">
                                                     <div class="founder pull-left"><span>Founded on :</span><span><?php // echo CakeTime::nice($community['Community']['created'], $timezone, '%B %e, %Y'); ?></span></div>
                                             <div class="founder pull-left"><span>Community leader :</span><span ><a href="<?php // echo Common::getUserProfileLink($creator['user_name'], TRUE); ?>" 
                                           data-hovercard="<?php // echo $creator['user_name']; ?>">
                                               <?php // echo __(h($creator['user_name'])); ?>
                                                </a> </span></div>		
                                            </div>
                                            <div class="col-lg-3">
                                               <?php
//                                        if (!($user['id'] === $creator['user_id'])) {
//                                            if (isset($userStatus['CommunityMember']['status'])) {

//                                                switch ($userStatus['CommunityMember']['status']) {
//                                                    case 1:
                                                        ?>

                                                        <button id="status" class="btn pull-right btn_leave ladda-button" data-style="expand-right" data-spinner-color="#3581ED" onclick="setUserStatus(<?php // echo $community['Community']['id']; ?>,<?php // echo $user['id']; ?>)"><span class="ladda-label"><?php // echo __('Leave'); ?></span><span class="ladda-spinner"></span></button>

                                                        <?php
//                                                        break;
//                                                    case 2:
                                                        ?>

                                                        <button id="status" class="btn pull-right btn_active" disabled><?php // echo __('Waiting for approval'); ?></button>

                                                        <?php
//                                                        break;
//                                                }
//                                            } else {
                                                ?>

                                                <button id="status" class="btn pull-right btn_leave ladda-button" data-style="expand-right" data-spinner-color="#3581ED" onclick="setUserStatus(<?php // echo $community['Community']['id']; ?>,<?php // echo $user['id']; ?>)"><span class="ladda-label"><?php // echo __('Join'); ?></span></button>

                                                <?php
//                                            }
//                                        }
                                        ?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="settings_field">
                                         <?php
//                                    if ($creator['user_id'] === $user['id']) {
                                        ?>

                                        <span class="btn-toolbar pull-right">
                                            <span class="btn-group pull-right">
                                                <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown" style="margin: -5px 0px 0px;">                                                            
                                                    <div class="edit_common edit_common_default"></div>
                                                </button>

                                                <ul class="dropdown-menu">
                                                    <li><a href="/community/edit/<?php // echo $community['Community']['id']; ?>"><?php // echo __('Edit Community'); ?></a></li>
                                                    <li><a id="delete_community_button" href="/community/details/delete/<?php // echo $community['Community']['id']; ?>"><?php echo __('Delete Community'); ?></a></li>
                                                    <li><a href="javascript:void(0);" id="btn_changeCover">Change Cover</a></li>
                                                </ul>
                                            </span>
                                        </span>

                                        <?php
//                                    }
                                    ?>
                                     </div>
                                </div>
                            </div>
                            
                        <?php
//                        if(trim($community['Community']['description']) != "") {
                        ?>                        
                        <div class="event_settings row">                            
                            <span class="">Description:</span>
                            <?php // echo nl2br(h($community['Community']['description'])); ?>
                        </div>
                        <?php
//                        }
                        ?>
                        </div>
                        <?php
//                        if (((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1')) || ($community['Community']['type'] == '1')) {
                        ?>
                    
                        <div class="group_options">
                            <nav class="navbar navbar-default" role="navigation">
                                 Brand and toggle get grouped for better mobile display 
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                 Collect the nav links, forms, and other content for toggling 
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                                    <div class="group_catagories pull-left">
                                        <ul>
                                            <?php
//                                            if (!empty($section_list)) {
//                                                foreach ($section_list as $sec_url => $sec_name) {
//                                                    $sel_class = $section == $sec_url ? "current" : "";
//                                                    echo '<li class="' . $sel_class . '"><a href="' . $community_detail_url . '/' . $sec_url . '">' . $sec_name . '</a></li>';
//                                                    $sel_class = "";
//                                                }
//                                            }
                                            ?>
                                        </ul>
                                    </div>

                                </div> /.navbar-collapse 
                            </nav>
                        </div>
                        <?php // } ?>
                    </div>-->
                </div>
            </div>
        </div>
        <div class="row mr_0">
            <div class="col-lg-9">
                
<!--			Community Details
                <div class="event_wraper">
                    <div class="group_descrption">
                        <div class="row">
                            <div class="col-lg-4 ">
                                <?php // echo $this->Html->image(Common::getCommunityThumb($community['Community']['id']), array('class' => 'img-responsive')); ?>
                                <span class="pull-left group_info"><?php // echo __('Members'); ?><span id="group_members_count" class="group_members"> <?php echo __('(' . $community['Community']['member_count'] . ')'); ?></span></span>
                                <span class="pull-left group_info"><?php // echo __('Events'); ?> <span class="group_members"> <?php // echo __('(' . $community['Community']['event_count'] . ')'); ?></span></span></div>
                            <div class="col-lg-8  ">
                                <div class="block">
                                    <h2 class="pull-left"><?php // echo h($community['Community']['name']); ?></h2>

                                    <?php
//                                    if (!($user['id'] === $creator['user_id'])) {
//                                        if (isset($userStatus['CommunityMember']['status'])) {

//                                            switch ($userStatus['CommunityMember']['status']) {
//                                                case 1:
                                                    ?>

                                                    <button id="status" class="btn pull-right btn_leave ladda-button" data-style="expand-right" data-spinner-color="#3581ED" onclick="setUserStatus(<?php echo $community['Community']['id']; ?>,<?php echo $user['id']; ?>)"><span class="ladda-label"><?php echo __('Leave'); ?></span><span class="ladda-spinner"></span></button>

                                                    <?php
//                                                    break;
//                                                case 2:
                                                    ?>

                                                    <button id="status" class="btn pull-right" disabled><?php // echo __('Waiting for approval'); ?></button>

                                                    <?php
//                                                    break;
//                                            }
//                                        } else {
                                            ?>

                                            <button id="status" class="btn pull-right btn_leave ladda-button" data-style="expand-right" data-spinner-color="#3581ED" onclick="setUserStatus(<?php // echo $community['Community']['id']; ?>,<?php echo $user['id']; ?>)"><span class="ladda-label"><?php echo __('Join'); ?></span></button>

                                            <?php
//                                        }
//                                    }
                                    ?>

                                </div>
                                <p><label><?php // echo __('Founded on '); ?></label><span class="owner"><?php // echo CakeTime::nice($community['Community']['created'], $timezone, '%B %e, %Y'); ?></span></p>
                                <p>
                                    <label><?php // echo __('Community leader '); ?></label>
                                    <a href="<?php // echo Common::getUserProfileLink($creator['user_name'], TRUE); ?>" 
                                       data-hovercard="<?php // echo $creator['user_name']; ?>" class="owner">
                                           <?php // echo __(h($creator['user_name'])); ?>
                                    </a>
                                </p>
                                <p><?php // echo nl2br(h($community['Community']['description'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php
//                    if (((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1')) || ($community['Community']['type'] == '1')) {
                        ?>

                        <div class="group_options">
                            <nav class="navbar navbar-default" role="navigation">
                                 Brand and toggle get grouped for better mobile display 
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                 Collect the nav links, forms, and other content for toggling 
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                                    <div class="group_catagories pull-left">
                                        <ul>
                                            <?php
//                                            if (!empty($section_list)) {
//                                                foreach ($section_list as $sec_url => $sec_name) {
//                                                    $sel_class = $section == $sec_url ? "current" : "";
//                                                    echo '<li class="' . $sel_class . '"><a href="' . $community_detail_url . '/' . $sec_url . '">' . $sec_name . '</a></li>';
//                                                    $sel_class = "";
//                                                }
//                                            }
                                            ?>
                                        </ul>
                                    </div>

                                    <?php
//                                    if ($creator['user_id'] === $user['id']) {
                                        ?>

                                        <div class="btn-toolbar">
                                            <div class="btn-group pull-right">
                                                <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">                                                            
                                                    <div class="edit_common edit_common_default"></div>
                                                </button>

                                                <ul class="dropdown-menu">
                                                    <li><a href="/community/edit/<?php // echo $community['Community']['id']; ?>"><?php // echo __('Edit Community'); ?></a></li>
                                                    <li><a id="delete_community_button" href="/community/details/delete/<?php // echo $community['Community']['id']; ?>"><?php // echo __('Delete Community'); ?></a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <?php
//                                    }
                                    ?>

                                </div> /.navbar-collapse 
                            </nav>
                        </div>

                        <?php
//                    }
                    ?>
                </div>-->
				
               

                <?php
                if (((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1')) || ($community['Community']['type'] == '1')) {
                    ?>
                    <div id="group-section-alert" class="alert" style="display:none;">
                        <button class="close" type="button">×</button>
                        <div class="alert-content"></div>
                    </div>                    
                    <div id="group_element_container">
                        <?php echo $this->element('Community.Details/' . $section); ?>
                    </div>

                    <?php
                }
                ?>

            </div>
            <div class="col-lg-3" id="rhs">
                <div class="event_lhs">

                    <?php
//                    if ((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1')) {
//                        ?>

<!--                        <div id="cmty_latest_members" class="member_list">
                            <h4>Latest Members</h4>
                            <div class="row content">
                                <div class="text-center">//<?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
                            </div>
                        </div>-->

                        <?php
//                    }?>
                    <div id="community_members_online" class="member_list disease_rhs" data-community-id="<?php echo $community['Community']['id']; ?>">
                        <h4>Online Members (<?php echo $onlineMembersCount;?>)</h4>
                        <div class="row content details_container">
                            <?php echo $this->element('Community.Details/online_members'); ?>
                            <!--<div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));               ?></div>-->
                        </div>
                    </div>
                    <div id="profile_friends_online" class="member_list disease_rhs">
                        <h4>Online Friends (<?php echo $onlineFriendsCount;?>)</h4>
                        <div class="row content details_container">
                            <?php echo $this->element('User.Profile/online_friends'); ?>
                            <!--<div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));               ?></div>-->
                        </div>
                    </div>
                    <?php
                    echo $this->element('ads');
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->AssetCompress->script('community_events.js', array('block' => 'scriptBottom'));
$this->AssetCompress->addScript('vendor/jquery.dotdotdot.min.js');
?>
<script>
<?php
if ((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1') || ($community['Community']['type'] == '1')) {
    ?>
//    if ((isset($userStatus['CommunityMember']['status'])) && ($userStatus['CommunityMember']['status'] == '1')) {
//        ?>
//                                                $(function() {
//                                                    load_recent_members_list(//<?php // echo $community['Community']['id']; ?>);
//                                                });
//                                                function load_recent_members_list(community_id) {
//                                                    $("#cmty_latest_members .content").html('<div class="text-center">//<?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>');
//                                                    $.ajax({
//                                                        url: '/community/details/updateRecentMembersList/' + community_id,
//                                                        dataType: 'json',
//                                                        success: function(result) {
//                                                            $("#cmty_latest_members .content").html(result['htm_content']);
//                                                        }
//                                                    });
//                                                }
<?php // } ?>
        function load_events(community_id, event_type, page) {
            if (typeof (page) === "undefined") {
                page = 1;
            }
            if (page != 1) {
                var l = Ladda.create(document.querySelector('#load-more'));
                l.start();
            }
            var id;
            if (event_type == 6) {
                id = 'upcoming_events_list';
            } else if (event_type == 7) {
                id = 'past_events_list';
            }
            if (id === 'upcoming_events_list' || id === 'past_events_list') {

                $.ajax({
                    url: '/community/details/getEventList/' + community_id + '/' + '' + '/page:' + page,
                    dataType: 'json',
                    success: function(result) {
                        $("#more_button"+event_type).remove();
                        if (page == 1) {
                            $("#" + "upcoming_events_list" + " #event_list").html(result['htm_content']);
                            if ($('#upcoming_events_list .indvdl_event').length) {
                                $("#createButton").removeClass('hidden');
                                $('#upcoming_events_list .page-header').removeClass('hide');
                            } else {
//                                $('#upcoming_events_list .page-header').addClass('hide');
                            }
                            $("#createButton").removeClass('hidden');
                            $('#upcoming_events_list .page-header').removeClass('hide');
                        } else {
                            $("#" + "upcoming_events_list" + " #event_list").append(result['htm_content']);
                        }
                        $('#' + "upcoming_events_list" + ':has(.indvdl_event)').removeClass('hidden');
                        if (result.paginator.nextPage == true) {
                            $('#' + id).append('<div id="more_button' + event_type + '" class="block">' +
                                    '<a href="javascript:load_events(' + community_id + ',' + event_type + ',' + (result.paginator.page + 1) + ');" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label"><?php echo __('More'); ?></span></a>' +
                                    '</div>');
                        }
                        applyHoverEffect();
                    }
                }).always(function() {
                    if (page != 1) {
                        l.stop();
                    }
                });;
            }
        }
    <?php
}
?>
                                        function updateCommunityMemberStatus(status, user_id, _this) {
                                            var result;
                                            var _self = _this;
                                            var y = null;

                                            $("#reject_" + user_id).attr('disabled', 'disabled');
                                            $("#add_" + user_id).attr('disabled', 'disabled');

                                            $(_self).closest("div.btn-group").children("button").addClass("disabled");
                                            $(_self).closest("div.media-body").children("button").addClass("disabled");
                                            if ($(_self).hasClass("group-member-approve-reject-btn")) {
                                                $(_self).css("background-color", "#2c589e");
                                            }
                                            if (status != 'update_admin') {
                                                y = Ladda.create(_self);
                                                y.start();
                                            }
                                            var community_id = $("#group-id").val();
                                            $.ajax({
                                                cache: false,
                                                url: '/community/details/updateCommunityMemberStatus/community_id:' + community_id + '/status:' + status + '/user_id:' + user_id,
                                                dataType: 'json',
                                                beforeSend: function() {
                                                },
                                                success: function(data) {
                                                    if (y != null) {
                                                        y.stop();
                                                    }
                                                    if (data.success == "success") {
                                                        if (y != null) {
                                                            y.stop();
                                                            $(_self).attr("disabled", "disabled");
//                                                                $(_self).css({
//                                                                    'color': '#fff!important',
//                                                                    'border': '1px solid #004f7f',
//                                                                    'background-color': '#2c589e'
//                                                                });
                                                        }
                                                        updateCommunityMembersTab();
                                                        load_recent_members_list(community_id);
                                                        if (typeof data.member_count != 'undefined') {
                                                            $("#group_members_count").html(' (' + data.member_count + ')');
                                                        }
                                                    }
                                                    showMemberAlert(data.message, data.success);
                                                }
                                            });
                                        }
                                        function updateCommunityMembersTab() {
                                            var community_id = $("#group-id").val();
                                            $.ajax({
                                                url: '/community/details/getCommunityMembersTab/community_id:' + community_id,
                                                dataType: 'json',
                                                beforeSend: function() {
                                                },
                                                success: function(data) {
                                                    $("#group_element_container").html(data);
                                                }
                                            });
                                        }
        $(document).ready( function(){
            $('.event_name h3').dotdotdot({ height : 46});
            $('.event_name .event_decrptionn').dotdotdot({ height : 40});
        });
</script>
