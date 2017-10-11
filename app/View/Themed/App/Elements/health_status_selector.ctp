<div class="modal fade <?php if (isset($promptHealthStatusUpdate)) { ?> prompt <?php } ?>" id="healthStatusSelectionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header blue-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('Hi %s!', $username); ?></h4>
            </div>
            <div class="modal-body">
				<form id="health_status_form">
					<input type="hidden" id="health_status" value="" name="data[health_status]" />
					<div class="heading">
						<h1>
							<?php
							$messagePrefix = __('Hi %s, ', $username);
							if (isset($promptHealthStatusUpdate)):
								if (isset($userType) && ($userType == User::ROLE_PATIENT)) {
									echo __('%sHow are you feeling today?', $messagePrefix);
								} else {
									echo __('%sHow are you today?', $messagePrefix);
								}								
							else:
								echo __('%sHow are you feeling now?', $messagePrefix);
							endif;
							?>
						</h1>
						<h2 class="hide"><?php echo __('%sHow are you feeling now?', $messagePrefix); ?></h2>
					</div>
					<?php if (!empty($healthStatusList)): ?>
						<div id="health_status_box_container">
							<?php foreach ($healthStatusList as $healthStatus) : ?>
								<div class="health_status_box <?php echo $healthStatus['class']; ?>" data-health_status="<?php echo $healthStatus['value']; ?>">
									<div>
										<?php echo $this->Html->image($healthStatus['image']); ?>
									</div>
									<span><?php echo $healthStatus['text']; ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					
					<div class="col-lg-12 hide" id="health_status_comment_container">
						<div class="col-lg-12 health_status_cmnt_lbl">
							<?php echo __('Would you like to add a comment?'); ?>
						</div>
						<div class="col-lg-12">
							<textarea name="data[health_status_comment]" id="health_status_comment" class="form-control" placeholder="<?php echo __('I am feeling, I ate or did...'); ?>"></textarea>
						</div>
					</div>
					
				</form>
				<br clear="all" />
            </div>
            <div class="modal-footer">
				<?php
				echo $this->Html->tag('button', 'Save', array(
					'class' => 'btn btn_active ladda-button',
					'type' => 'button',
					'id' => 'save_health_status_btn',
					'disabled' => 'disabled',
					'data-spinner-color' => '#3581ED',
					'data-style' => 'expand-right',
				));
				?>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="close_health_status_modal"><?php echo __('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>