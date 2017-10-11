<?php $friendCount = 0; 
      $allFriendsInvited = true;
?>
<div class="select_all">
	  <input type="checkbox" class="select_all_check_box" id="selectAll">
      <?php
           echo $this->Html->tag('label', 'Select All Friends', array('for' => 'selectAll', 'class' => 'select_all_checkbox'));
      ?>
      <span class="pull-right"><span id="selected_count">0</span> selected</span>
</div>
<div id="search_friends_list" class="clearfix form-group">
    <div class="col-lg-4">
        <label>
            <?php
//          echo (isset($isCommunityEvent) && ($isCommunityEvent === true)) ? __('Invite more friends') : __('Invite Friends');
			echo __('Search Friends');
            ?>
        </label>
    </div>
    <div class="col-lg-8">
        <input id="search_friends" type="text" name="search-friends" data-searchBox="invite_friends" class="search_widget_txt form-control" placeholder="Search">
    </div>
</div>
<div id="invite_friends" class="clearfix form-group">
    <div class="col-lg-12">
        <div id="friends_list" class="invite_frnds_list">
            <?php
            if (is_array($friendsList) && !empty($friendsList)) {
                foreach ($friendsList as $friend) {
                    if (isset($friend['status'])) {
                        if ($friend['status'] === 'not invited') {
							$allFriendsInvited = false;
                            ?>
                            <div id="<?php echo $friend['friend_id']; ?>" class="col-lg-4 col-sm-4 col-md-4">
                                <div id="invite_friend<?php echo $friend['friend_id']; ?>" class="invite_frnds not_invited">
                                    <input type="checkbox" id="friend<?php echo $friendCount; ?>" class="pull-left invite_box" value="<?php echo $friend['friend_id']; ?>" name="friend_id[<?php
                                    echo $friendCount;
                                    $friendCount++;
                                    ?>]">
                                    <div class="pull-left profile_img">
                                        <?php echo  Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'x_small');?>
                                    </div>
                                    <label class="pull-left"><?php echo $friend['friend_name']; ?></label>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div id="<?php echo $friend['friend_id']; ?>"class="col-lg-4 col-sm-4 col-md-4">
                                <div id="invite_friend<?php echo $friend['friend_id']; ?>" class="invite_frnds active">
                                    <div class="pull-left profile_img">
                                        <?php echo Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'x_small');?>
                                    </div>
                                    <label class="pull-left"><?php echo $friend['friend_name']; ?></label>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
						$allFriendsInvited = false;
                        ?>
                        <div id="<?php echo $friend['friend_id']; ?>" class="col-lg-4 col-sm-4 col-md-4">
                            <div id="invite_friend<?php echo $friend['friend_id']; ?>" class="invite_frnds not_invited">
                                <input type="checkbox" id="friend<?php echo $friendCount; ?>" class="pull-left invite_box" value="<?php echo $friend['friend_id']; ?>" name="friend_id[<?php
                                echo $friendCount;
                                $friendCount++;
                                ?>]">
                                <div class="pull-left profile_img">
                                  <?php echo Common::getUserThumb($friend['friend_id'], $friend['friend_type'], 'x_small');?>
                                </div>
                                <label class="pull-left"><?php echo $friend['friend_name']; ?></label>
                            </div>
                        </div>
                        <?php
                    }
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
<?php if($allFriendsInvited == true) { ?>
<script>
	$(".select_all_check_box").prop('disabled', true);
</script>
<?php } ?>