/**
 * Skip step 1
 */
$(document).on('click', '#import_contacts_wizard #skip_step_1', function() {
    showStep2();
});

function showStep2() {
    $('#import_contact_step_1').addClass('hide');
    $('#import_contact_step_2').removeClass('hide');
    var stepsLi = $('#import_contacts_wizard ul.steps li');
    stepsLi.eq(0).removeClass('active');
    stepsLi.eq(1).addClass('active');
}

/**
 * Skip step 2
 */
$(document).on('click', '#import_contacts_wizard #skip_step_2', function() {
    $('#import_contact_step_2').addClass('hide');
    doRedirectAfterStep2();
});

/**
 * Redirect to home page after step2
 */
function doRedirectAfterStep2() {
    window.location.href = '/profile';
}

/**
 * Initialize global variables on doc ready
 */
var existingContactsCheckBoxList;
var newContactsCheckBoxList;
$(document).ready(function() {
    existingContactsCheckBoxList = $('input[name="existing_contacts[]"]');
    newContactsCheckBoxList = $('input[name="new_contacts[]"]');

    // stylize scrollbars
    $('.slim-scroll').each(function() {
        var $this = $(this);
        $this.slimScroll({
            color: '#BBDAEC',
            railColor: '#EBF5F7',
            size: '12px',
            height: '300px',
            railVisible: true
        });
    });
});

/**
 * Select/Deselect all existing contacts
 */
$(document).on('change', '#select_all_existing_contacts', function() {
    var selectedCols = $('#import_contact_step_1 .contact_persons');
    if ($(this).is(':checked')) {
        existingContactsCheckBoxList.prop('checked', true);
        selectedCols.addClass('active');
    }
    else {
        existingContactsCheckBoxList.prop('checked', false);
        selectedCols.removeClass('active');
    }
    handleExistingContactsCheckboxChange();
});

/**
 * Select/Deselect all existing contacts
 */
$(document).on('change', '#select_all_new_contacts', function() {
    var selectedCols = $('#import_contact_step_2 .indvdl_people');
    if ($(this).is(':checked')) {
        newContactsCheckBoxList.prop('checked', true);
        selectedCols.addClass('active');
    }
    else {
        newContactsCheckBoxList.prop('checked', false);
        selectedCols.removeClass('active');
    }
    handleNewContactsCheckboxChange();
});

/**
 * Update 'existing contacts' count, on changing the checkbox status
 * and add/remove active class to the checkbox container
 */
$(document).on('change', 'input[name="existing_contacts[]"]', function() {
    handleExistingContactsCheckboxChange();
    var selectedCol = $(this).closest('.contact_persons');
    if ($(this).is(':checked')) {
        selectedCol.addClass('active');
    }
    else {
        selectedCol.removeClass('active');
    }
});

/**
 * Update 'new contacts' count, on changing the checkbox status
 * and add/remove active class to the checkbox container
 */
$(document).on('change', 'input[name="new_contacts[]"]', function() {
    handleNewContactsCheckboxChange();
    var selectedCol = $(this).closest('.indvdl_people');
    if ($(this).is(':checked')) {
        selectedCol.addClass('active');
    }
    else {
        selectedCol.removeClass('active');
    }
});

/**
 * Function to show total selected 'existing contacts' count
 * and enable/disable the 'Add connections' btn.
 */
function handleExistingContactsCheckboxChange() {
    var count = $('input[name="existing_contacts[]"]:checked').length;
    $('#selected_existing_contacts_count').html(count);
    var btn = $('#add_existing_connections');
    if (count > 0) {
        btn.prop('disabled', false);
    }
    else {
        btn.prop('disabled', true);
    }
}

/**
 * Function to show total selected 'new contacts' count
 * and enable/disable the 'Add connections' btn.
 */
function handleNewContactsCheckboxChange() {
    var count = $('input[name="new_contacts[]"]:checked').length;
    $('#selected_new_contacts_count').html(count);
    var btn = $('#add_new_connections');
    if (count > 0) {
        btn.prop('disabled', false);
    }
    else {
        btn.prop('disabled', true);
    }
}

/**
 * Add existing connections
 */
$(document).on('click', '#add_existing_connections', function() {
    var btn = $(this);
    var data = existingContactsCheckBoxList.serialize();
    $.ajax({
        url: '/user/friends/addExistingConnections',
        data: data,
        method: 'POST',
        dataType: 'json',
        beforeSend:function(){
            btn.prop('disabled', true);
        },
        success: function(result) {
            if (result.message !== '') {
                $('#import_contact_message').removeClass('hide');
                $('#import_contact_message .message').html(result.message);
                if (result.success === true) {
                    $('#import_contact_message').removeClass('alert-error').addClass('alert-success');
                    showStep2();
                }
                else if (result.error === true) {
                    btn.prop('disabled', false);
                    $('#import_contact_message').removeClass('alert-success').addClass('alert-error');
                }
                window.scrollTo(0, 0);
            }
            else {
                $('#import_contact_message').addClass('hide');
                $('#import_contact_message .message').html('');
            }
        }
    });
});

/**
 * Add existing connections
 */
$(document).on('click', '#add_new_connections', function() {
    var btn = $(this);
    var data = newContactsCheckBoxList.serialize();
    $.ajax({
        url: '/user/friends/addNewConnections',
        data: data,
        method: 'POST',
        dataType: 'json',
        beforeSend:function(){
            btn.prop('disabled', true);
        },
        success: function(result) {
            $('#import_contact_message').addClass('hide');
            $('#import_contact_message .message').html('');
            if (result.success === true) {
                doRedirectAfterStep2();
            }
            else if (result.error === true) {
                btn.prop('disabled', false);
                $('#import_contact_message').removeClass('hide');
                $('#import_contact_message .message').html(result.message);
                $('#import_contact_message').removeClass('alert-success').addClass('alert-error');
                window.scrollTo(0, 0);
            }
        }
    });
});

/**
 * Trigger checkbox click on clicking on the associated user block.
 */
$(document).on('click', '#import_contact_step_1 .contact_persons, #import_contact_step_2 .indvdl_people', function(event) {
    var checkboxElement = 'input[type="checkbox"]';
    var target = $(event.target);
    if (!target.is(checkboxElement)) {
        $(this).find(checkboxElement).trigger('click');
    }
});