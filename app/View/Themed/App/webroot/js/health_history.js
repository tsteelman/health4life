$(document).ready(function() {
    
    // dob datepicker
    $('#UserHealthHistoryDob').datepicker({
		dateFormat: 'mm/dd/yy',
		changeMonth: true,
		changeYear: true,
		minDate: new Date(1900, 01, 01),
		maxDate: new Date(),
		onClose: function() {
			$(this).valid();
		}
	});
    
    // apply autocomplete search to location field in personal information tab
    if ($("#UserHealthHistoryLocation").length > 0) {
        var locationCache = {};
        $("#UserHealthHistoryLocation").autocomplete({
            minLength: 2,
            source: function(request, response) {
                var term = request.term;
                if (term in locationCache) {
                    response(locationCache[ term ]);
                    return;
                }

                $.ajax({
                    url: '/search/search/searchLocations',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        term: term
                    },
                    success: function(data) {
                        locationCache[ term ] = data;
                        response(data);
                    }
                });
            },
            search: function(event) {
                if (event.ctrlKey === true) {
                    return false;
                }
                $('#no_location_msg').hide();
                $('#UserHealthHistoryCityId').val('');
            },
            change: function() {
                if ($('#UserHealthHistoryLocation').val() === '') {
                    $("#UserHealthHistoryCityId").val('');
                }
            },
            response: function(event, ui) {
                $('span[for="UserHealthHistoryCityId"].help-block').hide();
                if (ui.content.length === 0) {
                    $('#no_location_msg').show();
                    $('#UserHealthHistoryCityId').val('');
                } else {
                    $('#no_location_msg').hide();
                }
                handleLocationError();
            },
            select: function(event, ui) {
                if (ui.item) {
                    $('#UserHealthHistoryCityId').val(ui.item.id);
                    $('#UserHealthHistoryCityId').valid();
                }
            }
        });
    }

    $.validator.addMethod('isValidYear', function(value, element) {
        var re = new RegExp("([1-2][0-9][0-9][0-9])");
        var isValid = this.optional(element) || re.test(value);
        if (isValid === true) {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            isValid = (value <= currentYear) ? true : false;
            if (isValid === true) {
                var bornYear = $('#born_year').val();
                if (bornYear !== '') {
                    isValid = (value >= bornYear) ? true : false;
                }
            }
        }
        return isValid;
    });
});


/*
 * Back button functionality
 */
$(document).on('click', '#back_btn', function() {
    window.location = $(this).attr('data-href');
});

$(document).on('blur', '#UserHealthHistoryLocation', function() {
    handleLocationError();
});
$(document).on('change', '#UserHealthHistoryLocation', function() {
    handleLocationError();
});
$(document).on('keyup', '#UserHealthHistoryLocation', function(event) {
    handleLocationError();
});

/**
 * Handle location error color
 */
function handleLocationError() {
    if ($('span[for="UserHealthHistoryCityId"].help-block').is(':visible') || $('#no_location_msg').is(':visible')) {
        $('#location_col').addClass('error');
    }
    else {
        $('#location_col').removeClass('error');
    }
}

/*
 * Add new record in surgery/injury tabs
 */
$(document).on('click', '#add_record', function() {
    var lastRecordIndex;
    var newRecordIndex;
    if ($('#last_record_index').val() !== '') {
        lastRecordIndex = $('#last_record_index').val();
        newRecordIndex = parseInt(lastRecordIndex) + 1;
    }
    else {
        newRecordIndex = 0;
    }

    $('#no_records_msg').hide();
    $('.records_container').removeClass('hide');
    var record = $('#sample_record').clone();
    record.removeClass('hide').removeAttr('id');
    record.find('input').each(function() {
        var inputName = $(this).attr('name');
        var inputId = $(this).attr('id');
        inputName = inputName.replace('index', newRecordIndex);
        inputId = inputId.replace('Index', newRecordIndex);
        $(this).attr('name', inputName);
        $(this).attr('id', inputId);
    });
    $('.records_container').append(record);
    $('#last_record_index').val(newRecordIndex);
});

/*
 * Remove a record in surgery/injury tabs
 */
$(document).on('click', '.record .close', function() {
    $(this).parents('.record').remove();
    if ($('.records_container .record').length === 0) {
        $('.records_container').addClass('hide');
    }
});

$(document).on('click', '#UserHealthHistorySurgeriesForm .btn-next', function() {
    runRelatedFormsValidationScript();
});

$(document).on('click', '#UserHealthHistoryInjuriesForm .btn-next', function() {
    runRelatedFormsValidationScript();
});

$(document).on('submit', 'form', function() {
    $('button[type="submit"]').prop('disabled', true);
});

$(document).on('click', '#UserHealthHistoryPersonalForm .btn-next', function() {
    if ($('#UserHealthHistoryCityId').val() === '0') {
        $('#UserHealthHistoryCityId').val('');
    }
    $('#no_location_msg').hide();
});