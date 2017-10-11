var friendsCheckBoxList;
$(document).ready(function() {
    if ($('#teamForm').length > 0) {
        // init photo uploader
        createUploader();
    }
    friendsCheckBoxList = $('input[name="friends_list[]"]');


    /**
     * Event type pop over
     */
    $('#team_privacy_help').popover({
        placement: 'right',
        trigger: 'hover focus',
        title: 'Team privacy',
        html: true,
        container: '#team_privacy_popover'
    });
});
/**
 * Function to load my team section
 * 
 * @param int page 
 */
function load_myteam(page) {

    if (typeof (page) === "undefined") {
        page = 1;
    }
    if (page !== 1) {
        var l = Ladda.create(document.querySelector('#load-more'));
        l.start();
    }
    $.ajax({
        url: '/myteam/loadTeamSupportingMe/page:' + page,
        dataType: 'json',
        success: function(result) {
            $('.loader').hide();
            if (page === 1) {
                $('#team_supporting_me #myTeamList').html(result.htm_content);

            } else {
                $('#team_supporting_me #myTeamList').append(result.htm_content);
            }
            if (result.paginator.count > 0) {
                $('#team_supporting_me').show();
            }
            if (result.paginator.nextPage == true) {
                $('#team_supporting_me').append('<div id="more_button_my_team' + (result.paginator.page + 1) + '" class="block">' +
                        '<a href="javascript:load_myteam(' + (result.paginator.page + 1) + ')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">more</span></a>' +
                        '</div>');
            }
        }
    }).always(function() {
        if (page !== 1) {
            l.stop();
            $("#team_supporting_me #more_button_my_team" + page).remove();
        }
    });
}

/**
 * Function to load user supported section 
 */
function load_user_supported_team(page) {

    if (typeof (page) === "undefined") {
        page = 1;
    }
    if (page !== 1) {
        var l = Ladda.create(document.querySelector('#load-more-user-supported'));
        l.start();
    }
    $.ajax({
        url: '/myteam/loadTeamUserSupport/page:' + page,
        dataType: 'json',
        success: function(result) {
            $('.loader').hide();
            if (page === 1) {
                $('#team_user_support #mySupportTeamList').html(result.htm_content);

            } else {
                $('#team_user_support #mySupportTeamList').append(result.htm_content);
            }
            if (result.paginator.count > 0) {
                $('#team_user_support').show();
            }
            if (result.paginator.nextPage == true) {
                $('#team_user_support').append('<div id="more_button_support_team' + (result.paginator.page + 1) + '" class="block">' +
                        '<a href="javascript:load_user_supported_team(' + (result.paginator.page + 1) + ')" id="load-more-user-supported" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">more</span></a>' +
                        '</div>');
            }
        }
    }).always(function() {
        if (page !== 1) {
            l.stop();
            $("#team_user_support #more_button_support_team" + page).remove();
        }
    });
}

/**
 * Function to load team invitation section 
 */
function load_team_invitation(page) {

    if (typeof (page) === "undefined") {
        page = 1;
    }
    if (page !== 1) {
        var l = Ladda.create(document.querySelector('#load-more-invitation'));
        l.start();
    }
    $.ajax({
        url: '/myteam/loadTeamInvitation/page:' + page,
        dataType: 'json',
        success: function(result) {
            $('.loader').hide();
            if (page === 1) {
                $('#team_invitation #team_invitation_list').html(result.htm_content);

            } else {
                $('#team_invitation #team_invitation_list').append(result.htm_content);
            }
            if (result.paginator.count > 0) {
                $('#team_invitation').show();
            }
            if (result.paginator.nextPage == true) {
                $('#team_invitation').append('<div id="more_button_team_invitation' + (result.paginator.page + 1) + '" class="block">' +
                        '<a href="javascript:load_team_invitation(' + (result.paginator.page + 1) + ')" id="load-more-invitation" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">more</span></a>' +
                        '</div>');
            }


        }
    }).always(function() {
        if (page !== 1) {
            l.stop();
            $("#team_invitation #more_button_team_invitation" + page).remove();
        }
    });
}

/**
 * Function to load my team section
 * 
 * @param int page 
 */
function load_recommended_teams(page) {

    if (typeof (page) === "undefined") {
        page = 1;
    }
    if (page !== 1) {
        var l = Ladda.create(document.querySelector('#load-more-recommendation'));
        l.start();
    }
    $.ajax({
        url: '/myteam/loadRecommendedTeam/page:' + page,
        dataType: 'json',
        success: function(result) {
            $('.loader').hide();
            if (page === 1) {
                $('#team_recommendation #myRecommendedTeamList').html(result.htm_content);
                if ($('.team_details').length) {
                    $('.page-header').removeClass('hide');
                }
            } else {
                $('#team_recommendation #myRecommendedTeamList').append(result.htm_content);
            }
            if (result.paginator.count > 0) {
                $('#team_recommendation').show();
            }
            if (result.paginator.nextPage == true) {
                $('#team_recommendation').append('<div id="more_button_team_recommendation' + (result.paginator.page + 1) + '" class="block">' +
                        '<a href="javascript:load_recommended_teams(' + (result.paginator.page + 1) + ')" id="load-more-recommendation" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">more</span></a>' +
                        '</div>');
            }
        }
    }).always(function() {
        if (page !== 1) {
            l.stop();
            $("#team_recommendation #more_button_team_recommendation" + page).remove();
        }
    });
}

/**
 * Cancel edit
 */
$(document).on('click', '#cancel_edit', function() {
    var href = $(this).attr('data-href');
    $(location).attr('href', href);
});

/**
 * Team Join request
 */
$(document).on('click', '.public_join_team', function() {
    var teamId = $(this).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(this);
        var joinTeamBtn = $(this);
        $.ajax({
            type: 'POST',
            url: '/myteam/api/joinTeam',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(joinTeamBtn);
                joinTeamBtn.html('Waiting for approval');
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    window.location = '/myteam/' + teamId;
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(joinTeamBtn);
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
});

/**
 * Approve team
 */
$(document).on('click', '.approve_team', function() {
    var teamId = $(this).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(this);
        var approveTeamBtn = $(this);
        var declineTeamBtn = $(this).next('.decline_team');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/approveTeam',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(approveTeamBtn);
                disableBtn(declineTeamBtn);
                declineTeamBtn.hide();
                approveTeamBtn.html('Redirecting to the team...');
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    window.location = '/myteam/' + teamId;
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(approveTeamBtn);
                            enableBtn(declineTeamBtn);
                            declineTeamBtn.show();
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
});

/**
 * Decline team
 */
$(document).on('click', '.decline_team', function() {
    var declineBtn = this;
    bootbox.dialog({
        message: "Do you really want to decline this team?",
        title: "Decline team request",
        closeButton: true,
        backdrop: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        declineTeam(declineBtn);
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});
function declineTeam(declineBtn) {
    var teamId = $(declineBtn).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(declineBtn);
        var declineTeamBtn = $(declineBtn);
        var approveTeamBtn = $(declineBtn).prev('.approve_team');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/declineTeam',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(approveTeamBtn);
                disableBtn(declineTeamBtn);
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    if ($('#team_invitation').length > 0) {
                        $('#team_invite_' + teamId).hide();
                    }
                    else {
                        $(location).attr('href', '/myteam');
                    }
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(approveTeamBtn);
                            enableBtn(declineTeamBtn);
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
}

/**
 * Approve team member join invitation
 */
$(document).on('click', '.accept_team_invite', function() {
    var teamId = $(this).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(this);
        var acceptTeamInviteBtn = $(this);
        var declineTeamInviteBtn = $(this).next('.decline_team_invite');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/acceptTeamInvitation',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(acceptTeamInviteBtn);
                disableBtn(declineTeamInviteBtn);
                declineTeamInviteBtn.hide();
                acceptTeamInviteBtn.html('Redirecting to the team...');
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    window.location = '/myteam/' + teamId;
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(acceptTeamInviteBtn);
                            enableBtn(declineTeamInviteBtn);
                            declineTeamInviteBtn.show();
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
});

/**
 * Decline team member join invitation
 */
$(document).on('click', '.decline_team_invite', function() {
    var declineBtn = this;
    bootbox.dialog({
        message: "Do you really want to decline this team invitation?",
        title: "Decline Invitation",
        closeButton: true,
        backdrop: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        declineTeamInvitation(declineBtn);
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});
function declineTeamInvitation(declineBtn) {
    var teamId = $(declineBtn).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(declineBtn);
        var declineTeamInviteBtn = $(declineBtn);
        var acceptTeamInviteBtn = $(declineBtn).prev('.accept_team_invite');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/declineTeamInvitation',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(acceptTeamInviteBtn);
                disableBtn(declineTeamInviteBtn);
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    $(location).attr('href', '/myteam');
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(acceptTeamInviteBtn);
                            enableBtn(declineTeamInviteBtn);
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
}

/**
 * Function to enable a button
 * @param {button element} button
 */
function enableBtn(button) {
    button.prop('disabled', false);
}

/**
 * Function to disable a button
 * @param {button element} button
 */
function disableBtn(button) {
    button.prop('disabled', true);
}

/**
 * Team photo uploader
 */
function createUploader() {

    var $uploadBtn = $('#bootstrapped-fine-uploader');
    var $messages = $('#uploadmessages');
    var $previewImg = $('#preview_img');
    var $ias;

    function createCropper(cropBox, responseJSON) {
        var minWidth = 240;
        var minHeight = 106;
        if (responseJSON.imageWidth < minWidth) {
            minHeight = ( minHeight / minWidth )  * responseJSON.imageWidth;
            minWidth = responseJSON.imageWidth;            
        }
        else if (responseJSON.imageHeight < minHeight) {
            minWidth = ( minHeight / minWidth )  * responseJSON.imageHeight;
            minHeight = responseJSON.imageHeight;
        }

        $ias = cropBox.find(".bootbox-body img").imgAreaSelect({
            parent: '.bootbox',
            autoHide: false,
            mustMatch: true,
            handles: true,
            instance: true,
            aspectRatio: '120 : 53',
            x1: 0, y1: 0, x2: minWidth,
            y2: minHeight,
            minHeight: minHeight,
            minWidth: minWidth,
            onInit: function() {
                cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
            },
            onSelectStart: function() {
                cropBox.find(".modal-footer .btn-success").removeAttr("disabled");
            },
            onCancelSelection: function() {
                cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");
            }
        });
    }

    function previewPhoto(response) {
        var cropSelection = $ias.getSelection(false);
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '/myteam/api/cropPhoto',
            data: {
                'crop_image': true,
                'x1': cropSelection.x1,
                'y1': cropSelection.y1,
                'w': cropSelection.width,
                'h': cropSelection.height,
                'cropfileName': response.fileName
            },
            beforeSend: function() {
                $previewImg.attr("src", app.site_url + 'theme/App/img/loading.gif');
            },
            success: function(data) {
                $('#TeamImage').val(data.fileName);
                $previewImg.attr("src", data.fileUrl);
            }
        });
    }

    function showErrorMessage(msg) {
        $messages.html('<div class="alert alert-error">' + msg + '</div>');
    }
    function showInfoMessage(msg) {
        $messages.html('<div class="alert alert-info">' + msg + '</div>');
    }

    var uploader = new qq.FineUploaderBasic({
        button: $uploadBtn[0],
        debug: false,
        multiple: false,
        request: {
            endpoint: '/myteam/api/uploadPhoto'
        },
        validation: {
            acceptFiles: "image/*",
            allowedExtensions: app.imageExtensions,
            minSizeLimit: "1024",
            sizeLimit: "5242880"
        },
        callbacks: {
            onError: function(id, name, reason, xhr) {
                showErrorMessage(reason);
                $messages.show();
                $uploadBtn.show();
                $("#team_step3 .team_for .btn").removeAttr("disabled");               
            },
            onSubmit: function(id, fileName) {
                $uploadBtn.hide();
                $messages.show();
                $("#team_step3 .team_for .btn").attr("disabled", "disabled");
            },
            onUpload: function(id, fileName) {
                var upload_msg = '<img src="' + app.site_url + '/img/loading.gif" alt="Initializing. Please hold."> ' +
                        'Initializing ' + '"' + fileName + '"';
                showInfoMessage(upload_msg);                
            },
            onProgress: function(id, fileName, loaded, total) {
                if (loaded < total) {
                    var progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                    var progressMsg = '<img src="' + app.site_url + '/img/loading.gif" alt="In progress. Please hold."> ' +
                            'Uploading ' + '"' + fileName + '" ' + progress;
                    showInfoMessage(progressMsg);
                }
            },
            onComplete: function(id, fileName, responseJSON) {
              
                
                $uploadBtn.show();
                if (responseJSON.success) {
                    $messages.hide();
                    var cropBox = bootbox.dialog({
                        closeButton: false,
                        message: "test",
                        title: "Crop Team Photo",
                        show: true,
                        animate: false,
                        buttons: {
                            success: {
                                label: "Accept",
                                className: "accept btn-success",
                                callback: function() {
                                    previewPhoto(responseJSON);
                                }
                            },
                            cancel: {
                                label: "Cancel",
                                className: "btn-default"
                            }
                        }
                    });

                    cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");
                    var imgHTML = '<div class="upload_area"><img src="' + responseJSON.fileurl + '"' +
                            ' alt="Uploaded photo" /></div>';
                    imgHTML += '<div>To make adjustments, please drag around the white rectangle below.' +
                            ' When you are happy with the photos, click "Accept" button. Your beautiful, clean,' +
                            ' non-pixelated image should be at minimum 120x120 pixels.</div>';
                    cropBox.find(".bootbox-body").html(imgHTML);

                    setTimeout(function() {
                        createCropper(cropBox, responseJSON);
                    }, 500);
                } else {
                    $messages.show();
                    var errorMsg = 'Error with ' + '"' + fileName + '": ' + responseJSON.error;
                    showErrorMessage(errorMsg);
                }

                $("#team_step3 .team_for .btn").removeAttr("disabled");
            }
        }
    });
}

/**
 * Listing friends for inviting to the team
 */
function listFriends(type, teamId) {
    var friendList = friendsCheckBoxList.serialize();
    var url = "";
    data = friendList + '&teamId=' + teamId;
    if (type == 1 || type == 2) {
        url = "/myteam/api/inviteFriend";
    } else if (type == 3) {
        setSelectedPatient($("input.patient-select:checked").data());
        $('#choose-patient-friend').modal('hide');
        friendsCheckBoxList.prop('checked', false);
        $("input.patient-select").removeAttr("disabled");
        $('#import_contact_step_1 .contact_persons').removeClass('active');
    }
    if (friendList.length == 0) {
        $('#team_error_message').text('Please select friend(s).').show();
    } else {
        if (url != "") {
            $('#team_error_message').hide();
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(result) {
                    if (result.success === true) {
                        if (type == 1) {
                            location.reload();
                        } else if (type == 2) {
                            $(location).attr('href', '/myteam/' + teamId);
                        }
                    } else {
                        $(location).attr('href', '/myteam');
                    }
                }
            });
        }
    }
}

/**
 * Selecting a patient friend for creating a team
 */
$(document).on('click', '.patient-select', function() {
	var allColumns = $('#import_contact_step_1 .contact_persons');
    if (this.checked) {
		$("input.patient-select").not(this).attr("checked", false);
		allColumns.removeClass('active');
		var selectedCol = $(this).closest('.contact_persons');
		selectedCol.addClass('active');
    }
});

$(document).on('click', '#search_friends', function() {
	$("input.patient-select").removeAttr("disabled");
	var selectedCols = $('#import_contact_step_1 .contact_persons');
    friendsCheckBoxList.prop('checked', false);
    selectedCols.removeClass('active');
});
/**
 * Select/Deselect all friends
 */
$(document).on('change', '#select_all_friends', function() {
    var selectedCols = $('#import_contact_step_1 .contact_persons');
	friendsList = $('input[name="friends_list[]"]').not(':hidden');
    if ($(this).is(':checked')) {
        friendsList.prop('checked', true);
        selectedCols.addClass('active');
    }
    else {
        friendsList.prop('checked', false);
        selectedCols.removeClass('active');
    }
    handleSelectedCountChange();
});

/**
 * Function to show total selected friend's count
 */
function handleSelectedCountChange() {
    var count = $('input[name="friends_list[]"]:checked').not(':hidden').length;
    $('#selected_friends_count').html(count);
}

/**
 * Update selected friend's count, on changing the checkbox status
 * and add/remove active class to the checkbox container
 */
$(document).on('change', 'input[name="friends_list[]"]', function() {
    handleSelectedCountChange();
    var selectedCol = $(this).closest('.contact_persons');
    if ($(this).is(':checked')) {
        selectedCol.addClass('active');
    } else {
        selectedCol.removeClass('active');
    }
});
/**
 * Leave from team
 */
$(document).on('click', '#leave_team_btn', function() {
    var leaveBtn = this;
    var teamId = $(leaveBtn).data('team-id');
    var loading = Ladda.create(leaveBtn);
    $.ajax({
        type: 'POST',
        url: '/myteam/api/getUserTeamStatus',
        data: {'team_id': teamId},
        dataType: 'json',
        beforeSend: function() {
            loading.start();
        },
        success: function(result) {
            if (result.success === true) {
                $(leaveBtn).data('team-id', teamId);
                $(leaveBtn).data('role', result.role);
                $(leaveBtn).data('total-members', result.total_members);
                $(leaveBtn).data('organizer-count', result.organizer_count);

                leaveTeamPopup(leaveBtn, loading);				

            } else if (result.error === true) {
                bootbox.alert(result.message, function() {
                    if (result.errorType && (result.errorType === 'fatal')) {
                        $(location).attr('href', '/myteam');
                    }
                    else {
                        enableBtn(leaveBtn);
                        loading.stop();
                    }
                });
            }
        }
    });

});

function leaveTeamPopup(leaveBtn, loading) {

    var role = $(leaveBtn).data('role');
    var total_members = $(leaveBtn).data('total-members');
    var organizer_count = $(leaveBtn).data('organizer-count');

    if (role == 0) { //member type 
        bootbox.dialog({
            message: "Are you sure you want to leave this team?",
            title: "Leave Team",
            closeButton: true,
            backdrop: true,
            onEscape: function() {
            },
            buttons: {
                main: {
                    label: "Yes",
                    className: "btn btn_active",
                    callback: function(confirmed) {
                        if (confirmed) {
                            leaveTeam(leaveBtn);
                        }
                    }
                },
                danger: {
                    label: "No",
                    className: "btn btn_clear",
					callback: function(declined) {
						loading.stop();
					}
                }
            }
        });
    }
    else if (role == 1 || role == 3) { //patient or patient+organizer type
        bootbox.dialog({
            message: "The team will be discontinued and the members will be notified. Are you sure?",
            title: "Leave Team",
            closeButton: true,
            backdrop: true,
            onEscape: function() {
            },
            buttons: {
                main: {
                    label: "Yes",
                    className: "btn btn_active",
                    callback: function(confirmed) {
                        if (confirmed) {
                            leaveTeam(leaveBtn);
                        }
                    }
                },
                danger: {
                    label: "No",
                    className: "btn btn_clear",
					callback: function(declined) {
						loading.stop();
					}
                }
            }
        });
    }
    else { //case organizer
        if (organizer_count > 1) { //more than one organizer
            bootbox.dialog({
                message: "Are you sure you want to leave this team?",
                title: "Leave Team",
                closeButton: true,
                backdrop: true,
                onEscape: function() {
                },
                buttons: {
                    main: {
                        label: "Yes",
                        className: "btn btn_active",
                        callback: function(confirmed) {
                            if (confirmed) {
                                leaveTeam(leaveBtn);
                            }
                        }
                    },
                    danger: {
                        label: "No",
                        className: "btn btn_clear",
						callback: function(declined) {
						loading.stop();
						}
                    }
                }
            });
        }
        else {
            $("#transfer-organizer").modal("show");
			loading.stop();
        }
    }

}

function leaveTeam(leaveBtn) {
    var teamId = $(leaveBtn).data('team-id');

    if (teamId > 0) {
        $.ajax({
            type: 'POST',
            url: '/myteam/api/leaveTeam',
            data: {'team_id': teamId},
            dataType: 'json',
            success: function(result) {
                if (result.success === true) {
                    $(location).attr('href', '/myteam');
                }
            }
        });
    }
}

$(document).on('click', '#assign_organizer_button', function() {
    var teamMemberId = $('#assign_organizer_dropdown').val();
    var teamId = $('#transfer-organizer').data('team-id');
    $('#assign_message').html('');
    if (teamMemberId > 0) {
        $.ajax({
            type: 'POST',
            url: '/myteam/api/assignOrganizer',
            data: {'team_member_id': teamMemberId},
            dataType: 'json',
            success: function(result) {
                if (result.success === true) {
                    if (result.type == 'member') {
                        $('#assign_message').html(result.username + ' is promoted as the Team Lead. Waiting for approval.');
                        $('#assign_message').show();
                        window.location = '/myteam/' + teamId + '/members';
                    } else {
                        $('#assign_message').html(result.username + ' is promoted as the Team Lead and is notified.');
                        $('#assign_message').show();
                        window.location = '/myteam';
                    }
                }
            }
        });
    }
});

$('#manage-user-menu li').on('click', function() {
    var type = $(this).attr('class');
    var teamMemberId = $(this).parent().data('member-id');
    var membername = $(this).parent().data('username');
    if (type === 'promote-option') {
        promoteAsOrganizer(teamMemberId, membername);
        $(this).hide();
        $(this).parent().prepend('<li class="approval-wait-option"><a href="#">Waiting for Approval</a></li>');
    }
    else if (type === 'remove-option') {
        $("#remove-user-reason").modal("show");
        $("#remove_user_button").data("member-id", teamMemberId);
    }
    else if (type === 'demote-option') {
        bootbox.dialog({
            message: "Are you sure you want to remove Team Lead privilege?",
            title: "Demote from Team Lead",
            closeButton: true,
            backdrop: true,
            onEscape: function() {
            },
            buttons: {
                main: {
                    label: "Yes",
                    className: "btn btn_active",
                    callback: function(confirmed) {
                        if (confirmed) {
                            demoteOrganizer(teamMemberId);
                        }
                    }
                },
                danger: {
                    label: "No",
                    className: "btn btn_clear"
                }
            }
        });
    }

});

function promoteAsOrganizer(teamMemberId, membername) {
    $.ajax({
        type: 'POST',
        url: '/myteam/api/upgradeToOrganizer',
        data: {'team_member_id': teamMemberId},
        dataType: 'json',
        success: function(result) {
            if (result.success === true) {
                bootbox.dialog({
                    message: "Your request to promote " + membername + " to Team Lead has been sent for approval",
                    title: "Promotion to Team Lead",
                    closeButton: true,
                    backdrop: true,
                    onEscape: function() {
                    },
                    buttons: {
                        main: {
                            label: "Ok",
                            className: "btn btn_active",
                            callback: function(confirmed) {
                                if (confirmed) {
//                                    location.reload();
                                }
                            }
                        }
                    }
                });
            }
        }
    });
}

function demoteOrganizer(teamMemberId) {
    $.ajax({
        type: 'POST',
        url: '/myteam/api/demoteOrganizer',
        data: {'team_member_id': teamMemberId},
        dataType: 'json',
        success: function(result) {
            if (result.success === true) {
                bootbox.dialog({
                    message: "Team Lead privilage has been removed successfully",
                    title: "Demote to Member",
                    closeButton: true,
                    backdrop: true,
                    onEscape: function() {
                    },
                    buttons: {
                        main: {
                            label: "Ok",
                            className: "btn btn_active",
                            callback: function(confirmed) {
                                if (confirmed) {
                                    location.reload();
                                }
                            }
                        }
                    }
                });
            }
        }
    });
}

$(document).on('click', '#remove_user_button', function() {
    $("#remove-user-reason").modal("hide");
    var teamMemberId = $(this).data('member-id');
    var reason = $('#remove-reason').val();

    bootbox.dialog({
        message: "Are you sure to remove this member from team?",
        title: "Remove User",
        closeButton: true,
        backdrop: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '/myteam/api/removeTeamUser',
                            data: {
                                'team_member_id': teamMemberId,
                                'reason': reason
                            },
                            dataType: 'json',
                            success: function(result) {
                                if (result.success === true) {
                                    $('#team_member_' + teamMemberId).remove();
                                    location.reload();
                                }
                            }
                        });
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

/**
 * Approve new role
 */
$(document).on('click', '.approve_role', function() {
    var teamId = $(this).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(this);
        var approveRoleBtn = $(this);
        var declineRoleBtn = $(this).next('.decline_role');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/approveRole',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(approveRoleBtn);
                disableBtn(declineRoleBtn);
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    var content = $("<div/>").html(result.members_container).text();
                    $('.role_approval').hide();
                    $('#members_container').html(content);
                    window.location.reload();
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                            location.reload();
                        }
                        else {
                            enableBtn(approveRoleBtn);
                            enableBtn(declineRoleBtn);
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
});

/**
 * Decline new role
 */
$(document).on('click', '.decline_role', function() {
    var declineBtn = this;

    bootbox.dialog({
        message: "Do you really want to decline role request?",
        title: "Decline Team Lead request",
        closeButton: true,
        backdrop: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        declineRole(declineBtn);
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

function declineRole(declineBtn) {
    var teamId = $(declineBtn).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(declineBtn);
        var declineRoleBtn = $(declineBtn);
        var approveRoleBtn = $(declineBtn).prev('.approve_role');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/declineRole',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(approveRoleBtn);
                disableBtn(declineRoleBtn);
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    $('.role_approval').hide();
                    location.reload();
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(approveRoleBtn);
                            enableBtn(declineRoleBtn);
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
}
/**
 * Approve team join request
 */
$(document).on('click', '.approve-join-request', function() {
    var teamMemberId = $(this).data('member-id');

    $.ajax({
        type: 'POST',
        url: '/myteam/api/approveTeamJoinRequest',
        data: {
            'team_member_id': teamMemberId
        },
        dataType: 'json',
        success: function(result) {
            if (result.success === true) {
                location.reload();
            }
        }
    });

});

$(document).on('click', '.decline-join-request', function() {
    var teamMemberId = $(this).data('member-id');
    bootbox.dialog({
        message: "Are you sure to decline this request?",
        title: "Decline Request",
        closeButton: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '/myteam/api/cancelTeamJoinRequest',
                            data: {
                                'team_member_id': teamMemberId
                            },
                            dataType: 'json',
                            success: function(result) {
                                if (result.success === true) {
                                    location.reload();
                                }
                            }
                        });
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

/**
 * Cancel team join request
 */
$(document).on('click', '#cancel-join-request', function() {
    var teamMemberId = $(this).data('member-id');
    bootbox.dialog({
        message: "Are you sure to cancel this request?",
        title: "Cancel Request",
        closeButton: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '/myteam/api/cancelTeamJoinRequest',
                            data: {
                                'team_member_id': teamMemberId
                            },
                            dataType: 'json',
                            success: function(result) {
                                if (result.success === true) {
                                    location.reload();
                                }
                            }
                        });
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

/**
 * Cancel button click in all invite friends pop up.
 */
$(document).on('click', '.invite_cancel', function() {
    $(".search_widget_txt").val('');
    $(".none_found").addClass('hidden');
    $('#search_friends').keyup();
    $('input[type="checkbox"]').prop('checked', false);
    $('input[type="checkbox"]').removeAttr("disabled");
    $('.contact_persons').removeClass('active');
    $('#selected_friends_count').html(0);
    $("#team_error_message").hide();
});

$('#remove-user-reason').on('hidden.bs.modal', function(e) {
    $('#remove-reason').val('');
});

/*
 * Show/hide team email and site notification settings based on yes/no
 */
$(document).on('click', 'input[name="data[TeamSetting][enable_notification]"]', function() {
    if ($(this).is(':checked')) {
        $('#team_site_email_notifications').removeClass('hide');
    }
    else {
        $('#team_site_email_notifications').addClass('hide');
    }
});
/**
 * Request permission to see the patient's medical details.
 */
$(document).on('click', '.req_medical_data_permision', function() {
    var teamId = $(this).data('team-id');
    if (teamId > 0) {
        $.ajax({
            type: 'POST',
            url: '/myteam/api/requestMedicalDataAccess',
            data: {'team_id': teamId},
            dataType: 'json',
            beforeSend: function() {
                $('.req_medical_data_permision').html('Requesting...');
            },
            success: function(result) {
                if (result.success === true) {

                    $('.req_medical_data_permision').html('Waiting for approval');
                    $('.req_medical_data_permision').addClass('no-text-decoration').addClass('cursor-default').removeClass('req_medical_data_permision');
//                     $('.med-data-rqst-msg').html("Requested permission to view patient medical data");
//                    $('#med-data-rqst-msg-wraper').remove();
//                    showInnerAlert('Your request to view patient medical data has been sent','success');
                }
                else {
                    bootbox.alert(result.message, function() {
                        location.reload();
                    });
                }
            }
        });
    }
});
/**
 * Approve permission to view patient's medical details by patient of the team.
 */
//$(document).on('click', '.approve_view_med_data_request', function() {
function managePermissionRequest(user_id, team_id, action, el) {
    var _self = $(el);
    var _self_wraper = _self.parent();
    var _self_wraper_html = _self.parent().html();
    var action_text = '';
    var ladda_finish_button = Ladda.create(el);
    if (user_id > 0 && team_id > 0) {
        $.ajax({
            type: 'POST',
            url: '/myteam/api/managePermissionRequest',
            data: {
                'user_id': user_id,
                'team_id': team_id,
                'action': action
            },
            dataType: 'json',
            beforeSend: function() {
                $("#change_all_requests_btn").attr('disabled', 'disabled');
                ladda_finish_button.start();
                if (action == 0) {
//                    _self_wraper.html('Rejected successfully');
                    action_text = 'No permission';//Rejected
                } else if (action == 1) {
//                    _self_wraper.html('Approved successfully');
                    action_text = 'Approved';
                }

            },
            success: function(result) {
                ladda_finish_button.stop();
                  _self.hide();
                  _self_wraper.parent().find('.approve_reject_msg').html(action_text).show();
                if (result.success) {

//                    _self_wraper.parents('.requested_users_row').fadeOut(500);
//                    _self.attr('disabled', true);
//                    _self.attr('value',action_text);
//                    $("#change_all_requests_btn").removeAttr('disabled');
                    _self_wraper.find('.approve_reject_btn').not(_self).show();
                }
                else if (result.error) {
                    bootbox.alert('Some error occured. Please try agin.', function() {
                        location.reload();
                    });
                }
            }
        });
    }
}

/*
 //Multple request management functionality has been removed.
 // Code to select unselect all on clicking user_select_all button
 
 $("#user_select_all").change(function() {
 if ($(this).prop('checked')) {
 $(".selecet_requested_users").prop('checked', true);
 
 $("#change_all_requests_btn").removeAttr('disabled');
 } else {
 $(".selecet_requested_users").prop('checked', false);
 $("#change_all_requests_btn").attr('disabled', 'disabled');
 }
 });
 */

/*
 //Multple request management functionality has been removed.
 //to unselect the select all button if user uncheck any check box form the list.
 
 $(".selecet_requested_users").change(function() {
 if ($(this).prop('checked')) {
 $("#change_all_requests_btn").removeAttr('disabled');
 } else {
 $("#user_select_all").prop('checked', false);
 //        $("#change_all_requests_btn").attr('disabled', 'disabled');
 }
 });
 */

/*
 //Multple request management functionality has been removed.
 //to handle the case of secting already rejected people by select all to reject again.
 
 $("#change_all_requests").on('change', function() {
 var value = $(this).val();
 var approve_btn_visibility = 0;
 var btn_class = ".approve_view_med_data_request";
 //    var visible_btn_count = 0;
 console.log('visible_btn_countsadasd     :', $("#user_select_all").prop('checked'));
 //    console.log('visible_btn_countsadasd     :', visible_btn_count);
 if ($("#user_select_all").prop('checked')) {
 if (value == 0) {
 btn_class = ".reject_view_med_data_request";
 }
 $(".requested_users_row").each(function() {
 //             visible_btn_count = $(this).find('.approve_reject_btn').is(":visible").length;
 approve_btn_visibility = $(this).find(btn_class).is(":visible");
 if (!approve_btn_visibility) {
 $(this).find('.selecet_requested_users').prop('checked', false);
 } else {
 $(this).find('.selecet_requested_users').prop('checked', true);
 }
 });
 }
 });
 */

/*
 //Multple request management functionality has been removed.
 //ajax call on clicking the Done button to apporve or reject multiple users.
 
 $(document).on('click', '#change_all_requests_btn', function() {
 var userArray = new Array();
 var action = $("#change_all_requests").val();
 var teamId = $("#user_select_all").data('team_id');
 var actionText = 'Approved';
 var other_btn_class = 'reject_view_med_data_request';//approve_view_med_data_request
 var ladda_button = Ladda.create(document.querySelector('#change_all_requests_btn'));
 $('.selecet_requested_users:checked').each(function() {
 userArray.push($(this).val());
 });
 if (userArray.length > 0) {
 if (action == 0) {
 actionText = 'No permission';//Rejected
 other_btn_class = 'approve_view_med_data_request';
 }
 $.ajax({
 type: 'POST',
 url: '/myteam/api/manageMultiplePermissionRequest',
 data: {
 'team_id': teamId,
 'userArray': userArray,
 'action': action
 },
 dataType: 'json',
 beforeSend: function() {
 $('#change_all_requests_btn').attr('disabled', 'disabled');
 $('.requested_users_wraper :checkbox').attr('disabled', 'disabled');
 $('.approve_reject_btn').attr('disabled', 'disabled');
 ladda_button.start();
 },
 success: function(result) {
 ladda_button.stop();
 if (result.success === true) {
 $('.approve_reject_btn').removeAttr('disabled');
 showInnerAlert(actionText + ' all the selected users requests.');
 for (var i = 0; i < userArray.length; i++) {
 
 //                        $("#user_row_" + userArray[i]).fadeOut(300).remove();
 $("#user_row_" + userArray[i]).find('.approve_reject_btn').hide(1);
 $("#user_row_" + userArray[i]).find('.' + other_btn_class).show(1);
 $("#user_row_" + userArray[i]).find('.approve_reject_msg').html(actionText).show();
 //                        $("#user_row_" + userArray[i]).find('.approve_reject_btn').hide(1, function() {
 //                            console.log('hide approve_reject_btn');
 //                            console.log('other_btn_class', other_btn_class);
 //                            $("#user_row_" + userArray[i]).find('.' + other_btn_class).show(1, function() {
 //                                console.log('show class',other_btn_class);
 //                                $("#user_row_" + userArray[i]).find('.approve_reject_msg').html(actionText).show();
 //                                console.log('approve_reject_msg show');
 //                            });
 //                        });
 }
 $('.selecet_requested_users, #user_select_all').removeAttr('disabled');
 //                    if ($('.selecet_requested_users').length > 0) {
 //                        $('.requested_users_wraper :checkbox').removeAttr('disabled');
 //                        $('#change_all_requests_btn').removeAttr('disabled');
 //                    } else {
 //                        $('.requested_users_wraper').hide().remove();
 //                    }
 
 }
 else if (result.error === true) {
 alert("Error");
 }
 }
 });
 }
 });
 
 */
//});

/**
 * Approve team privacy changed to public
 */
$(document).on('click', '.approve_public_privacy', function() {
    var teamId = $(this).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(this);
        var acceptTeamInviteBtn = $(this);
        var declineTeamInviteBtn = $(this).next('.decline_public_privacy');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/changeTeamPrivacy',
            data: {'team_id': teamId, 'action': 'approve'},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(acceptTeamInviteBtn);
                disableBtn(declineTeamInviteBtn);
                declineTeamInviteBtn.hide();
                acceptTeamInviteBtn.html('Redirecting to the team...');
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    $('.approval_container').remove();
                    showSuccessMessage(result.message);
                    if ($('#teamForm #TeamPrivacy').length) {
                        var el = $('#teamForm #TeamPrivacy');
                        el.prop('disabled', false);
                        el.val(1);
                        el.next('i').remove();
                    }

                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(acceptTeamInviteBtn);
                            enableBtn(declineTeamInviteBtn);
                            declineTeamInviteBtn.show();
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
});

/**
 * Function to decline team privacy change to public
 */
$(document).on('click', '.decline_public_privacy', function() {
    var declineBtn = this;
    var teamId = $(declineBtn).data('team_id');
    if (teamId > 0) {
        var loading = Ladda.create(declineBtn);
        var declineTeamBtn = $(declineBtn);
        var approveTeamBtn = $(declineBtn).prev('.approve_public_privacy');
        $.ajax({
            type: 'POST',
            url: '/myteam/api/changeTeamPrivacy',
            data: {'team_id': teamId, 'action': 'decline'},
            dataType: 'json',
            beforeSend: function() {
                disableBtn(approveTeamBtn);
                disableBtn(declineTeamBtn);
                approveTeamBtn.hide();
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
                    $('.approval_container').remove();
                    showSuccessMessage(result.message);
                    if ($('#teamForm #TeamPrivacy').length) {
                        var el = $('#teamForm #TeamPrivacy');
                        el.prop('disabled', false);
                        el.val(2);
                        el.next('i').remove();
                    }
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(acceptTeamInviteBtn);
                            enableBtn(declineTeamInviteBtn);
                            declineTeamInviteBtn.show();
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
});

function showSuccessMessage(message) {
    var HTML = '<div class="alert alert-success">' +
            '<button data-dismiss="alert" class="close" type="button" aria-hidden="true">×</button>' +
            '<div class="message">' + message + '</div></div>';
    $('.main_container .container #header-alert').after(HTML);
}

/**
 * Cancelling request to make a team for patient
 */
$(document).on('click', '.cancel_team_request', function() {
    var cancelBtn = this;
    bootbox.dialog({
        message: "Do you really want to cancel this request?",
        title: "Cancel team request",
        closeButton: true,
        backdrop: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        cancelTeamRequest(cancelBtn);
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

function cancelTeamRequest(cancelBtn) {
    var teamId = $(cancelBtn).data('team_id');
	var patientId = $(cancelBtn).siblings('.patient_id').val();
    if (teamId > 0) {
        var loading = Ladda.create(cancelBtn);
        var cancelTeamBtn = $(cancelBtn);
        $.ajax({
            type: 'POST',
            url: '/myteam/api/cancelTeamRequest',
            data: {'team_id': teamId , 'team_userid' : patientId },
            dataType: 'json',
            beforeSend: function() {
                disableBtn(cancelTeamBtn);
                loading.start();
            },
            success: function(result) {
                if (result.success === true) {
					$(location).attr('href', '/myteam');
                }
                else if (result.error === true) {
                    bootbox.alert(result.message, function() {
                        if (result.errorType && (result.errorType === 'fatal')) {
                            $(location).attr('href', '/myteam');
                        }
                        else {
                            enableBtn(cancelTeamBtn);
                            loading.stop();
                        }
                    });
                }
            }
        });
    }
}
