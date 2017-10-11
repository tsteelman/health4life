<?php // $eventData = isset($this->request->data['Event']) ? $this->request->data['Event'] : NULL; ?>
<?php  $eventData = isset($eventEditData) && !empty($eventEditData) ? $eventEditData : NULL; ?>
<div class="repeat_mode_fields">
    <?php echo $this->element('Event.event_start_end_form'); ?>
</div>
<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Repeats'); ?> </label></div>
    <div class="col-lg-8 col-sm-8 col-md-8">
        <?php
        if (isset($eventData['repeat']) && isset($eventData['repeat_mode'])) { 
//            debug($eventData);
                 $defaultRepeatMode = $eventData['repeat_mode'];
                 if (isset($eventData['repeat_interval']) && !empty($eventData['repeat_interval'])) {
                    $repeatIntervalOpt = $eventData['repeat_interval'];
                    $repeatIntervalTextVal = $repeatIntervalText[$defaultRepeatMode];
                 }
            } else {
                $repeatIntervalOpt = 1;
                $repeatIntervalTextVal = 'Weeks';
            }
            ?>
        <?php echo $this->Form->input('repeat_mode', array('options' => $repeatModes, 'selected' => $defaultRepeatMode)); ?>
    </div>
</div>
<div class="form-group" id="repeat_interval_fields">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Repeats every:'); ?> </label></div>
    <div class="col-lg-4 col-sm-4 col-md-4">
        <?php echo $this->Form->input('repeat_interval', array('class' => 'pull-left form-control width70', 'options' => $repeatIntervalList, 'selected' => $repeatIntervalOpt)); ?>
        <span id="interval_type" class="pull-left"><?php echo __($repeatIntervalTextVal); ?></span>
    </div>
</div>

<!--<div class="form-group hidden" id="repeats_on_fields">
    <div class="col-lg-3"><label> <?php // echo __('Repeats on:'); ?> </label></div>
    <div class="col-lg-8">
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['MON'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_MON; ?>" /><?php // echo __('Mon'); ?>
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['TUE'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_TUE; ?>" /><?php // echo __('Tue'); ?>
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['WED'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_WED; ?>" /><?php // echo __('Wed'); ?>
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['THU'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_THU; ?>" /><?php // echo __('Thu'); ?>
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['FRI'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_FRI; ?>" /><?php // echo __('Fri'); ?>
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['SAT'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_SAT; ?>" /><?php // echo __('Sat'); ?>
        <input type="checkbox" name="data[Event][repeats_on][]" <?php // echo (isset($eventEditData) && $eventEditData['repeats_on_text_help']['SUN'] == TRUE)? 'checked="checked"' : ''; ?> value="<?php // echo Event::REPEATS_ON_SUN; ?>" /><?php // echo __('Sun'); ?>
    </div>
</div>-->
<!--<div class="form-group hide" id="repeats_by_fields">
    <div class="col-lg-3"><label> <?php // echo __('Repeats by:'); ?> </label></div>
    <div class="col-lg-8">
        <input type="radio" name="data[Event][repeats_by]" checked="checked" value="<?php // echo Event::REPEATS_BY_DAY_OF_MONTH; ?>" /><?php // echo __('Day of the month'); ?>
        <input type="radio" name="data[Event][repeats_by]" value="<?php // echo Event::REPEATS_BY_DAY_OF_WEEK; ?>" /><?php // echo __('Day of the week'); ?>
    </div>
</div>-->
<?php echo $this->element('Event.repeat_end_date'); ?>