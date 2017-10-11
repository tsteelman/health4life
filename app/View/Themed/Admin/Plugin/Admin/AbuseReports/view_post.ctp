<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Manage Abuse Reports', '/admin/AbuseReports');
$this->Html->addCrumb('Post');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1><?php echo __('Abuse Reported Post'); ?></h1>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
				<?php echo $this->element($element); ?>
			</div>
        </div>
    </div>
</div>

<?php if (isset($selectedCommentId)): ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('html, body').animate({
				scrollTop: $('#comment_<?php echo $selectedCommentId; ?>').offset().top
			});
		});
	</script>
<?php endif; ?>