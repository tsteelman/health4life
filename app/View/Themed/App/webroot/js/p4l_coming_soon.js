
/**
 *  Save prelaunch user detils 
 *  
 */
$(document).on('submit', '#PrelaunchUserComingSoonForm', function() {
    var loginForm = $(this);
    var loginUrl = loginForm.attr('action');
    var loginData = loginForm.serialize();
    var loginBtn = $(this).find('button[type="submit"]');

    $.ajax({
        type: 'POST',
        url: loginUrl,
        data: loginData,
        dataType: 'json',
        beforeSend: function() {
            // disable multiple clicks
            loginBtn.attr('disabled', 'disabled');
        },
        success: function(result) {
            if (result.success === true) {
                loginForm.remove();
                showSuccessMessage();
            }
            else if (result.error === true) {
//                hideAlerts();
//                $("#UserPassword").val('');
//                showLoginAlert('login_flash_error', result.message);
                loginBtn.removeAttr('disabled');
            }
        }
    });
    return false;
});

function showSuccessMessage() {
    var el = $('.comingsoon_signup');
    var msg = '<h2>Your contact details have been successfully saved</h2>';
    el.append(msg);
}