<div id="members" class="tab-pane">
	<div class="profile-users clearfix">

		<?php
		if (!empty($members)) {
			foreach ($members as $member) {
				$userName = $member['User']['username'];
				$userProfileUrl = "/admin/Users/view/{$userName}";
				$userPhoto = Common::getUserThumb($member['User']['id'], $member['User']['type'], 'small', 'profile_brdr_5', 'img');
				?>
				<div class="itemdiv memberdiv">
					<div class="inline position-relative">
						<div class="user">
							<?php echo $this->Html->link($userPhoto, $userProfileUrl, array('escape' => false)); ?>
						</div>
						<div class="body">
							<div class="name">
								<?php echo $this->Html->link(h($userName), $userProfileUrl); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		} else {
			echo __('No members found');
		}
		?>

	</div>
</div>