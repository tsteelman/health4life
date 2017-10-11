<?php  $eventData = isset($eventEditData) && !empty($eventEditData) ? $eventEditData : NULL;  ?>


<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Ends on'); ?> <span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8 col-sm-8 col-md-8">
        <input type="radio" name="data[Event][repeat_end_type]" checked = 'checked' value="<?php echo Event::REPEAT_END_NEVER; ?>" />
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
    <div class="col-lg-8 col-sm-9 col-md-9">
        <?php $isDisabled =  (isset($eventData['repeat_end_type']) && $eventData['repeat_end_type'] == Event::REPEAT_END_DATE) ? "" : 'disabled'; ?>
        <input type="radio" name="data[Event][repeat_end_type]"  <?php echo (isset($eventData['repeat_end_type']) && $eventData['repeat_end_type'] == Event::REPEAT_END_DATE) ? " checked = 'checked'" : ''; ?> value="<?php echo Event::REPEAT_END_DATE; ?>" class="flt_lft" />
        <span class="flt_lft padding-2"><?php echo __('On'); ?> </span>
        <?php echo $this->Form->input('end_date', array('type' => 'text', 'readonly'=>'readonly', 'disabled' => $isDisabled, 'class' => 'flt_lft form-control event_ends_on_date')); ?>
        <div class="date_hint"> <?php echo Date::getDateFormatText(); ?> </div>
        <div style="clear:both;"></div>
    </div>
</div>