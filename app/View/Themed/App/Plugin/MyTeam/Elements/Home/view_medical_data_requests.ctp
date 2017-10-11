<?php if (!empty($requestedUsersData)) { ?>
    <div class="patient_details requested_users_wraper">
        <div class="media">                            
            <div class="media-body requested_users_head">
                <h4>Patient medical information visibility</h4>
                <p class="requested_users_subhead"><?php echo __("(Manage the team member's permission to view medical records here)") ?></p>               
                <?php // if (count($requestedUsersData[0]) <= 0 && count($requestedUsersData[1]) <= 0 && count($requestedUsersData[2]) <= 0) { ?>

                <?php // } else { ?>
                
                    <!--
                    <div class="col-lg-3 form-group check_all">
                        <input type="checkbox" data-team_id="<?php // echo $teamId; ?>" id="user_select_all" class="pull-left" value="" name="requests_list[]" /> Select all
                    </div>
                    <div class="col-lg-5 form-group">
                        <select id='change_all_requests' class="form-control">
                            <option value="1">Approve</option>
                            <option value="0">Reject</option>
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <button 
                            id="change_all_requests_btn" 
                            class="btn_active btn ladda-button"
                            data-style="slide-left"
                            data-spinner-color="#3581ED"
                            disabled= 'disabled'>
                            <span class="ladda-spinner"></span><?php // echo __('Done') ?>
                        </button>
                    </div>
                    -->
                <?php // } ?>
            </div>
        </div>        
        <div class="requested_users_container"> 
            <?php
            $i = 0;
            $count = 0;
            foreach ($requestedUsersData as $requestedUsers) {
                $count = $count + intval(count($requestedUsers));
                if (!empty($requestedUsers) && is_array($requestedUsers) && count($requestedUsers) > 0) {
                    foreach ($requestedUsers as $requests) {
                        $status = $requests['TeamMember']['can_view_medical_data'];
                        ?>
                        <div id="user_row_<?php echo $requests['TeamMember']['user_id']; ?>" style="font-size: 13px;padding:0px 0px 0px 10px;" class="requested_users_row task_detail <?php if ($i % 2 == 0) echo 'task_odd'; ?>">
                            <div class="col-lg-7 col-md-7 col-sm-7 task_discription media">   
                                <!--<input type="checkbox" id="user_select_<?php // echo $requests['TeamMember']['user_id']; ?>" class="selecet_requested_users pull-left" value="<?php echo $requests['TeamMember']['user_id']; ?>" name="requests_list[]" />-->
                                <a class="pull-left" href="/profile/<?php echo $requests['User']['username']; ?>"> <?php echo Common::getUserThumb($requests['TeamMember']['user_id']); ?></a>
                                <!--</div>-->
                                <div class="task_discription requested_username media-body">     
                                    <h5 class="owner"><?php echo Common::getUserProfileLink($requests['User']['username'], FALSE, '', TRUE); ?></h5>
                                    <span class="approve_reject_msg" style="<?php // if ($status == 1) echo 'display: none;';       ?>">
                                        <?php
//                                        echo ($status == 2) ? 'Approved' : 'Rejected'; //Not Approved
                                        if ($status == 0) {
                                            echo 'No permission';
                                        } elseif ($status == 1) {
                                            echo 'Requested permission';
                                        } elseif ($status == 2) {
                                            echo 'Approved';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-7 col-sm-7 " style="padding: 30px 0px 10px 0px;">
                                        <!--<a href="javascript:void(0)" class="approve_view_med_data_request pull-left" onclick="managePermissionRequest(<?php // echo $requests['TeamMember']['user_id'] . ',' . $requests['TeamMember']['team_id'] . ',1'                    ?>, this)"><?php // echo __(h('Approve'));                    ?></a>-->
                                <!--<a href="javascript:void(0)" class="reject_view_med_data_request  pull-left" onclick="managePermissionRequest(<?php // echo $requests['TeamMember']['user_id'] . ',' . $requests['TeamMember']['team_id'] . ',0'                    ?>, this)"><?php // echo __(h('decline'));                    ?></a>--> 
                                <!--<div class="pull-right confrm_btns keep_open">-->
                <!--                                <span class="approve_reject_msg" style="<?php // if ($status == 1) echo 'display: none;';        ?>">
                                <?php // echo ($status == 2) ? 'Approved' : 'Rejected'; //Not Approved
                                ?>
                                </span>-->

                                <button type="button" 
                                        class="reject_view_med_data_request btn_normal pull-left ladda-button approve_reject_btn"
                                        data-style="slide-left"
                                        data-spinner-color="#3581ED"
                                        onclick="managePermissionRequest(<?php echo $requests['TeamMember']['user_id'] . ',' . $requests['TeamMember']['team_id'] . ',0' ?>, this)"
                                        style="<?php if ($status == 0) echo 'display: none;'; ?>">
                                    <span class="ladda-spinner"></span><?php echo __('Decline') ?>
                                </button>
                                <button type="button" 
                                        class="approve_view_med_data_request btn_active pull-left ladda-button approve_reject_btn"
                                        data-style="slide-left"
                                        data-spinner-color="#3581ED"
                                        onclick="managePermissionRequest(<?php echo $requests['TeamMember']['user_id'] . ',' . $requests['TeamMember']['team_id'] . ',1' ?>, this)"
                                        style="<?php if ($status == 2) echo 'display: none;'; ?>">
                                    <span class="ladda-spinner"></span><?php echo __('Approve') ?>
                                </button>

                                <!--</div>-->
                            </div>                

                        </div>

                        <?php
                        $i++;
                    }
                }
            }
            if ($count == 0) {
                ?>
                <div style= "font-size: 13px;padding:0px 0px 0px 10px;" class="requested_users_row task_detail task_odd">
                    <div class="col-lg-7 task_discription media">   
                        <p>No members in this team</p>
                    </div>
                </div>

            <?php }
            ?>
        </div>
        <!--<div class="view_all">-->
            <!--<a href="<?php // echo $moreTaskUrl;                                      ?>" class="pull-right owner">View All</a>-->
        <!--</div>-->
    </div>
<?php } ?>

<script>

    // to add slim scroll.
    $(document).ready(function() {
        $('.requested_users_container').each(function() {
            if ($(this).height() > 370) {
                $(this).slimScroll({
                    color: '#BBDAEC',
                    railColor: '#EBF5F7',
                    size: '12px',
                    height: '378px',
                    railVisible: true
                });
            }
        });
    });

</script>