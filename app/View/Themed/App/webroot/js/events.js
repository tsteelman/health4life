/**
 * Event types
 */
var EVENT_TYPE_PUBLIC = 1;
var EVENT_TYPE_PRIVATE = 2;
var EVENT_TYPE_SITE = 3;

/**
 * Ordinary event, virtual event 
 */
var ORDINARY_EVENT = 0;
var VIRTUAL_EVENT = 1;

/**
 * Repeat modes
 */
var REPEAT_MODE_DAILY = 1;
var REPEAT_MODE_WEEKLY = 2;
var REPEAT_MODE_MONTHLY = 3;
var REPEAT_MODE_YEARLY = 4;
//var REPEAT_MODE_WEEKDAY = 5;
//var REPEAT_MODE_MON_WED_FRI = 6;
//var REPEAT_MODE_TUE_THU = 7;


/**
 * Repeat end types
 */
var REPEAT_END_NEVER = 1;
var REPEAT_END_AFTER = 2;
var REPEAT_END_DATE = 3;

/**
 * Default date format
 */
var defaultDateFormat = 'mm/dd/yy';
var defaultTimeFormat = 'g:i A';/*'hh:mm tt';*/
var dateObj = getUserNow();
var defaultCalendarDate = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate(), 0, 0, 0, 0);//*'10/12/2012';*/

$(document).ready(function() {
    /**
     * Event type pop over
     */
    $('#event_type_help').popover({
        placement: 'right',
        trigger: 'hover focus',
        title: 'Event type',
        html: true,
        container: '#event_type_popover'
//        container: 'body'
    });

    /**
     * Set date picker's default date format
     */
    $.datepicker.setDefaults({
        dateFormat: defaultDateFormat
    });

    /**
     * Set time picker's default time format
     */
//    $.timepicker.setDefaults({
//        timeFormat: defaultTimeFormat,
//        hourGrid: 12,
//        minuteGrid: 15
//    });

    /**
     * Tags input
     */
    $('input#EventTags').tagsinput({
        confirmKeys: [13, 32]
    });

    /*
     * Wizard process
     */
    var eventWizard = $('#eventWizard').wizard();

    eventWizard.on('change', function(e, data) {
        if (typeof (data) != "undefined" && data.direction == "next") {
			if (data.step === 2) {
				handleCountryZipValidation($('#EventCountry'));
			}
            var formValid = $(form).valid();
            if (!formValid) {
                return false;
            }
        }
    });

    eventWizard.on('finished', function(e, data) {
        if ($(form).valid()) {
            $(form).submit();
            eventWizard.find('button').attr('disabled', 'disabled');
        }
    });

    /**
     * Scroll to top on moving to next page in the wizard for better user experience
     */
    eventWizard.on('changed', function() {
        window.scrollTo(0, 0);
    });

    /**
     * Start and End date and time picker
     */

    $('#EventStartDate, #EventStartDateTime, #EventUptoDate').datepicker({
        defaultDate: defaultCalendarDate
//        beforeShow: function() {
//        },
//        beforeShowDay: function(date) {
//            if (defaultCalendarDate.toString() == date.toString()) {
//                return [true, "currentDateTimezone", 'defaultCalendarDate'];
//            } else {
//                return [true, '', ''];
//            }
//        }
    });
    $('#EventEndDate').datepicker({
        minDate:  new Date($('#EventStartDateTime').val())
    });
    var oneMinute = 60 * 1000;
    var oneDay = 24 * 60 * 60 * 1000;

    // event start time    
//    var eventStartTimeInitHour = 0;
//    var eventStartTimeInitMinute = 0;
//    var eventStartTime = $.trim($("#EventStartTime").val());
//    if (eventStartTime !== '') {
//        var eventStartTimeData = getHrMinFromTimeStr(eventStartTime);
//        eventStartTimeInitHour = eventStartTimeData['hour'];
//        eventStartTimeInitMinute = eventStartTimeData['minute'];
//    }
//    $("#EventStartTime").timepicker({
//        hour: eventStartTimeInitHour,
//        minute: eventStartTimeInitMinute,
//        beforeShow: function() {
//            var endDateTime = $("#EventEndTime").datetimepicker('getDate');
//            var maxDateTime = endDateTime;
//            if (endDateTime !== null) {
//                maxDateTime = new Date(endDateTime.getTime() - oneMinute);
//            }
//            $(this).datetimepicker("option", {maxDateTime: maxDateTime});
//        }
//    });

    // event end time    
//    var eventEndTimeInitHour = 0;
//    var eventEndTimeInitMinute = 0;
//    var eventEndTime = $.trim($("#EventEndTime").val());
//    if (eventEndTime !== '') {
//        var eventEndTimeData = getHrMinFromTimeStr(eventEndTime);
//        eventEndTimeInitHour = eventEndTimeData['hour'];
//        eventEndTimeInitMinute = eventEndTimeData['minute'];
//    }
//    $("#EventEndTime").timepicker({
//        hour: eventEndTimeInitHour,
//        minute: eventEndTimeInitMinute,
//        beforeShow: function() {
//            var startDateTime = $("#EventStartTime").datetimepicker('getDate');
//            var minDateTime = startDateTime;
//            if (startDateTime !== null) {
//                minDateTime = new Date(startDateTime.getTime() + oneMinute);
//            }
//            $(this).datetimepicker("option", {minDateTime: minDateTime});
//        }
//    });
//
//    $('#EventStartDateTime').datepicker({
//        minDate: 0,
//        onClose: function() {
//            var selectedDate = $(this).datepicker('getDate');
//            var minDate = new Date(selectedDate.getTime() + oneDay);
//            $("#EventEndDate").datepicker("option", "minDate", minDate);
//        }
//    });
//    $('#EventEndDate').datepicker({
//        minDate: "+1d",
//        onClose: function() {
//            var selectedDate = $(this).datepicker('getDate');
//            var maxDate = new Date(selectedDate.getTime() - oneDay);
//            $("#EventStartDateTime").datepicker("option", "maxDate", maxDate);
//        }
//    });

    /*
     * Initialize the event photo uploader
     */
    initEventPhotoUploader();    
    
    $.validator.addMethod("onedayEndTimeValid", function(value, element) {
        var endTime = $('#EventEndTime').val();
        var startTime = $('#EventStartTime').val();
        return compareTime(endTime,startTime);
       
    }, "Please enter time greater than start time");
    
    $.validator.addMethod("repeatEndTimeValid", function(value, element) {
        var endTime = $('#repeat_event_mode_time .time.end').val();
        var startTime = $('#repeat_event_mode_time .time.start').val();
        var date2 = $("#EventUptoDate").datepicker('getDate');
        var date1 = $("#EventStartDateTime").datepicker('getDate');
        var timeStatus = compareTime(endTime,startTime);
        if(date1 > date2) {
            return false;
        } else if(date1 < date2){
            return true;
        } else {
            return timeStatus;
        }
    }, "Please enter time greater than start time");
    $.validator.addMethod("repeatStartTimeValid", function(value, element) {
//        var endTime = $('#repeat_event_mode_time .time.end').val();
//        var startTime = $('#repeat_event_mode_time .time.start').val();
//        return compareTime(endTime,startTime);
        return $("#EventUptoTimeonly").valid();
    }, "Please enter time less than end time");
    
    if($('#is_full_day').length > 0 && !$('#is_full_day').prop('checked')) {
        $("#EventUptoTimeonly").rules("add", {
            required: true,
            regex: /(([0-9]|[1][012])\:[03]0\s(a|p)m)$/i,
            repeatEndTimeValid: true,
            messages: {
                regex: "Please enter a valid end time",
                required:"Please enter an end time for the event"
            }
        });
    }
    if ($(".repeat_radio").first().is( ":checked" )) {
        $("#EventEndTime").rules("add", {
            onedayEndTimeValid: true,
            messages: {
                required:"Please enter an end time for the event" 
            }
        });
    }
 
});

/**
 * Function to get hour and minute as an array by parsing a time string
 */
function getHrMinFromTimeStr(timeStr) {
    console.log('getHrMinFromTimeStr');
    // parse
    var timeArr = timeStr.split(" ");
    var hrMinStr = timeArr[0];
    var amPM = timeArr[1];
    var hrMinArr = hrMinStr.split(":");
    var hour = parseInt(hrMinArr[0]);
    var minute = parseInt(hrMinArr[1]);

    // convert to 24 hour
    var hrIncrement;
    if (hour === 12) {
        hrIncrement = 0;
        hour = (amPM === 'am') ? 0 : 12;
    } else {
        hrIncrement = (amPM === 'am') ? 0 : 12;
    }
    hour = hour + hrIncrement;

    // data
    var data = new Array();
    data['hour'] = hour;
    data['minute'] = minute;

    return data;
}

/**
 * Disable disease field on selecting private event type
 * Make disease field mandatory on selecting public event type
 */
$(document).on('change', '#EventEventType', function() {
    var eventType = parseInt($(this).val());
    if (eventType === EVENT_TYPE_PRIVATE) {
        $('#disease_field_group').addClass('hide');
    }
    else {
        $('#disease_field_group').removeClass('hide');
    }

    if (eventType === EVENT_TYPE_SITE) {
        $('#event_wizard_step3_common').addClass('hide');
        $('#event_wizard_step3_sitewide').removeClass('hide');
    }
    else {
        $('#event_wizard_step3_common').removeClass('hide');
        $('#event_wizard_step3_sitewide').addClass('hide');
    }
});
$(document).on('change', '#eventForm .repeat_radio', function() {
    if ($(this).val() === '1') {
        $('#eventForm #repeat_event_fields').removeClass('hide');
        $('#eventForm #one_day_event_fields').addClass('hide');
        
        $('#EventEndTime').rules('remove', 'onedayEndTimeValid');
    }
    else {
        $('#eventForm #repeat_event_fields').addClass('hide');
        $('#eventForm #one_day_event_fields').removeClass('hide');
        
         $("#EventEndTime").rules("add", {
            onedayEndTimeValid: true,
            messages: {
                required:"Please enter a valid end time" 
            }
        });
    }
});
$(document).on('change', '#EventVirtualEvent', function() {
    console.log('$(this).val():',$(this).val());
    if (parseInt($(this).val()) === VIRTUAL_EVENT) {
        $('#online_event_fields').removeClass('hide');
        $('#onsite_event_fields').addClass('hide');
    }
    else if (parseInt($(this).val()) === ORDINARY_EVENT) {
        $('#online_event_fields').addClass('hide');
        $('#onsite_event_fields').removeClass('hide');
    }
    else {
        $('#online_event_fields').addClass('hide');
        $('#onsite_event_fields').addClass('hide');
    }
});

$(document).on('click', '#add_diagnosis_btn', function() {
    var diagnosisLastIndex = $('#diagnosis_last_index').val();
    var diagnosisNewIndex = parseInt(diagnosisLastIndex) + 1;
    $.ajax({
        type: 'POST',
        url: '/event/add/getDiagnosisForm',
        data: {'index': diagnosisNewIndex},
        beforeSend: function() {
        },
        success: function(result) {
            $('#diagnosis_form_container').append(result);
            $('#diagnosis_last_index').val(diagnosisNewIndex);
        }
    });
});

/**
 * Search disease names
 */
$(document).on('focus', '.disease_search', function() {
    var minLength = 2;
    initDiseaseAutoComplete(this, minLength);
});

/**
 * Shows repeat mode related fields on changing the mode
 */
$(document).on('change', '#EventRepeatMode', function() {
    $('#repeat_interval_fields').addClass('hide');
//    $('#repeats_on_fields').addClass('hide');
//    $('#repeats_by_fields').addClass('hide');
    var mode = parseInt($(this).val());
    switch (mode) {
        case REPEAT_MODE_DAILY:
            $('#repeat_interval_fields').removeClass('hide');
            $('#interval_type').html('days');
            break;
        case REPEAT_MODE_WEEKLY:
            $('#repeat_interval_fields').removeClass('hide');
//            $('#repeats_on_fields').removeClass('hide');
            $('#interval_type').html('weeks');
            break;
        case REPEAT_MODE_MONTHLY:
            $('#repeat_interval_fields').removeClass('hide');
//            $('#repeats_by_fields').removeClass('hide');
            $('#interval_type').html('months');
            break;
        case REPEAT_MODE_YEARLY:
            $('#repeat_interval_fields').removeClass('hide');
            $('#interval_type').html('years');
            break;
    }
});

$(document).on('change', '[name="data[Event][repeat_end_type]"]', function() {
    $('#eventForm #EventRepeatOccurrences').attr('disabled', 'disabled').val('');
    $('#eventForm #EventEndDate').attr('disabled', 'disabled').val('');
    var ends_on = parseInt($(this).val());
    switch (ends_on) {
        case REPEAT_END_AFTER:
            $('#eventForm #EventRepeatOccurrences').removeAttr('disabled');
            break;
        case REPEAT_END_DATE:
            $('#eventForm #EventEndDate').removeAttr('disabled');
            break;
    }
});

function initEventPhotoUploader() {
	$uploadBtn = $('#bootstrapped-fine-uploader');
	$previewImg = $('#uploadPreview img#preview_image');
	$previewLoadingImg = $('#uploadPreview img#loading_img');

	var uploader = new qq.FineUploaderBasic({
		button: $uploadBtn[0],
		debug: false,
		multiple: false,
		request: {
			endpoint: '/event/add/photo'
		},
		validation: {
			acceptFiles: 'image/*',
			allowedExtensions: app.imageExtensions,
			minSizeLimit: '1024',
			sizeLimit: '5242880'
		},
		callbacks: {
			onError: function(id, name, reason, xhr) {
				showUploadError(reason);
				$uploadBtn.show();
                                $('#btn_event_wizard_step1').prop('disabled', false);
			},
			onSubmit: function(id, fileName) {
				$uploadBtn.hide();
			},
			onUpload: function(id, fileName) {
				showUploadLoadingMessage('Uploading ' + '“' + fileName + '” ');
                                $('#btn_event_wizard_step1').prop('disabled', true);
			},
			onProgress: function(id, fileName, loaded, total) {
				if (loaded < total) {
					var progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
					showUploadLoadingMessage('Uploading ' + '“' + fileName + '” ' + progress);
				}
			},
			onComplete: function(id, fileName, responseJSON) {
                                $('#btn_event_wizard_step1').prop('disabled', false);
				$uploadBtn.show();
				if (responseJSON.success) {
					hideUploadMessage();
					showImageCropDialog(id, responseJSON);
				} else {
					if (responseJSON.error) {
						showUploadError('Error with ' + '“' + fileName + '”: ' + responseJSON.error);
					}
					else {
						showUploadError('Failed to upload photo.');
					}
				}
			}
		}
	});

	var cropBox;
	function showImageCropDialog(id, responseJSON) {
		var imgHTML = '<div class="crop_area"><img src="' + responseJSON.fileUrl + '"' +
				' alt="Uploaded photo" /></div>';
		imgHTML += '<div>To make adjustments, please drag around the white rectangle.' +
				' When you are happy with the photo, click "Accept" button.</div>';
		cropBox = bootbox.dialog({
			closeButton: false,
			message: imgHTML,
			title: "Crop Image",
			animate: false,
			buttons: {
				success: {
					label: "Accept",
					className: "accept btn-success",
					callback: function() {
						previewCroppedPhoto(id, responseJSON);
					}
				},
				cancel: {
					label: "Cancel",
					className: "btn-default",
					callback: function() {
						uploader.cancelAll();
						hideUploadMessage();
					}
				}
			}
		});

		cropBox.find(".modal-footer .btn-success").attr("disabled", "disabled");

		setTimeout(function() {
			createCropper(cropBox, responseJSON);
		}, 500);
	}

	var ias;
	function createCropper(cropBox, responseJSON) {
		var uploadedImg = cropBox.find(".bootbox-body img");
		srcWidth = responseJSON.imageWidth;
		srcHeight = responseJSON.imageHeight;
		var aspectRatio = '262 : 114';
		var cropMinDim = getCropMinDimensions(srcWidth, srcHeight, aspectRatio);
		ias = uploadedImg.imgAreaSelect({
			parent: '.bootbox',
			autoHide: false,
			mustMatch: true,
			handles: true,
			instance: true,
			imageWidth: srcWidth,
			imageHeight: srcHeight,
			aspectRatio: aspectRatio,
			x1: 0, y1: 0,
			x2: cropMinDim.width, y2: cropMinDim.height,
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

	function getCropMinDimensions(srcWidth, srcHeight, aspectRatio) {
		var aspectRatioArr = aspectRatio.split(':');
		var aspectWidth = aspectRatioArr[0];
		var aspectHeight = aspectRatioArr[1];
		var maxCropWidth = Math.floor(srcWidth / aspectWidth);
		var maxCropHeight = Math.floor(srcHeight / aspectHeight);
		var scale = Math.min(maxCropWidth, maxCropHeight);
		var cropMinDim = {
			width: scale * aspectWidth,
			height: scale * aspectHeight
		};
		return cropMinDim;
	}

	function previewCroppedPhoto(id, response) {
		var scaledSelection = ias.getSelection(false);
		$.ajax({
			dataType: 'json',
			type: 'POST',
			url: '/event/add/cropImage',
			beforeSend:function(){
				$previewLoadingImg.removeClass('hide');
				$previewImg.hide();
			},
			data: {
				'x1': scaledSelection.x1,
				'y1': scaledSelection.y1,
				'w': scaledSelection.width,
				'h': scaledSelection.height,
				'fileName': response.fileName
			},
			success: function(responseJSON) {
				$('#EventImage').val(responseJSON.fileName);
				var timestamp = new Date().getTime();
				var imageSrc = responseJSON.fileUrl + '?' + timestamp;
				$previewImg.attr('src', imageSrc);
				$previewImg.load(function() {
					$previewLoadingImg.addClass('hide');
					$previewImg.fadeIn();
				});
			}
		});
	}
}
$messages = $('#uploadmessages div');
$loadingImg = '<img src="/img/loading.gif" /> ';
function showUploadError(message) {
    $messages.html(message);
    $messages.removeClass('alert-info').addClass('alert-error').show();
	$messages.parent().removeClass('upload_progress');
}
function showUploadLoadingMessage(message) {
    $messages.html($loadingImg + message);
    $messages.first().removeClass('alert-error').addClass('alert-info').show();
	$messages.parent().addClass('upload_progress');
}
function hideUploadMessage() {
    $messages.html('').hide();
	$messages.parent().removeClass('upload_progress');
}

/**
 * On clicking cancel button or close icon, 
 * redirect to event listing page from 'create event' page, or 
 * redirect to event detail page from 'edit event' page.
 */
$(document).on('click', '#cancel_event_wizard, #close_event_wizard', function() {
    var backUrl = $(form).data('backurl');
    window.location.href = backUrl;
});

var rotate = null;

$(document).on('click', '.rsvp_button', function() {
    var status = null;
    var type = null;
    var button = null;
    var event_id = $(this).data('event');
    if ($("#yes_" + event_id).hasClass('active')) {
        $('#yes_' + event_id + ' span.ladda-label').css({'color': '#fff'});
    } else if ($("#no_" + event_id).hasClass('active')) {
        $('#no_' + event_id + ' span.ladda-label').css({'color': '#fff'});
    } else if ($("#maybe_" + event_id).hasClass('active')) {
        $('#maybe_' + event_id + ' span.ladda-label').css({'color': '#fff'});
    }


    $('.rsvp_button').removeClass('active');
    switch ($(this).data('id')) {
        case 'rsvp_yes_button':
            status = '1';
            button = "#yes_" + $(this).data('event');
            break;
        case 'rsvp_no_button':
            button = "#no_" + $(this).data('event');
            status = '2';
            break;
        case 'rsvp_maybe_button':
            button = "#maybe_" + $(this).data('event');
            status = '3';
            break;
    }

    rotate = Ladda.create(this);
    if (typeof isCommunityEvent === 'undefined') {
        type = 'listing';
        if (rotate != null) {
            rotate.start();
        }
    } else {
        type = 'details';
        if ( (isCommunityEvent && !isCommunityMember) || (isCommunityEvent && isInvited)) {
            $('#join_community').modal();
            return false;
        } else if (isCommunityEvent && isCommunityMember && !isApprovedCommunityMember) {
            $('#waiting_community_approval').modal();
            return false;
        } else if (rotate != null) {
            rotate.start();
        }
    }
    disableButton(this);
    if (status != null) {
        markAttendance($(this).data('event'), status, type, button, rotate);
    }
});

function disableButton(id) {
    $(id).css({
        'border': '1px solid #004f7f',
        'background-color': '#2c589e',
        'color': '#fff'
    });
    $(id).attr('disabled', 'disabled');
}

/*
 * Mark the attendance when user responds to request
 */
function markAttendance(event_id, attendance, type, button, rotate) {
//    console.log('markAttendance');
    $('#yes_' + event_id).attr('disabled', 'disabled');
    $('#maybe_' + event_id).attr('disabled', 'disabled');
    $('#no_' + event_id).attr('disabled', 'disabled');
    $.ajax({
        url: '/event/details/index/' + event_id + '/attendance:' + attendance,
        beforeSend: function() {
        },
        success: function(data) {
//            console.log('markAttendance succes data',data);
            if (data != 'false') {
                markAttendanceSuccess(data, event_id, type, button, rotate);
            } else {
                location.reload();
            }
        }
    });
}

function markAttendanceSuccess(data, event_id, type, button, rotate) {
//    console.log('markAttendanceSuccess typevarun : '+type);
    $('#yes_' + event_id).removeAttr('disabled');
    $('#maybe_' + event_id).removeAttr('disabled');
    $('#no_' + event_id).removeAttr('disabled');

    if (type == 'listing') {
//        console.log('type listing');
        $('#' + event_id + ' button').removeClass('active');
        $.ajax({
            url: '/event/updateCount/' + event_id,
            success: function(data) {
                console.log('type listing success data: ',data);
                $("#" + event_id + ' .member_count').html(data);
            }
        });
    } else {
//        console.log('No listing.', data);
        $("#all_attendess_list_container").html(data);
        refreshAttendanceCount(event_id);
        refreshEventPosts(event_id);
        
    }
    if (rotate != null) {
        rotate.stop();
    }
    changeStyleRsvpButtons(event_id);
    disableButton(button);
    $(button).addClass('active');
}

function refreshEventPosts(event_id) {
//    console.log('refresh events posts');
//    var url = window.location.href + '/refresh:1';
    var url = '/event/details/index/' + event_id +  '/refresh:1';
    $.ajax({
        type: 'GET',
        url: url,
        success: function(content) {
//            console.log('refresh event posts content', content);
            $('#post_content').html(content);
			resetNewPostCount();
        }
    });
}

/*
 *  Funtion to refresh the attendance count in 
 *  event detail page 
 *  @returns {boolean}
 */
function refreshAttendanceCount(event_id) {
    
        var url = '/event/details/index/' + event_id +  '/attendance_count:1';
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function(result) {
                if ( result.success ) {
                    $('#event_attending_count').html(result.attending_count);
                    $('#event_maybe_count').html(result.maybe_count);
                }
            }
        });
       return true;
}

function changeStyleRsvpButtons(event_id) {
    $('#yes_' + event_id).css({
        'background-color': '',
        'border': '',
        'color': ''
    });
    $('#maybe_' + event_id).css({
        'background-color': '',
        'border': '',
        'color': ''
    });
    $('#no_' + event_id).css({
        'background-color': '',
        'border': '',
        'color': ''
    });
    $('#yes_' + event_id + ' span.ladda-label').css({'color': ''});
    $('#no_' + event_id + ' span.ladda-label').css({'color': ''});
    $('#maybe_' + event_id + ' span.ladda-label').css({'color': ''});
}

/**
 * Video url posting in  event 
 * ******************************************
 */

var eventUrlRegex = /(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i;
var isCrawlingEventUrl = false;
var isEventLinkDataPresent = false;
var eventLink;

$(document).on('keyup', '#EventOnlineEventDetails', function(e) {
    eventLink = this;
    checkEventLink();
});

$(document).on('paste', '#EventOnlineEventDetails', function(e) {
    eventLink = this;
    // Short pause to wait for paste to complete
    setTimeout(function() {
        if ($('#EventOnlineEventDetails').valid()) {
            grabVideoUrl();
        }
    }, 100);
});

$(document).on('click', '#closeOnlineEventPreview', function() {
    $('#preview').fadeOut("fast", function() {
        clearOnlineEventPreview();
        $('#link_input_section').removeClass('hide');
        $('#EventOnlineEventDetails').val('').removeClass('textbox_loader').removeAttr('disabled');
        $('#event_vido_url_form').removeClass('event_video_url_review');
    });
});

/**
 * Grab url on clicking 'Add' button
 */
$(document).on('click', '#event_grab_video_url_btn', function() {
    grabVideoUrl();
});

function clearOnlineEventPreview() {
    $('#preview').hide();
    $('#previewImage').html("");
    $('#previewTitle').html("");
    $('#previewUrl').html("");
    $('#previewDescription').html("");
    $('#event_grab_video_url_btn').attr('disabled', 'disabled');
}

function grabVideoUrl() {
    var link = $('#EventOnlineEventDetails').val();
    $.ajax({
        type: 'POST',
        url: '/post/api/textCrawler',
        data: {
            'link': link
        },
        dataType: 'json',
        beforeSend: function() {
            $('#event_grab_video_url_btn').attr('disabled', 'disabled');
            $('#EventOnlineEventDetails').addClass("textbox_loader").attr('disabled', 'disabled');
            $('#preview').hide();

            isCrawlingEventUrl = true;
        },
        success: function(response) {
            $('#event_grab_video_url_btn').removeAttr('disabled');
            if (response.url && (response.video !== 'no')) {
                $('#EventOnlineEventDetails').removeClass('textbox_loader').removeAttr('disabled');
                $('#event_vido_url_form').addClass('event_video_url_review');
                $('#link_input_section').addClass('hide');
                $('#preview').show();
                $('#previewTitle').html(response.title);
                $('#previewUrl').html(response.cannonicalUrl);
                $('#previewDescription').html(response.description);
                try {
                    if (response.images !== '' && response.images !== false && response.images !== null) {
                        images = (response.images).split("|");
                        response.images = images;
                        $('#previewImages').show();
                        images.length = parseInt(images.length);
                        previewImagesCount = images.length;

                        var appendImage = "";
                        for (i = 0; i < images.length; i++) {
                            if (i === 0)
                                appendImage += "<img id='imagePreview" + i + "' src='" + images[i] + "' style='width: 130px; height: auto' ></img>";
                            else
                                appendImage += "<img id='imagePreview" + i + "' src='" + images[i] + "' style='width: 130px; height: auto; display: none' ></img>";
                        }
                        $('#previewImage').html("<a href='" + response.pageUrl + "' target='_blank'>" + appendImage + "</a><div id='whiteImage' style='width: 130px; color: transparent; display:none;'>...</div>");
                    }
                    else {
                        $('#previewImages').hide();
                    }
                } catch (err) {
                    $('#previewImages').hide();
                }

                isEventLinkDataPresent = true;
                isCrawlingEventUrl = false;
            }
            else {
                isCrawlingEventUrl = false;
                //showEventVodeoLinkError('Please enter a valid video URL.');
                $('#EventOnlineEventDetails').removeClass('textbox_loader').removeAttr('disabled');
            }
        }
    });
}

function showEventVodeoLinkError(message) {
    if ($("#event_vido_url_form span[for='EventOnlineEventDetails']").length > 0) {
        $("#event_vido_url_form span[for='EventOnlineEventDetails']").html(message).show();
    }
    else {
        var PostLinkErrorHTML = '<span for="EventOnlineEventDetails" class="help-block">' + message + '</span>';
        $("#event_vido_url_form #link_input_section").append(PostLinkErrorHTML);
    }
    $("#event_vido_url_form .form-group").addClass('error');
}

function setEventVideoData(response) {
    $('#EventLinkTitle').val(response.title);
    $('#EventLinkUrl').val(response.url);
    $('#EventLinkPageUrl').val(response.pageUrl);
    $('#EventLinkCannonicalUrl').val(response.cannonicalUrl);
    $('#EventLinkDescription').val(response.description);
    var selectedImg = '';
    if (response.images !== false) {
        if (response.images instanceof Array) {
            selectedImg = response.images[0];
        }
        else {
            selectedImg = response.images;
        }
    }
    $('#EventLinkImage').val(selectedImg);
    $('#EventLinkVideo').val(response.video);

    var videoIframe = '';
    if (response.video === 'yes') {
        videoIframe = response.videoIframe;
    }
    $('#EventOnlineEventDetails').val(response.videoIframe);
}

function checkEventLink() {
    isEventLinkDataPresent = false;
    if ($.trim($(eventLink).val()) !== "") {
        var text = $(eventLink).val();

        // add http if not present
        if (text.substr(0, 7) !== 'http://') {
            if (text.substr(0, 8) != 'https://') {
                text = 'http://' + text;
            }
        }
        if (text.substr(text.length - 1, 1) !== '/') {
            text = text + '/';
        }

        if (eventUrlRegex.test(text)) {
            $('#event_grab_video_url_btn').removeAttr('disabled');
        }
        else {
            $('#event_grab_video_url_btn').attr('disabled', 'disabled');
            $('#preview').fadeOut("fast", function() {
                clearOnlineEventPreview();
            });
        }
    }
    else {
        $('#event_grab_video_url_btn').attr('disabled', 'disabled');
    }
}

/**** event creation ****************/

//    var startTime = '';
//    var endTime = '';
//    var startTimeValue = false;
//    var endTimeValue = false;
//    var first = true;
//    $(document).ready(function() {
//        defaultTime = $('#EventStartTime').data('default-time');
//        if (defaultTime !== false) {
//            first = false;
//        }
//    });
//    
//    $('#EventStartTime').timepicker({
//        minuteStep: 1,
//        appendWidgetTo: 'body',
//        showMeridian: true,
//        showInputs: true,
//        disableFocus: true,
//        disableMousewheel: true
//    });
//
//    $('#EventEndTime').timepicker({
//        minuteStep: 1,
//        appendWidgetTo: 'body',
//        showMeridian: true,
//        showInputs: true,
//        disableFocus: true,
//        disableMousewheel: true
//    });
//
//    $('#EventStartTime').timepicker().on('show.timepicker', function(e) {
//        if (first) {
//            $('#EventStartTime').timepicker('setTime', now());
//        }
//    });
//
//    $('#EventStartTime').timepicker().on('hide.timepicker', function(e) {
//        var hours = parseInt(e.time.hours);
//        var mins = parseInt(e.time.minutes);
//        var mid = e.time.meridian;
//        startTime = time(hours, mins, mid);
//        mins = mins + 30;
//
//        if (mins > 59) {
//            mins = mins % 60;
//            hours += 1;
//            if (hours > 12) {
//                hours = hours % 12;
//            } else if (hours == 12) {
//                if (mid == 'AM') {
//                    mid = 'PM';
//                } else {
//                    mid = 'AM';
//                }
//            }
//        }
//        mins = ('0' + mins).slice(-2);
//        if (first) {
//            $('#EventEndTime').timepicker('setTime', hours + ':' + mins + ' ' + mid);
//            endTime = time(hours, mins, mid);
//        } else {
//            if (endTime) {
//                if (endTime < startTime) {
//                    $('#EventEndTime').timepicker('setTime', hours + ':' + mins + ' ' + mid);
//                    endTime = time(hours, mins, mid);
//                }
//            }
//        }
//        first = false;
//    });
//
//    $('#EventEndTime').timepicker().on('show.timepicker', function(e) {
//        if (first) {
//            $('#EventStartTime').timepicker('setTime', now());
//            $('#EventEndTime').timepicker('setTime', now());
//        }
//    });
//
//    $('#EventEndTime').timepicker().on('changeTime.timepicker', function(e) {
//        var hours = parseInt(e.time.hours);
//        var mins = parseInt(e.time.minutes);
//        var mid = e.time.meridian;
//        endTime = time(hours, mins, mid);
//    });
//
//    $('#EventEndTime').timepicker().on('hide.timepicker', function(e) {
//        var hours = parseInt(e.time.hours);
//        var mins = parseInt(e.time.minutes);
//        var mid = e.time.meridian;
//        endTime = time(hours, mins, mid);
//
//        if (startTime == '') {
//            startTimeValue = $('#EventStartTime').data('default-time');
//            var split_array = startTimeValue.split(':');
//            var starthour = parseInt(split_array[0]);
//            var secondsplit = split_array[1].split(' ');
//            var startmin = parseInt(secondsplit[0]);
//            var startmid = secondsplit[1];
//            startTime = time(starthour, startmin, startmid);
//        }
//
//        if (endTime <= startTime) {
//            $('#EventEndTime').timepicker('setTime', startTimeValue);
//        }
//        first = false;
//    });
//
//    $('#EventStartTime').timepicker().on('changeTime.timepicker', function(e) {
//        startTimeValue = e.time.value;
//        var hours = parseInt(e.time.hours);
//        var mins = parseInt(e.time.minutes);
//        var mid = e.time.meridian;
//
//        startTime = time(hours, mins, mid);
//        if (endTime < startTime) {
//            $('#EventEndTime').timepicker('setTime', startTimeValue);
//        }
//    });

    function time(hours, mins, mid) {
        if (mid == 'PM') {
            if (hours != 12) {
                hours += 12;
            }
        } else {
            if (hours == 12) {
                hours = 0;
            }
        }
        return ((hours * 3600) + (mins * 60));
    }

    function now() {
        var day = getUserNow();
        var hours = new Date(day).getHours();
        var mins = new Date(day).getMinutes();
        var mid = 'AM';
        if (hours == 0) { //At 00 hours we need to show 12 am
            hours = 12;
        } else if (hours > 12) {
            hours = hours % 12;
            mid = 'PM';
        }
        return (hours + ':' + mins + ' ' + mid);
    }

$(document).ready( function () {
    $('#eventDuration .time.start').timepicker({ 
        'showDuration': true,
        'timeFormat': 'g:i a'
    });
    $('#eventDuration .time.end').timepicker({ 
        'maxTime': '11:30 pm', //12.00 am
        'showDuration': true,
        'timeFormat': 'g:i a'
    });
    
    
    $('#eventDuration').datepair({
        'defaultTimeDelta': 1800000
    });
    
    
    $('#repeat_event_mode_time .time.start').timepicker({ 
        'forceRoundTime': true,
        'showDuration': true,
        'timeFormat': 'g:i a'
    });
    
    $('#repeat_event_mode_time .time.end').timepicker({ 
        'forceRoundTime': true,
        'maxTime': '11:30 pm', //12.00 am
        'showDuration': true,
        'timeFormat': 'g:i a'
    });
//    var datePairOptions = {
//        'defaultTimeDelta': 1800000
//    };
//    var datePairContainer = document.getElementById('repeat_event_mode_time')
//    var repeatEventDatepair = new Datepair(datePairContainer, datePairOptions);
//    
//    $('#repeat_event_mode_time').datepair({
//        'defaultTimeDelta': 1800000
//    });
//    $('#repeat_event_mode_start').datepair({
//        'defaultTimeDelta': 1800000
//    });
    
//    $('#repeat_event_mode_end').datepair({
//        'defaultTimeDelta': 1800000
//    });
});

$(document).on('change', '.time.end', function() {
    var endTime = $('#eventForm .time.end').val();
    var startTime = $('#eventForm .time.start').val();
    if (startTime != '') {
		
        var isStartDaterGrater = compareTime(startTime, endTime);
        if (isStartDaterGrater) {
//            $('#eventForm .time.end').val($('#eventForm .time.start').val());
        }
    }
});

$(document).on('change', '#EventStartTime, #EventEndTime', function() {
    $("#EventEndTime").valid();
});

$(document).on('change', '#repeat_event_mode_time .time.end', function() {
    var endTime = $('#repeat_event_mode_time .time.end').val();
    var startTime = $('#repeat_event_mode_time .time.start').val();
    if (startTime != '') {
        var isStartDaterGrater = compareTime(startTime, endTime);
//        if (isStartDaterGrater) {
//            $('#repeat_event_mode_time .time.end').val($('#repeat_event_mode_time .time.start').val());
//        }
    }
});

$(document).on('change', '#is_full_day',  function(){
    if($('#is_full_day').prop('checked')){
        $('.not_full_day_event').addClass('hidden');
        $('#EventUptoTimeonly').rules('remove', 'required');
        $('#EventUptoTimeonly').rules('remove', 'regex');
        $('#EventUptoTimeonly').rules('remove', 'repeatEndTimeValid');
        $("#EventUptoTimeonly").valid();
//        $('#EventStartDateTimeonly').rules('remove', 'repeatStartTimeValid');
    } else {
        $('.not_full_day_event').removeClass('hidden');
//        $("#EventStartDateTimeonly").rules("add", {
//            repeatStartTimeValid: true
//        });
        $("#EventUptoTimeonly").rules("add", {
            required: true,
            regex: /(([0-9]|[1][012])\:[03]0\s(a|p)m)$/i,
            repeatEndTimeValid: true,
            messages: {
                regex: "Please enter a valid end time",
                required:"Please enter a valid end time" 
            }
        });
        
//        $('#repeat_event_mode_end').datepair({
//            'defaultTimeDelta': 1800000
//        });
    }
    $(document).on('change', '.setErrorDivRepeat',  function(){
        if($("#EventStartDateTimeonly").valid()) {
            $("#EventUptoTimeonly").valid();
        }
    });
    setEndDateValidation();
});

$(document).on('change', '#EventStartDateTime', function() {
    console.log('change in dates called');
    if(!compareEventDates()){
        $('#EventUptoDate').val($(this).val());
    }
 
});
$(document).on('change', '#EventUptoDate', function() {
    console.log('change in dates called');
    if(!compareEventDates()){
        $('#EventStartDateTime').val($(this).val());
    }
  
});


function compareEventDates(){
    var startDate = $('#EventStartDateTime').val();
    var endDate = $('#EventUptoDate').val();
    var result = false;
    if(startDate != '' && endDate != '') {
        if($('#is_full_day').prop('checked')){
            endDate = startDate;
        } else {
            startDateObj = new Date(endDate);
            endDateObj = new Date(startDate);
            if(endDateObj > startDateObj){
                console.log('false');
                result = false;
                
            } else {
                console.log('true');
                result = true;
            }
        }
    }
    setEndDateValidation();
     return result;
}

function setEndDateValidation() {
    var startDate = $('#EventStartDateTime').val();
    var endDate = $('#EventUptoDate').val();
    var endsOnDate = startDate;
    var is_fullday = $('#is_full_day').prop('checked');
    if(endDate != '' && !is_fullday) {
        endsOnDate = endDate;
    } else {
        endsOnDate = startDate;
    }
     $("#EventEndDate").datepicker('option','minDate', new Date(endsOnDate));
}
/**
 * Functin to compare two time stirng
 * 
 * @param {string} startTime
 * @param {string} endTime
 * @returns {boolean} startTime > endTime
 */
function compareTime(startTime, endTime) {
        if ( endTime.length !== 0 && startTime.length !== 0) {
                var endHour = endTime.match(/([0-9]|[1][012]):/);
                var startHour = startTime.match(/([0-9]|[1][012]):/);
                if(endHour != null && startHour != null) {
                    endHour = endHour[1];
                    var endMin = endTime.match(/:([0-5][0|5])/);
                    endMin = endMin[1];
                    var endMid = endTime.match(/((a|p)m)/);
                    endMid = endMid[1];
                    startHour = startHour[1];
                    var startMin = startTime.match(/:([0-5][0|5])/);
                    startMin = startMin[1];
                    var startMid = startTime.match(/((a|p)m)/);
                    startMid = startMid[1];
                    endTime = time(endHour, endMin, endMid.toUpperCase());
                    startTime = time(startHour, startMin, startMid.toUpperCase());
                    return (startTime >= endTime) ;
                } else {
                    return false;
                }

        } else {
                return false;
        }
}
