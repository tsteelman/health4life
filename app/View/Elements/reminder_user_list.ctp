<div
	style="border-top: 1px solid #EBEBEB; padding: 10px 0px; color: rgb(69, 74, 77); font-family: 'Open Sans', sans-serif; line-height: normal;">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td style="vertical-align:middle; width: 10%;">
				<a href="<?php echo $link ?>" style="margin-right: 20px; float: left;">
					<img
						alt=" <?php echo $user['User']['username'] ?>"
						style="
						<?php
						switch ($user ['User'] ['type']) {
							case '1' :
								echo' border: 2px solid #70BF54; ';
								break;
							case '2' :
								echo' border: 2px solid #3C9CD7; ';
								break;
							case '3' :
								echo' border: 2px solid #957EBA; ';
								break;
							case '4' :
								echo' border: 2px solid #20BEC6; ';
						}
						?>
			height: 60px; width: 60px;  border-radius: 500px; vertical-align: middle;"
						src="<?php echo __(Common::getUserThumb($user['User']['id'], $user['User']['type'], 'small', '', 'url')); ?>" /></a>
			</td>
			<td style="vertical-align:middle; width: 50%;">
				<div>
					<h5 style="margin: 0; font-family: 'Open Sans', sans-serif;">
						<a href="<?php echo $link ?>"
						   style="text-decoration: none; color: #2C589E; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: 700; line-height: 1.1;"><?php echo __($user['User']['username']) ?></a>
					</h5>
					<?php if (!empty($diseases)): ?>
						<span style="color: #959595; font-family: 'Open Sans', sans-serif; font-size: 12px; margin: 0; font-weight: 400; width: 100%;float: left;padding-top: 12px;">
							<span><?php echo h($diseases[0]); ?></span>
						</span>
					<?php endif; ?>
					<?php if (!empty($medications)): ?>
						<span style="color: #959595; font-family: 'Open Sans', sans-serif; font-size: 12px; margin: 0; font-weight: 400; width: 100%;float: left;padding-top: 12px;">
							<span><?php echo h($medications[0]); ?></span>
						</span>
					<?php endif; ?>
					<span
						style="color: #959595; font-family: 'Open Sans', sans-serif; font-size: 12px; margin: 0; font-weight: 400;"><?php echo $location ?></span>
				</div>
			</td>
			<td style="vertical-align:middle; width: 34%;">
				<div style="float: right;">
					<a href="<?php echo $accept_link ?>"
					   style="color: #ffffff; font-family: 'Open Sans', sans-serif; border: 1px solid #004f7f; border-radius: 4px; background-color: #2c589e; display: inline-block; padding: 6px 11px; margin-bottom: 0px; font-size: 14px; line-height: 1.428571429; text-align: center; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Approve</a>

					<a href="<?php echo $reject_link ?>"
					   style="color: #2c589e; font-family: 'Open Sans', sans-serif; border: 1px solid #cecece; border-radius: 4px; background-color: #f1f1f1; display: inline-block; padding: 6px 11px; margin-bottom: 0px; font-size: 14px; line-height: 1.428571429; text-align: center; white-space: nowrap; vertical-align: middle; cursor: default; outline: none !important; text-decoration: none;">Decline</a>
				</div>
			</td>
		</tr>
	</table>
</div>
