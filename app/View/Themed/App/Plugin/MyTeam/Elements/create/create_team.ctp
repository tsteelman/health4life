<div class="create_team_section">
    <div class="page-header">
        <h3><?php echo __('Create New Team'); ?></h3>
    </div>
    <div class="create_team_details">
        <div class="row">
            <div class="col-lg-7">
                
                <div id="team_step1" class="teamstep team_step_1">
                    <h4><?php echo __('I would like to create a team for supporting'); ?></h4>
                    <div class="team_for">
                            <?php
                            //if($isPatient) {
                                $patientHasPhoto = Common::userHasThumb($userDetails['id'], 'medium');
                                $patientPhoto = $patientHasPhoto ? $patientHasPhoto : '';
                                
                            ?>
                            <button class="btn btn_me userType" 
                                    data-usertype="self" 
                                    data-userid="<?php echo $userDetails['id']; ?>"  
                                    data-username="<?php echo $userDetails['username']; ?>"  
                                    data-hasuserphoto="<?php echo $patientPhoto; ?>"  
                                    data-userphoto="<?php echo Common::getUserThumb($userDetails['id'], $userDetails['type'], 'medium', '', 'link'); ?>"                                     
                                    type="button">Me</button>
                        <span class="team_span">OR</span>
                            
                            <button class="btn btn_friend userType" 
                                    data-usertype="friend" 
                                    data-userid="<?php echo $userDetails['id']; ?>"  
                                    data-username="<?php echo $userDetails['username']; ?>"  
                                    data-userphoto="<?php echo Common::getUserThumb($userDetails['id'], $userDetails['type'], 'medium', '', 'link'); ?>" 
                                    data-toggle="modal" data-target="#choose-patient-friend" data-backdrop="static" data-keyboard="false" 
                                    type="button">My Friend </button>   
                        </div>
                        
                     </div> 
               
                    <div id="team_step2" class="teamstep team_step_2" style="display:none">
                        <h4><?php echo __('I would like to create a team for supporting'); ?></h4>
                        <div class="media">
                            <button type="button" title="Cancel and choose another" class="close cancelUser" data-dismiss="modal" aria-hidden="true">×</button>
                            <a href="#" ><img id="team_userphoto" class="pull-left" src="/theme/app/img/team_default.png"></a>
                            
                                <h3 class="owner" id="team_username">Name</h3>                                
                            
                        </div>
                        <div class="team_for">
                            <button  class=" btn continue_btn gotoStep3" type="button">Continue</button>  
                            <button  class=" btn btn_clear cancelUser" type="button" >Cancel</button>   
                        </div>
                    </div>
                
                
                <div id="team_step3"  class="teamstep team_step_3" style="display:none" onsubmit="createTeam(); return false;">
                        <form name="frmCreateTeam" id="frmCreateTeam" method="POST">
                            <h4>Team For <span class="username">Name</span></h4>
                            <?php echo $this->element('MyTeam.photo_upload'); ?>                        
                            <input type="hidden" id="TeamImage" class="userphoto" name="team_photo" />
                            <input type="hidden" class="team_userid" name="team_userid" />
                             <div class="team_form">
                                 <label>Team Name <span class="red_star_span">*</span></label><span class="team_span">(50 characters)</span>
                               <input placeholder="Team name" name="team_name" id="team_name" type="text" maxlength="50"  class="form-control">
                               <div style="display:none;" id="team_name_error" class="help-block error" for="team_name">Please enter Team Name.</div>
							   <label>Short Description</label><span class="team_span">(150 characters)</span>
                               <textarea placeholder="Enter a short description about this team" name="team_about" maxlength="150" class="form-control"></textarea>
                               <label>Team privacy</label>
                               <span>
                                    <?php
                                    echo $this->Html->image('/img/calendar_tooltip_icon_small.png', array(
                                            'alt' => '?',
                                            'id' => 'team_privacy_help',
                                            'data-content' => $this->Html->nestedList($teamPrivacyHintList)
                                    ));
                                    ?>
                                    <div id="team_privacy_popover"></div>
                                </span>
                               <select name="privacy" class="form-control">
                                   <option value="1">Public</option>
                                   <option value="2" selected="selected">Private</option>                                   
                               </select>
                             </div>
                             <div class="team_for">
                                 <button  class=" btn continue_btn createTeam" type="button">Create</button>  
                                 <button  class=" btn btn_clear cancelUser" type="button" >Cancel</button>   
                             </div>
                        </form>                                
                    </div>
                
                    <div id="team_step4"  class="teamstep team_step_4" style="display:none">
                        <form name="frmCreateTeamInvite" id="frmCreateTeamInvite">
                            <h4>Invite friends to <span class="username">Name</span></h4>

                             <div class="team_for">
                                 <button  class=" btn continue_btn inviteFriends" type="button" data-toggle="modal" data-target="#friend-invite" data-backdrop="static" data-keyboard="false">Invite Now</button>  
                                 <button  class=" btn btn_clear skipInvite" type="button" >Skip this step</button>   
                             </div>
                        </form>                                
                    </div>                
                
                
                    <div class="tip_for_team">
                        <h6>Tip:</h6>
                        <ul>
							<li class="for_me">You can create a Team for yourself and invite friends to join.</li>
                            <li class="for_friend">To create a team you must first be registered as friends with the user and the user will need to approve the creation of the team.</li>
                        </ul>
                    </div>                
            </div>
        </div>            
    </div>
</div>

<!-- Team for friend-->
<div class="modal fade" id="choose-patient-friend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close invite_cancel" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Select Friend</h4>
                        </div>
                        <div class="modal-body">
                           
                            <?php echo $this->element('invite_friend_team', array('type' => 3)); ?>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for inviting friends to team-->
<div class="modal fade" id="friend-invite" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close invite_cancel" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Invite Friends</h4>
                        </div>
                        <div class="modal-body">
                            <?php echo $this->element('invite_friend_team', array('type' => 2)); ?>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
        var patientFriends = <?php echo $myFriendsListJson; ?>
</script>
