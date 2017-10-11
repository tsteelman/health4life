<?php
$sender = $message['User'];
$profileUrl = Common::getUserProfileLink( $sender['username'], TRUE);
$userThumb = Common::getUserThumb($sender['id'], $sender['type'], 'small');
?>
<div class="message_details">
	<div class="media">
		<?php if (isset($isLast) && $isLast === true): ?>
			<span id="last_msg_top"></span>
		<?php endif; ?>
		<?php
		echo $this->Html->link($userThumb, $profileUrl, array('class' => 'pull-left', 'escape' => false));
		?>
		<div class="media-body">
			<div class="clearfix">
				<h5 class="pull-left">
					<?php
					echo $this->Html->link($sender['username'], $profileUrl, array('class' => 'owner'));
					?>
				</h5>
				<span class="pull-right">
					<?php
					echo __(CakeTime::nice($message['UserMessage']['created'], $timezone, '%B %e, %Y at %l:%M%P'));
					?>
				</span>
			</div>
			<p><?php echo nl2br(h($message['UserMessage']['message'])); ?></p>
		</div>
	</div>
</div>