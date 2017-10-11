<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Notifications');
?>
<div class="container">
	<div class="row notification">
		<div class="col-lg-9 notification_container">

			<h2><?php echo __('Notifications'); ?></h2> 

			<div class="notification_list"><?php if (isset($notificationData) && !empty($notificationData)) : ?>
					<div id="notification_list_container">
						<?php
						foreach ($notificationData as $notification):
							$textClass = isset($notification['content']['icon']) ? 'pull-left mt_8' : '';
							?>
							<div data-href="<?php echo $notification['content']['href']; ?>" class="notfctn_item <?php echo $notification['content']['class']; ?>">
								<div class="media">
									<?php if (!empty($notification['userThumb'])) : ?>
										<a class="pull-left">
											<?php echo $notification['userThumb']; ?>
										</a>
									<?php elseif (!empty($notification['content']['icon'])): ?>
										<a class="pull-left">
											<?php echo $notification['content']['icon']; ?>
										</a>
									<?php endif; ?>
									<div class="media-body">
										<div class="">
											<h5 class="">
												<?php if (!empty($notification['username'])) : ?>
													<a class="pull-left owner"><?php echo $notification['username']; ?></a>
												<?php endif; ?>
												<span class="pull-right"><?php echo $notification['modified']; ?></span>
											</h5>
											<span class="<?php echo $textClass; ?>"><?php echo $notification['content']['text']; ?></span>
										</div>
									</div>
									<?php echo $this->element('Notification.additional_info_form', array('notification' => $notification)); ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div>
						<?php echo __('No notifications.'); ?>
					</div>
				<?php endif; ?></div>
		</div>
		<?php echo $this->element('layout/rhs', array('list' => true)); ?>
	</div>
</div>