<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb($username);
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1 class="blue">
            <span class="middle">
				<?php echo h($profileUser['name']); ?>
            </span>
        </h1>
    </div>
    <div id="user_profile" class="user-profile row-fluid">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-18">
                <li class="active">
                    <a data-toggle="tab" href="#home">
                        <i class="green icon-user bigger-120"></i>
						<?php echo __('Profile'); ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content no-border padding-24">
                <div id="home" class="tab-pane in active">
                    <div class="row-fluid">
                        <div class="span2">
							<?php echo $profileImg; ?>
							<div class="space space-8"></div>
							<a class="span10 green" href="/admin/users/editProfile">
								<i class="icon-pencil bigger-130"></i>
								<?php echo __('Edit Profile'); ?>
							</a>
                        </div>

                        <div class="span9">
                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"><?php echo __('Username'); ?></div>
                                    <div class="profile-info-value">
                                        <span><?php echo h($profileUser['username']); ?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Email'); ?> </div>
                                    <div class="profile-info-value">
                                        <span><?php echo h($profileUser['email']); ?></span>
                                    </div>
                                </div>

								<?php if (isset($profileUser['date_of_birth'])): ?>
									<div class="profile-info-row">
										<div class="profile-info-name"> <?php echo __('Birth Date'); ?> </div>
										<div class="profile-info-value">
											<span><?php echo $profileUser['date_of_birth']; ?></span>
										</div>
									</div>
								<?php endif; ?>

								<?php if (isset($profileUser['gender'])): ?>
									<div class="profile-info-row">
										<div class="profile-info-name"> <?php echo __('Gender'); ?> </div>
										<div class="profile-info-value">
											<span><?php echo $profileUser['gender']; ?></span>
										</div>
									</div>
								<?php endif; ?>

								<div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Timezone'); ?> </div>
                                    <div class="profile-info-value">
                                        <span><?php echo $profileUser['timezone']; ?></span>
                                    </div>
                                </div>
								<div class="profile-info-row">
                                    <div class="profile-info-name"> <?php echo __('Is Super Admin?'); ?> </div>
                                    <div class="profile-info-value">
                                        <span><?php echo $superAdminStatus; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="hr hr-8 dotted"></div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>