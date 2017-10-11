<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Admins');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
			<?php echo __('Admin Users List'); ?>
            <a href="/admin/users/addAdmin">
				<button class="btn btn-primary pull-right">
					<?php echo __('Add new admin'); ?>  
				</button>
            </a>
        </h1>
    </div>

	<?php echo $this->Session->flash('flash'); ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="table-header">
					<?php echo __('Manage Admin Users'); ?>
                </div>
                <div class="dataTables_wrapper" role="grid">
					<table id="sample-table-2" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th> <?php echo __('Id'); ?></th>
								<th> <?php echo __('Username'); ?></th>
								<th> <?php echo __('Email'); ?></th>
								<th> <?php echo __('Full Name'); ?></th>
								<th><i class="icon-time"></i><?php echo __('Created Date'); ?></th>
								<th></th>
							</tr>
						</thead>

						<tbody>  
							<?php
							if (isset($adminUsersList) && $adminUsersList != NULL) {
								foreach ($adminUsersList as $row) {
									?>
									<tr>
										<td><?php echo h($row['User']['id']); ?></td>
										<td>
											<?php echo h($row['User']['username']); ?>
										</td>
										<td><?php echo h($row['User']['email']); ?></td>
										<td><?php echo h($row['User']['first_name']) . ' ' . h($row['User']['last_name']); ?></td>
										<td><?php echo $row['User']['created']; ?></td>

										<td class="td-actions">
											<div class="hidden-phone visible-desktop action-buttons">
												<a class="green" href="/admin/users/editAdmin/<?php echo $row['User']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="Edit">
													<i class="icon-pencil bigger-130"></i>
												</a>
												<?php if ($row['User']['show_status_change_icon'] == true): ?>
													<?php if ($row['User']['status'] === User::STATUS_BLOCKED): ?>
														<a data-href="/admin/users/activateAdmin/<?php echo $row['User']['id'] ?>" class="green activate_admin" data-rel="tooltip" title="Activate">
															<i class="icon-check-sign bigger-130"></i>
														</a>
													<?php else: ?>
														<a data-href="/admin/users/deactivateAdmin/<?php echo $row['User']['id'] ?>" class="red deactivate_admin" data-rel="tooltip" title="Deactivate" data-userid="<?php echo $row['User']['id'] ?>">
															<i class="icon-ban-circle bigger-130"></i>
														</a>
													<?php
													endif;
												endif;
												?>
											</div>
										</td>
									</tr>

									<?php
								}
							}
							?>
						</tbody>
					</table>
					<div class="pagination pagination-small" style='float: right;'>
						<ul>
							<?php
							if ($this->Paginator->numbers()) {
								echo $this->Paginator->prev(__('<<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
								echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
								echo $this->Paginator->next(__('>>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
							}
							?>
						</ul>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<?php echo $this->AssetCompress->script('bootbox.min'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#users-li a").trigger('click');
	});

	$(document).on('click', 'a.activate_admin', function() {
		var actionBtn = $(this);
		var confirmMsg = '<br />Are you sure you want to activate this admin?<br /><br />';
		bootbox.confirm(confirmMsg, function(isConfirmed) {
			if (isConfirmed === true) {
				var actionUrl = actionBtn.attr('data-href');
				window.location.href = actionUrl;
			}
		});
	});

	$(document).on('click', 'a.deactivate_admin', function() {
		var actionBtn = $(this);
		var confirmMsg = '<br />Are you sure you want to deactivate this admin?<br /><br />';
		bootbox.confirm(confirmMsg, function(isConfirmed) {
			if (isConfirmed === true) {
				var actionUrl = actionBtn.attr('data-href');
				var userId = actionBtn.attr('data-userid');
				
				socket.emit('block_user', {userId: userId});
				window.location.href = actionUrl;
			}
		});
	});
</script>