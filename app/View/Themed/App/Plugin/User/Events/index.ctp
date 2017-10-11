<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Events');
?>
<?php $this->extend('Profile/view'); ?>
<div id="myEvents" class="event_list">
    <div class="content">
        <?php echo $this->element('Event.events_row'); ?>
    </div>
</div>
<!--<script>
$(function() {
    load_events_list(8);
    load_events_list(9);
});
function load_events_list(type, page) {
    var id;
    if (typeof(page) === "undefined") {
        page = 1;
    }
    if (page !== 1) {
        var l = Ladda.create(document.querySelector('#load-more' + type));
        l.start();
    }
    switch (type) {
        case 1:
            id = "myEvents";
            break;
        case 2:
            id = 'pendingEvents';
            break;
        case 3:
            id = 'upcomingEvents';
            break;
        case 4:
            id = 'interestEvents';
            break;
        case 5:
            id = 'pastEvents';
            break;
    }
    load_events_ajax(type, id, page, l);
}

function load_events_ajax(type, id, page, l) {
    setTimeout(function() {
        $.ajax({
            url: '/profile/view/index/' + type + '/page:' + page,
            success: function(result) {
                if (page === 1) {
                    $('#' + id + ' .content').html(result);
                    if ($('#myEvents .indvdl_event').length) {
                        $("#createButton").removeClass('hidden');
                    }
                    $('#' + id + ':has(.indvdl_event)').removeClass('hidden');
                } else {
                    $('#' + id + ' .content').append(result);
                }
                applyHoverEffect();
            }
        }).always(function() {
            if (page !== 1) {
                l.stop();
                $("#" + id + " #more_button" + type + page).remove();
            }
        });
    }, 1000);
}
</script>-->