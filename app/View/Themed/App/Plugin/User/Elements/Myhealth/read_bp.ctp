<!-- Modal -->
<div class="modal fade health_reading_popup" id="readBp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add your BP</h4>
            </div>
            <div class="modal-body">
                <div class="row health_status_editor">
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Systolic 
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id="bp_value1" name="" class="form-control read_field" type="text" value="" maxlength="6">
                            <span id="bp_value1_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                        </div>
                        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                            <span>mmHg</span>
                        </div>
                    </div>
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Diastolic 
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id="bp_value2" name="" class="form-control read_field" type="text" value="" maxlength="6">
                            <span id="bp_value2_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                        </div>
                        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                            <span>mmHg</span>
                        </div>
                    </div>
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Date
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id="bp_date" name="" class="form-control current_health_date read_field" type="text" 
                                   value="<?php
                                   if (isset($date_today)) {
                                       echo $date_today;
                                   }
                                   ?>" readonly>
                            <span id="bp_date_error_message" style="display: none; color: red;"> Please enter valid date.</span>
                        </div>
                    </div>
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Time
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id="bp_time" name="" class="form-control current_health_time timepicker read_field" type="text" 
                                   value="">
                            <span id="bp_time_error_message" style="display: none; color: red;"> Please enter valid Time.</span>
                        </div>
                    </div>


                    <?php // echo $this->element('invite_friends');   ?>

                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Added Weight successfully.
                        </div>
                    </div>
                    <div id="bp_error_message" class="alert alert-error health_reading_error_message" style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
                <button id="bp_submit_button" type="button" class="btn btn_active ladda-button" data-style="expand-right" onclick = ""><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var today = <?php echo "'" . $date_today . "'"; ?>;
    
    $(document).ready(function() {    
        $("#bp_time").val(now());
        
        $('#bp_time').timepicker({
            'step': 15,
            'forceRoundTime': true,
            'showDuration': true,
            'timeFormat': 'g:i A'
        });
        $('#readBp').on('shown.bs.modal', function(e) {
             $("#bp_time").val(now());
        });
        
        $('#readTemperature').on('shown.bs.modal', function(e) {
             $("#temperature_time").val(now());
        });
    });
    

//    $('#bp_time').timepicker({
//        minuteStep: 1,
//        appendWidgetTo: 'body',
//        showMeridian: true
//    });
//
//    $(document).ready(function() {
//        $('#bp_time').timepicker('setTime', now());
//    });
//
//    $('#bp_time').timepicker().on('show.timepicker', function(e) {
//        $(".bootstrap-timepicker-widget").css("z-index", '1045');
//    });
//
//    $('#bp_time').timepicker().on('changeTime.timepicker', function(e) {
//        checkTime(this, e);
//    });

    function time(hours, mins) {
        return ((hours * 3, 600) + (mins * 60));
    }

    function now() {
        
        var day = getUserNow();
        var DAY_OBJ = new Date(day);
        var hours = DAY_OBJ.getHours();
//        var mins = new Date(day).getMinutes();
        var mins = (DAY_OBJ.getMinutes()<10?'0':'') + DAY_OBJ.getMinutes();
        var mid = ' AM';
        if (hours == 0) { //At 00 hours we need to show 12 am
            hours = 12;
        } else if (hours > 12) {
            hours = hours % 12;
            mid = ' PM';
        } else if (hours == 12) {
            mid = ' PM';
        }
        return (hours + ':' + mins + mid);
    }

    function nowobject() {
        var day = getUserNow();
        var hours = new Date(day).getHours();
        var mins = new Date(day).getMinutes();
        var mid = 'AM';
        if (hours == 0) { //At 00 hours we need to show 12 am
            hours = 12;
        } else if (hours > 12) {
            hours = hours % 12;
            mid = 'PM';
        } else if (hours == 12) {
            mid = 'PM';
        }
        var now = {'hour': hours, 'min': mins, 'mid': mid};
        return (now);
    }

//    function checkTime(data, e) {
//        var div_id = $(data).attr('id').split("_");
//        if ($('#' + div_id[0] + '_date').val() == today) {
//            var hours = parseInt(e.time.hours);
//            var mins = parseInt(e.time.minutes);
//            var mid = e.time.meridian;
//            var time_now = nowobject();
//            if (mid == time_now.mid) {
//                if (time_now.hour == 12 && hours == 12) {
//                    if (mins > time_now.min) {
//                        $('#' + div_id[0] + '_time').timepicker('setTime', now());
//                    }
//                } else if (hours < time_now.hour) {
//                    $('#' + div_id[0] + '_time').timepicker('setTime', now());
//                } else if (hours > time_now.hour) {
//                    if (hours != 12) {
//                        if (mid == time_now.mid) {
//                            $('#' + div_id[0] + '_time').timepicker('setTime', now());
//                        }
//                    }
//                } else if (hours == time_now.hour) {
//                    if (mins > time_now.min) {
//                        if (mid == time_now.mid) {
//                            $('#' + div_id[0] + '_time').timepicker('setTime', now());
//                        }
//                    }
//                }
//            }
//        }
//    }

</script>