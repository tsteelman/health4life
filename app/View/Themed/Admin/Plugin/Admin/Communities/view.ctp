<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Communities', '/admin/communities');
$this->Html->addCrumb($community['name']);
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1 class="blue">
            <span class="middle">
				<?php echo h($community['name']); ?>
            </span>
        </h1>
    </div>
    <div id="user_profile" class="user-profile row-fluid">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-18">
                <li class="active">
                    <a data-toggle="tab" href="#basic_info">
                        <i class="blue bigger-120"></i>
						<?php echo __('Basic Info'); ?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#events">
                        <i class="red icon-calendar bigger-120"></i>
						<?php
						echo __('Events');
						echo ($eventsCount > 0) ? " ({$eventsCount})" : '';
						?>
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#members">
                        <i class="green icon-group bigger-120"></i>
						<?php
						echo __('Members') . " ({$membersCount})";
						?>
                    </a>
                </li>
            </ul>

            <div class="tab-content no-border padding-24">
				<?php echo $this->element('Admin.Communities/basic_info'); ?>
				<?php echo $this->element('Admin.Communities/events'); ?>
				<?php echo $this->element('Admin.Communities/members'); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("ul.nav-list li").removeClass('active');
	$("#community-list-li").addClass('active');
</script>