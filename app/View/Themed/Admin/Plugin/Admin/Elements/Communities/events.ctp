<div id="events" class="tab-pane">
	<div class="profile-users clearfix">

		<?php
		if (!empty($events)) :
			foreach ($events as $event) :
				?>
				<div class="pull-left event_box">
					<div class="event_image">
						<?php
						$eventImg = $this->Html->image($event['image']);
						echo $this->Html->link($eventImg, $event['url'], array('escape' => false));
						?>
					</div>
					<div>
						<div class="name">
							<?php echo $this->Html->link(h($event['name']), $event['url']); ?>
						</div>
						<span class="event_time">
							<?php echo $event['time']; ?>
						</span>
					</div>
				</div>
				<?php
			endforeach;
		else :
			echo __('No events found');
		endif;
		?>

	</div>
</div>