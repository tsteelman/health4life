<div class="discussion_area">
	<?php
	if ((isset($viewActivity) && ($viewActivity === true)) || !isset($viewActivity)) {
		echo $this->element('Post.form');
		echo $this->element('Post.new_post_notification');
		?>
		<div class="event_wraper <?php echo (empty($posts)) ? 'hide' : ''; ?>" id="post_container">
			<?php if ($hasFilterPermission === true) : ?>
				<div class="filter_option">
					<div class="btn-toolbar">
						<div class="btn-group pull-right">
							<button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">                                                            
								<div class="filter"><?php echo __(''); ?></div>
							</button>

							<ul class="dropdown-menu" id="post_filter">
								<input id="filter_value_hidden" type="hidden" value="0" />
								<?php foreach ($filterOptions as $value => $option) : ?>
									<li><a data-filter_value="<?php echo $value; ?>"><?php echo $option; ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div id="post_list">
				<?php
				if (!empty($posts)) {
					echo $this->element('Post.post_list');
				}
				?>
			</div>
			<div id="post_loading" class="hide">
				<span>
					<?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
					<label>Loading, please wait...</label>
				</span>
			</div>
		</div>
		<div id="no_posts_msg" class="<?php echo empty($posts) ? '' : 'hide'; ?>">
			<?php
			if (isset($noPostsMessage) && $noPostsMessage != NULL) {
				echo $this->element('warning', array('message' => __($noPostsMessage),'hideCloseBtn'=>true));
			} else {
				echo $this->element('warning', array('message' => __('No discussion started yet!'),'hideCloseBtn'=>true));
			}
			?>
		</div>
		<?php
	}
	?>
</div>