
<!-- Modal -->
<div class="modal fade cerateReminderDialog" id="create_calendar_reminder_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close invite_close_button close_modal_buttons" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title outer-pop-up" style="color:#fff">Add New Calendar Entry</h4>
                <ul class="select-event-type nav nav-tabs" role="tablist">

                    <li class="active select-event-type-options" id="add_type_event_1_wraper">
                        <a href="#add_calendar_event_form" role="tab" data-toggle="tab" id="add_type_event_1">Personal Reminder</a>
                    </li>

                    <li class="select-event-type-options" id="add_type_event_2_wraper">
                        <a href="#add_event_form" role="tab" data-toggle="tab" id="add_type_event_2">Event</a>
                    </li>
                    <li class="select-event-type-options" id="add_type_event_3_wraper">
                        <a href="#add_appoinment_form" role="tab" data-toggle="tab" id="add_type_event_3">Appoinment</a>
                    </li>


                    <!--                    <li class="select-event-type-options" id="add_type_event_3_wraper">
                                            <a role="tab" data-toggle="tab">Medication Scheduler</a>
                                        </li>-->
                </ul>
                <input type="hidden" id="new_event_category" value="1" />

            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <?php echo $this->element("Calendar.add_calendar_event_form"); ?>
                    <?php echo $this->element("Calendar.add_event_form"); ?>
                    <?php echo $this->element("Calendar.add_appoinment_form"); ?>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e1e1e1 !important;">
                <button class="btn btn_invite_frnds pull-left hide" data-toggle="modal" data-target="#inviteFriends" data-backdrop="static" data-keyboard="false">Invite Friends <span>(<span id="selected_no">0</span> Selected )</span></button>
                <button id="save_event_button" type="button" class="btn btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="delete_event_button" type="button" class="btn btn-primary ladda-button" data-style="expand-right"><span class="ladda-label">Delete</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn-default invite_close_button close_modal_buttons" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo $this->element("Calendar.add_event_invite_friend"); ?>


<?php
echo $this->jQValidator->validator();
?>


<script type="text/javascript">

    var defaultVariables = Array();
    /**
     * Functin to initialize default variables for the model
     * 
     * @returns {boolean}
     */
    function initDefaultVariables() {
        defaultVariables = {
            's_date': $('#stpartdate').val(),
            's_time': $('#stparttime').val(),
            'e_time': $('#etparttime').val(),
            'e_name': $.trim($("#Subject").val())
        };
    }

    $(document).ready(function() {

        $("#timezone").val(new Date().getTimezoneOffset() / 60 * -1);

        $('#eventDuration1 .time.start').timepicker({
            'showDuration': true,
            'timeFormat': 'g:i a'
        });

        $('#eventDuration1 .time.end').timepicker({
            'maxTime': '11:30 pm',
            'showDuration': true,
            'timeFormat': 'g:i a'
        });

        $('#appoinment_time').timepicker({
            'showDuration': true,
            'timeFormat': 'g:i a'
        });


        $('#eventDuration1').datepair({
            'defaultTimeDelta': 1800000
        });

        $("#stpartdate").datepicker({
            picker: $("#stpartdate"),
            showtarget: $("#stpartdate")
        });

        $("#stpartdate").click(function() {
            $('.dropdowncontainer').hide();
            $("#stpartdate").datepicker({
                picker: $("#stpartdate"),
                showtarget: $("#stpartdate")
            });
        });

        $("#appoinment_date").datepicker({
            picker: $("#appoinment_date"),
            showtarget: $("#appoinment_date")
        });


        $("#add_type_event_1_wraper").click(function() {

            $('.cerateReminderDialog .btn_invite_frnds').addClass('hide');
            $('#new_event_category').val(1);

            $("#stparttime").val(defaultVariables.s_time);
            $("#etparttime").val(defaultVariables.e_time);
            $("#stpartdate").datepicker('setDate', defaultVariables.s_date);
            $("#Subject").val(defaultVariables.e_name);

        });

        $("#add_type_event_2_wraper").click(function() {

            $('#new_event_category').val(2);
            $('.cerateReminderDialog .btn_invite_frnds').removeClass('hide');

            $("#EventStartTime").val(defaultVariables.s_time);
            $("#EventEndTime").val(defaultVariables.e_time);
            $("#EventStartDate").datepicker('setDate', defaultVariables.s_date);
            $("#EventName").val(defaultVariables.e_name);

        });

        $("#add_type_event_3_wraper").click(function() {

            $('#new_event_category').val(3);
            $('.cerateReminderDialog .btn_invite_frnds').addClass('hide');

            $("#appoinment_time").val(defaultVariables.s_time);
            $("#appoinment_date").datepicker('setDate', new Date(defaultVariables.s_date));

        });

        $(".calendar_invite_close").click(function() {
            $('#inviteFriends').modal('hide');
        });

        $(".invite_okay_btn_calendar").click(function() {
            $('#inviteFriends').modal('hide');
        });

        $('#inviteFriends').on('hidden.bs.modal', function(e) {
            $("#selected_no").html($("#selected_count").html());
        });

        $('#Subject, #EventName').on('input', function() {
            // Save input name to difault values
            defaultVariables.e_name = $(this).val();
        });

        $('#stpartdate, #EventStartDate, #appoinment_date').on('change', function() {
            // Save selected date to difault values
            defaultVariables.s_date = $(this).val();
        });

        $('#stparttime, #EventStartTime, #appoinment_time').on('change', function() {
            // Save selected start time to difault values
            defaultVariables.s_time = $(this).val();

            // save the end time
            var elId = $(this).attr('id');
            if (elId === 'stparttime') {

                /*
                 * Wait for end time change event to complete
                 */
                setTimeout(function() {
                    defaultVariables.e_time = $('#etparttime').val();
                }, 200);
            } else if (elId === 'EventStartTime') {

                /*
                 * Wait for end time change event to complete
                 */
                setTimeout(function() {
                    defaultVariables.e_time = $('#EventEndTime').val();
                }, 200);
            } else {
                var isStartDaterGrater = compareTime(defaultVariables.s_time, defaultVariables.e_time);
                if (isStartDaterGrater) {
                    defaultVariables.e_time = defaultVariables.s_time;
                }
            }
        });

        $('#etparttime, #EventEndTime').on('change', function() {
            // Save selected end time to difault values
            defaultVariables.e_time = $(this).val();

            // save the start time
            var elId = $(this).attr('id');
            if (elId === 'etparttime') {

                /*
                 * Wait for start time change event to complete
                 */
                setTimeout(function() {
                    defaultVariables.s_time = $('#stparttime').val();
                }, 200);
            } else {

                /*
                 * Wait for start time change event to complete
                 */
                setTimeout(function() {
                    defaultVariables.s_time = $('#stparttime').val();
                }, 200);
            }
        });

    });
</script>