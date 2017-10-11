<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Start date'); ?><span class="red_star_span"> *</span></label></div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            <?php echo $this->Form->input('start_date', array('type' => 'text', 'readonly' => 'readonly')); ?> 
        </div>
    <div class="date_hint"> <?php echo Date::getDateFormatText(); ?> </div>
     <div class="col-lg-12 col-md-12 col-sm-12 ">
        <div class="col-lg-3 col-md-3 col-sm-3"></div>
        <div id="customDateErrorMsg" class="customErrorWraper col-lg-9 col-md-9 col-sm-9"></div>        
    </div>
</div>
<div id="eventDuration" class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Time'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-3 col-md-3 col-sm-3 form-group-col">
        <?php
        echo $this->Form->input('start_time', array('class' => 'form-control setErrorDivOneday time start', 'type' => 'text', 'placeholder' => 'Start Time'/*, 'data-default-time' => $startTime*/));
        ?> 
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1"><label><?php echo __('to'); ?></label></div>
    <div class="col-lg-3 col-md-3 col-sm-3 form-group-col">
        <?php echo $this->Form->input('end_time', array('class' => 'form-control setErrorDivOneday time end', 'type' => 'text', 'placeholder' => 'End Time'/*, 'data-default-time' => $endTime*/)); ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 ">
        <div class="col-lg-3 col-md-3 col-sm-3"></div>
        <div id="time_error_wrapper_no_rep" class="time_error_wrapper col-lg-9 col-md-9 col-sm-9"> </div>        
    </div>

</div>