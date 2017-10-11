<?php 
	$locationArr = array(
		$currentUserDetails['userDetails'][0]['City']['description'],
		$currentUserDetails['userDetails'][0]['State']['description'],
		$currentUserDetails['userDetails'][0]['Country']['short_name']
	);
	$location = join(',	', array_filter($locationArr));
?>
<div class="container">
    <div class="profile">
        <div class="row">
            <?php
            if ($status === MyFriends::STATUS_REQUEST_RECIEVED) {
                echo $this->element('User.Profile/approve_reject_invitation');
            }
            ?>
            <div class="col-lg-12">
                <div class="event_list">
                    <div class="event_wraper">
                        <div class="profile_container">
<!--                            <div class="row">
                                    <?php //echo $this->element('Dashboard/profile_tile', array('showFeeling' => true)); ?>
								</div>-->
<div class="row">
    
    
    
    <div class="profile_tile">
        <div class="col-lg-6 pull-left profile_tile_l" style="background: url('<?php echo $defaultProfileTileBg; ?>') 0px 0px no-repeat;">
            <div class="profile_cover_border"></div>    
            <?php if (  $is_same ) { ?>
            <div class="profile_settings_gear" id="btn_profile_bg_gear" title="Change cover image">
                <a class="pull-right" href="javascript:void(0);">
                    <img src="/theme/App/img/profile_setting.png"> 
                </a>
            </div>
            
            <?php 
                    echo $this->element('User.Profile/profile_bg_settings');
                } 
            ?>
            <div class="media profile_tile_details <?php if ( !$is_same ) { echo 'friend'; } ?>">
                <a class="pull-left" href="<?php echo Common::getUserProfileLink($currentUserDetails['userDetails'][0]['User']['username'], true); ?>">
			<?php echo Common::getUserThumb($currentUserDetails['userDetails'][0]['User']['id'], $currentUserDetails['userDetails'][0]['User']['type'], 'medium', 'user_medium_thumb profile_brdr_5');
                ?>
		</a>
                <div class="media-body">
                    <div class="row">
                        <?php if ($is_same) { ?>
                        <div class="col-lg-12">
                        <?php } else { ?>
                        <div class="col-lg-7">
                        <?php } ?>
                            <h2><?php echo $currentUserDetails['userDetails'][0]['User']['username']; ?></h2>
                             <?php 

                                /*
                                 * Check permission to view  health
                                 */
                                if ( $viewHealth ) {
                            ?>
                                    <div class="profile_feeling_wrapper">
                                         <?PHP echo $this->element('User.Myhealth/feeling_indicator');?>
                                    </div>
                            <?php 

                                }

                                /**
                                 * Has view disease permission
                                 */					
                                if ($viewDisease){ 

                                    $diseaseLength = strlen($currentUserDetails['userDiseaseDetails']);
                                    $treatmentLength = strlen($currentUserDetails['userTreatmentDetails']);
                                    if ( $is_same) {
                                        $diseaseMaxLength = $treatmentMaxLength = 54;
                                    } else {
                                        $diseaseMaxLength = $treatmentMaxLength = 23;
                                    }
                            ?>			
                            <p class="dashboard_profile_disease" <?php if($diseaseLength > $diseaseMaxLength) { ?> title="<?php echo h($currentUserDetails['userDiseaseDetails']); ?>" <?php } ?>><?php echo h($currentUserDetails['userDiseaseDetails']); ?></p>
                            <p class="dashboard_profile_disease" <?php if($treatmentLength > $treatmentMaxLength) { ?> title="<?php echo h($currentUserDetails['userTreatmentDetails']); ?>" <?php } ?>><?php echo h($currentUserDetails['userTreatmentDetails']); ?></p>
                            <?php }?>
                            
                            <p class="mrt_10"><a class="location" title="<?php echo $location; ?>"><?php echo $location; ?></a></p>
                        </div>
                        <?php
                            if (!$is_same) {
                                ?>
                                <div class="col-lg-5 profile_tile_buttons">
                           
                                    <div class="actions">
                                        <?php
                                        switch ($friend_status) {
                                            case MyFriends::STATUS_CONFIRMED :
                                                ?>
										<div><button id="remove_hovercard_button_<?php echo $user_details['id']; ?>" type="button"
                                                        class="uibutton confirm btn btn_normal ladda-button pull-right"
                                                        data-friend_id="<?php echo $user_details['id']; ?>"
                                                        data-style="expand-right"
                                                        data-spinner-color="#3581ED"
                                                        onclick="hovercardRemoveFriend(<?php echo $user_details['id']; ?>, false)">
                                                            <?php echo __('Remove friend'); ?>
                                                </button></div> 
                                                <?php
                                                break;
                                            case 0:
                                                ?>
										<div><button id="add_button_<?php echo $user_details['id']; ?>" type="button"
                                                        class="uibutton confirm btn btn_normal ladda-button pull-right"
                                                        data-style="expand-right"
                                                        data-spinner-color="#3581ED"
                                                        onclick="addFriend('<?php echo $user_details['id']; ?>', true)">
                                                            <?php echo __('Add friend'); ?>
                                                </button></div>
                                                <?php
                                                break;
                                            case MyFriends::STATUS_REQUEST_SENT:
                                                ?>
										<div><button type="button" class="uibutton confirm btn btn_normal pull-right" disabled>
                                                    <?php echo __('Awaiting approval'); ?>
                                                </button></div>
                                                <?php
                                                break;
                                            case MyFriends::STATUS_REQUEST_RECIEVED
                                                ?>
										<div><button type="button" class="uibutton confirm btn btn_normal pull-right" disabled>
                                                    <?php echo __('Awaiting your response'); ?>
                                                </button></div>
                                            <?php
                                        }
                                        ?>
                                        <button data-toggle="modal" data-target="#composeMessage" data-username = "<?php echo h($user_details['username']); ?>" data-user-id ="<?php echo $user_details['id']; ?>" data-backdrop="static" data-keyboard="true" class="btn message_button btn_active ladda-button pull-right" data-style="slide-right">
                                            <span class="ladda-spinner"></span>Send Message
                                        </button>
                                    </div>
                                 </div>
                        <?php  } ?>
                            
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 pull-right profile_tile_r">
            <?php 
                if ( $is_same ) { 
                    echo '<div id="btn_changeCover" class="profile_cover_gear" title="edit cover"></div>';
                    echo $this->element('profile_cover_settings');                     
                } 
            ?>
            <?php echo $this->element('cover_slideshow'); ?>
             <div id="cover_image_container" >
                <img src="<?php echo $defaultPhoto; ?>" />
            </div>

            <div class="profile_video pull-right"   ></div>
            <div class="minimize_icon hide" id="profile_video_minimize_icon"><img src="/theme/App/img/minimize_icon.png" /></div>
            <div id="profile_video_container_wrapper" >                    
                <div id="profile_video_container">&nbsp;</div>
            </div>
             <script type='text/javascript'>
                    <?php
                    if (isset($currentUserDetails['advertisementVideos']) && !empty($currentUserDetails['advertisementVideos'])) {
                        ?>
                        var jwFile = '<?php echo $currentUserDetails['advertisementVideos'][0]; ?>';
                        <?php
                    } else {
                        ?>
                        var jwFile = 'http://www.youtube.com/watch?v=XHXohR0EEDs';
                        <?php
                    }
                    ?>

            </script>
        </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
 
</div>
                        </div>
                        <?php echo $this->element('User.Profile/header_menu'); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">

            </div>
        </div>

        <div class="row mr_0" id="group-section-alert">



            <div class="col-lg-9">
<!--                <div class="discussion_area">
                    <div class="start_area">                        
                        <div class="event_wraper">
                            <div class="inspiring_quotation">
                                <img src="/theme/App//img/quotation_img.png" class="">
                                <?php echo Common::getQuotes(); ?>
                            </div>                        
                        </div>
                    </div>

                </div>-->


                <div  class="alert" style="display:none;">
                    <button class="close" type="button">×</button>
                    <div class="alert-content"></div>
                </div> 

				<?php
				$viewContent = $this->fetch('content');
				if (!empty($viewContent)):
					?>
					<div id="user_element_container"
						 <?php if ($controller == "following" ) { ?> class="video_chat_section" <?php } ?> >
							 <?php echo $this->fetch('content'); ?>
					</div>
				<?php endif; ?>

            </div>
            <div class="col-lg-3" id="rhs">            
                <div class="event_lhs">
                    <div id="profile_friends_online" class="member_list disease_rhs">
                        <h4>Online Friends (<?php echo $onlineFriendsCount;?>)</h4>
                        <div class="row content details_container">
                            <?php echo $this->element('User.Profile/online_friends'); ?>
                            <!--<div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));               ?></div>-->
                        </div>
                    </div>
                    <!--                    <div id="disease_patients_online" class="member_list disease_rhs">
                                            <h4>Patients Online</h4>
                                            <div class="row content details_container">
                    <?php // echo $this->element('Disease.users_with_same_disease'); ?>
                                                <div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));               ?></div>
                                            </div>
                                        </div>-->
                    <?php if ($viewTeam) : ?>
                        <?php echo $this->element('User.Profile/my_team'); ?>                        
                    <?php endif; ?>
                     <?php if (count($recommendedUsersDetails) > 0) : ?>
                    <div id="disease_ppl_mayknw_list" class="member_list disease_rhs">
                        <h4>People You May Know (<?php echo __(count($recommendedUsersDetails));?>)</h4>
                        <div class="row content details_container">
                            <?php echo $this->element('Disease.people_youmay_know_list'); ?>
                            <!--<div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));               ?></div>-->
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php echo $this->element('ads'); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    
//    $(document).on('click', '.profile_settings_gear', function(){
//        window.location.href= '/user/privacy'
//    });
    
    $(document).ready(function(){
        if( $('#feeling_date_status').length ) {
            var textEl = $('#feeling_date_status').html().trim();
            $('#feeling_date_status').html(textEl);
        }        
        initJWPlyer();
        
        // to add slim scroll to peopleYouMayKnow, friendsOnline, PatientsOnline sections.
        $('.disease_rhs .details_container').each(function() {
            if ($(this).height() > 400) {                    
                applySlimScroll( $(this), '450px' );                    
            }
        });
        
        if (isIE () == 9 || isIE11()) {
                $('#profile_video_container_wrapper').hide();
        }
    });
    
    function initJWPlyer() {
        jwplayer('profile_video_container').setup({
                    file: jwFile,
                    image: '/theme/App/img/tmp/dasboard_my_videos.png',
                    width: 554, 
                    height: 260,
                    displaytitle: false,
                    stretching: 'exactfit'
                   
        });
    }
    
    
    
</script>
<?php
$this->Html->script(array('//jwpsrv.com/library/+wt_PpJBEeOk_yIACmOLpg.js'), array('inline' => false));
$this->AssetCompress->script('profile', array('block' => 'scriptBottom'));
?>

