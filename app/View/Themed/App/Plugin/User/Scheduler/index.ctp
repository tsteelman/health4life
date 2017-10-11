<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');

if (substr($this->request->referer(1), 0, 9) == '/calendar') {
    $this->Html->addCrumb('Calendar', $this->request->referer(1));
} else if (isset($refer) && $refer == 'Calendar') {
    $this->Html->addCrumb('Calendar', '/calendar');
} else {
    $this->Html->addCrumb('My Health', '/profile/myhealth');
}

$this->Html->addCrumb(__('Medication Scheduler'));
?>
<div class="container">
    <div class="medication_scheduler">
		<div class="row">
			<div class="col-lg-7">
				<h2><?php echo __('Medication Scheduler'); ?></h2>
			</div>
			<div class="col-lg-5">
				<button class="btn pull-right add_medication" id="add_medication_btn"><?php echo __('Add Medication'); ?></button>
				<?php if (!empty($medications)) { ?>
					<button class="btn pull-right print_btn scheduler_print"><?php echo __('Print'); ?></button>
					<button class="btn pull-right delete_btn" id="delete_medication_btn"><?php echo __('Delete'); ?></button>
				<?php } ?>
			</div>
		</div>
		<?php if (!empty($medications)) : ?>
			<div class="scheduler_table">
				<div class="table-responsive medication_table">
					<form id="medications_form">
						<table class="table">
							<thead>
								<tr>
									<th><input type="checkbox" id="select_all_medications" /></th>
									<th><?php echo __('Medication'); ?></th>
									<th><?php echo __('Dose'); ?></th>
									<th><?php echo __('Form'); ?></th>
									<th><?php echo __('Frequency'); ?></th>
									<th><?php echo __('Times(s)'); ?></th>
									<th><?php echo __('Number/Amount'); ?></th>
									<th><?php echo __('Route'); ?></th>
									<th><?php echo __('Additional Instructions'); ?></th>
									<th><?php echo __('Prescribing provider'); ?></th>
									<th><?php echo __('Indication'); ?></th>
									<th><?php echo __('Actions'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($medications as $index => $medication) :
									$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
									?>
									<tr class="<?php echo $rowClass; ?>">
										<td>
											<input type="checkbox" name="data[MedicationSchedule][]" value="<?php echo $medication['id']; ?>" />
											<input type="hidden" class="medication_id" value="<?php echo $medication['medication_id']; ?>" />
											<input type="hidden" class="dosage" value="<?php echo $medication['dosage']; ?>" />
											<input type="hidden" class="dosage_unit" value="<?php echo $medication['dosage_unit']; ?>" />
											<input type="hidden" class="medication_form" value="<?php echo $medication['form_value']; ?>" />
											<input type="hidden" class="route" value="<?php echo $medication['route_value']; ?>" />
											<input type="hidden" class="frequency" value="<?php echo $medication['frequency_value']; ?>" />
											<input type="hidden" class="start_year" value="<?php echo $medication['start_year']; ?>" />
											<input type="hidden" class="start_month" value="<?php echo $medication['start_month']; ?>" />
											<input type="hidden" class="start_day" value="<?php echo $medication['start_day']; ?>" />
											<input type="hidden" class="end_date" value="<?php echo $medication['end_date_value']; ?>" />
											<?php foreach ($medication['time_list'] as $time) : ?>
												<input type="hidden" class="time_list" value="<?php echo $time; ?>" />
											<?php endforeach; ?>
										</td>
										<td class="medication_type"><?php echo h($medication['name']); ?></td>
										<td><?php echo h($medication['strength']); ?></td>
										<td><?php echo h($medication['form']); ?></td>
										<td><?php echo h($medication['frequency']); ?></td>
										<td class="time"><?php echo h($medication['time']); ?></td>
										<td class="amount"><?php echo h($medication['amount']); ?></td>
										<td><?php echo h($medication['route']); ?></td>
										<td class="additional_instructions"><?php echo h($medication['additional_instructions']); ?></td>
										<td class="prescribed_by"><?php echo h($medication['prescribed_by']); ?></td>
										<td class="indication"><?php echo h($medication['indication']); ?></td>
										<td><a class="edit_medication"><?php echo __('Edit'); ?></a></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		<?php else: ?>
			<div class="alert alert-warning">
				<div class="message"><?php echo __('No medication schedules.'); ?></div>
			</div>
		<?php endif; ?>
    </div>
</div>
<?php echo $this->element('User.Scheduler/medication_scheduler_dialog'); ?>