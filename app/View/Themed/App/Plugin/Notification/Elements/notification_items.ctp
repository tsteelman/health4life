<?php foreach ($notificationData as $notification):	?>
	<li data-href="<?php echo $notification['content']['href']; ?>" class="notfctn_item <?php echo $notification['content']['class']; ?>">
		<div class="media keep_open">
			<a class="pull-left keep_open">
				<?php
				if (!empty($notification['userThumb'])) :
					echo $notification['userThumb'];
				elseif (!empty($notification['content']['icon'])):
					echo $notification['content']['icon'];
				endif;
				?>
			</a>
			<div class="media-body keep_open">
				<div class="pull-left message_notification">
					<h5 class="keep_open">
						<a class="pull-left keep_open owner"><?php echo $notification['username']; ?></a>
						<span class="pull-right"><?php echo $notification['modified']; ?></span>
					</h5>
					<span class="keep_open notification_content"><?php echo $notification['content']['text']; ?></span>
				</div>
			</div>
			<?php echo $this->element('Notification.additional_info_form', array('notification' => $notification)); ?>
		</div>
	</li>
	<?php
endforeach;