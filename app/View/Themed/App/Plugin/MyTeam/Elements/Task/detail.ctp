<div class="patient_details">
    <div class="task_edit">
        <h4 class="pull-left">
            <?php
            echo __(h($task_details['Event']['name'])) . ' - ' .
            __(CakeTime::format($task_details['Event']['start_date'], '%a, %B %e', false, $timezone));
            ?>
        </h4>
        <?php if ($has_editPermission) { ?>
            <button id="btn_edit_task" class="btn btn_active pull-right"><?php echo __('Edit Task'); ?></button>
        <?php } ?>
    </div>
    <div class="detail_div">
        <div class="col-lg-3 detail_status">
            <p><?php echo __('Assigned To'); ?></p>
        </div>
        <div class="col-lg-9 detail_result">
            <div class="owner pull-left">
                <?php
                if ($task_details['CareCalendarEvent']['assigned_to'] != 0) {
                    $assigned_to_name = $teamMemberList[$task_details['CareCalendarEvent']['assigned_to']];
//                    echo __($teamMemberList[$task_details['CareCalendarEvent']['assigned_to']]);
                    echo Common::getUserProfileLink($assigned_to_name, false, 'owner', false);
                } else {
                    echo __('None');
                }
                ?>
            </div>
            <?php if ($has_updatePermission || $has_selfAssignPermission) { ?>
            <!--<div class="pull-left">-->
            <!--<a class="pull-left" id="update_button" href="javascript:void(0)" onclick="openUpdateDiv()"><?php echo __('Update'); ?></a>-->
            <button id="update_button" onclick="openUpdateDiv()" class="btn btn_normal pull-left"><?php echo __('Update'); ?></button>
            <!--</div>-->
            <?php } ?>
        </div>
    </div>

    <div class="detail_div">
        <div class="col-lg-3 detail_status"><p><?php echo __('Date'); ?></p></div>
        <div class="col-lg-9 detail_result">
            <?php
            echo __(Date::getUSFormatDate($task_details['Event']['start_date'], $timezone) . ' '
                    . Date::MySqlDateTimeoJSTime($task_details['Event']['start_date'], $timezone)
                    . ' -' . Date::MySqlDateTimeoJSTime($task_details['Event']['end_date'], $timezone));
            ?>
            <?php 
                if ( $task_details['CareCalendarEvent']['times_per_day'] > 1) {
            ?>
                <span class="additional_notes_span">
                    <?php
                    echo __('  (' . $task_details['CareCalendarEvent']['times_per_day']);                    
                    echo __(' times per day)');                   
                    ?>
                </span>
            <?php } ?>
            
        </div>
    </div>
    <div class="detail_div">
        <div class="col-lg-3 detail_status"><p><?php echo __('Task Type'); ?></p></div>
        <div class="col-lg-9 detail_result">
            <?php
//            $task_type_lower = strtolower($task_details['CareCalendarEvent']['type']);
//            $task_type_class = 'task_'.str_replace(" ","_",$task_type_lower);
//            
            ?>
            <!--<span class="task_type <?php // echo $task_type_class;    ?>"></span>-->
            <?php echo Common::getTaskTypeIcon($task_details['CareCalendarEvent']['type'], false); ?>
            <?php
            echo __($task_details['CareCalendarEvent']['type']);
            ?>

            <?php
            if ($task_details['CareCalendarEvent']['type'] == 'other' || $task_details['CareCalendarEvent']['type'] == 'visit by other') {
                ?> 
                <span class="additional_notes_span">
                    <?php
                    if (isset($task_details['CareCalendarEvent']['additional_notes']) && ($task_details['CareCalendarEvent']['additional_notes'] != '')) {
                        echo __('  (' . $task_details['CareCalendarEvent']['additional_notes'] . ')');
                    }
                    ?>
                </span>
            <?php }
            ?>


        </div>
    </div>
    <?php
    if (isset($task_details['Event']['description']) && ($task_details['Event']['description'] != '')) {
        ?>
        <div class="detail_div">
            <div class="col-lg-3 detail_status"><p><?php echo __('Description'); ?></p></div>
            <div class="col-lg-9 detail_result">
                <?php
                echo __(h($task_details['Event']['description']));
                ?>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="detail_div">
        <div class="col-lg-3 detail_status"><p><?php echo __('Created By'); ?></p></div>
        <div class="col-lg-9 detail_result owner">
            <?php
            $created_by_name = $task_details['Event']['created_by'];
            echo Common::getUserProfileLink($created_by_name, false, 'owner', false);
            ?>
        </div>
    </div>


    <!--    <div class="detail_div">
            <div class="col-lg-3 detail_status"><p><?php // echo __('Times per day');         ?></p></div>
            <div class="col-lg-9 detail_result">
    <?php
//            echo __($task_details['CareCalendarEvent']['times_per_day']);
//            if ($task_details['CareCalendarEvent']['times_per_day'] == 1) {
//                echo __(' time');
//            } else {
//                echo __(' times');
//            }
    ?>
            </div>
        </div>-->

    <div class="detail_div">
        <div class="col-lg-3 detail_status"><p><?php echo __('Status'); ?></p></div>
        <div class="col-lg-9 detail_result">
            <?php
            switch ($task_details['CareCalendarEvent']['status']) {
                case CareCalendarEvent::STATUS_OPEN:
                    $status = 'Open';
                    break;
                case CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL:
                    $status = 'Waiting for approval';
                    break;
                case CareCalendarEvent::STATUS_ASSIGNED:
                    $status = 'Assigned';
                    break;
                case CareCalendarEvent::STATUS_COMPLETED:
                    $status = 'Completed';
                    break;
            }
            echo __($status);
            ?>
        </div>
    </div>

</div>
<script type="text/javascript">
    $('#btn_edit_task').on('click', function() {
        window.location.href = '<?php echo $editUrl; ?>';
    });
</script>