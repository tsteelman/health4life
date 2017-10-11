<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Admins', '/admin/users/admins');
    $this->Html->addCrumb( $username);
?>
<div class="page-content">
	<div class="page-header position-relative">
		<h1>
			<?php echo $pageTitle; ?>
		</h1>
	</div>
	<?php echo $this->Session->flash('flash'); ?>

	<div class="row-fluid">
		<div class="span12 widget-container-span ui-sortable">
			<div class="widget-box">
				<div class="widget-header">
					<div class="widget-toolbar no-border">
						<ul id="myTab" class="nav nav-tabs">
							<li class="active">
								<a href="#basic_info" data-toggle="tab">
									<i class="green icon-edit bigger-125"></i>
									<?php echo __('Basic Info'); ?>
								</a>
							</li>
							<li>
								<a href="#change_pwd" data-toggle="tab">
									<i class="blue icon-key bigger-125"></i>
									<?php echo __('Change Password'); ?>
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-6">
						<div class="tab-content">
							<div class="tab-pane active" id="basic_info">
								<?php echo $this->element('Admin.Users/admin_user_form'); ?>
							</div>

							<div class="tab-pane" id="change_pwd">
								<?php echo $this->element('Admin.Users/change_admin_password_form'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo $this->jQValidator->validator(); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#users-li a").trigger('click');
		$("ul.nav-list li").removeClass('active');
		$("#admin-list-li").addClass('active');
	});
</script>