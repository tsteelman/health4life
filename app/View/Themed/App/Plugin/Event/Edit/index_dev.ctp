<div class="events form">
<?php echo $this->Form->create('Event'); ?>
	<fieldset>
		<legend><?php echo __('Edit Event'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('event_type');
		echo $this->Form->input('guest_can_invite');
		echo $this->Form->input('repeat');
		echo $this->Form->input('created_by');
		echo $this->Form->input('start_date');
		echo $this->Form->input('end_date');
		echo $this->Form->input('virtual_event');
		echo $this->Form->input('medium_of_event');
		echo $this->Form->input('location');
		echo $this->Form->input('country');
		echo $this->Form->input('zip');
		echo $this->Form->input('state');
		echo $this->Form->input('city');
		echo $this->Form->input('image');
		echo $this->Form->input('disease_id');
		echo $this->Form->input('tags');
		echo $this->Form->input('published');
		echo $this->Form->input('section');
		echo $this->Form->input('section_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>