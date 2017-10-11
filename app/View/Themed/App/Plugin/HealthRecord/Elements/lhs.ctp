<ul class="edit_profile_options">
	<?php foreach ($menuItems as $menuItem): ?>
		<li>
			<h4>
				<?php echo $this->Html->link($menuItem['label'], $menuItem['url'], array('class' => ($menuItem['active'] === true) ? 'selected' : '')); ?>
			</h4>
		</li>
	<?php endforeach; ?>
</ul>