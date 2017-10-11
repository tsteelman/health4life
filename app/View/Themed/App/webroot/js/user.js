/*
 * Function to handle friends functionalities (Add, Remove)
 */

var user_id = '';
var friend_id = '';
var search = false;

/*
 * Function to disable clicked button for friends functions
 * @param int id
 */
function disableButton(id) {
    $(id).css({
        'border': '1px solid #004f7f',
        'background-color': '#2c589e',
        'color': '#fff'
    });
    $(id).attr('disabled', 'disabled');
}

function enableButton(id) {
    $(id).removeAttr("style");
    $(id).removeAttr('disabled', 'disabled');
}
/*
 * Function to add friends
 * @param int friend_id
 * @param boolean search
 */
function addFriend(friend_id, search, is_notification) {
    var add_button = '#add_button_' + friend_id;
    if (typeof (is_notification) != 'undefined' && is_notification == true) {
        add_button = '#notification_add_button_' + friend_id;
    }
    rotate = Ladda.create(document.querySelector(add_button));
    if (rotate != null) {
        rotate.start();
    }
    disableButton($(add_button));
    $.ajax({
        url: '/user/friends/addFriend',
        cache: false,
        type: 'POST',
        data: {'friend_id': friend_id, 'search': search},
        success: function() {
            if (typeof (is_notification) != 'undefined' && is_notification == true) {
                addFriendResponse(friend_id, search, true);
            } else {
                addFriendResponse(friend_id, search);
            }
        }
    });
}
/*
 * Response function for add friend ajax call
 * @param int friend_id
 * @param boolean search
 * @param object rotate
 */
function addFriendResponse(friend_id, search, is_notification) {
    var add_button = '#add_button_' + friend_id;
    if (typeof (is_notification) != 'undefined' && is_notification == true) {
        add_button = '#notification_add_button_' + friend_id;
    }
    if (!search) {
        location.reload();
    } else {
        $(add_button).text("Request Sent");
        rotate = Ladda.create(document.querySelector(add_button));
        rotate.stop();
        disableButton($(add_button));
        setTimeout(function() {
            updateFriendNotificationListRow('people_mayknow_notification');
        }, 3000);
    }
}

/*
 * Function to approve friends request
 * @param int friend_id
 * @param boolean search
 */
function approveFriend(friend_id, search, is_notification) {
    var accept_button = '#accept_button_' + friend_id;
    var reject_button = '#reject_button_' + friend_id;
    if (typeof (is_notification) != 'undefined' && is_notification == true) {
        accept_button = '#notification_accept_button_' + friend_id;
        reject_button = '#notification_reject_button_' + friend_id;
    }
    rotate = Ladda.create(document.querySelector(accept_button));
    if (rotate != null) {
        rotate.start();
    }
    disableButton($(accept_button));
    $(reject_button).attr('disabled', 'disabled');
    $.ajax({
        url: '/user/friends/approveFriend/' + friend_id + '/' + search,
        type: 'POST',
        success: function(data) {
            $(accept_button).removeAttr("onclick");
            if (typeof (is_notification) != 'undefined' && is_notification == true) {
                approveFriendResponse(friend_id, search, rotate, true);
            } else {
                approveFriendResponse(friend_id, search, rotate);
            }

            if ($("#online_friends_list").length) {
                updateOnlineFriendsList();
            }
            if ($("#profile_friends_online").length) {
                updateProfileOnlineFriendsList();
                ;
            }
        }
    })
}

/*
 * Response function for approve friend ajax call
 * @param int friend_id
 * @param boolean search
 * @param object rotate
 */
function approveFriendResponse(friend_id, search, rotate, is_notification) {
    var accept_button = '#accept_button_' + friend_id;
    var reject_button = '#reject_button_' + friend_id;
    if (typeof (is_notification) != 'undefined' && is_notification == true) {
        accept_button = '#notification_accept_button_' + friend_id;
        reject_button = '#notification_reject_button_' + friend_id;
    }
    if (!search) {
        location.reload();
    } else {
        $(accept_button).remove();
        $(reject_button).text("You have responded.");
//        setTimeout(function() {
//            updateFriendNotificationListRow('pending_rquests_notification');
//        }, 3000);
        getFriendNotificationUpdates();
    }
    rotate.stop();
    disableButton($(reject_button));
}

/*
 * Function to reject friends request
 * @param int friend_id
 * @param boolean search
 */
function rejectFriend(friend_id, search, is_notification) {
    var accept_button = '#accept_button_' + friend_id;
    var reject_button = '#reject_button_' + friend_id;
    if (typeof (is_notification) != 'undefined' && is_notification == true) {
        accept_button = '#notification_accept_button_' + friend_id;
        reject_button = '#notification_reject_button_' + friend_id;
    }
    rotate = Ladda.create(document.querySelector(reject_button));
    if (rotate != null) {
        rotate.start();
    }
    disableButton($(reject_button));
    $(accept_button).attr("disabled", 'disabled');
    $.ajax({
        url: '/user/friends/rejectFriend/' + friend_id + '/' + search,
        type: 'POST',
        success: function() {
            $(reject_button).removeAttr("onclick");
            if (typeof (is_notification) != 'undefined' && is_notification == true) {
                rejectFriendResponse(friend_id, search, rotate, true);
            } else {
                rejectFriendResponse(friend_id, search, rotate);
            }
        }
    })
}

/*
 * Response function for ajax call of reject friends request
 * @param int friend_id
 * @param boolean search
 * @param object rotate
 */
function rejectFriendResponse(friend_id, search, rotate, is_notification) {
    var accept_button = '#accept_button_' + friend_id;
    var reject_button = '#reject_button_' + friend_id;
    if (typeof (is_notification) != 'undefined' && is_notification == true) {
        accept_button = '#notification_accept_button_' + friend_id;
        reject_button = '#notification_reject_button_' + friend_id;
    }
    if (!search) {
        location.reload();
    } else {
        $(accept_button).remove();
        $(reject_button).text("You have responded");
//        setTimeout(function() {
//            updateFriendNotificationListRow('pending_rquests_notification');
//        }, 3000);
        getFriendNotificationUpdates();
    }
    rotate.stop();
    disableButton($(reject_button));
}

/*
 * Function to remove friend from hovercard
 */
function hovercardRemoveFriend(friend_id, search) {
	bootbox.confirm("Are you sure you want to remove?", function(confirmed) {
		if (confirmed) {
			var rotate = Ladda.create(document.querySelector("#remove_hovercard_button_" + friend_id));
			if (rotate != null) {
				rotate.start();
			}
			disableButton($("#remove_hovercard_button_" + friend_id));
			
			$.ajax({
				url: '/user/friends/removeFriend',
				type: 'POST',
				data: {'friend_id': friend_id, 'search': search},
				success: function() {
					if (!search) {
						location.reload();
					} else {
						removeFriendResponse(friend_id, search, rotate);
						createAddFriendButton(friend_id, rotate);
					}

					// update online friends list
					if ($("#online_friends_list").length) {
						updateOnlineFriendsList();
						$('.popover').remove();
					}
				}
			});
		}
	});
}
/*
 * Functin create add friend button insted of remove friend button
 * after clicking remove frined hovercard button	
 */
function createAddFriendButton(friend_id, rotate) {
    if (rotate != null) {
        rotate.stop();
    }
    $("#remove_hovercard_button_" + friend_id).html("Add as friend");
    $("#remove_hovercard_button_" + friend_id).removeAttr('data-friend_id');
    $("#remove_hovercard_button_" + friend_id).attr('onclick', 'addFriend(' + friend_id + ', true)');
    $("#remove_hovercard_button_" + friend_id).attr('id', 'add_button_' + friend_id);
    enableButton($('#add_button_' + friend_id));

}

/*
 * Functio to remove friends
 */
function removeFriend(friend_id, search) {
    var rotate = Ladda.create(document.querySelector("#remove_button_" + friend_id));
    if (rotate != null) {
        rotate.start();
    }
    disableButton($("#remove_button_" + friend_id));
    $.ajax({
        url: '/user/friends/removeFriend',
        type: 'POST',
        data: {'friend_id': friend_id, 'search': search},
        dataType: 'json',
        success: function(response) {
            removeFriendResponse(friend_id, search, rotate);

            // set response as popup 
            if (!jQuery.isEmptyObject(response)) {

                var alert_types = new Array("warning", "success", "danger");

                $.each(alert_types, function(index, value) {
                    $("#header-alert").removeClass("alert-" + value);
                });
                $("#header-alert").addClass("alert-" + response.type);
                $("#header-alert .alert-content").html(response.message);
                $("#header-alert").show();
                setTimeout(function() {
                    $("#header-alert").hide();
                }, 5000);
            }
        }
    });
}

function removeFriendResponse(friend_id, search, rotate) {
    if (!search) {
        rotate.stop();
        disableButton($("#remove_button_" + friend_id));
        $("#remove_button_" + friend_id).text('Removed');
    } else {
        var newJson = {
            friends: {
                friend: []
            }
        };
        $("#" + friend_id).remove();
        for (var i = 0; i < friendList['friends']['friend'].length; i++) {
            if (friendList['friends']['friend'][i]['friend_id'] != friend_id) {
                newJson['friends']['friend'].push(friendList['friends']['friend'][i]);
            }
        }
        friendList = newJson;
        if (friendList['friends']['friend'].length == 0) {
            $("#none_found").removeClass('hidden');
        }
        if (rotate != null) {
            rotate.stop();
        }
    }

    $("#remove_button_" + friend_id).text('Removed');
    $("#remove_button_" + friend_id).removeAttr("onclick");
}


/*
 * Function to display mutual friends
 */
var mutualFriendsList = '';
$(document).on('click', '.mutual_frnds', function(e) {
    e.preventDefault();
    if (!$(this).hasClass('disabled')) {
        $("#mutualFriends .modal-body").html('<center><img src="/img/loader.gif" alt="Loading..."></center>');
        user_id = $(this).data('user_id');
        friend_id = $(this).data('friend_id');
        search = $(this).data('search');
        if (typeof (search) === 'undefined') {
            search = false;
        }
        $.ajax({
            url: '/user/friends/getMutualFriends',
            type: 'POST',
            data: {'user_id': user_id, 'friend_id': friend_id},
            dataType: 'json',
            success: function(result) {
                mutualFriendsList = JSON.parse(result['json']);
                $("#mutualFriends .modal-body").html(result['html_content']);
                if (($("#mutual-friends .col-lg-6").length) % 2 != 0) {
                    $("#mutual-friends .col-lg-6:last").css('border-bottom', '0px');
                }
            }
        })
        $('#mutualFriends').modal('show');
    } else {
        return false;
    }
})

/*
 * On clicking + button show the modal box
 */
$(document).on('click', '.btn_add_symtom_severity', function(e) {
    var symptomId = $(this).data('symptom_id');
    var symptomName = $(this).data('symptom_name');
    var currentSeverity = $(this).data('severity');
    var symptom_div_id = symptomName.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[ ]/g, '_');
    var lastUpdatedDate = $('#' + symptom_div_id).data('last-updated-date');

    if (lastUpdatedDate == "") {
        var lastUpdatedDate = getUserNow().toDateString();
    } else {
        var lastUpdatedDate = new Date(lastUpdatedDate).toDateString();
    }

    var userNowDate = getUserNow().toDateString();

    /*
     * Set the modal data
     */
    $('#model_add_symptom_serveriy .symptom-selected').html(symptomName); // set the symptom name selected
    $('#model_add_symptom_serveriy #selectedSymptomId').val(symptomId);    // symptom Id

    /*
     * Set the default values
     */
    $('#modal_symptom_datepicker').val('');  // unset the date picker
    $('#modal_symptom_date_selected').html('today'); // set the default date as today   
    $('.condition_indicator label').removeClass('on');     // remove previously selected severity 
    $('.condition_indicator input').attr("checked", false);   // unset the radio buttons
    $('#symptom_history_error_message').hide();       // hide the error message

    if (currentSeverity != 'no data' && (userNowDate === lastUpdatedDate)) {  ///&& lastUpdatedDate == getUserNow()
        $('.condition_' + currentSeverity).addClass('on');
        $('.condition_' + currentSeverity).find("input").prop('checked', true);
        $('.condition_' + currentSeverity).find("input").hecked = true; // for IE
    }
    $('#model_add_symptom_serveriy').modal('show');

});

/*
 * Save the severity on click of severity modal
 */
$(document).on('click', '#btn_symptom_severity_save', function() {

    var symptom_id = $.trim($('#model_add_symptom_serveriy #selectedSymptomId').val());
    var symptom_name = $.trim($('#model_add_symptom_serveriy .symptom-selected').html());
    var symptom_div_id = symptom_name.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[ ]/g, '_');
    var container = symptom_div_id + '_gauge_graph';

    /*
     * Desable the save button before save
     */
    $('#btn_symptom_severity_save').attr('disabled', 'disabled');

    /*
     * Check any severity is selected
     */
    if ($('input[name=symptomHistoryRadio]:radio:checked').length > 0) {
        var severity = $('input[name=symptomHistoryRadio]:radio:checked').val();
        var severityName = $('.condition_indicator label.on  span').html();
        var severityClass = 'dial_' + $('.condition_indicator .on').attr('class').replace(" on", "");
        var date = $('#modal_symptom_datepicker').val();                       // current date selected
        var lastUpdatedDate = $('#' + symptom_div_id).data('last-updated-date');    // last updated date
        var newSeverity = $('.condition_indicator .on .name').html().toLowerCase();  // selecte severity name 
        var dialValue = getDialValue(newSeverity);
        var callback_function = callback_saveSymptomSeverity;

        var params = {
            'date': date,
            'severityName': severityName,
            'severityClass': severityClass,
            'newSeverity': newSeverity,
            'lastUpdatedDate': lastUpdatedDate,
            'symptom_div_id': symptom_div_id,
            'container': container,
            'dialValue': dialValue
        };		
        saveSymtomSeverity(symptom_id, date, severity, callback_function, params);

    }
    else {
        $('#symptom_history_error_message').text('Please select valid severity.').show();
        $('#btn_symptom_severity_save').removeAttr('disabled');
        return false;
    }


});

function getDialValue(severity) {
    var dialValue = 0;
    switch (severity.toLowerCase()) {
        case 'none':
            dialValue = 25;
            break;
        case 'mild':
            dialValue = 75;
            break;
        case 'moderate':
            dialValue = 125;
            break;
        case 'severe':
            dialValue = 175;
            break;
        default :
            dialValue = 0;
    }

    return dialValue;
}
/*
 * Function to change the severity tile after each save
 * @param {array} params
 *                      : date
 *                      : lastUpdatedDate
 *                      : symptom_div_id
 *                      : severityName
 *                      : severityClass
 *                      : newSeverity
 */
function callback_saveSymptomSeverity(params) {

    var date = params.date;
    var lastUpdatedDate = params.lastUpdatedDate;
    var symptom_div_id = params.symptom_div_id;
    var severityName = params.severityName;
    var severityClass = params.severityClass;
    var newSeverity = params.newSeverity;
    /*
     * create date object for current date
     * and last updated date
     */
    if (date == "") {
        var currentDateObj = getUserNow();
    } else {
        var currentDateObj = new Date(date);
    }

    if (lastUpdatedDate == "") {
        var lastUpdatedDateObj = getUserNow();
    } else {
        var lastUpdatedDateObj = new Date(lastUpdatedDate);
    }

    /*
     * Remove time form date object
     */
    currentDateObj.setHours(0, 0, 0, 0);
    lastUpdatedDateObj.setHours(0, 0, 0, 0);

    /*
     * If the current ented date is grater than or equal to last updated date
     * then change the health indiactor dial
     */
    if (currentDateObj >= lastUpdatedDateObj) {
        chartValue[ params.container ].series[0].points[0].update(params.dialValue);

        $('#' + symptom_div_id + ' p ').html(severityName); // change the name
        $('#' + symptom_div_id).removeClass().addClass('col-lg-3 ' + severityClass); // change the dial image
        $('#' + symptom_div_id + ' .btn_add_symtom_severity ').data('severity', newSeverity); // change the button severity data 
        $('#' + symptom_div_id).data('last-updated-date', date);
        var dateFormated = $.datepicker.formatDate('M d, yy', currentDateObj);
        $('#' + symptom_div_id + ' p ').attr('title', 'Last updated: ' + dateFormated);
    }

    /*
     * Reset the modal
     */
    $('#model_add_symptom_serveriy').modal('hide'); // hide modal
    $('#btn_symptom_severity_save').removeAttr('disabled'); // change modal button status to enabled
    $('#model_add_symptom_serveriy .condition_popup_container').find('label').removeClass('on'); // remove the selected severity 
    $('#modal_symptom_datepicker').val(''); // remove the datepicker value 
    $('#modal_symptom_date_selected').html('today'); // set the defalult date 
}

function saveSymtomSeverity(symptom_id, date, severity, callback_function, params) {
	chartValue [ params.container ].showLoading('saving..');
    $.ajax({
        url: '/user/api/addUserSeverity',
        data: {
            'id': symptom_id,
            'date': date,
            'severity': severity
        },
        type: 'POST',
        success: function(result) {
            chartValue [ params.container ].hideLoading();
            socket.emit('my_health_update', {
					room:$('#graphUpdatedInRoom').val(),
                                        type: 'symptom'
				});
            if (typeof callback_function == "function") {
                callback_function(params);
            }
        },
        error: function(error) {
            chartValue [ params.container ].hideLoading();
        }
    });
}

$(document).on('click', '#btn_add_all_symptom_severity', function() {
    $.ajax({
        url: '/user/api/getAddSymptomListHTML',
        success: function(result) {

        }
    });
});

$(document).on('click', '#btn_daily-health-load-more', function() {
    $('.health_indicator_div .row').removeClass('hidden');
    hideAllPainData();
    appendlatestPainDetails();
    $('#btn_daily-health-load-more').hide();
});

$(document).on('click', '#btn_daily-health-load-less', function() {
    $('.health_indicator_div .row.health_dial_indicator:gt(2)').addClass('hidden');
    appendlatestPainDetails();
    $('#btn_daily-health-load-more').show();
});

function clearVariablesAndData() {

    /*Reseting data variables*/
    selected_body_main_part = 1;
    selected_pain_type = 1;
    all_pain_array = new Array();
    save_pain_array = new Array();
    new_pain_obj = {};
    append_pin = false;
    bodyPartsArray = 0;
    bodySubPartsArray = 0;
    x_pos_image = 0;
    y_pos_image = 0;
    id_counter = 0;
    current_fixed_pin_id = 0;
}

function hideAllPainData() {
    clearVariablesAndData();
    $('.fixed_pin, .latest_pain_pins').each(function() {
        $(this).fadeOut(10, function() {
            $(this).remove();
        });

    });
}

/*Show last pain entry on body image.*/
function appendlatestPainDetailsFixed() {
    if (typeof latestPainDataDetails != 'undefined' && latestPainDataDetails != null) {
        if (latestPainDataDetails.length > 0) {
            // $("#draggables_container").hide();
//            $("#save_pain_finish_btn_container").hide();
//            $("#latest_pain_message_container").show();
            var skeleton_offset = $('bodypain_tracker_container').offset();
//            var container = $('#last_pain_details_container');
            var container = $("#paintracker_div");
            $(latestPainDataDetails).each(function(index, pin) {
                if (typeof (pin) != 'undefined' && parseInt(pin.severity) > 0 && pin.severity != null) {
                    var pain_type = pin.pain_type;
                    var img_clone = $("#drag_pain_icon" + pain_type).clone().removeAttr('id').removeAttr('class');
                    img_clone.addClass("drag_pain_icon" + pain_type + 'ui-draggable ui-draggable-dragging fixed_pin').attr('id', id_counter);
                    img_clone.css({
                        'position': 'absolute',
                        'left': pin.pos_x + 'px',
                        'top': pin.pos_y + 'px'});
//                $('#drag_pain_icon' + pain_type).after(img_clone);
//                img_clone.appendTo(container);
                    container.append(img_clone);

                    obj = {};
                    obj [ 'severity' ] = parseInt(pin.severity);
                    obj [ 'pain_type' ] = parseInt(pin.pain_type);
                    obj [ 'selected_body_main_part' ] = parseInt(pin.bodyPartMain);
                    obj [ 'pos_x' ] = parseInt(pin.pos_x);
                    obj [ 'pos_y' ] = parseInt(pin.pos_y);

                    all_pain_array [ id_counter ] = obj;

                    id_counter++;
                }
            });


            /* 
             var skeleton_offset = $('#skeleton_front_view').offset();
             for (var i = 0; i < latestPainDataDetails.length; i++) {
             var pain_type = latestPainDataDetails[i]['pain_type'];
             var container = $("body");
             var img_clone = $("#drag_pain_icon" + pain_type).clone();
             var img_top = parseInt(skeleton_offset.top) + parseInt(latestPainDataDetails[i]['pos_y']);
             var img_left = parseInt(skeleton_offset.left) + parseInt(latestPainDataDetails[i]['pos_x']);
             img_clone.removeAttr('id').removeAttr('class').addClass('latest_pain_pins').css({
             'position': 'absolute',
             'left': img_left + 'px',
             'top': img_top + 'px'
             })
             .appendTo(container);
             }
             */

        }
    }
//    console.log('called method');
//    if (typeof latestPainDataDetails != 'undefined' && latestPainDataDetails != null) {
//        console.log('first conditoion true.');
//        if (latestPainDataDetails.length > 0) {
//            console.log('second condition true');
////            $("#draggables_container").hide();
////            $("#save_pain_finish_btn_container").hide();
////            $("#latest_pain_message_container").show();
//
//            var skeleton_offset = $('#skeleton_front_view').offset();
//            for (var i = 0; i < latestPainDataDetails.length; i++) {
//                var pain_type = latestPainDataDetails[i]['pain_type'];
//                var container = $("body");
//                var img_clone = $("#drag_pain_icon" + pain_type).clone();
//                console.log('img_clone',img_clone);
//                var img_top = parseInt(skeleton_offset.top) + parseInt(latestPainDataDetails[i]['pos_y']);
//                var img_left = parseInt(skeleton_offset.left) + parseInt(latestPainDataDetails[i]['pos_x']);
//                img_clone.removeAttr('id').removeAttr('class').addClass('latest_pain_pins').css({
//                    'position': 'absolute',
//                    'left': img_left + 'px',
//                    'top': img_top + 'px'
////                    'position': 'absolute',
////                    'left': '150px',
////                    'top': '150px'
//                })
//                        .appendTo(container);
//                console.log('appended pin.',img_clone);
//            }
//
//        }
//    }
}

function appendlatestPainDetails() {
    if (typeof latestPainDataDetails != 'undefined' && latestPainDataDetails != null) {
        if (latestPainDataDetails.length > 0) {
            // $("#draggables_container").hide();
//            $("#save_pain_finish_btn_container").hide();
//            $("#latest_pain_message_container").show();
            var container = $("#paintracker_div");
            var skeleton_offset = $('bodypain_tracker_container').offset();
            $(latestPainDataDetails).each(function(index, pin) {
                if (typeof (pin) != 'undefined' && parseInt(pin.severity) > 0 && pin.severity != null) {
                    var pain_type = pin.pain_type;
                    var img_clone = $("#drag_pain_icon" + pain_type).clone().removeAttr('id').removeAttr('class');
                    img_clone.addClass("drag_pain_icon" + pain_type + 'ui-draggable ui-draggable-dragging fixed_pin').attr('id', id_counter);
                    img_clone.css({
                        'position': 'absolute',
                        'left': pin.pos_x + 'px',
                        'top': pin.pos_y + 'px'});
//                $('#drag_pain_icon' + pain_type).after(img_clone);
                    container.append(img_clone);
                    obj = {};
                    obj [ 'severity' ] = parseInt(pin.severity);
                    obj [ 'pain_type' ] = parseInt(pin.pain_type);
                    obj [ 'selected_body_main_part' ] = parseInt(pin.bodyPartMain);
                    obj [ 'pos_x' ] = parseInt(pin.pos_x);
                    obj [ 'pos_y' ] = parseInt(pin.pos_y);

                    all_pain_array [ id_counter ] = obj;

                    id_counter++;
                }
            });


            /* 
             var skeleton_offset = $('#skeleton_front_view').offset();
             for (var i = 0; i < latestPainDataDetails.length; i++) {
             var pain_type = latestPainDataDetails[i]['pain_type'];
             var container = $("body");
             var img_clone = $("#drag_pain_icon" + pain_type).clone();
             var img_top = parseInt(skeleton_offset.top) + parseInt(latestPainDataDetails[i]['pos_y']);
             var img_left = parseInt(skeleton_offset.left) + parseInt(latestPainDataDetails[i]['pos_x']);
             img_clone.removeAttr('id').removeAttr('class').addClass('latest_pain_pins').css({
             'position': 'absolute',
             'left': img_left + 'px',
             'top': img_top + 'px'
             })
             .appendTo(container);
             }
             */

        }
    }
}

/**
 * Function to unblock a user
 */
var userId;
$(document).on('click', '.unblock_user', function() {
	userId = $(this).attr('data-user_id');
	if (userId > 0) {
		$('#confirm_unblock_dialog form input[type="hidden"]').val(userId);
		var username = $(this).attr('data-username');
		$('#confirm_unblock_dialog .username').html(username);
		$('#confirm_unblock_dialog').modal('show');
	}
});
$(document).on('click', '#confirm_user_unblock', function() {
	var hideUser = false;
	if ($('#confirm_unblock_dialog #unblock_messaging').is(':visible')) {
		if ($('#confirm_unblock_dialog #unblock_messaging').is(':checked')) {
			hideUser = true;
		}
	}
	else {
		hideUser = true;
	}
	if (hideUser === true) {
		$('#blocked_user_' + userId).remove();
	}
	var data = $('#confirm_unblock_dialog form').serialize();
	$('#confirm_unblock_dialog form input[type="hidden"]').val('');
	$('#confirm_unblock_dialog').modal('hide');
	$.ajax({
		type: 'POST',
		url: '/user/blocking/unblockUser',
		data: data
	});
});

/**
 * Function to handle recommend friends email notification change
 */
$(document).on('click', 'input[name="data[NotificationSetting][recommend_friends]"]', function() {
	var frequencySelecter = $('#NotificationSettingRecommendFriendsFrequency')
	if ($(this).is(':checked')) {
		frequencySelecter.removeClass('hide');
	}
	else {
		frequencySelecter.addClass('hide');
	}
});

/**
 * Select all options for printing
 */
$(document).on('click', '.select_all_graphs', function() {
	var optionsList = $('input[name="graph_options_list[]"]');
	optionsList.prop('checked', true);
	var titleList = $('input[name="graph_title_list[]"]');
	titleList.prop('checked', true);
});

/**
 * Clear all options for printing
 */
$(document).on('click', '.clear_all_graphs', function() {
	var optionsList = $('input[name="graph_options_list[]"]');
	optionsList.prop('checked', false);
	var titleList = $('input[name="graph_title_list[]"]');
	titleList.prop('checked', false);
});

/**
 * custom date selection for printing
 */
$(document).on('click', '#dateSelectType2', function() {
	$('.dateSelection').removeClass('hidden');
	$('.periodSelection').addClass('hidden');
	$('#print_error_message').hide();
});

/**
 * All date selection for printing
 */
$(document).on('click', '#dateSelectType1', function() {
	$('.periodSelection').removeClass('hidden');
	$('.dateSelection').addClass('hidden');
	$('#print_error_message').hide();
});

/**
 * Health graph printing
 */
$(document).on('click', '#print_button', function() {
	var selectedOptions = [];
	var startDate = '';
	var endDate = '';
	var dateRange = '';
	graphType = $(this).data('graphtype');
	if ($("#dateSelectType1").prop("checked")) {
		dateRange = $(".periodSelectionValue").val();
	} else if ($("#dateSelectType2").prop("checked")) {
		dateRange = 1;
		startDate = $("#PrintFrom").val();
		endDate = $("#PrintTo").val();
	}
	if(dateRange == '') {
		$('#print_error_message').text('Please choose a date option.').show();
	} else if(startDate > endDate) {
		$('#print_error_message').text('From-date should be less than To-date.').show();
		if(startDate.length == 0 || endDate.length == 0) {
			$('#print_error_message').text('Please enter Both dates.').show();
		}
	} else if(startDate < endDate && dateRange == 1 && startDate.length == 0  ) {
		$('#print_error_message').text('Please enter Both dates.').show();
	} else if(startDate == endDate && dateRange == 1 && startDate.length == 0) {
		$('#print_error_message').text('Please enter Both dates.').show();
	} else {
		if(graphType == 'normal') {
			$('input[name="graph_options_list[]"]:checked').each(function() {
				selectedOptions.push($(this).attr('value'));
			});
			if(selectedOptions == '') {
				$('#print_error_message').text('Please choose the options to print.').show();
			} else {
				window.open('/healthinfo/print?graphIds='+selectedOptions+'&customDates='+dateRange+'&mindate='+startDate+'&maxdate='+endDate , '_blank');
				$("#printGraph").modal('hide');
			}
		} else if(graphType == 'symptom') {
			selectedOptions = 16;
			symptomIds = [];
			$('input[name="graph_options_list[]"]:checked').each(function() {
				symptomIds.push($(this).attr('value'));
			});
			if(symptomIds == '') {
				$('#print_error_message').text('Please choose the options to print.').show();
			} else {
				window.open('/healthinfo/print?graphIds='+selectedOptions+'&customDates='+dateRange+'&mindate='+startDate+'&maxdate='+endDate+'&symptoms='+symptomIds , '_blank');
				$("#printGraph").modal('hide');
			}
		}
		$('input[type="radio"]').prop('checked', false);
		$('input[type="checkbox"]').prop('checked', false);
		$('input[type="text"]').val('');
		$('.periodSelection').addClass('hidden');
		$('.dateSelection').addClass('hidden');
	}
});

/**
 * Closing print pop up
 */
$(document).on('click', '.close_print', function() {
	$('input[type="checkbox"]').prop('checked', false);
	$('input[type="radio"]').prop('checked', false);
	$('input[type="text"]').val('');
	$('.periodSelection').addClass('hidden');
	$('.dateSelection').addClass('hidden');
});

