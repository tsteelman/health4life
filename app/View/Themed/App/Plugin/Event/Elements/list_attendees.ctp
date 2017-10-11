<?php
if (isset($joinedUsersList1) && $joinedUsersList1 != NULL) {
    ?>             
    <div class="member_list">
        <div class="page-header">
            <h4><span class="pull-left">Attending  </span><span style="float: left;color: #959595;padding-left: 10px;"><?php echo __('(' . count($joinedUsersList1) . ')'); ?></span></h4></div>
        <div class="row">
            <div class="member_list_view">
                <?php
                foreach ($joinedUsersList1 as $attending) {
                    ?>    
                    <div class="col-xs-12">
                        <div class="pull-left">
                            <?php
                            echo $attending['profile_picture'];
                            ?>
                        </div>
                        <div class="indvdl_list name_details pull-left">
                            <a href="<?php echo Common::getUserProfileLink($attending['user_name'], TRUE); ?>" 
                               data-hovercard="<?php echo $attending['user_name'];?>" class="owner">
                                    <?php echo h($attending['user_name']);?>
                            </a>
                            <?php 
                            if (isset($attending['disease']) && $attending['disease'] != NULL) {
                                $AllDiseaseNames = NULL;
                                foreach ($attending['disease'] as $disease_names) {
                                    $AllDiseaseNames[] = h($disease_names['Disease']['name']);
                                }
                                ?>
                            <span class="user_disease_list" title="<?php echo implode(", ", $AllDiseaseNames); ?>">
                                    <?php echo implode(", ", $AllDiseaseNames); ?>
                                </span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php
if (isset($maybeJoinUsersList1) && $maybeJoinUsersList1 != NULL) {
    ?>
    <div class="member_list">
        <div class="page-header">
            <h4><span class="pull-left">Maybe </span><span style="float: left;color: #959595;padding-left: 10px;"><?php echo __('('.count($maybeJoinUsersList1).')'); ?></span></h4></div>
        <div class="row">
            <div class="member_list_view">
                <?php
                foreach ($maybeJoinUsersList1 as $maybeJoinUser) {
                    ?>    
                    <div class="col-xs-12">
                        <div class="pull-left">
                            <?php
                            echo $maybeJoinUser['profile_picture'];
                            ?>
                        </div>
                        <div class="indvdl_list name_details pull-left">
                            <a href="<?php echo Common::getUserProfileLink($maybeJoinUser['user_name'], TRUE); ?>" 
                               data-hovercard="<?php echo $maybeJoinUser['user_name'];?>" class="owner">
                                    <?php echo h($maybeJoinUser['user_name']);?>
                            </a>
                            <?php
                            if (isset($maybeJoinUser['disease']) && $maybeJoinUser['disease'] != NULL) {
                                $AllDiseaseNames = NULL;
                                foreach ($maybeJoinUser['disease'] as $disease_names) {
                                    $AllDiseaseNames[] = h($disease_names['Disease']['name']);
                                }
                                ?>
                                <span class="user_disease_list" title="<?php echo implode(", ", $AllDiseaseNames); ?>">
                                    <?php echo implode(", ", $AllDiseaseNames); ?>
                                </span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
//if (isset($eventType) && $eventType == 2) {
    if (isset($pendingApprovalUsersList1) && $pendingApprovalUsersList1 != NULL) {
        ?>
        <div class = "member_list">
            <div class = "page-header">
                <h4><span class="pull-left">Invited</span>  <span style="float: left;color: #959595;padding-left: 10px;"><?php echo __('('.count($pendingApprovalUsersList1).')'); ?></span></h4></div>
            <div class = "row">
                <div class="member_list_view">
                    <?php
                    foreach ($pendingApprovalUsersList1 as $pending) {
                        ?>    
                        <div class="col-xs-12">
                            <div class="pull-left">
                                <?php
                                echo $pending['profile_picture'];
                                ?>
                            </div>
                            <div class="indvdl_list name_details pull-left">
                                <a href="<?php echo Common::getUserProfileLink($pending['user_name'], TRUE); ?>" 
                                   data-hovercard="<?php echo $pending['user_name'];?>" class="owner">
                                        <?php echo h($pending['user_name']);?>
                                </a>
                                <?php
                                if (isset($pending['disease']) && $pending['disease'] != NULL) {
                                    $AllDiseaseNames = NULL;
                                    foreach ($pending['disease'] as $disease_names) {
                                        $AllDiseaseNames[] = h($disease_names['Disease']['name']);
                                    }
                                    ?>
                                    <span class="user_disease_list" title="<?php echo implode(", ", $AllDiseaseNames); ?>">
                                        <?php echo implode(", ", $AllDiseaseNames); ?>
                                    </span>
                                    <?php
                                }
                                ?>
                            </div>                  
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
//}
?>