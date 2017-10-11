<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
	$this->Html->addCrumb('My Profile', '/profile');
} else {
	$this->Html->addCrumb($user_details['username'] . "'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Following');
$this->extend('Profile/view');
$diseaseCount = count($diseaseFollowList);
$profileCount = count($profileFollowList);
?>
<div class="followers_container">
    <h3>Disease following</h3>
    <div id="tabs" class="followers_tab">
        <ul>
			<?php if (!empty($diseaseFollowList)): ?>	
				<li><a href="#disease-tab"><span>Disease</span></a></li>
			<?php endif; ?>	
			<?php if (!empty($profileFollowList)): ?>		
				<!--<li><a href="#profile-tab"><span>Profile</span></a></li>-->
			<?php endif; ?>
        </ul>
        <div id="disease-tab">
			<?php
			$disNum = 0;
			if ($diseaseCount == 0) {
				?>
				<div class="text-center friends_noresult_padding">
					<?php if ($is_same) { ?>
						You are not following any disease yet.	
					<?php } else { ?>
						This user is not following any disease yet.
					<?php } ?>			

				</div>		
				<?php
			}

			foreach ($diseaseFollowList as $diseaseFollow):
				$disease_image = Common::getDiseaseThumb($diseaseFollow['Disease']['id']);
				if (($disNum % 2) == 0) {
					?>		
					<div class="disease_followers  followers_list">
						<?php
					}
					?>
					<div  class="col-lg-6 col-md-6 col-sm-6  followers clearfix" >

						<div class="media">
							<?php if (isset($disease_image) && $disease_image != '') { ?>
								<a class="pull-left" href="/condition/index/<?php echo $diseaseFollow['Disease']['id']; ?>">
									<img src="<?php echo $disease_image; ?>" class="border_caregiver  user_small_thumb "> 
								</a>
							<?php } ?>
							<div class="media-body">
								<div class="pull-left">
									<h5>
										<?php
										if ($is_same) {
											$name = h(Common::truncate($diseaseFollow['Disease']['name'], 18));
										} else {
											$name = h(Common::truncate($diseaseFollow['Disease']['name'], 34));
										}
										?>
										<a title="<?php echo $name['title']; ?>" href="/condition/index/<?php echo $diseaseFollow['Disease']['id']; ?>">
											<?php
											echo $name['name'];
											?>
										</a>
									</h5>
									<?php
									if ($is_same) {
										?>
										<div class="btn-toolbar">
											<div class="btn-group">
												<button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">
													<div class="edit_member edit_arow"></div>
												</button>
												<ul data-notification-type="<?php echo FollowingPage::DISEASE_TYPE; ?>" id="disease-follow-user-menu" class="dropdown-menu" data-follow-id="<?php echo $diseaseFollow['Disease']['id']; ?>">
													<li data-status="<?php echo FollowingPage::NOTIFICATION_ON; ?>" class="notification_on notification_switch" <?php if ($diseaseFollow['FollowingPage']['notification']) { ?> style="display: none" <?php } ?>>
														<a id="remove_button_62" type="button" onclick="">
															Notification on                                                                            </a>
													</li>
													<li data-notification-type="<?php echo FollowingPage::DISEASE_TYPE; ?>" data-status="<?php echo FollowingPage::NOTIFICATION_OFF; ?>" class="notification_off notification_switch" <?php if (!$diseaseFollow['FollowingPage']['notification']) { ?> style="display: none" <?php } ?>>
														<a id="remove_button_62" type="button" onclick="">
															Notification off                                                                            </a>
													</li>
												</ul>
											</div>
										</div>
									<?php } ?>
								</div>
								<?php
								if ($is_same) {
									?>
									<div class="pull-right">
										<button class="btn disease_follow_btn pull-right btn_normal ladda-button" style="display:none;" data-disease-id="<?php echo $diseaseFollow['Disease']['id']; ?>">
											Follow</button>						
										<button data-toggle="modal" data-disease-id="<?php echo $diseaseFollow['Disease']['id']; ?>" class="btn  disease_unfollow_btn pull-right btn_normal ladda-button" >Unfollow</button>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php
					$disNum++;
					if ((($disNum % 2) == 0) || ($diseaseCount == $disNum)) {
						?>            
					</div>
					<?php
				}

			endforeach;
			?>
        </div>


        <div id="profile-tab">
			<?php
//$profileNum = 0;
//
//foreach ($profileFollowList as $profileFollow):
//	 $location = "";
//        $location = $profileFollow['City']['description']  . ', ' . $profileFollow['State']['description'];
//
//	if (($profileNum % 2) == 0) {
			?>
			<!--		            <div class="followers_list profile_followers">
			<?php //} ?>
								<div  class="col-lg-6  followers" >
			
									<div class="media">
										<a class="pull-left" href="<?php echo Common::getUserProfileLink($profileFollow['User']['username'], true); ?>">
			<?php echo Common::getUserThumb($profileFollow['User']['id'], $profileFollow['User']['type']); ?> 
										</a>
										<div class="media-body">
											<div class="pull-left">
												<h5>
			<?php echo Common::getUserProfileLink($profileFollow['User']['username'], FALSE); ?>
												</h5>
			<?php
			if (!is_null($profileFollow['Disease']['name'])):
				$disease_name = Common::truncate($profileFollow['Disease']['name'], 18);
				?>
													<p><?php echo $disease_name['name']; ?></p>
			<?php endif; ?>
												<span><?php echo $location; ?></span>
			<?php
			if ($is_same) {
				?>
													<div class="btn-toolbar">
														<div class="btn-group">
															<button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">
																<div class="edit_member edit_arow"></div>
															</button>
															<ul data-notification-type="<?php echo FollowingPage::USER_TYPE; ?>" id="profile-follow-dropdown-menu" class="dropdown-menu" data-follow-id="<?php echo $profileFollow['User']['id']; ?>">
																<li data-status="<?php echo FollowingPage::NOTIFICATION_ON; ?>" class="notification_on notification_switch" <?php if ($profileFollow['FollowingPage']['notification']) { ?> style="display: none" <?php } ?>>
																	<a id="remove_button_62" type="button" onclick="">
																		Notification on                                                                            </a>
																</li>
																<li data-status="<?php echo FollowingPage::NOTIFICATION_OFF; ?>" class="notification_off notification_switch" <?php if (!$profileFollow['FollowingPage']['notification']) { ?> style="display: none" <?php } ?>>
																	<a id="remove_button_62" type="button" onclick="">
																		Notification off                                                                            </a>
																</li>
															</ul>
														</div>
													</div>
			<?php } ?>
											</div>
			<?php
			if ($is_same) {
				?>
												<div class="pull-right">
														<button class="btn profile_follow_btn pull-right btn_normal ladda-button" style="display:none;" data-profile-id="<?php echo $profileFollow['User']['id']; ?>">
											Follow</button>						
													<button data-toggle="modal"  class="btn  profile_unfollow_btn pull-right btn_normal ladda-button" data-profile-id="<?php echo $profileFollow['User']['id']; ?>">Unfollow</button>
												</div>
<?php } ?>
										</div>
									</div>
								</div>
			
			<?php
//	$profileNum++;
//	if ((($profileNum % 2) == 0) || ($profileCount == $profileNum)) {
			?>            
								</div>-->
			<?php
//					}
//				endforeach;
			?>
        </div>
	</div>

</div>

<script>
	$("#tabs").tabs();
</script>