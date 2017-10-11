<div class="container">
	<div class="comingsoon_text p4l_text">
		<img src="/theme/App/img/logo_120.png">
	</div>
	<h3><?php echo $userName; ?></h3>
	<h5>Health record <?php if(!empty($startDate)) { ?>
		from <?php echo $startDate; ?> to <?php echo $endDate; ?> 
		<?php } ?> 
	</h5>
	<h5>Created at <?php echo $date; ?></h5>
	<div class="graph_container"> 
		<?php if($tableType == "scheduler") { ?>
			<h3>Medication Scheduler</h3>
				<div class="medication_table">
					<table class="table">
								<thead>
									<tr>
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
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($healthValues as $index => $medication) :
										$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
										?>
										<tr class="<?php echo $rowClass; ?>">
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
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
			<?php } else if ($tableType == "condition") { ?>
						<h3>Conditions</h3>
						<div class="medication_table">
							<table class="table">
										<thead>
											<tr>
												<th><?php echo __('Diagnosis'); ?></th>
												<th><?php echo __('Year of Diagnosis'); ?></th>
												<th><?php echo __('Medication(s)'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($healthValues as $index => $condition) :
												$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
												$year = $condition['PatientDisease']['diagnosis_date'];
												?>
												<tr class="<?php echo $rowClass; ?>">
													<td class="medication_type"><?php echo h($condition['Diseases']['name']); ?></td>
													<td><?php echo ($year == "0000-00-00 00:00:00")? "-" : strftime("%Y", strtotime($year)) ?></td>
													<td><?php echo h($condition['Treatment']['name']); ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
			<?php } ?>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		window.print();
	});
</script>

