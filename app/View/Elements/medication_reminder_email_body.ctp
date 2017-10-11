<div style="width: 100%;float: left;margin-top: 10px;padding-top: 10px;">
	<div style="width: 100%;clear:both;">
		<div style="width:155px;float:left;"><?php echo __('Medication'); ?></div>
		<div style="float:left;"><div style="float:left;margin-right: 10px;">:</div><?php echo h($medication['name']); ?></div>
	</div>
	<div style="width: 100%;clear:both;">
		<div style="width:155px;float:left;"><?php echo __('Dose'); ?></div>
		<div style="float:left;"><div style="float:left;margin-right: 10px;">:</div><?php echo $medication['dose']; ?></div>
	</div>
	<div style="width: 100%;clear:both;">
		<div style="width:155px;float:left;"><?php echo __('Number/Amount'); ?></div>
		<div style="float:left;"><div style="float:left;margin-right: 10px;">:</div><?php echo $medication['amount']; ?></div>
	</div>
	<?php if (!empty($medication['form'])) : ?>
		<div style="width: 100%;clear:both;">
			<div style="width:155px;float:left;"><?php echo __('Form'); ?></div>
			<div style="float:left;"><div style="float:left;margin-right: 10px;">:</div><?php echo $medication['form']; ?></div>
		</div>
	<?php endif; ?>
	<?php if (!empty($medication['route'])) : ?>
		<div style="width: 100%;clear:both;">
			<div style="width:155px;float:left;"><?php echo __('Route of administration'); ?></div>
			<div style="float:left;"><div style="float:left;margin-right: 10px;">:</div><?php echo $medication['route']; ?></div>
		</div>
	<?php endif; ?>		
	<?php if (!empty($medication['additional_instructions'])) : ?>
		<div style="width: 100%;clear:both;">
			<div style="width:155px;float:left;"><?php echo __('Additional instructions'); ?></div>
			<div style="float:left;"><div style="float:left;margin-right: 10px;">:</div><?php echo h($medication['additional_instructions']); ?></div>
		</div>
	<?php endif; ?>		
</div>
<div style="width: 100%;clear:both;">
	<br clear="all" />
	<?php
	$link = $this->Html->link('here', $medication['stop_reminder_link']);
	echo __('Click %s to stop receiving reminder for this medication.', $link);
	?>
	<br clear="all" />
</div>