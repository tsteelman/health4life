<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Events', '/admin/events');
$this->Html->addCrumb($event_details['Event']['name']);
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1 class="blue">
            <span class="middle">
                <?php echo __($event_details['Event']['name']); ?>
            </span>

            <span class="middle label label-purple arrowed-in-right">
                <?php
                if ($event_details['Event']['start_date'] < $now) {
                    ?>
                    <i class="icon-circle smaller-80"></i>
                    <?php
                    echo __('Past Event');
                } else if ($event_details['Event']['start_date'] > $now) {
                    ?>
                    <i class="icon-circle smaller-80"></i>
                    <?php
                    echo __('Upcoming Event');
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
                        <i class="green icon-calendar bigger-120"></i>
                        <?php echo __('Event Detais'); ?>
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#members">
                        <i class="blue icon-group bigger-120"></i>
                        <?php echo __('Members (' . (count($attending_members)+count($maybe_members)+count($invited_members)) . ')'); ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content no-border padding-24">
                <div id="home" class="tab-pane in active">
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="space"></div>
                            <?php echo $this->Html->image(Common::getEventThumb($event_details['Event']['id']), array('class' => 'img-responsive')); ?>
                        </div><!--/span-->

                        <div class="span7">

                            <div class="profile-user-info">

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Type'); ?> </div>

                                    <div class="profile-info-value">
                                        <?php
                                        switch ($event_details['Event']['event_type']) {
                                            case Event::EVENT_TYPE_PUBLIC :
                                                $type = 'Public';
                                                break;
                                            case Event::EVENT_TYPE_PRIVATE :
                                                $type = 'Private';
                                                break;
                                            case Event::EVENT_TYPE_SITE :
                                                $type = 'Site Wide Event';
                                                break;
                                        }
                                        ?>
                                        <span><?php echo __(h($type)); ?></span>
                                    </div>
                                </div>
                                
                                <div class="profile-info-row">
                                    <div class="profile-info-name"><?php echo __("Creator"); ?></div>

                                    <div class="profile-info-value">
                                        <span>
                                            <?php echo __(h($creator['User']['username'])); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"><?php echo __("Disease"); ?></div>

                                    <div class="profile-info-value">
                                        <span>
                                            <?php echo __(h(implode(', ', $disease_names))); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"><?php echo __("Community"); ?></div>

                                    <div class="profile-info-value">
                                        <span>
                                            <?php
                                            if ($event_details['Event']['community_id'] == NULL) {
                                                echo __('No');
                                            } else {
                                                echo __(h($event_details['Community']['name']));
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Location'); ?> </div>

                                    <div class="profile-info-value">
                                        <i class="icon-map-marker light-orange bigger-110"></i>
                                        <span>
                                            <?php
                                            if ($event_details['Event']['location'] != '') {
                                                echo __(h($event_details['Event']['location']));
                                            } else if ($event_details['Event']['online_event_details']) {
                                                echo __(h($event_details['Event']['online_event_details']));
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Start Date'); ?> </div>

                                    <div class="profile-info-value">
                                        <span><?php echo Date::getUSFormatDateTime($event_details['Event']['start_date'], $timezone); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('End Date'); ?> </div>

                                    <div class="profile-info-value">
                                        <span>
                                            <span><?php echo Date::getUSFormatDateTime($event_details['Event']['end_date'], $timezone) ?></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Description'); ?> </div>

                                    <div class="profile-info-value">
                                        <span>
                                            <span>
                                                <?php 
                                                if($event_details['Event']['description'] == '') {
                                                    echo __('No description');
                                                } else {
                                                    echo __(h($event_details['Event']['description'])); 
                                                }
                                                ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="hr hr-8 dotted"></div>
                        </div><!--/span-->
                    </div><!--/row-fluid-->
                </div><!--#home-->

                <!-----------------Members Tab detail view------------>
                <div id="members" class="tab-pane">
                    <div class="profile-users clearfix">

                        <div id="accordion2" class="accordion">
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                                        <?php echo __('Attending Members (' . count($attending_members) . ')'); ?>
                                    </a>
                                </div>

                                <div class="accordion-body collapse" id="collapseOne">
                                    <div class="accordion-inner">
                                        <?php
                                        if (!empty($attending_members)) {
                                            foreach ($attending_members as $member) {
                                                ?>
                                                <div class="itemdiv memberdiv">
                                                    <div class="inline position-relative">
                                                        <div class="user">
                                                            <a href="/admin/Users/view/<?php echo $member['User']['username']; ?>">
                                                                <?php echo Common::getUserThumb($member['User']['id'], $member['User']['type'], 'small', 'profile_brdr_5', 'img');
                                                                ?>
                                                            </a>
                                                        </div>
                                                        <div class="body">
                                                            <div class="name">
                                                                <a href="/admin/Users/view/<?php echo $member['User']['username']; ?>">
                                                                    <?php
                                                                    echo __(h($member['User']['username']));
                                                                    ?>
                                                                </a>
                                                                <div>
                                                                    <?php echo Date::getUSFormatDate($member['Modified'], $timezone);?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo __(h('No members found'));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseTwo" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                                        <?php echo __('Maybe Attending Members (' . count($maybe_members) . ')'); ?>
                                    </a>
                                </div>

                                <div class="accordion-body collapse" id="collapseTwo">
                                    <div class="accordion-inner">
                                        <?php
                                        if (!empty($maybe_members)) {
                                            foreach ($maybe_members as $member) {
                                                ?>
                                                <div class="itemdiv memberdiv">
                                                    <div class="inline position-relative">
                                                        <div class="user">
                                                            <a href="/admin/Users/view/<?php echo $member['User']['username']; ?>">
                                                                <?php echo Common::getUserThumb($member['User']['id'], $member['User']['type'], 'small', 'profile_brdr_5', 'img');
                                                                ?>
                                                            </a>
                                                        </div>
                                                        <div class="body">
                                                            <div class="name">
                                                                <a href="/admin/Users/view/<?php echo $member['User']['username']; ?>">
                                                                    <?php
                                                                    echo __(h($member['User']['username']));
                                                                    ?>
                                                                </a>
                                                                <div>
                                                                    <?php echo Date::getUSFormatDate($member['Modified'], $timezone);?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo __(h('No members found'));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseThree" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                                        <?php echo __('Invited Members (' . count($invited_members) . ')'); ?>
                                    </a>
                                </div>

                                <div class="accordion-body collapse" id="collapseThree">
                                    <div class="accordion-inner">
                                        <?php
                                        if (!empty($invited_members)) {
                                            foreach ($invited_members as $member) {
                                                ?>
                                                <div class="itemdiv memberdiv">
                                                    <div class="inline position-relative">
                                                        <div class="user">
                                                            <a href="/admin/Users/view/<?php echo $member['User']['username']; ?>">
                                                                <?php echo Common::getUserThumb($member['User']['id'], $member['User']['type'], 'small', 'profile_brdr_5', 'img');
                                                                ?>
                                                            </a>
                                                        </div>
                                                        <div class="body">
                                                            <div class="name">
                                                                <a href="/admin/Users/view/<?php echo $member['User']['username']; ?>">
                                                                    <?php
                                                                    echo __(h($member['User']['username']));
                                                                    ?>
                                                                </a>
                                                                <div>
                                                                    <?php echo Date::getUSFormatDate($member['Modified'], $timezone);?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo __(h('No members found'));
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="hr hr10 hr-double"></div>
                </div><!--/#Members-->

            </div>
        </div>
    </div>
</div>
<script>
    $("ul.nav-list li").removeClass('active');
    $("#event-list-li").addClass('active');
</script>