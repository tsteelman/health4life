<?php if (!empty($medications)) : ?>
	<div class="table-responsive medication_table">
		<table class="table">
			<thead>
				<tr>
					<th><?php echo ('Medication'); ?></th>
					<th><?php echo ('Dose'); ?></th>
					<th><?php echo ('Time(s)'); ?></th>
					<th><?php echo ('Number/Amount'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($medications as $index => $medication) :
					$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
					?>
					<tr class="<?php echo $rowClass; ?>">
						<td class="medication_type"><?php echo h($medication['name']); ?></td>
						<td><?php echo h($medication['strength']); ?></td>
						<td><?php echo h($medication['time']); ?></td>
						<td><?php echo h($medication['amount']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="col-lg-12" id="no_medications">
		<span>
			<label>
				<?php echo __('No medication schedules for the date.'); ?>
			</label>
		</span>
	</div>
<?php endif; ?>
<form id="hidden_medication_data_form">
	<input type="hidden" id="selected_date" value="<?php echo $selectedDate; ?>" />
	<input type="hidden" id="medication_date" value="<?php echo $medicationDate; ?>" />
	<input type="hidden" id="next_date" value="<?php echo $nextDate; ?>" />
	<input type="hidden" id="prev_date" value="<?php echo $prevDate; ?>" />
</form>