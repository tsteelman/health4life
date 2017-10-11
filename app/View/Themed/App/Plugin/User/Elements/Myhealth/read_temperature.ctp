<!-- Modal -->
<div class="modal fade health_reading_popup" id="readTemperature" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add your Temperature</h4>
            </div>
            <div class="modal-body">
                <div class="row health_status_editor">
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Temperature 
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id="temperature_value" name="" class="form-control read_field" type="text" value="" maxlength="6">
                            <span id="temperature_value_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                        </div>
                        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                            <span id="read_temperature_unit">
                                <?php
                                if ($unitSettings['temp_unit'] == 1) {
                                    echo '&deg;C';
                                } else {
                                    echo '&deg;F';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Date
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id ="temperature_date" name="" class="form-control current_health_date read_field" type="text" value="<?php
                            if (isset($date_today)) {
                                echo $date_today;
                            }
                            ?>" readonly>
                            <span id="temperature_date_error_message" class=" health_reading_error_message" style="display: none; color: red;"> Please enter valid date.</span>
                        </div>
                    </div>                    
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Time
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id ="temperature_time" name="" class="form-control timepicker read_field" type="text" value="">
                            <span id="temperature_time_error_message" style="display: none; color: red;"> Please enter valid time.</span>
                        </div>
                    </div>                    
                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Added Weight successfully.
                        </div>
                    </div>
                    <div id="temperature_error_message" class="alert alert-error health_reading_error_message" style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
                <button id="read_temperature_button" type="button" class="btn btn_active ladda-button" data-style="expand-right" onclick = "" ><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var today = <?php echo "'" . $date_today . "'"; ?>;
    $(document).ready(function() {
//        $('#temperature_time').timepicker('setTime', now());

        /*
         * Cancel / close button click in all health values reading pop-ups.
         */
        $(".health_reading_popup .btn_clear, .health_reading_popup .close").click(function() {
            clearHealthPopup();
        });

        $('.modal').on('show.bs.modal', function(e) {
            resetDefaulttHealthPopup();
        });

    });
    
    $(document).ready(function() {    
        $("#temperature_time").val(now());
        
        $('#temperature_time').timepicker({
            'step': 15,
            'forceRoundTime': true,
            'showDuration': true,
            'timeFormat': 'g:i A'
        });
    });

//    $('#temperature_time').timepicker({
//        minuteStep: 1,
//        appendWidgetTo: 'body',
//        showMeridian: true
//    });
//
//    $('#temperature_time').timepicker().on('show.timepicker', function(e) {
//        $(".bootstrap-timepicker-widget").css("z-index", '1045');
//    });
//
//    $('#temperature_time').timepicker().on('changeTime.timepicker', function(e) {
//        checkTime(this, e);
//    });
    
    function clearHealthPopup() {
        $(".health_reading_popup .read_field").val('');
        $('#bp_time').timepicker('setTime', now());
        $('#temperature_time').timepicker('setTime', now());
        $('.current_health_date').datepicker('setDate', today);
        $(".health_reading_error_message").text('').hide();
    }
    
    function resetDefaulttHealthPopup() {
        $('.current_health_date').datepicker('setDate', today);
        $('#bp_time').timepicker('setTime', now());
        $(".health_reading_error_message").text('').hide();
    }


</script>