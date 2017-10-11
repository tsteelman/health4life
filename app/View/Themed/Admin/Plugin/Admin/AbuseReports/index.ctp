<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Manage Abuse Reports');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1><?php echo __('Abuse Reports List'); ?></h1>
    </div>
	<?php echo $this->Session->flash('flash'); ?>

    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="table-header">
					<?php echo __('Abuse Reports'); ?>
                </div>
                <div class="dataTables_wrapper" role="grid">
					<?php echo $this->element('Admin.AbuseReports/filter_form'); ?>
					<form action="" method="POST">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th><input type="checkbox" id="select_all_abuse_reports" /><?php echo __('Select All'); ?></th>
									<th><?php echo __('Content'); ?></th>
									<th><?php echo __('Reported by'); ?></th>
									<th><?php echo __('Reason'); ?></th>
									<th><i class="icon-time bigger-110 "></i><?php echo __('Reported on'); ?></th>
									<th><?php echo __('Comments'); ?></th>
								</tr>
							</thead>

							<tbody>  
								<?php
								if (isset($abuseReports) && $abuseReports != NULL) {
									foreach ($abuseReports as $index => $abuseReport) {
										?>

										<tr>
											<td><input type="checkbox" style="opacity: 1;position: static;margin: 0px;" name="data[AbuseReports][<?php echo $index; ?>][id]" value="<?php echo $abuseReport['AbuseReport']['id']; ?>" /></td>
											<td>
												<?php if (!empty($abuseReport['content'])): ?>
													<?php echo h($abuseReport['content']); ?>
													<br />
												<?php endif; ?>

												<?php
												if ($abuseReport['AbuseReport']['object_type'] === 'post') :
													echo $this->Html->link(__('View post details'), "/admin/AbuseReports/viewPost/{$abuseReport['AbuseReport']['object_id']}");
												elseif ($abuseReport['AbuseReport']['object_type'] === 'comment') :
													echo $this->Html->link(__('View comment details'), "/admin/AbuseReports/viewComment/{$abuseReport['AbuseReport']['object_id']}");
												endif;
												?>
											</td>
											<td><?php echo h($abuseReport['ReportedUser']['username']); ?></td>
											<td><?php echo h($abuseReport['AbuseReport']['reason']); ?></td>
											<td><?php echo $abuseReport['AbuseReport']['created']; ?></td>
											<td><textarea name="data[AbuseReports][<?php echo $index; ?>][admin_comment]"></textarea></td>
										</tr>

										<?php
									}
								}
								?>
							</tbody>
						</table>

						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" name="delete_abuse_reports" id="delete_abuse_reports" value="<?php echo __('Delete Selected'); ?>" />
							<input type="submit" class="btn btn-primary" name="reject_abuse_reports" id="reject_abuse_reports" value="<?php echo __('Reject Selected'); ?>" />
							<input type="button" class="btn" id="cancel_abuse_reports" value="<?php echo __('Cancel'); ?>" />
						</div>
					</form>
					<?php echo $this->element('pagination'); ?>

				</div>
			</div>
        </div>
    </div>
</div> 

<?php echo $this->AssetCompress->script('bootbox.min'); ?>
<script type="text/javascript">
	$(document).on('change', '#AbuseReportFilter', function() {
		$(this).closest('form').submit();
	});
	$(document).on('click', '#select_all_abuse_reports', function() {
		if($(this).is(':checked')){
			$('input[type="checkbox"]').prop('checked', true);
		}
		else{
			$('input[type="checkbox"]').prop('checked', false);
		}
	});
	$(document).on('click', '#delete_abuse_reports, #reject_abuse_reports', function() {
		var selectedCount = $('tbody input[type="checkbox"]:checked').length;
		if(selectedCount > 0){
			return true;
		}
		else{
			bootbox.alert('Please select atleast one report to proceed.');
			return false;
		}
	});
	$(document).on('click', '#cancel_abuse_reports', function() {
		window.location = '/admin/dashboard';
	});
</script>