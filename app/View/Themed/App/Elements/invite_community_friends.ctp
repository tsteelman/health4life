<?php $communityCount = 0; 
      $allFriendsInvited = true;
?>
<div class="select_all">
	  <input type="checkbox" class="select_all_community_check_box" id="selectAll">
      <?php
           echo $this->Html->tag('label', 'Select All Community', array('for' => 'selectAll', 'class' => 'select_all_checkbox'));
      ?>
      <span class="pull-right"><span id="selected_community_count">0</span> selected</span>
</div>
<div id="invite_community_friends" class="clearfix form-group">
    <div class="col-lg-12">
        <div id="communities_list" class="invite_communities_list">
            <?php			
            if (is_array($communities) && !empty($communities)) {
                foreach ($communities as $community) {
					?>
				
					  <div id="<?php echo $community['Community']['id']; ?>" class="col-lg-6 col-sm-6 col-md-6">
                                <div id="invite_community<?php echo $community['Community']['id']; ?>" class="invite_community community_not_invited">
                                    <input type="checkbox" id="community<?php echo $communityCount; ?>" class="pull-left invite_box" value="<?php echo $community['Community']['id']; ?>" name="community_id[<?php
                                    echo $communityCount;
                                    $communityCount++;
                                    ?>]">
<!--                                    <div class="pull-left profile_img">
                                        
                                    </div>-->
                                    <label class="pull-left"><?php echo $community['Community']['name']; ?> </label>
									&nbsp;( <?php echo $community['Community']['member_count']; ?> Members )
                                </div>
                            </div>
			<?php
                }
                ?>
                <div id="none_found" class="col-lg-12 alert alert-warning hidden">You have not joined any communities.</div>
                <?php
            } else {
                ?>
                <div id="none_found" class="col-lg-12 alert alert-warning">You have not joined any communities.</div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php if($allFriendsInvited == true) { ?>
<script>
//	$(".select_all_check_box").prop('disabled', true);
</script>
<?php } ?>