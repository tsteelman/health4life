<?php
foreach ($events as $teamId => $eventData) :
	$team = $eventData['Team'];
	$teamEvents = $eventData['TeamEvents'];
	$teamUrl = Router::Url('/', true) . "myteam/{$teamId}";
	$teamLink = $this->Html->link($team['name'], $teamUrl);
	?>
	<h4><?php echo __('Tasks in team %s:', $teamLink); ?></h4>
	<table border="0">
		<tbody>
			<?php foreach ($teamEvents as $teamEvent) : ?>
			<table border="0">
				<tr>
					<td><?php echo __('Task Title:'); ?></td>
					<td><?php echo h($teamEvent['name']); ?></td>
				</tr>
				<?php if (!empty($teamEvent['description'])) : ?>
					<tr>
						<td><?php echo __('Task Description:'); ?></td>
						<td><?php echo h($teamEvent['description']); ?></td>
					</tr>
				<?php endif; ?>
				<tr>
					<td><?php echo __('Task Type:'); ?></td>
					<td><?php echo $teamEvent['type']; ?></td>
				</tr>
			</table>
			<br />
		<?php endforeach; ?>
	</tbody>
	</table>
<?php endforeach; ?>