<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Manage Users', '/admin/users');
$this->Html->addCrumb($user['User']['username']);
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1 class="blue">
            <span class="middle">
                <?php echo __($user['User']['first_name'] . ' ' . $user['User']['last_name']); ?>
            </span>

            <span class="middle label label-purple arrowed-in-right">
                <?php
                if ($online_status) {
                    ?>
                    <i class="icon-circle smaller-80 online"></i>
                    online
                    <?php
                } else {
                    ?>
                    <i class="icon-circle smaller-80 offline"></i>
                    offline
                    <?php
                }
                ?>
            </span>
        </h1>
    </div>
    <div id="user_profile" class="user-profile row-fluid">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-18">
                <li class="active">
                    <a data-toggle="tab" href="#home">
                        <i class="green icon-user bigger-120"></i>
                        Profile
                    </a>
                </li>

                <li class="hidden">
                    <a data-toggle="tab" href="#feed">
                        <i class="orange icon-rss bigger-120"></i>
                        Activity Feed
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#friends">
                        <i class="blue icon-group bigger-120"></i>
                        <?php echo __('Friends (' . count($friends_list) . ')'); ?>
                    </a>
                </li>

                <li class="hidden">
                    <a data-toggle="tab" href="#pictures">
                        <i class="pink icon-picture bigger-120"></i>
                        Pictures
                    </a>
                </li>
            </ul>

            <div class="tab-content no-border padding-24">
                <div id="home" class="tab-pane in active">
                    <div class="row-fluid">
                        <div class="span2">
                            <div class="space space-32"></div>
                            <?php echo Common::getUserThumb($user['User']['id'], $user['User']['type'], 'medium', 'profile_brdr_5', 'img');
                            ?>
                        </div><!--/span-->

                        <div class="span9">

                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"><?php echo __('Username'); ?></div>

                                    <div class="profile-info-value">
                                        <span><?php echo __(h($user['User']['username'])); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Email'); ?> </div>

                                    <div class="profile-info-value">
                                        <span><?php echo __(h($user['User']['email'])); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Location'); ?> </div>

                                    <div class="profile-info-value">
                                        <i class="icon-map-marker light-orange bigger-110"></i>
                                        <span><?php echo __(h($location)); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Age'); ?> </div>

                                    <div class="profile-info-value">
                                        <span><?php echo __($user['User']['age']); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Type'); ?> </div>

                                    <div class="profile-info-value">
                                        <span>
                                            <?php
                                            switch ($user['User']['type']) {
                                                case '1':
                                                    $type = 'Patient ';
                                                    break;
                                                case '2':
                                                    $type = 'Family ';
                                                    break;
                                                case '3':
                                                    $type = 'Caregiver ';
                                                    break;
                                                case '4':
                                                    $type = 'Other ';
                                                    break;
                                                default :
                                                    $type = 'Not Set';
                                                    break;
                                            }
                                            echo __(h($type));
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Joined'); ?> </div>

                                    <div class="profile-info-value">
                                        <span><?php echo __(date('M d, Y', strtotime($user['User']['created']))); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Last Online'); ?> </div>

                                    <div class="profile-info-value">
                                        <span><?php
                                            echo $this->Time->timeAgoInWords($user['User']['last_login_datetime'], array(
                                                'format' => 'F jS, Y',
                                                'accuracy' => array('hour' => 'hour'),
                                                'end' => '+1 year'
                                            ));
                                            ?></span>
                                    </div>
                                </div>

                                <?php if (isset($user['User']['about_me']) && !empty($user['User']['about_me'])) {
                                    ?>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> <?php echo __('About Me'); ?> </div>

                                        <div class="profile-info-value">
                                            <span><?php echo __(h($user['User']['about_me'])); ?></span>
                                        </div>
                                    </div>
                                <?php }
                                ?>

                            </div>

                            <div class="hr hr-8 dotted"></div>
                        </div><!--/span-->
                    </div><!--/row-fluid-->

                    <div class="space-20"></div>

                    <div class="row-fluid">
                        <div class="span4 widget-container-span ui-sortable">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h5 class="smaller"><?php echo __('Conditions'); ?></h5>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-6">
                                        <?php
                                        if (isset($user_diseases) && !empty($user_diseases)) {
                                            ?>
                                            <ul>
                                                <?php
                                                foreach ($user_diseases as $disease) {
                                                    ?>
                                                    <li><?php echo __($disease); ?></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        } else {
                                            ?>
                                            <div> <?php echo __('No conditions found'); ?> </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="span4 widget-container-span ui-sortable">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h5 class="smaller"><?php echo __('Symptoms'); ?></h5>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-6">
                                        <?php
                                        if (isset($user_symptoms) && !empty($user_symptoms)) {
                                            ?>
                                            <ul>
                                                <?php
                                                foreach ($user_symptoms as $symptom) {
                                                    ?>
                                                    <li><?php echo __($symptom); ?></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        } else {
                                            ?>
                                            <div> <?php echo __('No Symptoms found'); ?> </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="span4 widget-container-span ui-sortable">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h5 class="smaller"><?php echo __('Treatments'); ?></h5>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-6">
                                        <?php
                                        if (isset($user_treatments) && !empty($user_treatments)) {
                                            ?>
                                            <ul>
                                                <?php
                                                foreach ($user_treatments as $treatment) {
                                                    ?>
                                                    <li><?php echo __($treatment); ?></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        } else {
                                            ?>
                                            <div> <?php echo __('No treatment found'); ?> </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!--#home-->

                <!-----------------Feeds Tab detail view------------>
                <div id="feed" class="tab-pane">
                    <div class="profile-feed row-fluid">
                        <div class="span6">
                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="Alex Doe's avatar" src="assets/avatars/avatar5.png">
                                    <a class="user" href="#"> Alex Doe </a>
                                    changed his profile photo.
                                    <a href="#">Take a look</a>

                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        an hour ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="Susan Smith's avatar" src="assets/avatars/avatar1.png">
                                    <a class="user" href="#"> Susan Smith </a>

                                    is now friends with Alex Doe.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        2 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <i class="pull-left thumbicon icon-ok btn-success no-hover"></i>
                                    <a class="user" href="#"> Alex Doe </a>
                                    joined
                                    <a href="#">Country Music</a>

                                    group.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        5 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <i class="pull-left thumbicon icon-picture btn-info no-hover"></i>
                                    <a class="user" href="#"> Alex Doe </a>
                                    uploaded a new photo.
                                    <a href="#">Take a look</a>

                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        5 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="David Palms's avatar" src="assets/avatars/avatar4.png">
                                    <a class="user" href="#"> David Palms </a>

                                    left a comment on Alex's wall.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        8 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>
                        </div><!--/span-->

                        <div class="span6">
                            <div class="profile-activity clearfix">
                                <div>
                                    <i class="pull-left thumbicon icon-edit btn-pink no-hover"></i>
                                    <a class="user" href="#"> Alex Doe </a>
                                    published a new blog post.
                                    <a href="#">Read now</a>

                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        11 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="Alex Doe's avatar" src="assets/avatars/avatar5.png">
                                    <a class="user" href="#"> Alex Doe </a>

                                    upgraded his skills.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        12 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <i class="pull-left thumbicon icon-key btn-info no-hover"></i>
                                    <a class="user" href="#"> Alex Doe </a>

                                    logged in.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        12 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <i class="pull-left thumbicon icon-off btn-inverse no-hover"></i>
                                    <a class="user" href="#"> Alex Doe </a>

                                    logged out.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        16 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="profile-activity clearfix">
                                <div>
                                    <i class="pull-left thumbicon icon-key btn-info no-hover"></i>
                                    <a class="user" href="#"> Alex Doe </a>

                                    logged in.
                                    <div class="time">
                                        <i class="icon-time bigger-110"></i>
                                        16 hours ago
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="icon-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="icon-remove bigger-125"></i>
                                    </a>
                                </div>
                            </div>
                        </div><!--/span-->
                    </div><!--/row-->

                    <div class="space-12"></div>

                    <div class="center">
                        <a href="#" class="btn btn-small btn-primary">
                            <i class="icon-rss bigger-150 middle"></i>

                            View more activities
                            <i class="icon-on-right icon-arrow-right"></i>
                        </a>
                    </div>
                </div><!--/#feed-->

                <!-----------------Friends Tab detail view------------>
                <div id="friends" class="tab-pane">
                    <div class="profile-users clearfix">

                        <?php
                        if(!empty($friends_list)){
                        foreach ($friends_list as $friend) {
                            ?>
                            <div class="itemdiv memberdiv">
                                <div class="inline position-relative">
                                    <div class="user">
                                        <a href="/admin/Users/view/<?php echo $friend['friend_name']; ?>">
                                            <?php echo Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'small', 'profile_brdr_5', 'img');
                                            ?>
                                            <!--<img src="assets/avatars/avatar4.png" alt="Bob Doe's avatar">-->
                                        </a>
                                    </div>
                                    <div class="body">
                                        <div class="name">
                                            <a href="/admin/Users/view/<?php echo $friend['friend_name']; ?>">
                                                <?php 
                                                    echo __(h($friend['friend_name']));
                                                ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        } else {
                            echo __(h('No friends found'));
                        }
                        ?>

                    </div>

                    <div class="hr hr10 hr-double"></div>
                </div><!--/#friends-->

                <div id="pictures" class="tab-pane">
                    <ul class="ace-thumbnails">
                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-1.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-2.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-3.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-4.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-5.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-6.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-1.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>

                        <li>
                            <a href="#" data-rel="colorbox">
                                <img alt="150x150" src="assets/images/gallery/thumb-2.jpg">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="icon-link"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-paper-clip"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="icon-remove red"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div><!--/#pictures-->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#users-li a").trigger('click');
        $("ul.nav-list li").removeClass('active');
        $("#user-list-li").addClass('active');
     });
</script>