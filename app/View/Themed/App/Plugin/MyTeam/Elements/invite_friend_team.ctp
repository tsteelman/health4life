<div id="import_contact_step_1">
    <div class="people_list team_invite">
        <?php if($type == 3) { ?> 
            <div class="row">
                <div class="col-lg-3"><label>Search Friend</label></div>
                                <div class="col-lg-9">
                                        <input id="search_friends" type="text" name="search-friends" data-searchBox="invite_patient_friend" class="search_widget_txt form-control" placeholder="Search">
                                </div>
            </div>
        <?php } ?>
        <p style="font-size: 18px">            
            <?php
            $friendsCount = count($myFriends);
            if ($friendsCount > 0 && $type != 3) {
                echo __(" Invite Friends to the team.");
            }
            ?>
        </p>
        <?php if ($friendsCount > 0): ?>
            <?php if($type != 3) { ?> 
                <div class="select_all">
                    <?php
                        echo $this->Form->checkbox('select_all_friends');
                        echo $this->Html->tag('label', 'Select All', array('for' => 'select_all_friends', 'class' => 'select_all_checkbox'));
                    ?>
                    <span class="pull-right"><span id="selected_friends_count">0</span> selected</span>
                </div>
                <div class="row">
                    <div class="col-lg-3"><label>Search Friend</label></div>
                    <div class="col-lg-9">
                        <?php if($type == 1) { ?> 
                            <input id="search_friends" type="text" name="search-friends" data-searchBox="invite_team_member" class="search_widget_txt form-control" placeholder="Search">
                        <?php } else if($type == 2) { ?>   
                            <input id="search_friends" type="text" name="search-friends" data-searchBox="invite_my_friends" class="search_widget_txt form-control" placeholder="Search">
                        <?php } ?>   
                    </div>
                </div>
             
            <?php } ?>
            <div class="contact_list slim-scroll">
                <?php foreach ($myFriends as $friend): ?>
              
                        <div class="contact_persons col-lg-6 invite_team_members<?php echo $friend['User']['friend_id']; ?>">
                            <input type="checkbox" 
                                   id="user_select_<?php echo $friend['User']['friend_id']; ?>"
                                   data-username="<?php echo h($friend['User']['friend_name']); ?>"
                                   data-hasuserphoto="<?php echo Common::userHasThumb($friend['User']['friend_id'], 'medium'); ?>"
                                   data-userphoto="<?php echo Common::getUserThumb($friend['User']['friend_id'], $friend['User']['friend_type'], 'medium', '', 'link'); ?>"
                                   data-userid="<?php echo $friend['User']['friend_id']; ?>"
                                   class="pull-left <?php if($type == 3) { ?> patient-select <?php } ?>" value="<?php echo $friend['User']['friend_id']; ?>" name="friends_list[]" />
                            <div class="media">
                                <a class="pull-left"> 
                                    <?php echo Common::getUserThumb($friend['User']['friend_id'], $friend['User']['friend_type'], 'small'); ?>  
                                </a>
                                <div class="media-body">
                                    <div class="pull-left">
                                        <h5>
                                            <a class="owner">
                                                <?php echo h($friend['User']['friend_name']); ?>
                                            </a>
                                        </h5>
                                        <?php
                                              $diseaseLimit = 28;
                                              if (isset($friend['Disease'][0]) && ($friend['Disease'][0] !== '')) { ?>
                                                    <span><?php echo h(String::truncate($friend['Disease'][0]['Diseases']['name'], $diseaseLimit, array('exact' => true))); ?></span><br/>
                                         <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
                <div class="col-lg-12 alert alert-warning hidden none_found">No friends found</div>
            </div>
        <div id="team_error_message" class="alert alert-error" style="display: none;">Please choose someone</div>
        <div class="modal-footer block"> 
                <?php if($type == 1) { ?> 
                    <button type="button" class="btn btn_active invite_submit" onclick = "listFriends(<?php  echo $type. ',' . $teamId ?>);"><?php echo __('Ok'); ?></button>
                <?php } else if($type == 2) { ?>   
                    <button type="button" class="btn btn_active invite_submit" onclick = "listFriends(<?php  echo $type ?>, getTeamDetails().team_id );"><?php echo __('Ok'); ?></button>
                <?php } else if($type == 3) { ?>  
                    <button type="button" class="btn btn_active invite_submit" onclick = "listFriends(<?php  echo $type ?>);"><?php echo __('Ok'); ?></button>    
                <?php } ?>     
                <button id="close_invite_button" type="button" class="btn btn_clear invite_cancel" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
        </div>
        <?php else : ?>
            <p> No friends to invite</p>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
        var myFriendList = <?php echo $myFriendsListJson; ?>
</script>