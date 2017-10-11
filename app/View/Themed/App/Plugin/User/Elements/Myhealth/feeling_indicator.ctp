<?php
$date_today = CakeTime::format('Y-m-d', date('Y-m-d  H:i:s'), false, $timezone);
if (isset($user_details['feeling']) && !is_null($user_details['feeling'])) {
    ?>
    <p><span class="pull-left"><?php echo __('Feeling');?></span><span class="pull-left feeling_condition 
            <?php echo $user_details['feeling'];
            echo ($is_same) ? ' my_health_add' : ''; ?>"></span>
        <span id="feeling_date_status" class="pull-left">
            <?php if ($date_today == $user_details['feeling_date']) { ?>
                <?php echo __('Today');?>
    <?php } ?>
        </span>
    </p>
<?php } else { ?>
    <p><span class="pull-left">Feeling</span><span class="pull-left feeling_condition 
            feeling_very_good my_health_add"></span>
    </p>
<?php } ?>

    
    