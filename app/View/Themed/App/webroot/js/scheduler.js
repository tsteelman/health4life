$(document).on('click', '#add_medication_btn', function() {
	clearMedicationScheduleForm();
	var selectedDate = $('#hidden_medication_data_form').find('#selected_date').val();
	$('#MedicationSchedulerFormSelectedDate').val(selectedDate);
	$('#medication_scheduler_dialog').find('.modal-title.add_title').removeClass('hide');
	$('#medication_scheduler_dialog').find('.modal-title.edit_title').addClass('hide');
	$('#medication_scheduler_dialog').modal('show');
});
function clearMedicationScheduleForm() {
	$('#medication_scheduler_form')[0].reset();
	$('#medication_scheduler_form').find('span.help-block').html('').hide();
	$('#medication_scheduler_form').find('.form-group, .form-group-col').removeClass('error');
	$('#medication_time_error').show().addClass('hide');
	$('#selected_times').html('').removeClass('facelist');
	$('#MedicationSchedulerFormEndDate').prop('disabled', true);
	$('#last_time_row_index').val('');
	$('#MedicationSchedulerFormRepeatFrequency').val('DAILY:1');
	$('#save_medication_schedule').prop('disabled', false);
	$('#cancel_medication_schedule').prop('disabled', false);
	var dateObj = getUserNow();
	$('#MedicationSchedulerFormStartYear').val(dateObj.getFullYear());
	$('#MedicationSchedulerFormStartMonth').val(dateObj.getMonth() + 1);
	$('#MedicationSchedulerFormStartDay').val(dateObj.getDate());
	handleStartDateChange();
	hideDosageError();
}

$(document).on('change', '.start_date_row select', function() {
	handleStartDateChange();
});

/*
 * Function to handle start date options change
 */
function handleStartDateChange() {
	var yearField = $('#MedicationSchedulerFormStartYear');
	var monthField = $('#MedicationSchedulerFormStartMonth');
	var dayField = $('#MedicationSchedulerFormStartDay');

	var selectedYear = yearField.val();
	var selectedMonth = monthField.val();
	var selectedDay = dayField.val();

	if (selectedYear === '') {
		monthField.prop('disabled', true);
		selectedMonth = '';
		monthField.val(selectedMonth);
	}
	else {
		monthField.prop('disabled', false);
	}

	if (selectedMonth === '') {
		dayField.prop('disabled', true);
		dayField.val('');
	}
	else {
		dayField.prop('disabled', false);
	}

	if (selectedYear != '' && selectedMonth != '') {
		var monthLastDay = new Date(selectedYear, selectedMonth, 0).getDate();

		dayField.empty();
		dayField.append($('<option></option>').val('').html("Day"));
		for (var i = 1; i <= monthLastDay; i++) {
			dayField.append($('<option>Day</option>').val(i).html(i));
		}

		if (selectedDay <= monthLastDay) {
			dayField.val(selectedDay);
		}
		else {
			dayField.val('');
		}
	}
}

$(document).ready(function() {
	var defaultDateFormat = 'mm/dd/yy';
	var dateObj = getUserNow();
	var defaultCalendarDate = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate(), 0, 0, 0, 0);
	$('#MedicationSchedulerFormEndDate').datepicker({
		dateFormat: defaultDateFormat,
		defaultDate: defaultCalendarDate,
		onClose: function() {
			$(this).valid();
		}
	});
	applyTimePicker($('#new_time input'));
	$.validator.addMethod('greaterThanOrEqualTo', function(endDateValue, element, startDateValue) {
		var valid = false;
		if ((endDateValue === '') || (startDateValue === '')) {
			valid = true;
		} else {
			var endDate = new Date(endDateValue);
			var startDate = new Date(startDateValue);
			valid = new Date(endDate) >= new Date(startDate);
		}
		return valid;
	});
});

function addTimeValidation() {
	$('#MedicationSchedulerFormTime').rules('add', {
		required: true,
		regex: /(([0-9]|[1][012])\:[03]0\s(a|p)m)$/i,
		messages: {
			required: 'Please select time.',
			regex: 'Please enter a valid time.'
		}
	});
}
function removeTimeValidation() {
	$('#MedicationSchedulerFormTime').rules('remove', 'required regex');
}

function addTimeRow(timeValue) {
	var lastTimeRowIndex;
	var newTimeIndex;
	if ($('#last_time_row_index').val() !== '') {
		lastTimeRowIndex = $('#last_time_row_index').val();
		newTimeIndex = parseInt(lastTimeRowIndex) + 1;
	}
	else {
		newTimeIndex = 0;
	}
	var timeRow = $('#sample_time_row .medication_time_row').clone();
	var timeInput = timeRow.find('input');
	var inputName = timeInput.attr('name');
	var inputId = timeInput.attr('id');
	inputName = inputName.replace('index', newTimeIndex);
	inputId = inputId.replace('Index', newTimeIndex);
	timeInput.attr('name', inputName);
	timeInput.attr('id', inputId);
	timeInput.val(timeValue);
	timeRow.find('.medication_time').html(timeValue);
	$('#selected_times').append(timeRow);
	$('#selected_times').addClass('facelist');
	$('#last_time_row_index').val(newTimeIndex);
}

function showMedicationTimeError(msg) {
	$('#medication_time_error').html(msg).removeClass('hide').addClass('help-block').show();
	$('#medication_time_error').parents('.form-group').addClass('error');
}

function hideMedicationTimeError() {
	$('#medication_time_error').html('').addClass('hide').removeClass('help-block').hide();
	$('#medication_time_error').parents('.form-group').removeClass('error');
}

function applyTimePicker(input) {
	input.timepicker({
		'forceRoundTime': true,
		'showDuration': true,
		'timeFormat': 'g:i a'
	}).on('selectTime', function() {
		hideMedicationTimeError();
		var selectedTime = $(this).val();
		if (!isAlreadySelectedTime(selectedTime)) {
			addTimeRow(selectedTime);
			$('#MedicationSchedulerFormTime').val('');
		}
		else {
			showMedicationTimeError('This time is already selected.');
		}
	});
}

/**
 * Function to check if a time is already selected
 * 
 * @param {String} selectedTime
 * @returns {Boolean}
 */
function isAlreadySelectedTime(selectedTime) {
	var selectedTimes = $('input.hidden_medication_time').map(function() {
		if (this.value !== "") {
			return this.value;
		}
	}).get();
	var isSelected = ($.inArray(selectedTime, selectedTimes) > -1) ? true : false;
	return isSelected;
}

$(document).on('keyup', '#MedicationSchedulerFormTime', function() {
	if ($(this).val() === '') {
		hideMedicationTimeError();
	}
});

/**
 * Search medicine names
 */
var medicationCache = {};
var medicineIdField;
$(document).on('focus', '#MedicationSchedulerFormMedicineName', function() {
	var minLength = 2;
	medicineIdField = $('#MedicationSchedulerFormMedicineId');
	var element = this;
	$(element).autocomplete({
		minLength: minLength,
		source: function(request, response) {
			var searchTerm = $.trim(request.term);
			if (searchTerm in medicationCache) {
				var cacheData = medicationCache[searchTerm];
				response(cacheData);
			}
			else {
				$.ajax({
					url: '/api/searchTreatments',
					dataType: 'json',
					data: {
						term: searchTerm
					},
					success: function(data) {
						medicationCache[searchTerm] = data;
						response(data);
					}
				});
			}
		},
		select: function(event, ui) {
			if (ui.item) {
				var medicineId = ui.item.id;
				medicineIdField.val(medicineId);
			}
		},
		search: function(event) {
			if (event.ctrlKey === true) {
				return false;
			}
			medicineIdField.val('');
		},
		change: function() {
			var medicineId = medicineIdField.val();
			if (!(medicineId > 0)) {
				$(element).val('');
			}
		},
		response: function(event, ui) {
			if (ui.content.length === 0) {
				medicineIdField.val('');
				$(element).val('');
			}
		}
	});
});
$(document).on('click', '#remind_until_cancelled', function() {
	changeEndDateInputStatus();
	if ($(this).is(':checked')) {
		$('#MedicationSchedulerFormEndDate').val('');
	}
});
$(document).on('change', '#MedicationSchedulerFormRepeatFrequency', function() {
	changeEndDateInputStatus();
});
function changeEndDateInputStatus() {
	if ($('#remind_until_cancelled').is(':checked') || ($('#MedicationSchedulerFormRepeatFrequency').val() === '')) {
		$('#MedicationSchedulerFormEndDate').prop('disabled', true);
	}
	else {
		$('#MedicationSchedulerFormEndDate').prop('disabled', false);
	}
}
$(document).on('click', '.delete_medication_time', function() {
	$(this).parent('.medication_time_row').remove();
	if($('#selected_times .medication_time_row').length === 0){
		$('#selected_times').removeClass('facelist');	
	}
});
$(document).on('focus', 'input[name="data[MedicationSchedulerForm][time]"]', function() {
	removeTimeValidation();
});
$(document).on('click', '#save_medication_schedule', function() {
	var timeField = $('input[name="data[MedicationSchedulerForm][time]"]');
	var validateTime;
	if ($('#selected_times input').length > 0) {
		if ($('#MedicationSchedulerFormTime').val() === '') {
			validateTime = false;
		}
		else {
			validateTime = true;
		}
	}
	else {
		validateTime = true;
	}

	if (validateTime === true) {
		addTimeValidation();
	}
	else {
		removeTimeValidation();
	}

	var endDateField = $('input[name="data[MedicationSchedulerForm][end_date]"]');
	if ($('#remind_until_cancelled').is(':checked')) {
		endDateField.rules('remove', 'required, greaterThanOrEqualTo');
	}
	else {
		var startDate = getStartDateValue();
		endDateField.rules('add', {
			required: true,
			greaterThanOrEqualTo: startDate,
			messages: {
				required: 'Please select the date to stop medication.',
				greaterThanOrEqualTo: 'Stop date cannot be less than start date.'
			}
		});
	}

	var isFormValid = $('#medication_scheduler_form').valid();
	var isTimeValid;
	if (validateTime === true) {
		isTimeValid = timeField.valid();
		if (isTimeValid === true) {
			var selectedTime = timeField.val();
			if (isAlreadySelectedTime(selectedTime)) {
				isTimeValid = false;
				showMedicationTimeError('This time is already selected.');
			}
			else {
				isTimeValid = true;
				hideMedicationTimeError();
			}
		}
	}
	else {
		isTimeValid = true;
	}

	var isDosageValid = isMedicationDosageValid(true);
	if (isFormValid && isTimeValid && isDosageValid) {
		saveMedicationSchedule();
	}
});

$(document).on('keyup', '#MedicationSchedulerFormDosage', function() {
	isMedicationDosageValid();
});
$(document).on('change', '#MedicationSchedulerFormDosageUnit', function() {
	isMedicationDosageValid();
});

function isMedicationDosageValid(isOnSubmit) {
	var isDosageValid = false;
	var dosageErrorMsg = '';
	if ($('#MedicationSchedulerFormDosage').val() === '' || $('#MedicationSchedulerFormDosageUnit').val() === '') {
		isDosageValid = false;
		dosageErrorMsg = 'Please enter dose of medicine.';

		if ($('#MedicationSchedulerFormDosage').val() === '') {
			if (isOnSubmit === true) {
				$('#MedicationSchedulerFormDosage').parent('.form-group').addClass('error');
			}
		}
		else {
			$('#MedicationSchedulerFormDosage').parent('.form-group').removeClass('error');
		}

		if ($('#MedicationSchedulerFormDosageUnit').val() === '') {
			if (isOnSubmit === true) {
				$('#MedicationSchedulerFormDosageUnit').parent('.form-group').addClass('error');
			}
		}
		else {
			$('#MedicationSchedulerFormDosageUnit').parent('.form-group').removeClass('error');
		}
	}
	else {
		var dose = $('#MedicationSchedulerFormDosage').val();
		if (isNaN(dose) || (dose <= 0)) {
			isDosageValid = false;
			dosageErrorMsg = 'Please enter a valid dose.';
			if (isOnSubmit === true) {
				$('#MedicationSchedulerFormDosage').parent('.form-group').addClass('error');
			}
		}
		else {
			isDosageValid = true;
			$('#MedicationSchedulerFormDosage').parent('.form-group').removeClass('error');
		}
	}

	if (isDosageValid === false) {
		if (isOnSubmit === true) {
			showDosageError(dosageErrorMsg);
		}
	}
	else {
		hideDosageError();
	}
	return isDosageValid;
}

function showDosageError(dosageErrorMsg) {
	$('.dose_row').addClass('error');
	$('.dose_row .error_span').html(dosageErrorMsg).removeClass('hide');
}
function hideDosageError() {
	$('.dose_row').removeClass('error');
	$('.dose_row .error_span').html('').addClass('hide');
}

function getStartDateValue() {
	var selectedYear = $('#MedicationSchedulerFormStartYear').val();
	var selectedMonth = $('#MedicationSchedulerFormStartMonth').val();
	var selectedDay = $('#MedicationSchedulerFormStartDay').val();
	var selectedDate = '';
	if (selectedYear !== '') {
		if (selectedMonth === '') {
			selectedMonth = 1;
		}
		if (selectedDay === '') {
			selectedDay = 1;
		}
		selectedDate = selectedMonth + '/' + selectedDay + '/' + selectedYear;
	}
	return selectedDate;
}

function saveMedicationSchedule() {
	var submitBtn = '#save_medication_schedule';
	var cancelBtn = '#cancel_medication_schedule';
	var loading = Ladda.create(document.querySelector(submitBtn));
	$.ajax({
		type: 'POST',
		url: '/user/scheduler/save',
		data: $('#medication_scheduler_form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			loading.start();
			$(submitBtn).prop('disabled', true);
			$(cancelBtn).prop('disabled', true);
		},
		success: function(result) {
			if (result.success === true) {
				$(submitBtn).prop('disabled', true);
				$(cancelBtn).prop('disabled', true);
				if (result.refresh) {
					location.reload();
				}
				else {
					loading.stop();
					$('#medication_scheduler_dialog').modal('hide');
					if (result.content) {
						$('#medication_schedules').html(result.content);
					}
					else {
						bootbox.alert(result.message);
					}
				}
			}
			else if (result.error === true) {
				loading.stop();
				$(submitBtn).prop('disabled', false);
				$(cancelBtn).prop('disabled', false);
				bootbox.alert(result.message, function() {
					if (result.errorType && (result.errorType === 'fatal')) {
						window.location.reload();
						window.scrollTo(0, 0);
					}
				});
			}
		}
	});
}
$(document).on('click', '.medication_section .month_prev, .medication_section .month_next', function() {
	var selectedDate = $(this).attr('data-date');
	$.ajax({
		type: 'POST',
		url: '/user/myhealth/getMedicationsOnDate',
		data: {
			date: selectedDate
		},
		beforeSend: function() {
			var loading = $('#medication_loading').clone();
			loading.removeClass('hide');
			$('#medication_schedules').html(loading);
		},
		success: function(result) {
			$('#medication_schedules').html(result);
			var dataForm = $('#hidden_medication_data_form');
			var medicationDate = dataForm.find('#medication_date').val();
			var nextDate = dataForm.find('#next_date').val();
			var prevDate = dataForm.find('#prev_date').val();
			$('.medication_section .selected_date').html(medicationDate);
			$('.medication_section .month_next').attr('data-date', nextDate);
			$('.medication_section .month_prev').attr('data-date', prevDate);
		}
	});
});

$(document).on('click', '#select_all_medications', function() {
	if ($(this).is(':checked')) {
		$('input[type="checkbox"]').prop('checked', true);
	}
	else {
		$('input[type="checkbox"]').prop('checked', false);
	}
});

$(document).on('click', '#delete_medication_btn', function() {
	var selectedCount = $('tbody input[type="checkbox"]:checked').length;
	if (selectedCount > 0) {
		var confirmMessage = "Deleting the selected medication(s) will remove all reminders and medication history of those medications. \n\
		If you are no longer taking these medication(s), change the stop date to the day you stopped taking the medication. \n\
		This way you will not receive reminders, but will keep a record of your medication history.\n\
		If you still want to delete the selected medications, click 'OK' button.";
		bootbox.confirm(confirmMessage, function(isConfirmed) {
			if (isConfirmed) {
				var formData = $('#medications_form').serialize();
				$.ajax({
					url: '/user/scheduler/deleteMedications',
					type: 'POST',
					data: formData
				});
				$('tbody input[type="checkbox"]:checked').closest('tr').remove();
				$('tbody tr').removeClass('alternative_row');
				$('tbody tr').each(function(index) {
					if (index % 2 === 0) {
						$(this).addClass('alternative_row');
					}
				});
			}
		});
	}
	else {
		bootbox.alert('Please select any medication.');
		return false;
	}
});

$(document).on('click', 'a.edit_medication', function() {
	var row = $(this).closest('tr');
	clearMedicationScheduleForm();
	var id = row.find('input[type="checkbox"]').val();
	var medicineName = row.find('.medication_type').html();
	var medicineId = row.find('.medication_id').val();
	var dosage = row.find('.dosage').val();
	var dosageUnit = row.find('.dosage_unit').val();
	var indication = row.find('.indication').text();
	var amount = row.find('.amount').html();
	var instructions = row.find('.additional_instructions').text();
	var prescribedBy = row.find('.prescribed_by').text();
	var medicationForm = row.find('.medication_form').val();
	var medicationRoute = row.find('.route').val();
	var medicationFrequency = row.find('.frequency').val();
	var medicationStartYear = row.find('.start_year').val();
	var medicationStartMonth = row.find('.start_month').val();
	var medicationStartDay = row.find('.start_day').val();
	var medicationEndDate = row.find('.end_date').val();
	$('#MedicationSchedulerFormId').val(id);
	$('#MedicationSchedulerFormMedicineName').val(medicineName);
	$('#MedicationSchedulerFormMedicineId').val(medicineId);
	$('#MedicationSchedulerFormDosage').val(dosage);
	$('#MedicationSchedulerFormDosageUnit').val(dosageUnit);
	$('#MedicationSchedulerFormIndication').val(indication);
	$('#MedicationSchedulerFormAmount').val(amount);
	$('#MedicationSchedulerFormAdditionalInstructions').val(instructions);
	$('#MedicationSchedulerFormPrescribedBy').val(prescribedBy);
	$('#MedicationSchedulerFormForm').val(medicationForm);
	$('#MedicationSchedulerFormRoute').val(medicationRoute);
	$('#MedicationSchedulerFormRepeatFrequency').val(medicationFrequency);
	$('#MedicationSchedulerFormStartYear').val(medicationStartYear);
	$('#MedicationSchedulerFormStartMonth').val(medicationStartMonth);
	$('#MedicationSchedulerFormStartDay').val(medicationStartDay);
	handleStartDateChange();
	if (medicationEndDate !== '') {
		$('#MedicationSchedulerFormEndDate').val(medicationEndDate);
		$('#MedicationSchedulerFormEndDate').prop('disabled', false);
		$('#remind_until_cancelled').prop('checked', false);
	}
	row.find('.time_list').each(function() {
		var timeValue = $(this).val();
		if (parseInt(timeValue[0]) === 0) {
			timeValue = timeValue.replace('0', '');
		}
		addTimeRow(timeValue);
	});
	$('#medication_scheduler_dialog').find('.modal-title.add_title').addClass('hide');
	$('#medication_scheduler_dialog').find('.modal-title.edit_title').removeClass('hide');
	$('#medication_scheduler_dialog').modal('show');
});

/*
 * Printing Medication scheduler
 */
$(document).on('click', '.scheduler_print', function() {
	window.open('/healthinfo/print?graphIds=20' , '_blank');
});