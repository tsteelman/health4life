<?php
$profileUrl =Common::getUserProfileLink( $message['username'], TRUE);
$userThumb = Common::getUserThumb($message['user_id'], $message['user_type'], 'small');
$rowClass = '';
if(isset($message['is_read']))
{
	$rowClass = ($message['is_read'] == UserMessage::STATUS_READ) ? 'read' : 'unread';
}
?>
<div class="message_list <?php echo $rowClass; ?>">
	<div class="col-lg-5 col-sm-5">
		<div class="checkbox pull-left">
			<input type="checkbox" value="<?php echo $message['user_id']; ?>" name="message_users[]" />
		</div>
		<div class="media">
			<?php
			echo $this->Html->link($userThumb, $profileUrl, array('class' => 'pull-left', 'escape' => false));
			?>
			<div class="media-body">
				<div class="pull-left">
					<h5>
						<?php
						echo $this->Html->link($message['username'], $profileUrl, array('class' => 'owner'));
						?>
					</h5>
					<span>
						<?php
						echo __(CakeTime::nice($message['created'], $timezone, '%B %e, %Y at %l:%M%P'));
						?>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-7 col-sm-7">
		<p>
			<?php
			echo h(String::truncate($message['message'], 75, array('exact' => false)));
			?>
		</p>
	</div>
</div>