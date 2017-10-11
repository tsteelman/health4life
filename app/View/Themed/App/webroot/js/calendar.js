var CALENDAR_TYPE_ALL = 1;
var CALENDAR_TYPE_ORDINARY_EVENTS = 2;
var CALENDAR_TYPE_ORDINARY_AND_REMINDER_EVENTS = 3;
var CALENDAR_TYPE_CARECALENDAR_EVENTS = 4;

$(document).ready(function() {

    var calendar_type = parseInt($("#calendarType").val());
    var team_id = parseInt($("#teamId").val());
    var view = "week";
    if (calendar_type == 4) {
        view = "month";
    }


    var DATA_FEED_URL = "/calendar/calendar/ajaxCalendar";
    var op = {
        view: view,
        theme: 3,
        calendarType: calendar_type,
        teamId: team_id,
        filterType: 0,
        assignedToFilter: null,
        needTypeFilter: null,
        statusFilter: null,
//        showday: new Date(),
        EditCmdhandler: Edit,
//        DeleteCmdhandler: Delete,
        ViewCmdhandler: View,
        onWeekOrMonthToDay: wtd,
        onBeforeRequestData: cal_beforerequest,
        onAfterRequestData: cal_afterrequest,
        onRequestDataError: cal_onerror,
        autoload: true,
        enableDrag: false,
        datestrshow: null,
        url: DATA_FEED_URL + "?method=list",
        quickAddUrl: DATA_FEED_URL + "?method=add",
        quickUpdateUrl: DATA_FEED_URL + "?method=update",
        quickDeleteUrl: DATA_FEED_URL + "?method=remove"
    };
    var $dv = $("#calhead");
    var _MH = document.documentElement.clientHeight;
    var dvH = $dv.height() + 2;
    op.height = _MH - dvH;
    op.eventItems = [];

    var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
    if (p && p.datestrshow) {
        $("#txtdatetimeshow").text(p.datestrshow);
    }
    $("#caltoolbar").noSelect();
    $("#txtdatetimeshow").click(function() {//hdtxtshow
        $("#hdtxtshow").datepicker({//hdtxtshow
            picker: "#txtdatetimeshow",
            showtarget: $("#txtdatetimeshow"),
//            onReturn: function(r) {
            onSelect: function() {
                var r = $("#hdtxtshow").datepicker("getDate");//to do . apply timezone to this dateobject.
                var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            }
        });
        $("#hdtxtshow").datepicker('show');
    });

    var v = getiev();
    if (v > 0) {
        $(document.body).addClass("ie ie" + v);
    }

    function cal_beforerequest(type)
    {
        var t = "Loading data...";
        switch (type)
        {
            case 1:
                t = "Loading data...";
                break;
            case 2:
            case 3:
            case 4:
                t = "The request is being processed ...";
                break;
        }
        $("#errorpannel").hide();
//        $("#loadingpannel").html(t).show();//original
        $("#loadingpannel").show();//replaced with a gif loading image.
        $("#refresh_img_container").hide();
    }
    function cal_afterrequest(type)
    {
        switch (type)
        {
            case 1:
                $("#loadingpannel").hide();
                $("#refresh_img_container").show();
                break;
            case 2:
            case 3:
            case 4:
//                $("#loadingpannel").html("Success!");//original. replaced with a gif loading image.
                $("#loadingpannel").show();
                $("#refresh_img_container").hide();
                window.setTimeout(function() {
                    $("#loadingpannel").hide();
                    $("#refresh_img_container").show();
                }, 2000);
                break;
        }
        if (p && p.datestrshow) {  // was added to resolve issue "Events not loading."
            $("#txtdatetimeshow").text(p.datestrshow);
        }

    }
    function cal_onerror(type, data)
    {
        $("#errorpannel").show();
    }
    function Edit(data)
    {
//        var eurl = "edit.php?id={0}&start={2}&end={3}&isallday={4}&title={1}";
//        if (data)
//        {
//            var url = StrFormat(eurl, data);
//            OpenModelWindow(url, {width: 600, height: 400, caption: "Manage  The Calendar", onclose: function() {
//                    $("#gridcontainer").reload();
//                }});
//        }
    }
    function View(data)
    {
        var str = "";
        $.each(data, function(i, item) {
            str += "[" + i + "]: " + item + "\n";
        });
        alert(str);
    }
    function Delete(data, callback)
    {

        $.alerts.okButton = "Ok";
        $.alerts.cancelButton = "Cancel";
        hiConfirm("Are You Sure to Delete this Event", 'Confirm', function(r) {
            r && callback(0);
        });
    }
    function wtd(p)
    {
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
        }
        $("#caltoolbar div.fcurrent").each(function() {
            $(this).removeClass("fcurrent");
        })
        $("#showdaybtn").addClass("fcurrent");
    }
    //to show day view
    $("#showdaybtn").click(function(e) {
        //document.location.href="#day";
        $("#caltoolbar div.fcurrent").each(function() {
            $(this).removeClass("fcurrent");
        })
        $(this).addClass("fcurrent");
        var p = $("#gridcontainer").swtichView("day").BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
        }
    });
    //to show week view
    $("#showweekbtn").click(function(e) {
        //document.location.href="#week";
        $("#caltoolbar div.fcurrent").each(function() {
            $(this).removeClass("fcurrent");
        })
        $(this).addClass("fcurrent");
        var p = $("#gridcontainer").swtichView("week").BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
        }

    });
    //to show month view
    $("#showmonthbtn").click(function(e) {
        //document.location.href="#month";
        $("#caltoolbar div.fcurrent").each(function() {
            $(this).removeClass("fcurrent");
        });
        $(this).addClass("fcurrent");
        var p = $("#gridcontainer").swtichView("month").BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
        }
    });

    $("#showreflashbtn").click(function(e) {
        $("#gridcontainer").reload();
    });

    //Add a new event
    $("#faddbtn").click(function(e) {
        var url = "edit.php";
        OpenModelWindow(url, {width: 500, height: 400, caption: "Create New Calendar"});
    });
    //go to today
    $("#showtodaybtn").click(function(e) {
		$("#caltoolbar div.fcurrent").each(function() {
            $(this).removeClass("fcurrent");
        })
        $("#showtodaybtn").addClass("fcurrent");
        var p = $("#gridcontainer").gotoDate().BcalGetOp();
		$("#gridcontainer").swtichView("day").BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
        }


    });
    //previous date range
    $("#sfprevbtn").click(function(e) {
        var p = $("#gridcontainer").previousRange().BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
            $("#gridcontainer").reload();
        }

    });
    //next date range
    $("#sfnextbtn").click(function(e) {
        var p = $("#gridcontainer").nextRange().BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
            $("#gridcontainer").reload();
        }
    });
    $("#assigned_to_filter, #need_type_filter, #status_filter").change(function(e) {
        var p = $("#gridcontainer").BcalGetOp();
//        p.filterType = $(this).val();
        p.assignedToFilter = $("#assigned_to_filter").val();
        p.needTypeFilter = $("#need_type_filter").val();
        p.statusFilter = $("#status_filter").val();

        $("#gridcontainer").reload();
    });


    /*
     * popover to dispaly the color codes used in various type of events and types.
     */
    $("#info_colorcodes_img").popover({
        placement: 'auto',
        trigger: 'hover',
//        title: function() {
//            return getStatusInfoTitle();
//        },
        html: true,
        content: function() {
            return getStatusInfoDetails();
        },
        container: 'body'
    });
    function getStatusInfoDetails() {
        var popover_html = '';
        if (calendar_type == 4) {
//            popover_html = $("#carecalendar_legend_container").html();
            return $("#carecalendar_legend_container").html();
        }else{
//            popover_html = $("#main_calendar_legend_container").html();
            return  $("#main_calendar_legend_container").html();
        }

//        return popover_html;
    }

 $('#ApptEndDate').datepicker({
//        minDate:  new Date($('#appoinment_date').val())
 });
});

try {
    document.execCommand("BackgroundImageCache", false, true);
} catch (e) {
}
var popUpWin;
function PopUpCenterWindow(URLStr, width, height, newWin, scrollbars) {
    var popUpWin = 0;
    if (typeof (newWin) == "undefined") {
        newWin = false;
    }
    if (typeof (scrollbars) == "undefined") {
        scrollbars = 0;
    }
    if (typeof (width) == "undefined") {
        width = 800;
    }
    if (typeof (height) == "undefined") {
        height = 600;
    }
    var left = 0;
    var top = 0;
    if (screen.width >= width) {
        left = Math.floor((screen.width - width) / 2);
    }
    if (screen.height >= height) {
        top = Math.floor((screen.height - height) / 2);
    }
    if (newWin) {
        open(URLStr, '', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=' + scrollbars + ',resizable=yes,copyhistory=yes,width=' + width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
        return;
    }

    if (popUpWin) {
        if (!popUpWin.closed)
            popUpWin.close();
    }
    popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=' + scrollbars + ',resizable=yes,copyhistory=yes,width=' + width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
    popUpWin.focus();
}

function OpenModelWindow(url, option) {
    var fun;
    try {
        if (parent != null && parent.$ != null && parent.$.ShowIfrmDailog != undefined) {
            fun = parent.$.ShowIfrmDailog
        }
        else {
            fun = $.ShowIfrmDailog;
        }
    }
    catch (e) {
        fun = $.ShowIfrmDailog;
    }
    fun(url, option);
}
function CloseModelWindow(callback, dooptioncallback) {
    parent.$.closeIfrm(callback, dooptioncallback);
}


function StrFormat(temp, dataarry) {
    return temp.replace(/\{([\d]+)\}/g, function(s1, s2) {
        var s = dataarry[s2];
        if (typeof (s) != "undefined") {
            if (s instanceof (Date)) {
                return s.getTimezoneOffset()
            } else {
                return encodeURIComponent(s)
            }
        } else {
            return ""
        }
    });
}


function fomartTimeShow1(h, mm) {
//            return h < 10 ? "0" + h + ":00" : h + ":00";
//    var m = parseInt(mm);
    var m = mm;
    if (h == 0) {
        return "12:" + m + " AM";
    } else if (h < 12) {
        return h < 10 ? "0" + h + ":" + m + " AM" : h + ":" + m + " AM";
    } else if (h >= 12) {
        h = (h - 12 == 0) ? 12 : h - 12;
        return h < 10 ? "0" + h + ":" + m + " PM" : h + ":" + m + " PM";
    }

}
function StrFormatNoEncode(temp, dataarry) {
    return temp.replace(/\{([\d]+)\}/g, function(s1, s2) {
        var s = dataarry[s2];
        if (typeof (s) != "undefined") {
            if (s instanceof (Date)) {
                return s.getTimezoneOffset()
            } else {
                return (s);
            }
        } else {
            return "";
        }
    });
}

function getiev() {
    var userAgent = window.navigator.userAgent.toLowerCase();
    $.browser.msie8 = $.browser.msie && /msie 8\.0/i.test(userAgent);
    $.browser.msie7 = $.browser.msie && /msie 7\.0/i.test(userAgent);
    $.browser.msie6 = !$.browser.msie8 && !$.browser.msie7 && $.browser.msie && /msie 6\.0/i.test(userAgent);
    var v;
    if ($.browser.msie8) {
        v = 8;
    }
    else if ($.browser.msie7) {
        v = 7;
    }
    else if ($.browser.msie6) {
        v = 6;
    }
    else {
        v = -1;
    }
    return v;
}

$(document).on('change', '#CalendarForm #stpartdate', function() {
    var sd = $("#CalendarForm #stpartdate").datepicker('getDate');
    if($("#CalendarForm #EventEndDate").hasClass('hasDatepicker')) {
        $("#CalendarForm #EventEndDate").datepicker('option','minDate', new Date(sd));
    } else {
        $("#CalendarForm #EventEndDate").datepicker({
           minDate: new Date(sd)
        });
    }
});

$(document).on('change', '[name="data[CalendarForm][repeat_end_type]"]', function() {
    $('#CalendarForm #EventRepeatOccurrences').attr('disabled', 'disabled').val('');
    $('#CalendarForm #EventEndDate').attr('disabled', 'disabled').val('');
    var ends_on = parseInt($(this).val());
    switch (ends_on) {
        case REPEAT_END_AFTER:
            $('#CalendarForm #EventRepeatOccurrences').removeAttr('disabled');
            break;
        case REPEAT_END_DATE:
            var sd = $("#CalendarForm #stpartdate").datepicker('getDate');
            $('#CalendarForm #EventEndDate').removeAttr('disabled');
            $("#CalendarForm #EventEndDate").datepicker('option','minDate', new Date(sd));
            break;
    }
});

$(document).on('change', '#CalendarForm .repeat_radio', function() {
    if ($(this).val() === '1') {
        $('#CalendarForm #repeat_event_fields').removeClass('hide');
        $('#CalendarForm #one_day_event_fields').addClass('hide');
    }
    else {
        $('#CalendarForm #repeat_event_fields').addClass('hide');
        $('#CalendarForm #one_day_event_fields').removeClass('hide');
    }
});

// $('#ApptEndDate').datepicker({
////        minDate:  new Date($('#appoinment_date').val())
// });

$(document).on('change', '#AppoinmentForm .repeat_radio', function() {
    if ($(this).val() === '1') {
        $('#AppoinmentForm #appt_repeat_event_fields').removeClass('hide');
//        $('#AppoinmentForm #one_day_event_fields').addClass('hide');
    }
    else {
        $('#AppoinmentForm #appt_repeat_event_fields').addClass('hide');
//        $('#AppoinmentForm #one_day_event_fields').removeClass('hide');
    }
});


$(document).on('change', '#AppoinmentForm #appoinment_date', function() {
    console.log('poda');
    var sd = $("#AppoinmentForm #appoinment_date").datepicker('getDate');
    if($("#AppoinmentForm #ApptEndDate").hasClass('hasDatepicker')) {
        $("#AppoinmentForm #ApptEndDate").datepicker('option','minDate', new Date(sd));
    } else {
        $("#AppoinmentForm #ApptEndDate").datepicker({
           minDate: new Date(sd)
        });
    }
});

$(document).on('change', '[name="data[AppoinmentForm][repeat_end_type]"]', function() {
    $('#CalendarForm #EventRepeatOccurrences').attr('disabled', 'disabled').val('');
    $('#AppoinmentForm #ApptEndDate').attr('disabled', 'disabled').val('');
    var ends_on = parseInt($(this).val());
    var sd = $('#appoinment_date').datepicker('getDate');
    switch (ends_on) {
        case REPEAT_END_AFTER:
            $('#AppoinmentForm #ApptRepeatOccurrences').removeAttr('disabled');
            break;
        case REPEAT_END_DATE:
            $("#AppoinmentForm #ApptEndDate").datepicker('option','minDate', new Date(sd));
            $('#AppoinmentForm #ApptEndDate').removeAttr('disabled');
            break;
    }
});

/**
 * Shows repeat mode related fields on changing the mode
 */
$(document).on('change', '#ApptRepeatMode', function() {
    $('#appt_repeat_interval_fields').addClass('hide');
//    $('#repeats_on_fields').addClass('hide');
//    $('#repeats_by_fields').addClass('hide');
    var mode = parseInt($(this).val());
    switch (mode) {
        case REPEAT_MODE_DAILY:
            $('#appt_repeat_interval_fields').removeClass('hide');
            $('#appt_interval_type').html('days');
            break;
        case REPEAT_MODE_WEEKLY:
            $('#appt_repeat_interval_fields').removeClass('hide');
//            $('#repeats_on_fields').removeClass('hide');
            $('#appt_interval_type').html('weeks');
            break;
        case REPEAT_MODE_MONTHLY:
            $('#appt_repeat_interval_fields').removeClass('hide');
//            $('#repeats_by_fields').removeClass('hide');
            $('#appt_interval_type').html('months');
            break;
        case REPEAT_MODE_YEARLY:
            $('#appt_repeat_interval_fields').removeClass('hide');
            $('#appt_interval_type').html('years');
            break;
    }
});
