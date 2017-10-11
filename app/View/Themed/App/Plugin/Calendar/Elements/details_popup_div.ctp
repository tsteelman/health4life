<!--pop-up for view details-->
<div id="bbit-cs-buddle_wraper" style="z-index: 180; width: 300px;visibility:hidden;display: none !important;" class="calendar_event_popup bubble">
    <div id="bbit-cs-buddle" class="calendar_event_popup bubble">
    <!--<div id="bbit-cs-buddle" style="z-index: 180; width: 300px;visibility:hidden;" class="calendar_event_popup bubble">-->

        <table class="bubble-table" cellSpacing="0" cellPadding="0">
            <tbody>
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
                                                <td class="cb-value">
                                                    <div class="textbox-fill-wrapper">
                                                        <div id="bbit-cs-thumb">

                                                        </div>
                                                        <div class="textbox-fill-mid">
                                                            <a id="event_name_container" href="javascript:void(0);">
                                                                <h3 id="bbit-cs-what" title="" class="textbox-fill-div lk" style="cursor:pointer;">
                                                                </h3>
                                                            </a>

                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=cb-value>
                                                    <div id="bbit-cs-buddle-timeshow" style="display: none !important;">

                                                    </div>
                                                    <div id="bbit-cs-buddle-timeshow_12">

                                                    </div>
                                                    <div id="event_location_container" class="block"> 
                                                        <img src="/theme/App/img/tmp/location_indicator.png" alt="...">
                                                        <span id="event_location">
                                                        </span>
                                                    </div>

                                                    <div id="medication_data_container" class="medication_data_container" style="display: none;">
                                                        <span id="med_details">
                                                        </span>
                                                        <span id="med_rep_details">
                                                        </span>
                                                    </div>
                                                    <div id="appointment_data_container" class="appointment_data_container" style="display: none;">
                                                        <span id="appointment_details">
                                                            
                                                        </span>
                                                    </div>
                                                    <div id="event_description">

                                                    </div>
                                                    <div id="team_task_details" style="display: none;">
                                                        <table style="width: 100%" class="bubble-table123" cellSpacing="0" cellPadding="0">
                                                            <tr>
                                                                <td style="width: 40%;">
                                                                    <div>Team </div>                                                                
                                                                </td>
                                                                <td style="width: 6%;">
                                                                    <div>: </div>                                                                
                                                                </td>
                                                                <td style="width: 50%;">
                                                                    <a id="details_popup_team_name" href="#"></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%;">
                                                                    <div>Status: </div>                                                                
                                                                </td>
                                                                <td style="width: 6%;">
                                                                    <div>: </div>                                                                
                                                                </td>
                                                                <td style="width: 50%;">
                                                                    <span id="details_popup_status"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%;">
                                                                    <div>Assigned To: </div>                                                                
                                                                </td>
                                                                <td style="width: 6%;">
                                                                    <div>: </div>                                                                
                                                                </td>
                                                                <td style="width: 50%;">
                                                                    <a id="details_popup_assigned_to" href="#"></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%;">
                                                                    <div>Task Type:</div>                                                        
                                                                </td>
                                                                <td style="width: 6%;">
                                                                    <div>: </div>                                                                
                                                                </td>
                                                                <td style="width: 50%;">
                                                                    <span id="details_popup_task_icon" class="task_type"></span>
                                                                    <span id="details_popup_task_type"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 40%;">
                                                                    <div>Created By: </div>                                                                
                                                                </td>
                                                                <td style="width: 6%;">
                                                                    <div>: </div>                                                                
                                                                </td>
                                                                <td style="width: 50%;">
                                                                    <a id="details_popup_created_by" href="#"></a>
                                                                </td>
                                                            </tr>
                                                        </table>


                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-bottom-calendar-popup">
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="event_repeat_mode oneday_mode">
                                        <span class="event_type oneday pull-left"></span>One-time
                                        <a class="view_more_button pull-right more" href="/event">more</a>
                                    </p>
                                    <p class="event_repeat_mode repeat_mode" style="display: none;">
                                        <span class="event_type recurring_event everyday pull-left"></span> Recurring Event
                                        <a class="view_more_button pull-right more" href="/event">more</a>
                                    </p>
                                    <p class="event_repeat_mode carecalendar_mode" style="display: none;">
                                        <a class="view_more_button pull-right more" href="#">more</a>
                                    </p>
                                    <p class="event_repeat_mode reminder_mode" style="display: none;">
                                        <span class="event_type_txt oneday_mode_txt"><span class="event_type oneday pull-left"></span>Reminder</span>
                                        <span class="event_type_txt repeat_mode_txt"><span class="event_type everyday pull-left"></span>Reminder (Recurring)</span>
                                        <a id="edit_reminder_button" class="pull-right more edit_reminder_button" href="javascript:void(0)">Edit</a>
                                    </p>
                                    <p class="event_repeat_mode medication_mode" style="display: none;">
                                        <span class="event_type oneday pull-left"></span>Medication
                                        <a class="view_more_button pull-right more" href="/scheduler">more</a>
                                    </p>
                                    <p class="event_repeat_mode appointment" style="display: none;">
                                        <span class="event_type_txt oneday_mode_txt"><span class="event_type oneday pull-left"></span>Appointment</span>
                                        <span class="event_type_txt repeat_mode_txt"><span class="event_type everyday pull-left"></span>Appointment (Recurring)</span>
                                        <a id="edit_reminder_button" class="pull-right more edit_reminder_button" href="javascript:void(0)">Edit</a>
                                        <!--<a id="edit_appointment_button" class="pull-right more edit_appointment_button" href="javascript:void(0)">Edit</a>-->
                                    </p>
                                </div>
                            </div>
                        </div>
                </tr>
            </tbody>
        </table>
        <div id="bubbleClose2" class="bubble-closebutton">
        </div>
        <div class="arrow"></div>
    </div>
</div>