<?php
        $i = 0;
        foreach ($tasks as $task) {
            ?>
            <div style="font-size: 13px;padding:0px 0px 0px 10px;" class="task_detail <?php if ($i % 2 == 0) echo 'task_odd'; ?>">

                <div class="col-lg-2" style="padding:10px 0px 0px 0px;width: 10%;text-align: center;">     
                    <?php echo Common::getTaskTypeIcon($task['CareCalendarEvent']['type'], false); ?>
                </div>
                <div class="col-lg-4 task_discription">     
                    <a href="<?php echo $taskDetailsBaseUrl . $task['Event']['id']; ?>"><?php echo __(h($task['Event']['name'])); ?></a> 
                </div>
                <div class="col-lg-4" style="padding: 10px 0px 10px 0px;">
                    <sapn>
                        <?php
                        switch ($task['CareCalendarEvent']['status']) {
                            case 0:
                                echo __(h('Open'));
                                break;
                            case 1:
                                echo __(h('Waiting for approval'));
                                //echo Common::getUserProfileLink($task['Assignee']['username'], false, 'owner', false);
                                break;
                            case 2:
                                echo __(h('Assigned To '));
                                echo Common::getUserProfileLink($task['Assignee']['username'], false, 'owner', false);
                                break;
                            case 3:
                                echo __(h('Completed'));
                        }
                        ?>
                        </span> 
                        <a href="task_list.ctp"></a>
                </div>
                <div class="col-lg-2 task_date"><?php echo CakeTime::nice($task['Event']['start_date'], $timezone, '%m-%d-%Y'); ?></div>                

            </div>

            <?php
            $i++;
        }
        ?>

        <input type="hidden" value="<?php echo $todayOffset; ?>" id="todayOffset">
        <div class="view_all">
             <div class="col-lg-4">
             <?php 
                  if (  $prevOffset != -1 ) { ?>
                   
                        <a data-offset="<?php echo $prevOffset; ?>" href="javascript:void(0);" class="paginator-link prev pull-left owner">View Prev</a>
                    
            <?php } ?>
            </div>
            <div class="col-lg-4 text-center">
            <?php 
                  if ( ! $isToday ) { ?>
                    
                        <a  data-offset="<?php echo $todayOffset; ?>" href="javascript:void(0);" class="paginator-link owner">Today</a>
                    
            <?php } ?>
            </div> 
            <div class="col-lg-4">
            <?php 
                  if ( !empty( $nextOffset ) ) { ?>
                  
                        <a  data-offset="<?php echo $nextOffset; ?>" href="javascript:void(0);" class="paginator-link next pull-right owner">View Next</a>
                   
            <?php } ?>
            </div>                      
        </div>
       