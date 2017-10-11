<div id="basic_info" class="tab-pane in active">
	<div class="row-fluid">
		<div class="span2">
			<div class="space space-4"></div>
			<?php echo $this->Html->image($community['image']); ?>
		</div>

		<div class="span9">

			<div class="profile-user-info">
				<div class="profile-info-row">
					<div class="profile-info-name"><?php echo __('Community name'); ?></div>

					<div class="profile-info-value">
						<span><?php echo h($community['name']); ?></span>
					</div>
				</div>

				<?php if (!is_null($community['description']) && ($community['description'] !== '')): ?>
					<div class="profile-info-row">
						<div class="profile-info-name"><?php echo __('Description'); ?></div>

						<div class="profile-info-value">
							<span><?php echo h($community['description']); ?></span>
						</div>
					</div>
				<?php endif; ?>

				<div class="profile-info-row">
					<div class="profile-info-name"> <?php echo __('Founded on'); ?> </div>

					<div class="profile-info-value">
						<span><?php echo h($community['created']); ?></span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name"> <?php echo __('Community leader'); ?> </div>

					<div class="profile-info-value">
						<span><?php echo h($community['leader']); ?></span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> <?php echo __('Community type'); ?> </div>

					<div class="profile-info-value">
						<span><?php echo $community['type']; ?></span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> <?php echo __('Location'); ?> </div>

					<div class="profile-info-value">
						<i class="icon-map-marker light-orange bigger-110"></i>
						<span><?php echo h($community['location']); ?></span>
					</div>
				</div>

				<div class="hr hr-8 dotted"></div>
			</div>
		</div>

		<div class="space-20"></div>

	</div>
</div>