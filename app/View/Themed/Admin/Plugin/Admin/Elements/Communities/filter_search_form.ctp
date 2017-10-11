<div class="row-fluid">
	<?php
	echo $this->Form->create('Community', array(
		'type' => 'get',
		'url' => array('controller' => 'communities', 'action' => 'index'),
	));
	?>
	<div class="span6">
		<div class="dataTables_length">
			<?php
			echo $this->Form->input('filter', array(
				'type' => 'select',
				'options' => $communityTypes,
				'label' => __('Filter by community type'),
				'div' => false,
				'empty' => __('All')
			));
			?>
		</div>
	</div>
	<div class="span6">
		<div class="dataTables_filter">
			<?php
			echo $this->Form->input('search_key', array(
				'label' => false,
				'placeholder' => __('Search by community name'),
				'aria-controls' => 'sample-table-2',
				'div' => false,
				'required' => false
					)
			);
			echo $this->Form->submit(__('search', true), array('div' => false, 'title' => 'search', 'class' => 'btn btn-small btn-inverse'));
			?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>