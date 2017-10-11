<div class="container print_container">
	<div class="print_logo">
		<img src="/theme/App/img/logo_h4l_120.png">
	</div>
	<h3><?php echo $userName; ?></h3>
	<?php if ($graphTitles[1] == 'conditions' || $graphTitles[1] == 'scheduler') { ?>
		<h5>Health Record from <?php echo Configure::read ( 'App.name' ); ?></h5>
	<?php } else { ?>
		<h5>Selections from <?php echo Configure::read ( 'App.name' ); ?> Record</h5>
	<?php } ?>
	<h5>Created at <?php echo $date; ?></h5>
	<div class="table_container"> 
		<?php foreach ($healthValues as $key => $userInfo) { ?>
				<?php if ($graphTitles[$key] == "conditions") { ?>
							<h3>Conditions</h3>
							<div class="print_table">
								<table class="table">
											<thead>
												<tr>
													<th><?php echo __('Diagnosis'); ?></th>
													<?php if ($userType == 1 || $userType == 3) { ?>
														<th><?php echo __('Year of Diagnosis'); ?></th>
													<?php } ?>
													<th><?php echo __('Medication(s)'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach ($userInfo as $index => $condition) :
													$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
													$year = $condition['PatientDisease']['diagnosis_date'];
													?>
													<tr class="<?php echo $rowClass; ?>">
														<td class="medication_type"><?php echo h($condition['Diseases']['name']); ?></td>
														<?php if ($userType == 1 || $userType == 3) { ?>
															<td><?php echo ($year == "0000-00-00 00:00:00")? "-" : strftime("%Y", strtotime($year)) ?></td>
														<?php } ?>
														<td><?php echo h($condition['Treatment']); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
				<?php } else if ($graphTitles[$key] == "friends") { ?>
							<h3>Friends</h3>
							<div class="print_table">
								<table class="table">
											<thead>
												<tr>
													<th><?php echo __('User Name'); ?></th>
													<th><?php echo __('Location'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach ($userInfo as $index => $friend) :
													$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
													?>
													<tr class="<?php echo $rowClass; ?>">
														<td class="medication_type"><?php echo h($friend['User']['username']); ?></td>
														<td><?php echo h($friend[0]['location']); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
				<?php } else if ($graphTitles[$key] == "teams") { ?>
							<h3>My Teams</h3>
							<div class="print_table">
								<table class="table">
											<thead>
												<tr>
													<th><?php echo __('Name'); ?></th>
													<th><?php echo __('My Role'); ?></th>
													<th><?php echo __('Members'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach ($userInfo as $index => $team) :
													$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
													?>
													<tr class="<?php echo $rowClass; ?>">
														<td class="medication_type"><?php echo h($team['teamName']); ?></td>
														<td><?php echo h($team['role']); ?></td>
														<td><?php echo h($team['members']); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
				<?php } else if ($graphTitles[$key] == "scheduler") { ?>
							<h3>Medication Scheduler</h3>
								<?php if(empty($userInfo)) { ?>
										<div class="print_table" style="text-align: center">No data to display</div>
									<?php } else { ?>
										<div class="print_table">
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
														foreach ($userInfo as $index => $medication) :
															$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
															?>
															<tr class="<?php echo $rowClass; ?>">
																<td class="medication_type"><?php echo h($medication['name']); ?></td>
																<td><?php echo h($medication['strength']); ?></td>
																<td><?php echo h($medication['form']); ?></td>
																<td><?php echo h($medication['frequency']); ?></td>
																<td><?php echo h($medication['time']); ?></td>
																<td><?php echo h($medication['amount']); ?></td>
																<td><?php echo h($medication['route']); ?></td>
																<td><?php echo h($medication['additional_instructions']); ?></td>
																<td><?php echo h($medication['prescribed_by']); ?></td>
																<td><?php echo h($medication['indication']); ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
				<?php } } } ?>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		window.print();
	});
</script>

