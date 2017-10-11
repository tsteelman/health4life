<div class="page-header">           
	<h2>
		<span>Family/Genetic History</span>&nbsp;
	</h2>    
	<div>Please select those who have had any of the following conditions</div>
</div>
<form method="POST">
	<div class="span6 widget-container-span ui-sortable">
		<div class="widget-box">
			<div class="widget-header">
				<div class="widget-toolbar no-border">
					<ul id="myTab" class="nav nav-tabs">
						<?php foreach ($familyMembers as $key => $familyMemberName) : ?>
							<li <?php echo ($key === 0) ? 'class="active"' : ''; ?> >
								<a href="#tab_<?php echo $key; ?>" data-toggle="tab"><?php echo $familyMemberName; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-6">
					<div class="tab-content">
						<?php foreach ($familyMembers as $key => $familyMemberName) : ?>
							<div class="tab-pane <?php echo ($key === 0) ? 'active' : ''; ?>" id="tab_<?php echo $key; ?>">
								<p><?php echo $this->element('Records/family_record', array('familyMemberName' => $familyMemberName)); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->element('buttons_row'); ?>
</form>