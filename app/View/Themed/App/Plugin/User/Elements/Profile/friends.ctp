<div id="user_element_container" class="profile_page">
    <div class="community_members">
        <div class="event_wraper">
            <div class="row">
                <div class="clearfix form-group">
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <input id="search_members" type="text" name="search-friends" class="search_widget_txt search_icon form-control" data-searchbox="friends_list" placeholder="Search">
                    </div>                   
                    <?php if ($is_same) {
                        ?>
<!--                        <div class="col-lg-3 col-md-3 col-sm-3 profile_page pull-right">
                            <a href="/user/invite">
                            <button class="btn btn_invite_frnds  pull-right" data-backdrop="static" data-keyboard="false" >Invite Friends</button>
                            </a>
                        </div>-->
						<div class= "print_friends_list">
							<button class="btn pull-right print_btn friends_print"><?php echo __('Print'); ?></button>
						</div>
                        <div class="col-lg-3 pull-right">
                            <a href="/search?type=people" class="btn btn-default btn_frnd_rqst pull-right">Friend Requests
                                <span><?php echo $pending_count; ?></span>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="clearfix form-group">
                    <div id="friends_list" class="members_list">
                            <?php
                            if (!empty($friends_array)) {
                                foreach ($friends_array as $friend) {
                                    ?>
                                    <div id="<?php echo $friend['User']['id']; ?>" class="col-lg-6 ">
                                        <div class="friends_list">
                                            <div class="media">
                                                <a class="pull-left" href="<?php echo Common::getUserProfileLink($friend['User']['username'], TRUE); ?>" data-hovercard="<?php echo $friend['User']['username'];?>"> 
                                                    <?php echo Common::getUserThumb($friend['User']['id'], $friend['User']['type'], 'small'); ?>
                                                </a>
                                                <div class="media-body">
                                                    <div class="pull-left">
                                                        <h5>
                                                            <a href="<?php echo Common::getUserProfileLink($friend['User']['username'], TRUE); ?>"
                                                               class ="owner" data-hovercard="<?php echo $friend['User']['username'];?>">
                                                                    <?php echo __(h($friend['User']['username']));?>
                                                            </a>
                                                        </h5>
                                                        <p class="text_over"><?php echo $friend[0]['diseases']; ?></p>
                                                        <span class="text_over"><?php echo $friend[0]['location']; ?></span>
                                                        <?php if($is_same) {
                                                            ?>
                                                            <div class="btn-toolbar">
                                                                <div class="btn-group">
                                                                    <button class="edit_area btn  dropdown-toggle"
                                                                            data-toggle="dropdown">
                                                                        <div class="edit_member edit_arow"></div>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a id="remove_button_<?php echo $friend['User']['id'];?>" type="button" 
                                                                               onclick="removeFriend(<?php echo $friend['User']['id'];?>, true)">
                                                                                <?php echo __('Remove');?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button data-toggle="modal" data-target="#composeMessage" data-username = "<?php echo __(h($friend['User']['username']));?>" data-user-id ="<?php echo $friend['User']['id']; ?>" data-backdrop="static" data-keyboard="true" class="btn message_button btn_more pull-right ladda-button" data-style="slide-right">
                                                            <span class="ladda-spinner"></span>Message				                                    
                                                        </button>
                                                        <br />
                                                        <?php   
                                                            if ($logged_in_user['id'] != $friend['User']['id']) {
                                                                
                                                                if($friend['mutual_friends_count'] == 0){
                                                                    $class = 'mutual_frnds disabled';
                                                                } else {
                                                                    $class = 'mutual_frnds';
                                                                }
                                                            ?>
                                                            <a class="<?php echo $class;?>" 
                                                                data-user_id="<?php echo $logged_in_user['id']; ?>"
                                                                data-friend_id="<?php echo $friend['User']['id'];?>">
                                                                    <?php echo __($friend['mutual_friends_count'] .' Mutual Friends');?>
                                                            </a>
                                                            <?php 
                                                            }
                                                        ?>
                                                    </div>

                                                </div>
                                            </div></div>

                                    </div>
                                    <?php
                                }
                                ?>
                                <div id="none_found" class="col-lg-12 alert alert-warning hidden">No friends found</div>
                                <?php
                            } else {
                                ?>
                                <div id="none_found" class="col-lg-12 alert alert-warning">No friends found</div>
                                <?php
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mutualFriends" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content mutual_friends_list">
            <div class="clearfix form-group modal-header">
                <div class="row">
                    <div class="col-lg-5">                        
                        <h4 class="modal-title">
                                <?php
                                echo __('Mutual Friends');
                                ?>
                        </h4>                           
                        
                    </div>
                    <div class="col-lg-7 pull-left">
                        <input id="search_mutual_friends" type="text" name="search-mutual-friends" class="search_widget_txt search_icon form-control" data-searchbox="mutual-friends" placeholder="Search">
                    </div>
                </div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button id="close_invite_button" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo $this->element('layout/email_invite_model'); ?>
<script>
    var friendList = <?php echo $friendsListJson; ?>;
    $(document).ready(function() {
        if( ($("#friends_list .col-lg-6").length)%2 != 0 ) {
            $("#friends_list .col-lg-6:last").css('border-bottom', '0px');
        }
    });
    
    /**
     * Epmty search box when the model is closed
     */
    $('#mutualFriends').on('hidden.bs.modal', function () {
            $('#search_mutual_friends').val('');
    })
</script>

