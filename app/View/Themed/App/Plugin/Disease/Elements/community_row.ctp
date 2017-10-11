<?php
if (!empty($communities)) {    
    foreach ($communities as $community) {
        ?>
<!--<div class="row">-->
        <div class="col-sm-4">
            <div class="indvdl_group">
               <div class="group_img">
                <?php echo $this->Html->image(Common::getCommunityThumb($community['Community']['id'])); ?></div>
                <a href="/community/details/index/<?php echo $community['Community']['id']; ?>">
                    <h4><?php echo h($community['Community']['name']); ?></h4> </a>
                <span>Members <span class="group_members"><?php echo __('(' . $community['Community']['member_count'] . ')'); ?></span></span>
                <span>Discussions <span class="group_members"><?php echo __('(' . $community['Community']['discussion_count'] . ')'); ?></span></span>
            </div>
        </div>
<!--</div>-->

        <?php
    }
} else {
	

        ?>
        <div id="blank_area">
                <p class="pull-left">It seems that you have not added any community yet</p>
                <a href="/community/add"  class="pull-left btn create_button"><?php echo __('Create new community'); ?>&nbsp;</a>
        </div>
        <?php
  
}
?>
