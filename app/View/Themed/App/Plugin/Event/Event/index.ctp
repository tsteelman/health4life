<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Events');
?>
<div class="container event_main_page_listing">
    <div class="event">
        <div class="row mr_0">

            <div class="col-lg-9">
                <div id="myEvents" class="event_list">
                    <div class="page-header">
                        <h3 class="pull-left" >My Events</h3>
                        <a id="createButton" href="/event/add"  class="pull-right btn create_button <?php echo empty($eventsMy)? ' hidden': ''; ?> ">Create New Event</a>
                    </div>
                    <div class="content">
                        <?php 
                            $events['nextPage'] = '';
                            $events['pageCount'] = $pageCountArray[1];
                            $events['events'] = $eventsMy;
                            $events['event_type'] = 1;
                            echo $this->element('Event.events_row', $events);
                        ?>

                        <!--<center><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></center>-->
                    </div>
                </div>

                <div id="pendingEvents" class="event_list <?php echo empty($eventsPending) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3>Pending Events</h3>
                    </div>
                    <div class="content">
                        <?php
                            $events['pageCount'] = $pageCountArray[2];
                            $events['events'] = $eventsPending;
                            $events['event_type'] = 2;
                            echo $this->element('Event.events_row', $events);
                        ?>
                        <!--<center><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></center>-->
                    </div>
                </div>

                <div id="upcomingEvents" class="event_list <?php echo empty($eventsUp) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3>Upcoming Events</h3>
                    </div>
                    <div class="content">
                        <?php 
                            $events['pageCount'] = $pageCountArray[3];
                            $events['events'] = $eventsUp;
                            $events['event_type'] = 3;
                            echo $this->element('Event.events_row', $events);
                        ?>
                        <!--<center><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></center>-->
                    </div>
                </div>

                <div id="interestEvents" class="event_list <?php echo empty($eventsInterested) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3>Events you might be interested in</h3>
                    </div>
                    <div class="content">
                        <?php 
                          $events['pageCount'] = $pageCountArray[4]; 
                          $events['events'] = $eventsInterested;
                          $events['event_type'] = 4;
                            echo $this->element('Event.events_row', $events);
                        ?>
                    </div>
                </div>

                <div id="pastEvents" class="event_list <?php echo empty($eventsPast) ? ' hidden': '';?> ">
                    <div class="page-header">
                        <h3>Past Events</h3>
                    </div>
                    <div class="content">
                        <?php 
                          $events['pageCount'] = $pageCountArray[5];  
                          $events['events'] = $eventsPast;
                          $events['event_type'] = 5;
                          echo $this->element('Event.events_row', $events);
                        ?>
                        <!--<center><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></center>-->
                    </div>
                </div>

            </div>
        <!--</div>-->
            <?php echo $this->element('layout/rhs', array('list' => true)); ?>

        </div>
    </div>
<?php
$this->AssetCompress->script('events', array('block' => 'scriptBottom'));
?>
<script>
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
                url: '/event/event/index/' + type + '/page:' + page,
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

</script>