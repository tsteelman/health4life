<div class="col-lg-3">
    <div class="team_setting">
		<?php
		echo $this->Html->image($teamImage, array(
			'class' => 'img-responsive',
			'alt' => __('Team Photo')
		));
		?>
		<h4><?php echo h($team['name']); ?></h4>
        <p><?php echo __('supporting'); ?></p>
        <h5><?php echo $patientName; ?></h5>
        <p>
            <?php 
            echo __('Created by %s', $organizerName);
//            echo __(' on %s', $createdDate); ?>
        </p>
        

		<?php if (isset($menuItems) && !empty($menuItems)) : ?>
			
        <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
            <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-2" style="height: auto;">
                <div>
                    <ul class="team_settings_options">
				<?php foreach ($menuItems as $menuItem): ?>           
					<li>
						<h4>
							<?php
							$itemClass = '';
							if (isset($menuItem['active']) && ($menuItem['active'] === true)) {
								$itemClass = 'selected';
							} elseif (isset($menuItem['disabled']) && ($menuItem['disabled'] === true)) {
								$itemClass = 'plus_disabled';
							}
							echo $this->Html->link($menuItem['label'], $menuItem['url'], array('class' => $itemClass));
							?>
						</h4>
					</li>
				<?php endforeach; ?>
			</ul>
                </div>                                            
            </div>
        </nav>
       
		<?php endif; ?>

    </div>
</div>