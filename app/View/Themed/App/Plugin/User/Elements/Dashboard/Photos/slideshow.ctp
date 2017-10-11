<div id="dashboard_slideshow_container" class="hide">
	<ul class="bxslider">
		<?php if (!empty($photos)) : ?>
			<?php foreach ($photos as $photo): ?>
				<li><img src="<?php echo $photo['src']; ?>" /></li>
			<?php endforeach; ?>
		<?php else: ?>
			<li><img src="<?php echo $defaultPhoto; ?>" /></li>
			<li><img src="/theme/App/img/dashboard_default2.jpg" /></li>
		<?php endif; ?>
	</ul>
</div>