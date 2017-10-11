<?php  $eventData = isset($eventEditData) && !empty($eventEditData) ? $eventEditData : NULL; ?>
<div id="repeat_event_mode_time" class="repeating_mode_datetime">
    <div class="form-group date_span">
        <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Event Date '); ?> <span class="red_star_span"> *</span></label></div>
        <div class="col-lg-9 col-sm-9 col-md-9" style="padding-left: 15px;">
        <div class="col-lg-2">
            <?php echo $this->Form->input('start_date_time', array('type' => 'text', 'readonly'=>'readonly','value' => $defaultStartDate)); ?>
        </div>
        <div id="repeat_event_mode_start" class="col-lg-2 form-group-col repeat_event_mode">
            <?php
                echo $this->Form->input('start_date_timeonly', array('class' => 'form-control setErrorDivRepeat time start', 'type' => 'text', 'placeholder' => 'Start Time'/*, 'data-default-time' => $startTime*/));
            ?>
        </div>
        <?php  $isFullDay = (!empty($eventData) && isset($eventData) && isset($eventData['upto_date'])) ? FALSE : TRUE; ?>
        <div class="not_full_day_event <?php echo ($isFullDay == TRUE) ? 'hidden' : ''; ?> ">
            <div class="col-lg-1" style="padding: 7px 3px 7px 0px;float: left;width: 20px">
                To
            </div>
            <div class="col-lg-2">
                <?php echo $this->Form->input('upto_date', array('type' => 'text','readonly'=>'readonly', 'value' => $uptoStartDate)); ?>
            </div>
            <div id="repeat_event_mode_end" class="col-lg-2 form-group-col repeat_event_mode">
                <?php echo $this->Form->input('upto_timeonly', array('class' => 'form-control time setErrorDivRepeat end', 'type' => 'text', 'placeholder' => 'End Time'/*, 'data-default-time' => $endTime*/)); ?>
            </div>
        </div>
        <div id="time_error_wrapper_rep" class="time_error_wrapper col-lg-12"> </div>  
    </div>
        <!--<div class="date_hint"> <?php // echo Date::getDateFormatText(); ?> </div>-->
    </div>
    <div class="form-group">
         <div class="full_day_event">
             <div class="col-lg-3 col-sm-3 col-md-3"></div>
             <div class="col-lg-4 col-sm-4 col-md-4">
                 <input type="checkbox" name="data[Event][is_full_day]" id="is_full_day" class="full_day_event_checkbox" value="1" <?php  echo ($isFullDay == TRUE) ? 'checked="checked"' : ''; ?> /><?php echo __('All Day Event'); ?>
            </div>
        </div>
    </div>
<!--<div class="form-group">
        <div class="not_full_day_event hidden">
            <div class="col-lg-3"><label> <?php // echo __('Event up to '); ?> <span class="red_star_span"> *</span></label></div>
            <div class="col-lg-4">
                <?php // echo $this->Form->input('upto_date', array('type' => 'text', 'value' => $defaultStartDate)); ?>
            </div>
            <div id="repeat_event_mode_end" class="col-lg-4 form-group-col repeat_event_mode">
                <?php // echo $this->Form->input('upto_timeonly', array('class' => 'form-control time end', 'type' => 'text', 'placeholder' => 'End Time'/*, 'data-default-time' => $endTime*/)); ?>
            </div>
        <div class="date_hint"> <?php // echo Date::getDateFormatText(); ?> </div>
        </div>
    
    </div>-->
</div>
