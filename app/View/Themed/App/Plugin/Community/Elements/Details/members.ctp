<div class="community_members">
    <input id="group-id" type="hidden" value="<?php echo $community_id; ?>">
    <?php
    if (isset($communityData['Community']['type']) && $communityData['Community']['type'] != NULL && $communityData['Community']['type'] == 2) {
        if (isset($not_approved_members) && $not_approved_members != NULL && $current_user_type >= 2) {
            ?>
            <div class="event_wraper">
                <?php foreach ($not_approved_members as $not_approved_member) { ?>
                    <div class="media">
                        <a class="pull-left" href="#"> 
                            <?php echo Common::getUserThumb($not_approved_member['id'], $not_approved_member['type'], 'small'); ?> 
                        </a>
                        <div class="media-body">
                            <div class="pull-left">
                                <h5>
                                    <a href="<?php echo Common::getUserProfileLink($not_approved_member['username'], TRUE); ?>" 
                                       data-hovercard="<?php echo $not_approved_member['username']; ?>" class="owner">
                                           <?php echo __(h($not_approved_member['username'])); ?>
                                    </a>
                                </h5>
                            </div>
                            <button id="reject_<?php echo $not_approved_member['id']; ?>" class="group-member-approve-reject-btn btn btn_normal pull-right ladda-button" data-style="slide-right" onclick="updateCommunityMemberStatus('ignore', <?php echo $not_approved_member['id']; ?>, this)"><span class="ladda-spinner"></span>Reject</button>
                            <button id="add_<?php echo $not_approved_member['id']; ?>" class="group-member-approve-reject-btn confirm btn btn_add pull-right ladda-button" data-style="slide-right" onclick="updateCommunityMemberStatus('add', <?php echo $not_approved_member['id']; ?>, this)"><span class="ladda-spinner"></span>Add</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
    <?php
    if (($communityData['Community']['type'] != NULL && $communityData['Community']['type'] == 1) || $current_user_status == 1) {

        if (isset($all_approved_members) && $all_approved_members != NULL) {
            ?>
            <div class="event_wraper">
                <div class="row">
                    <div class="clearfix form-group">
                        <script type="text/javascript">
                            var friendList = <?php echo $friendsListJson; ?>;
                            var membersList = <?php echo $membersListJson; ?>;
                        </script>
                        <div class="col-lg-5">
                            <input id="search_members" type="text" name="search-friends"
                                   class="search_widget_txt search_icon form-control" 
                                   data-searchBox="invite_members" placeholder="Search">
                        </div>
                        <?php
                        if ((isset($current_user_status)) && ($current_user_status == 1)) {
                            if ((isset($communityData['Community']['member_can_invite']) && $communityData['Community']['member_can_invite'] == 1) || ($current_user_type >= 2 )) {
                                ?>
                                <div class="col-lg-4 pull-right">
                                    <button class="btn btn_invite_frnds pull-right" data-toggle="modal" data-target="#inviteFriends" data-backdrop="static" data-keyboard="false" onclick="inviteButtonStatus()">Invite Friends</button>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="clearfix form-group">
                        <div id="invite_members" class="members_list">
                            <div class="col-lg-12">
                                <?php foreach ($all_approved_members as $approved_member) { ?> 
                                    <div id="<?php echo $approved_member['user']['id']; ?>" class="col-lg-4 col-sm-4 col-md-4">
                                        <div class="indvdl_membr">
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <?php echo Common::getUserThumb($approved_member['user']['id'], $approved_member['user']['type'], 'small'); ?>
                                                </a>
                                                <div class="media-body">
                                                    <h5>
                                                        <a href="<?php echo Common::getUserProfileLink($approved_member['user'] ['username'], TRUE); ?>" 
                                                           data-hovercard="<?php echo $approved_member['user']['username']; ?>" class="owner">
                                                               <?php echo __(h($approved_member['user'] ['username'])); ?>
                                                        </a>
                                                    </h5>
                                                    <?php if ($communityData['Community']['created_by'] == $current_user && $communityData['Community']['created_by'] != $approved_member['user']['id']) { ?>
                                                        <div class="btn-toolbar">
                                                            <div class="btn-group">
                                                                <button class="edit_area btn  dropdown-toggle"
                                                                        data-toggle="dropdown">
                                                                    <div class="edit_member edit_arow"></div>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a href="javascript:void(0)" onclick="updateCommunityMemberStatus('update_admin', <?php echo $approved_member['user']['id']; ?>, this)">
                                                                            <?php
                                                                            if ($approved_member['admin_status'] == 2) {
                                                                                echo __('Remove from admin');
                                                                            } else {
                                                                                echo __('Make Admin');
                                                                            }
                                                                            ?>
                                                                        </a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div id="none_found" class="col-lg-12 alert alert-warning hidden">No members found</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
<!-- Modal -->
<div class="modal fade inviteFriend" id="inviteFriends" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close invite_close_button" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" style="color:#fff">Invite Friends</h4>
			</div>
            <div class="modal-body">
                <div class="import_contact_step_1">
                    <?php echo $this->element('invite_friends'); ?>
                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Invitation has been sent
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e1e1e1 !important;">
                <button id="close_invite_button" type="button" class="btn btn-default invite_close_button" data-dismiss="modal">Close</button>
                <button id="invite_button" type="button" class="btn btn-primary ladda-button" data-style="expand-right" onclick = "inviteFriends(<?php echo $community_id . ',' . $current_user . ',2'; ?>);" disabled><span class="ladda-label">Invite</span><span class="ladda-spinner"></span></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->