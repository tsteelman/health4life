<div class="patient_details">
    <div class="media">
        <a href="<?php echo $patient['profileUrl']; ?>" class="pull-left">
            <?php echo $patient['photo']; ?>
        </a>
        <div class="media-body">
            <h4><?php echo __('About %s', h($patient['name'])); ?></h4>                                
        </div>
    </div>
    <div class="detail_div">
        <div class="col-lg-3 detail_status"><p><?php echo __('Health Status'); ?></p></div>
        <div class="col-lg-9 detail_result"><span class="pull-left"><?php echo __('Updated Status as'); ?></span><span class="pull-left feeling_condition <?php echo $patient['healthStatus']['smileyClass']; ?>" title="<?php echo $patient['healthStatus']['statusText']; ?>"></span> <?php echo $patient['healthStatus']['date']; ?></div>
    </div>
    <div class="detail_div">
        <div class="col-lg-3 detail_status"><?php echo __('Age'); ?></div>
        <div class="col-lg-9 detail_result"><?php echo __('%s years', $patient['age']); ?></div>
    </div>
    <div class="detail_div">
        <div class="col-lg-3 detail_status"><?php echo __('Location'); ?></div>
        <div class="col-lg-9 detail_result"><?php echo h($patient['location']); ?></div>
    </div>

    <?php if (isset($medDataViewPermission) && $medDataViewPermission != NULL) { ?>
        <?php if ($medDataViewPermission == TeamMember::VIEW_MEDICAL_DATA_PERMISSION_APPROVED) { ?>
            <div class="detail_div">
                <div class="col-lg-3 detail_status"><?php echo __('Diagnosis'); ?></div>
                <div class="col-lg-9 detail_result"><?php echo __(h($patient['diseases'])); ?></div>
            </div>
            <div class="detail_div">
                <div class="col-lg-3 detail_status"><?php echo __('Medication'); ?></div>
                <div class="col-lg-9 detail_result"><?php echo h($patient['medications']); ?></div>
            </div>
            <div class="detail_div">
                <div class="col-lg-3 detail_status"><?php echo __('Symptoms'); ?></div>
                <div class="col-lg-9 detail_result"><?php echo h($patient['symptoms']); ?></div>
            </div> 
            <?php
        } elseif (!isset($notMember)) {
//            if ($medDataViewPermission == TeamMember::VIEW_MEDICAL_DATA_PERMISSION_REJECTED) {
            
                ?>
                <div id="med-data-rqst-msg-wraper" class="detail_div">
                    <div class="col-lg-12 detail_result med-data-rqst-msg">

                        <?php
                        switch ($medDataViewPermission) {
                            case TeamMember::VIEW_MEDICAL_DATA_PERMISSION_REJECTED:
                                echo "No permission to view patient medical data. <a href='javascript:void(0)' class='req_medical_data_permision' data-team-id='$teamId'>Request permission to view</a>";
                                break;
                            case TeamMember::VIEW_MEDICAL_DATA_PERMISSION_REQUESTED:
                                echo "Requested permission to view patient medical data. <a href='javascript:void(0)' class='no-text-decoration cursor-default' data-team-id='$teamId'>Waiting for approval</a>";
                                break;
                        }
                        ?>
                    </div>
                </div> 

                <?php
            }
        }
//    }
    ?>
</div>
