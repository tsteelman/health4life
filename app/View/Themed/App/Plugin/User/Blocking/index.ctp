<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Manage Blocking');
?>
<div class="container">
    <div class="row edit">
        <div class="col-lg-3 edit_lhs" >
			<?php echo $this->element('User.Edit/lhs'); ?>
        </div>
        <div class="col-lg-9">
			<div class="page-header">           
				<h2><span><?php echo __('Manage Blocking'); ?></span></h2>
			</div>
			<?php if (!empty($blockedUsers)) : ?>
				<div id="blocked_users">
					<?php
					foreach ($blockedUsers as $userData) :
						$blockedUser = $userData['User'];
						?>
						<div class="user_row" id="blocked_user_<?php echo $blockedUser['id']; ?>">
							<div class="col-lg-8">
								<?php echo $this->Html->link($blockedUser['photo'], $blockedUser['link'], array('escape' => false)); ?>
								<?php echo $this->Html->link($blockedUser['username'], $blockedUser['link']); ?>
								<span class="font_grey"><?php echo __(' (blocked from posting %s messages)', $userData['blocked_message_type']); ?></span>
							</div>
							<?php echo $this->Html->tag('button', __('Unblock'), array('class' => 'unblock_user btn btn-primary', 'data-user_id' => $blockedUser['id'], 'data-username' => $blockedUser['username'])); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php
			else:
				echo __("You haven't added anyone to your block list.");
			endif;
			?>
        </div>
    </div>
</div>
<?php echo $this->element('User.Blocking/confirm_unblock_dialog'); ?>