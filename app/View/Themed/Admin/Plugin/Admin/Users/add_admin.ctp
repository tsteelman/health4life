<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Admins', '/admin/users/admins');
    $this->Html->addCrumb('Add');
?>
<div class="page-content">
	<div class="page-header position-relative">
		<h1>
			<?php echo $pageTitle; ?>
		</h1>
	</div>
	<?php echo $this->Session->flash('flash'); ?>

	<div class="row-fluid">
		<div class="span12">
			<?php echo $this->element('Admin.Users/admin_user_form'); ?>
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