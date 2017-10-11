<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Community');
?>
<div class="container">
    <div class="group">
        <div class="row mr_0">

            <div class="col-lg-9">
                <div id="myGroups" class="event_list">
                    <div class="page-header">
                        <h3 class="pull-left"><?php echo __('My Communities'); ?></h3>
                        <a id="createButton" href="/community/add"  class="pull-right btn create_button <?php echo empty($myCommunities) ? ' hidden': '';?> "><?php echo __('Create a New Community'); ?></a>
                    </div>
                    <div class="content">
                        <div class="row">
							<?php if($pageCountArray[1] > 0) { ?>
								<div class="group_list">
							<?php } ?>
                                <!--<div class="text-center"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>-->
									<?php 
										$communities['nextPage'] = '';
										$communities['pageCount'] = $pageCountArray[1];
										$communities['communities'] = $myCommunities;
										$communities['community_type'] = 1;
										echo $this->element('Community.community_row', $communities);
									?>
							<?php if($pageCountArray[1] > 0) { ?>
								</div>   
							<?php } ?>
                        </div>
						
                    </div>
                </div>

                <div id="memberGroups" class="event_list <?php echo empty($belongingCommunities) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3><?php echo __('Communities I belong to'); ?></h3>
                    </div>
                    <div class="content">
						<div class="row">
                            <div class="group_list">
								<?php
									$communities['pageCount'] = $pageCountArray[2];
									$communities['communities'] = $belongingCommunities;
									$communities['community_type'] = 2;
									echo $this->element('Community.community_row', $communities);
								?>
                            </div>                           
                        </div>
                    </div>
                </div>
                
                <div id="invitedGroups" class="event_list <?php echo empty($communityInvitations) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3><?php echo __('Community Invitations'); ?></h3>
                    </div>
                    <div class="content">
						<div class="row">
                            <div class="group_list">
								<?php
									 $communities['pageCount'] = $pageCountArray[3];
									 $communities['communities'] = $communityInvitations;
									 $communities['community_type'] = 3;
									 echo $this->element('Community.community_row', $communities);
								 ?>
							</div>
						</div>	
                    </div>
                </div>
                
                <div id="awaitingApprovalGroups" class="event_list <?php echo empty($awaitingCommunities) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3><?php echo __('Awaiting Approval'); ?></h3>
                    </div>
                    <div class="content">
						<div class="row">
                            <div class="group_list">
								<?php
									$communities['pageCount'] = $pageCountArray[4];
									$communities['communities'] = $awaitingCommunities;
									$communities['community_type'] = 4;
									echo $this->element('Community.community_row', $communities);
								?>
							</div>
						</div>
                    </div>
                </div>

                <div id="interestGroups" class="event_list <?php echo empty($interestedCommunities) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3><?php echo __('Communities I might be interested in'); ?></h3>
                    </div>
                    <div class="content">
						<div class="row">
                            <div class="group_list">
								<?php
								   $communities['pageCount'] = $pageCountArray[5];
								   $communities['communities'] = $interestedCommunities;
								   $communities['community_type'] = 5;
								   echo $this->element('Community.community_row', $communities);
							   ?>    
							</div>
						</div>
                    </div>
                </div>



            </div>

            <?php echo $this->element('layout/rhs', array('list' => true)); ?>

        </div>
    </div>
</div>
<script>
    $(function() {
        //load_groups_list(1);
        //load_groups_list(2);
        //load_groups_list(3);
        //load_groups_list(4);
        //load_groups_list(5);
    });
    function load_groups_list(type, page) {
        var id;
        if (typeof(page) === "undefined") {
            page = 1;
        }
        if (page !== 1) {
            var l = Ladda.create(document.querySelector('#load-more' + type));
            l.start();
        }
        switch (type) {
            case 1:
                id = 'myGroups';
                break;
            case 2:
                id = 'memberGroups';
                break;
            case 3:
                id = 'invitedGroups';
                break;
            case 4:
                id = 'awaitingApprovalGroups';
                break;
            case 5:
                id = 'interestGroups';
                break;
        }
        load_groups_ajax(type, id, page, l);
    }

    function load_groups_ajax(type, id, page, l) {
        setTimeout(function() {
            $.ajax({
                url: '/community/community/index/' + type + '/page:' + page,
                success: function(result) {
                    if (page === 1) {
						$('#' + id + ' .content .row .group_list').html(result);
                        if (id == 'myGroups') {
                            if ($("#blank_area").length) {
                                $('#myGroupsList').removeClass('group_list');
                            }
                            $("#"+id).removeClass('border_none');
                        }
                        $('#' + id + ':has(.indvdl_group)').removeClass('hidden');
                        if($('#myGroupsList .indvdl_group').length) {
                            $("#createButton").removeClass('hidden');
                        }
                        $('#myGroupsList:has(.indvdl_group)').addClass('group_list');
                    } else {
                        $('#' + id + ' .content .row .group_list').append(result);
                    }
                }
            }).always(function() {
                if(page !== 1) {
                    l.stop();
                    $("#" + id + " #more_button" + type + page).remove();
                }
                });
            }, 1000);
//        } else {
//            var l = Ladda.create(document.querySelector('#load-more' + type));
//            l.start();
//            setTimeout(function() {
//                $.ajax({
//                    url: '/community/community/index/' + type + '/page:' + page,
//                    dataType: 'json',
//                    success: function(result) {
//                        $('#' + id + ' .content .row .group_list').append(result.htm_content);
//                        if (result.paginator.nextPage == true) {
//                            $('#' + id + ' .content').append('<div id="more_button' + type + (result.paginator.page + 1) + '" class="block">' +
//                                    '<a href="javascript:load_groups_list(' + type + ', ' + (result.paginator.page + 1) + ')" id="load-more' + type + '" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label"><?php echo __('More');?></span></a>' +
//                                    '</div>');
//                        }
//                    }
//                }).always(function() {
//                    l.stop();
//                    $("#" + id + " #more_button" + type + page).remove();
//                });
//            }, 1000);
        }

</script>