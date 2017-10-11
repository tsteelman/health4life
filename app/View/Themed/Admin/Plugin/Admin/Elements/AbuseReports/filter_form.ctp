<div class="row-fluid">
	<?php
	echo $this->Form->create('AbuseReport', array(
		'type' => 'get',
		'url' => array('controller' => 'AbuseReports', 'action' => 'index'),
	));
	?>
	<div class="span6">
		<div class="dataTables_length">
			<?php
			echo $this->Form->input('filter', array(
				'type' => 'select',
				'options' => $objectTypes,
				'label' => __('Filter'),
				'div' => false,
				'empty' => __('All')
			));
			?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>