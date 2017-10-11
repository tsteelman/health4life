<?php if ($showMedicationTile === true) : ?>
	<div class="medication_section">
		<div class="row">
			<div class="medication_header">
				<div style="float:left; width: 320px;padding-left: 15px;">
					<h2><?php echo ('Medication Scheduler'); ?></h2>
				</div>
				<div style="float:right; width: 165px;padding-right: 15px;">
					<a href="/scheduler" class="medication_view pull-right"><?php echo ('View all Medication'); ?></a>
				</div>
			</div>

		</div>
		<div class="row medication_month">
			<div class="col-lg-8 month_selector col-md-8">
				<ul >
					<Li><a class="month_prev" data-date="<?php echo $prevDate; ?>"></a></li>
					<Li><a class="selected_date"><?php echo $medicationDate; ?></a></li>
					<Li><a class="month_next" data-date="<?php echo $nextDate; ?>"></a></li>
				</ul>
			</div>
			<div class="col-lg-4 col-md-4">
				<button class="btn pull-right add_medication" id="add_medication_btn"><?php echo ('Add Medication'); ?></button>
			</div>
		</div>
		<div id="medication_schedules">
			<?php echo $this->element('User.Scheduler/medication_schedules'); ?>
		</div>
	</div>
	<?php echo $this->element('User.Scheduler/medication_scheduler_dialog'); ?>
<?php endif; ?>
<div id="medication_loading" class="hide" style="text-align: center; height: 150px;line-height: 150px;" >
	<span>
		<?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
		<label><?php echo ('Loading, please wait...'); ?></label>
	</span>
</div>