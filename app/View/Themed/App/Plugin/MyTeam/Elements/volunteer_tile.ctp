<div class="col-lg-3">

    <?php
    if(isset($teams_count['total'])) {
        $count = $teams_count['total'];
        if(($teams_count['total'] == $teams_count['user_invited']) && ($teams_count['user_invited'] != 0)) {
            $count = TRUE;
        }
    } else {
        $count = TRUE;
    }
    
    if ($hasTeamCreatePermission && ($count != 0 || $count == TRUE) ) {
        ?>
<!--        <div id="team_create_main_rhs" class="team_invitations" >
            <div  class="create_team_container" >
                <div class="row">
                    <div class="team_container col-lg-4 rhs_create_team">
                        <div class="team_details create_team">
                            <img src="/theme/app/img/create_team_default.png">
                            <h3>Do you want to help someone you care for?</h3>            
                        </div>
                        <div class="team_members_details create_team_button">
                            <a href="/myteam/create" class="  pull-left">Create New Team</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    <?php } ?>

    <?php if ($isVolunteer == false) { ?>
        <div class="volunteer_div">
            <h4>Do you want to Volunteer ?</h4>
            <p>Do you wish to care for a loved one or friend or need who some help? </p>
            <button class="btn btn_active pull-left" id="create_volunteer" type="button" >Yes</button>
        </div>
    <?php } else { ?>
        <div class="volunteer_div">
            <h4>You are a Volunteer!</h4>
            <p>No, I do not want to volunteer anymore</p>
            <div class="change_link" data-toggle="modal" data-target="#delete_volunteer" data-backdrop="static" data-keyboard="false"><a>Click here to change</a></div>
        </div>
    <?php } ?>
    <?php echo $this->element('ads'); ?>
</div>

<!--Modal for volunteer change-->
<div class="modal fade" id="delete_volunteer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change from volunteer</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to change?
                <div class="modal-footer">                
                    <button id="remove_volunteer" type="button" class="btn btn_active ladda-button" data-style="expand-right"><span class="ladda-label">Yes</span><span class="ladda-spinner"></span></button>
                    <button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->