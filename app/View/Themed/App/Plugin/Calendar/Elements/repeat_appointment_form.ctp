<?php // $eventData = isset($this->request->data['Event']) ? $this->request->data['Event'] : NULL; ?>
<?php //  $eventData = isset($eventEditData) && !empty($eventEditData) ? $eventEditData : NULL; ?>
<div class="repeat_mode_fields">
    <?php // echo $this->element('Event.event_start_end_form'); ?>
</div>
<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Repeats'); ?> </label></div>
    <div class="col-lg-8 col-sm-8 col-md-8">
        <?php
//        if (isset($eventData['repeat']) && isset($eventData['repeat_mode'])) { 
////            debug($eventData);
//                 $defaultRepeatMode = $eventData['repeat_mode'];
//                 if (isset($eventData['repeat_interval']) && !empty($eventData['repeat_interval'])) {
//                    $repeatIntervalOpt = $eventData['repeat_interval'];
//                    $repeatIntervalTextVal = $repeatIntervalText[$defaultRepeatMode];
//                 }
//            } else {
                $repeatIntervalOpt = 1;
                $repeatIntervalTextVal = 'Weeks';
//            }
            ?>
        <?php echo $this->Form->input('repeat_mode', array('label' => false, 'id'=>'ApptRepeatMode', 'options' => $repeatModes, 'selected' => $defaultRepeatMode, 'class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group" id="appt_repeat_interval_fields">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Repeats every:'); ?> </label></div>
    <div class="col-lg-4 col-sm-4 col-md-5">
        <?php echo $this->Form->input('repeat_interval', array('label' => false, 'id'=>'ApptRepeatInterval', 'class' => 'pull-left form-control width70', 'options' => $repeatIntervalList, 'selected' => $repeatIntervalOpt)); ?>
        <span id="appt_interval_type" class="pull-left"><?php echo __($repeatIntervalTextVal); ?></span>
    </div>
</div>
<?php  $eventData = isset($eventEditData) && !empty($eventEditData) ? $eventEditData : NULL;  ?>
<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3">
        <label> <?php echo __('Ends on'); ?> <span class="red_star_span"> *</span></label>
    </div>
    <div class="col-lg-8 col-sm-8 col-md-8">
        <input type="radio" id="appt_never_end_chk" class="appt_repeat_end_type_chk" name="data[AppoinmentForm][repeat_end_type]" checked = 'checked' value="<?php echo Event::REPEAT_END_NEVER; ?>" />
        <span class="padding-2"><?php echo __('Never'); ?></span>
    </div>
</div>
<!--<div class="form-group">-->
    <!--<div class="col-lg-3"><label> </label></div>-->
    <!--<div class="col-lg-8">-->
        <!--<input type="radio" name="data[Event][repeat_end_type]" <?php // echo (isset($eventData['repeat_end_type']) && $eventData['repeat_end_type'] == Event::REPEAT_END_AFTER) ? " checked = 'checked'" : ''; ?> value="<?php // echo Event::REPEAT_END_AFTER; ?>" class="flt_lft" />-->
         <?php // $isDisabled =  (isset($eventData['repeat_end_type']) && $eventData['repeat_end_type'] == Event::REPEAT_END_AFTER) ? "" : 'disabled'; ?>
        <!--<span class="flt_lft padding-2 "><?php // echo __('After'); ?> </span>-->
        <?php // echo $this->Form->input('repeat_occurrences', array('type' => 'text', 'disabled' => $isDisabled, 'class' => 'flt_lft form-control event_occurrences')); ?>
        <!--<span class="flt_lft padding-2"> <?php // echo __('occurrences'); ?></span><div class="clearfix"></div>-->
    <!--</div>-->
<!--</div>-->
<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> </label></div>
    <div class="col-lg-9 col-sm-9 col-md-9">
        <?php $isDisabled =  (isset($eventData['repeat_end_type']) && $eventData['repeat_end_type'] == Event::REPEAT_END_DATE) ? "" : 'disabled'; ?>
        <input type="radio" id="appt_end_on_chk" class="appt_repeat_end_type_chk flt_lft" name="data[AppoinmentForm][repeat_end_type]"  <?php echo (isset($eventData['repeat_end_type']) && $eventData['repeat_end_type'] == Event::REPEAT_END_DATE) ? " checked = 'checked'" : ''; ?> value="<?php echo Event::REPEAT_END_DATE; ?>"/>
        <span class="flt_lft padding-2"><?php echo __('On'); ?> </span>
        <?php echo $this->Form->input('end_date', array('id'=> 'ApptEndDate', 'readonly'=> 'readonly', 'label' => false , 'type' => 'text', 'disabled' => $isDisabled, 'class' => 'flt_lft form-control event_ends_on_date')); ?>
        <div class="date_hint"> <?php echo Date::getDateFormatText(); ?> </div>
        <div style="clear:both;"></div>
    </div>
</div>