<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb($username, '/admin/users/profile');
$this->Html->addCrumb(__('Edit Profile'));
?>
<div class="page-content">
	<div class="page-header position-relative">
		<h1>
			<?php echo $title_for_layout; ?>
		</h1>
	</div><!--/.page-header-->

	<?php echo $this->Session->flash('flash'); ?>

	<div class="row-fluid">
		<div class="span12">
			<!--PAGE CONTENT BEGINS-->
			<div>
				<div id="user-profile-3" class="user-profile row-fluid">
					<div class="tabbable">
						<ul class="nav nav-tabs padding-16">
							<li class="active">
								<a href="#edit-basic" data-toggle="tab">
									<i class="green icon-edit bigger-125"></i>
									<?php echo __('Basic Info'); ?>
								</a>
							</li>

							<li class="">
								<a href="#edit-password" data-toggle="tab">
									<i class="blue icon-key bigger-125"></i>
									<?php echo __('Password'); ?>
								</a>
							</li>
						</ul>

						<div class="tab-content profile-edit-tab-content">
							<?php echo $this->element('Admin.Users/Profile/edit_basic_info'); ?>
							<?php echo $this->element('Admin.Users/Profile/change_password'); ?>
						</div>
					</div>
				</div><!--/span-->
			</div><!--/user-profile-->
		</div>

		<!--PAGE CONTENT ENDS-->
	</div><!--/.span-->
</div><!--/.row-fluid-->
<?php
echo $this->jQValidator->validator();
?>