<?php
$user = $userData['User'];
$userName = $user['username'];
$siteUrl = Router::url('/', true);
$profileUrl = "{$siteUrl}profile/{$userName}";
$tokenData = array(
	'friend_id' => $user['id'],
	'search' => 'false'
);
$token = base64_encode(json_encode($tokenData));
$addFriendUrl = "{$siteUrl}user/friends/addFriend?token={$token}";
switch ($user['type']) {
	case '1' :
		$borderColor = '#70BF54';
		break;
	case '2' :
		$borderColor = '#3C9CD7';
		break;
	case '3' :
		$borderColor = '#957EBA';
		break;
	case '4' :
		$borderColor = '#20BEC6';
}
$userPhotoUrl = Common::getUserThumb($user['id'], $user['type'], 'small', '', 'url');
$userPhoto = $this->Html->image($userPhotoUrl, array(
	'alt' => $userName,
	'style' => "border: 2px solid {$borderColor};height: 80px; width: 80px;  border-radius: 500px; vertical-align: middle;",
		));
?>
<table>
	<tr>
		<td style="vertical-align:top" width="10%">
			<?php
			echo $this->Html->link($userPhoto, $profileUrl, array(
				'style' => 'margin-right:5px;float:left;',
				'target' => '_blank',
				'escape' => false
			));
			?>
		</td>
		<td width="90%">
			<h5 style="font-family: 'Open Sans', sans-serif; margin: 0px;padding:0px;">
				<?php
				echo $this->Html->link($userName, $profileUrl, array(
					'style' => "text-decoration: none; color: #2C589E; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 700; max-width: 152px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; display: block;"
				));
				?>
			</h5>
			<div style="padding-top: 3px;">
				<?php if (!empty($userData['diseases'])): ?>
					<span style="display: inline-block;color: #959595; font-family: 'Open Sans', sans-serif; font-size: 12px; margin: 0; font-weight: 400; width: 100%;float: left;padding:0px;">
						<span style="display: inline-block;">
							<?php echo h($userData['diseases'][0]); ?>
						</span>
					</span>
				<?php endif; ?>

				<?php if (!empty($userData['medications'])): ?>
					<span style="display: inline-block;color: #959595; font-family: 'Open Sans', sans-serif; font-size: 12px; margin: 0; font-weight: 400; width: 100%;float: left;padding:0px;">
						<span style="display: inline-block;">
							<?php echo h($userData['medications'][0]); ?>
						</span>
					</span>
				<?php endif; ?>

				<span style="color: #959595; font-family: 'Open Sans', sans-serif; font-size: 12px; margin: 0; font-weight: 400; width: 100%;float: left;padding:0px;">
					<span><?php echo $userData['City']['description']; ?>, </span>
					<span><?php echo $userData['State']['description']; ?>, </span>
					<span><?php echo $userData['Country']['short_name']; ?></span>
				</span>
			</div>

			<div style="float: left; padding-top: 8px;">
				<?php
				echo $this->Html->link(__('Add Friend'), $addFriendUrl, array(
					'style' => "color: #2c589e; font-family: 'Open Sans', sans-serif; font-weight: 700; border: 1px solid #cecece; border-radius: 4px; background-color: #f1f1f1; display: inline-block; padding: 6px 12px; margin-bottom: 0px; font-size: 14px; font-weight: 700; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;"
				));
				?>
			</div>
		</td>
	</tr>
</table>