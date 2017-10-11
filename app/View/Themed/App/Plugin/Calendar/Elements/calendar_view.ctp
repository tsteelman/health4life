<?php
$this->AssetCompress->addScript(array(
    'vendor/wdCalendar_lang_US.js',
    'vendor/datepicker_lang_US.js',
    'vendor/jquery.dropdown.js',
    'vendor/jquery.calendar.js',
    'calendar.js'), 'maincalendar_view.js');
echo $this->AssetCompress->includeJs();
echo $this->AssetCompress->css('calendar');
?>




<input type="hidden" name="showDate" id="showDate" value="<?php echo $showDate; ?>"/>
<input type="hidden" name="calendarType" id="calendarType" value="<?php echo $calendarType; ?>"/>
<input type="hidden" name="teamId" id="teamId" value="<?php echo (isset($teamId)) ? $teamId : 0; ?>"/>
<div class="container">
    <div id="calendar_view" class="row calendar_view main_calendar_view">
        <div class="col-lg-9">
            <div id="errorpannel" class="ptogtitle loaderror alert alert-error" style="display: none;">Sorry, could not load your data, please try again later</div>
            <div id="calhead" style="padding-left:1px;padding-right:1px;">          
                <div class="cHead"><div class="ftitle">
                        <div class="page-header">
                            <h2 class="pull-left">My Calendar</h2>
                            <div class="calendar_create_buttons">
                                <a id="createButton" href="/event/add" class="pull-right btn create_button">Add Event</a>
                            <a id="createPersonalRemiderBtn" class="pull-right btn create_button">Add Appts/Reminders</a>
                            </div>

                        </div>
                    </div>
<!--                    <img id="loadingpannel" src="<?php // echo Configure::read("App.SITE_URL")                  ?>/img/loading.gif" alt="In progress. Please hold.">-->
                    <!--<div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Loading data...</div>-->
                    <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Sorry, could not load your data, please try again later</div>
                </div>          

                <div id="caltoolbar" class="ctoolbar"> 
                    <div class="calender_response">
                    <div id="showtodaybtn" class="fbutton">
                        <div><span title='Click to back to today ' class="showtoday">
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
                    <div  id="showweekbtn" class="fbutton <?php
                    if (!$showDate) {
                        echo 'fcurrent';
                    }
                    ?>">
                        <div><span title='Week' class="showweekview">Week</span></div>
                    </div>
                    <div class="btnseparator"></div>
                    <div  id="showmonthbtn" class="fbutton">
                        <div><span title='Month' class="showmonthview">Month</span></div>

                    </div>
                    <div class="btnseparator"></div>
                    <div id="sfprevbtn" title="Prev"  class="fbutton">
                        <span class="fprev"></span>

                    </div>
                    <div id="sfnextbtn" title="Next" class="fbutton">
                        <span class="fnext"></span>
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
                    <div class="fshowdatep fbutton">
                        <div>
                            <input id="user_timezone_offset" type="hidden" value="<?php echo $timezoneOffset; ?>"/>
                            <input id="timezoneOfindcator" type="hidden" value="<?php echo $timezoneOfindcator; ?>"/>
                            <input type="hidden" name="txtshow" id="hdtxtshow" />
                            <span id="txtdatetimeshow">Loading</span>

                        </div>
                    </div>
                    <!--                    <div   class="pull-right">
                                            <span id="info_colorcodes_img" class="event_notifier">
                                                <p><img src="/theme/App/img/event_bullet.png">Event</p>
                                                <p><img src="/theme/App/img/misc_bullet.png">Misc</p>
                                            </span>
                                        </div>-->
                    <div id="info_colorcodes_img_container" class="pull-right">
                        <img id="info_colorcodes_img" src="<?php echo Configure::read("App.SITE_URL") ?>/theme/App/img/calendar_tooltip_icon.png" alt="Info" title="Calendar Events">
                    </div>
                    <div class="btnseparator pull-right"></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div id="calendar_body_wraper"style="padding:1px;">

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

        <?php echo $this->element('layout/rhs', array('list' => true)); ?>

    </div>
</div>



<?php echo $this->element("Calendar.legent_popover_content"); ?>
<?php echo $this->element("details_popup_div"); ?>



<!--pop-up for create new event from calendar. type calendar reminder event start.-->
<div id="bbit-cal-buddle" style="z-index: 180; width: 400px;visibility:hidden;" class="bubble create_event_form_calendar">
    <table class="bubble-table" cellSpacing="0" cellPadding="0">
        <tbody>
            <tr>
                <td class="bubble-cell-side">
                    <div id="tl1" class="bubble-corner">
                        <div class="bubble-sprite bubble-tl">

                        </div>
                    </div>
                <td class="bubble-cell-main">
                    <div class="bubble-top">

                    </div>
                <td class="bubble-cell-side">
                    <div id="tr1" class="bubble-corner">
                        <div class="bubble-sprite bubble-tr">
                        </div>
                    </div>  
            <tr>
                <td class="bubble-mid" colSpan="3">
                    <div style="overflow: hidden" id="bubbleContent1">
                        <div>
                            <div>
                            </div>
                            <div class="cb-root">
                                <table class="cb-table" cellSpacing="0" cellPadding="0">
                                    <tbody>
                                        <tr>
                                            <th id="cb_key_time" class="cb-key">

                                            </th>
                                            <td class=cb-value>
                                                <div id="bbit-cal-buddle-timeshow">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th id="cb_key_content" class="cb-key">

                                            </th>
                                            <td class="cb-value">
                                                <div class="textbox-fill-wrapper">
                                                    <div class="textbox-fill-mid">
                                                        <input id="bbit-cal-what" class="textbox-fill-input"/>
                                                    </div>
                                                </div>
                                                <div id="cb_example" class="cb-example">

                                                </div>
                                            </td>
                                        </tr>
<!--                                        <tr>
                                            <th id="cb_key_description" class="cb-key">

                                            </th>
                                            <td class="cb-value">
                                                <div class="textbox-fill-wrapper">
                                                    <div class="textbox-fill-mid">
                                                        <textarea id="bbit-cal-description" class="textbox-fill-input">
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div id="cb_example_description" class="cb-example">

                                                </div>
                                            </td>
                                        </tr>-->
                                    </tbody>
                                </table>
                                <input id="bbit-cal-start" type="hidden"/>
                                <input id="bbit-cal-end" type="hidden"/>
                                <input id="bbit-cal-allday" type="hidden"/>
                                <input id="bbit-cal-quickAddBTN" value="" type="button"/>
                                &nbsp; <SPAN id="bbit-cal-editLink" class="lk">

                                    <StrONG>
                                        &gt;&gt;
                                    </StrONG>
                                </SPAN>
                            </div>
                        </div>
                    </div>
            <tr>
                <td>
                    <div id="bl1" class="bubble-corner">
                        <div class="bubble-sprite bubble-bl">
                        </div>
                    </div>
                <td>
                    <div class="bubble-bottom">
                    </div>
                <td>
                    <div id="br1" class="bubble-corner">
                        <div class="bubble-sprite bubble-br">
                        </div>
                    </div>
            </tr>
        </tbody>
    </table>
    <div id="bubbleClose1" class="bubble-closebutton">
    </div>
    <div id="prong2" class="prong">
        <div class=bubble-sprite>
        </div>
    </div>
</div>

<!--pop-up for create new event from calendar. type calendar reminder event end.-->

<!--<div id="create_calendar_reminder_dialog" style="display: none;">-->
<!--custom pop-up for calendar add reminder events. start-->
<?php echo $this->element("Calendar.new_calendar_reminder"); ?>
<!--custom pop-up for calendar add reminder events ens-->    
<!--</div>-->

