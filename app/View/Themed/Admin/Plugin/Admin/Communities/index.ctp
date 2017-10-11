<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Communities');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1><?php echo __('Community List'); ?></h1>
    </div>
	<?php echo $this->Session->flash('flash'); ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="table-header">
					<?php echo __('Community List'); ?>
                </div>
                <div class="dataTables_wrapper" role="grid">
					<?php echo $this->element('Admin.Communities/filter_search_form'); ?>
					<table id="sample-table-2" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th><?php echo __('Id'); ?></th>
								<th><?php echo __('Community name'); ?></th>
								<th><?php echo __('Created by'); ?></th>
								<th><i class="icon-time bigger-110 "></i><?php echo __('Created on'); ?></th>
								<th><?php echo __('Community type'); ?></th>
								<th></th>
							</tr>
						</thead>

						<tbody>  
							<?php
							if (isset($communities) && $communities != NULL) {
								foreach ($communities as $community) {
									?>

									<tr>
										<td><?php echo $community['Community']['id']; ?></td>
										<td><?php echo h($community['Community']['name']); ?></td>
										<td><?php echo h($community['User']['username']); ?></td>
										<td><?php echo h($community['Community']['created']); ?></td>
										<td><?php echo h($community['Community']['type']); ?></td>

										<td class="td-actions">
											<div class="hidden-phone visible-desktop action-buttons">
												<a href="/admin/communities/view/<?php echo $community['Community']['id'] ?>" class="blue" data-rel="tooltip" title="View details">
													<i class="icon-zoom-in bigger-130"></i>
												</a>
											</div>
										</td>
									</tr>

									<?php
								}
							}
							?>
						</tbody>
					</table>

					<?php echo $this->element('pagination'); ?>

				</div>
			</div>
        </div>
    </div>
</div> 

<script type="text/javascript">
	$(document).on('change','#CommunityFilter',function(){
		$(this).closest('form').submit();
	});
</script>