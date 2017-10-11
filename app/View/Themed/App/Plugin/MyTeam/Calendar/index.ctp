<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
} else {
	$this->Html->addCrumb('My Team', $module_url);
}
$this->Html->addCrumb($team['name'], $module_url . '/' . $team['id']);
$this->Html->addCrumb('Calendar');
?>
<?php
$this->AssetCompress->addScript(array(
    'vendor/wdCalendar_lang_US.js',
    'vendor/datepicker_lang_US.js',
    'vendor/jquery.dropdown.js',
    'vendor/jquery.calendar.js',
    'calendar.js'), 'carecalendar_view.js');
echo $this->AssetCompress->includeJs();
echo $this->AssetCompress->css('calendar');
?>




<input type="hidden" name="showDate" id="showDate" value="<?php echo $showDate; ?>"/>
<input type="hidden" name="calendarType" id="calendarType" value="<?php echo $calendarType; ?>"/>
<input type="hidden" name="teamId" id="teamId" value="<?php echo (isset($teamId)) ? $teamId : 0; ?>"/>

<div class="container">
    <div id="calendar_view" class="row team_discussion calendar_view carecalendar_calendar_view">       
        <?php echo $this->element('lhs'); ?>
        <div class="col-lg-9">       
            <?php echo $this->element('MyTeam.approve_decline_privacy_chane_box', array('teamId' => $team['id'])); ?>
            <div id="errorpannel" class="ptogtitle loaderror alert alert-error" style="display: none;">Sorry, could not load your data, please try again later</div>
            <div id="calhead" style="padding-left:1px;padding-right:1px;">          
                <div class="cHead"><div class="ftitle">
                        <div class="page-header">
                            <h3 class="pull-left">Care Calendar</h3>
                            <a id="createButton" href="/myteam/<?php echo (isset($teamId)) ? $teamId : 0; ?>/calendar/add" class="pull-right btn create_button">Add new task</a>
                        </div>
                    </div>
<!--                    <img id="loadingpannel" src="<?php // echo Configure::read("App.SITE_URL")                                ?>/img/loading.gif" alt="In progress. Please hold.">-->
                    <!--<div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Loading data...</div>-->
                </div>          

                <div id="caltoolbar" class="ctoolbar">
                    <div class="btnseparator"></div>
                    <div id="showtodaybtn" class="fbutton">
                        <div><span title='View all the needs' class="showtoday">
                                Today</span></div>
                    </div>
                    <div class="btnseparator"></div>

                    <div id="showdaybtn" class="fbutton <?php
                    if ($showDate) {
                        echo 'fcurrent';
                    }
                    ?>">
                        <div><span title='Day' class="showdayview">Day</span></div>
                    </div>
                    <div class="btnseparator"></div>
                    <div  id="showweekbtn" class="fbutton">
                        <div><span title='Week' class="showweekview">Week</span></div>
                    </div>
                    <div class="btnseparator"></div>
                    <div  id="showmonthbtn" class="fbutton <?php
                    if (!$showDate) {
                        echo 'fcurrent';
                    }
                    ?>">
                        <div><span title='Month' class="showmonthview">Month</span></div>

                    </div>
                    <div class="btnseparator"></div>
                    <div id="sfprevbtn" title="Prev"  class="fbutton">
                        <span class="fprev"></span>

                    </div>
                    <div id="sfnextbtn" title="Next" class="fbutton">
                        <span class="fnext"></span>
                    </div>
                    <div class="fshowdatep fbutton">
                        <div>
                            <input id="user_timezone_offset" type="hidden" value="<?php echo $timezoneOffset; ?>"/>
                            <input id="timezoneOfindcator" type="hidden" value="<?php echo $timezoneOfindcator; ?>"/>
                            <input type="hidden" name="txtshow" id="hdtxtshow" />
                            <span id="txtdatetimeshow">Loading</span>

                        </div>
                    </div>                    
                    <div class="btnseparator"></div>
                    <div  id="showreflashbtn" class="fbutton">

                        <div>
                            <span id="loadingpannel" class="loadingpannel_container" style="width: 25px;">
                                <img id="loadingpannel_img" src="<?php echo Configure::read("App.SITE_URL") ?>/img/loading.gif" alt="In progress. Please hold.">
                            </span>
                            <span id="refresh_img_container" title='Refresh view' class="showdayflash" style="display: none;"></span>
                        </div>
                    </div>
                    <div class="btnseparator"></div>


                    <div id="info_colorcodes_img_container">
                        <img id="info_colorcodes_img" src="<?php echo Configure::read("App.SITE_URL") ?>/theme/App/img/calendar_tooltip_icon.png" alt="Info" title="Task Status Informations">
                    </div>
                    <div class="clear"></div>
                </div>

                <div id="infobar" class="ctoolbar">                      
                    <div class="col-lg-4 filter_option_list">
                        <div class="col-lg-5">
                            <span>Assigned To</span>
                        </div>   
                        <div class="col-lg-7">
                            <?php
                            echo $this->Form->create('CareCalendar');
                            echo $this->Form->input('assigned_to_filter', array(
                                'options' => $teamMembers,
                                'empty' => 'All',
//                                'empty' => $teamMembers[0],
//                                'default' => $teamMembers[0],
                                'id' => 'assigned_to_filter',
                                'class' => 'form-control',
                                'label' => false
//                            'onchange' => "generate_day_select_box(this)"
                            ));
                            ?>
                        </div>   
                    </div>   
                    <div class="col-lg-4 filter_option_list">
                        <div class="col-lg-5">
                            <span>Task Type</span>
                        </div>   
                        <div class="col-lg-7">
                            <?php
                            echo $this->Form->input('need_type_filter', array(
                                'options' => $allNeedTypes,
                                'empty' => 'All',
//                                'empty' => $allNeedTypes[0],
//                                'default' => $allNeedTypes[0],
                                'id' => 'need_type_filter',
                                'class' => 'form-control',
                                'label' => false
//                            'onchange' => "generate_day_select_box(this)"
                            ));
                            ?>
                        </div>   
                    </div>   
                    <div class="col-lg-4 filter_option_list">
                        <div class="col-lg-5">
                            <span>Status</span>
                        </div>   
                        <div class="col-lg-7">
                            <?php
                            echo $this->Form->input('status_filter', array(
                                'options' => $allStatusList,
                                'empty' => 'All',
//                                'empty' => end($allStatusList),
//                                'default' => end($allStatusList),
                                'id' => 'status_filter',
                                'class' => 'form-control',
                                'label' => false
//                            'onchange' => "generate_day_select_box(this)"
                            ));
                            ?>
                        </div>   
                    </div>   

                </div>

            </div>
            <div id="calendar_body_wraper" style="padding:1px;">

                <div class="t1 chromeColor">
                    &nbsp;</div>
                <div class="t2 chromeColor">
                    &nbsp;</div>
                <div id="dvCalMain" class="calmain printborder">
                    <div id="gridcontainer" >
                    </div>
                </div>
                <div class="t2 chromeColor">

                    &nbsp;</div>
                <div class="t1 chromeColor">
                    &nbsp;
                </div>   
            </div>

        </div>
    </div>
</div>
<?php echo $this->element("Calendar.details_popup_div"); ?>
<?php echo $this->element("Calendar.legent_popover_content"); ?>
