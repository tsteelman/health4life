<table>
	<?php
	$recommendedUsers = array_chunk($recommendedUsers, 2);
	$totalRows = count($recommendedUsers);
	$lastRowIndex = $totalRows - 1;
	foreach ($recommendedUsers as $rowIndex => $userDataRow):
		$userDataLeftCol = $userDataRow[0];
		$userDataRightCol = $userDataRow[1];
		?>
		<tr>
			<td colspan="2" style="border-top: 1px solid #e1e1e1;">
				<span></span>
			</td>
		</tr>
		<tr>
			<td width="50%" style="border-right: 1px solid #e1e1e1;margin-right: 10px;">
				<?php
				echo $this->element('friend_recommendation_email_col', array('userData' => $userDataLeftCol));
				?>
			</td>
			<td width="50%">
				<?php
				echo $this->element('friend_recommendation_email_col', array('userData' => $userDataRightCol));
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" >
				<span></span>
			</td>
		</tr>
	<?php endforeach; ?>
</table>