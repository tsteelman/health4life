<div class="calender_events">
    <div class="dashboard_calender pull-left">
        <div id="my-calendar"></div>
        <input type="hidden" id="events" />
    </div>                                
    <div class="dashboard_events pull-right"> 
        <div class="dashboard_header">Reminder</div>
        <div id="calendar-details" class="tile_content"></div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#my-calendar").zabuto_calendar({
            year: <?php echo '"' . CakeTime::format(date('Y-m-d H:i:s'), '%Y', NULL, $timezone) . '"'; ?>,
            month: <?php echo '"' . CakeTime::format(date('Y-m-d H:i:s'), '%m', NULL, $timezone) . '"'; ?>,
            language: "en",
            cell_border: false,
            weekstartson: 0,
            today: true,
            first: true,
            ajax: {
                url: '/calendar/calendar/initializeDashboardCalendar',
                modal: false,
				cache: false
            },
            action: function() {
                getDateEvents(this.id);
            },
            dblclick: function() {
                goToCalendar(this.id);
            }
        });

        $(document).on('click', '#conditions_more', function() {
            $('#myModal').modal('show');
        });

//         $('.zabuto_calendar .calendar-dow .dow-clickable').hover(handlerIn, handlerOut);
    });

    function getDateEvents(id) {
        $('.zabuto_calendar td').children('*').removeClass('clicked');
        var events = JSON.parse($("#events").val());
        var date = '';
        var flag = false;
        if (id === undefined) {
            date = event_class_today;
        } else {
            date = $("#" + id).data("date");
            $("#" + id).children('*').addClass('clicked');
        }
        var selectedDay = date.slice(-2);
        var j = 0;
        $("#calendar-details").html('');
        for (var i = 0; i < events.length; i++) {
            var day = events[i].date.slice(-2);
            if (day === selectedDay) {
                flag = true;
                if (j < 3) {
                    $("#calendar-details").append('<div class="event_time">' +
                            '<span class="pull-left">' + events[i].time + '</span>' +
                            '<p><a href="' + events[i].link + '" title="' + events[i].name + '">' + events[i].name + '</a></p>' +
                            '</div>');
                }
                j++;
            }
        }
        var dateArray = date.split('-');
        var currentDate = new Date(dateArray[0], dateArray[1] - 1, dateArray[2]);
        if (!flag) {
            $("#calendar-details").append('<div class="event_time"><p>No reminder(s) on ' + $.datepicker.formatDate('mm-dd-yy', currentDate)
                    + '</p></div>');
        }
        var usDate = $.datepicker.formatDate('M dd yy', currentDate);
        $("#calendar-details").append('<div ><a class="dashboard_more pull-right" href="/calendar/' + date + '">Go to ' + usDate + '</a></div>');
    }



    function goToCalendar(id) {
        date = $("#" + id).data("date");
        window.location.href = ('/calendar/' + date);
    }

</script>