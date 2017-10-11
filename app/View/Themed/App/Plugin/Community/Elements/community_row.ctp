<?php
if (!empty($communities)) {
    foreach ($communities as $community) {
        $community_url = "/community/details/index/".$community['Community']['id'];
        ?>
        <div class="col-sm-4">
            <div class="indvdl_group">
                <div class="group_img">
                    <a href="<?php echo $community_url ?>">  <?php echo $this->Html->image(Common::getCommunityThumb($community['Community']['id'])); ?></a></div>
                <a href="<?php echo $community_url ?>">
                    <h4><?php echo h($community['Community']['name']); ?></h4> </a>
                <div class="clearfix">
                <span class="pull-left">Members <span class="group_members"><?php echo __('(' . $community['Community']['member_count'] . ')'); ?></span></span>
                <span class="pull-right">Discussions <span class="group_members"><?php echo __('(' . $community['Community']['discussion_count'] . ')'); ?></span></span>
                </div>
            </div>
        </div>

        <?php
    }
	//if ($this->Paginator->param('nextPage')) {
            if(empty($nextPage) || !isset($nextPage)) { 
                 if(isset($pageCount) && $pageCount > 1) {
                   $nextPage = 2; 
                }
            }
            if(!empty($nextPage) && $nextPage <= $pageCount) {
            ?>
                <div id="more_button<?php echo $community_type . $nextPage; ?>" class="block">
                    <a href="javascript:load_groups_list(<?php echo $community_type; ?>,<?php echo $nextPage; ?>);" id="load-more<?php echo $community_type; ?>" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED" style="color: #2c589e; margin-right: 5px;">
                        <span class="ladda-label"><?php echo __('More'); ?></span>
                    </a>
                </div>
            <?php
            }
   // }
} else { 

    if ($community_type == '0') {
        ?>
        <div class="text-center friends_noresult_padding" >
            <p>Sorry, no results containing all your search terms were found.</p>
        </div>
        <?php
    } else if ($community_type == '1') {
        ?>
		<div class="row" id="blank_area" style="padding: 0;">
            <p class="pull-left">It seems that you have not added any community yet</p>
            <a href="/community/add"  class="pull-left btn create_button"><?php echo __('Create new community'); ?>&nbsp;</a>
        </div>
        <?php
    } else if ($community_type == '6') {
        ?>
        <div class="text-center noresult_padding" >
            <p class="alert alert-error">No community joined.</p>
        </div>
        <?php
    }
}
?>