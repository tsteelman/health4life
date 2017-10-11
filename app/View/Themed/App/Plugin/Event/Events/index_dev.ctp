<div id="myEvents" class ="row-fluid">
    <p class="breadcrumb">My Events</p>
    <div class="row">
        <?php
        if (!empty($userEvents)) {
            foreach ($userEvents as $event) {
                ?>
                <a href="events/view/<?php echo $event['id']; ?>">
                    <div class="col-sm-2 col-md-2">
                        <div class="thumbnail">
        <?php echo $this->Html->image('default_event_image.png'); ?>
                            <div class="caption">
                                Event Name: <?php echo $event['name']; ?><br>
                                Event start: <?php echo $event['start_date']; ?>
                            </div>
                        </div>
                    </div>
                </a>

                <?php
            }
        } else {
            ?>
            <div class="row" style="margin-left: 5px;margin-right: 5px;">
                <div class="thumbnail">
                    <center>No Events found</center>
                </div>
            </div>
<?php } ?>
    </div>

</div>

<br>
<br>

<div id="pendingEvents" class ="row-fluid">
    <p class="breadcrumb">Pending Events</p>
    <div class="row">
        <?php
        if (!empty($pendingEvents)) {
            foreach ($pendingEvents as $event) {
                ?>
                <a href="events/view/<?php echo $event['id']; ?>">
                    <div class="col-sm-2 col-md-2">
                        <div class="thumbnail">
        <?php echo $this->Html->image('default_event_image.png'); ?>
                            <div class="caption">
                                Event Name: <?php echo $event['name']; ?><br>
                                Event start: <?php echo $event['start_date']; ?>
                            </div>
                        </div>
                    </div>
                </a>

                <?php
            }
        } else {
            ?>
            <div class="row" style="margin-left: 5px;margin-right: 5px;">
                <div class="thumbnail">
                    <center>No Events found</center>
                </div>
            </div>
<?php } ?>

    </div>
</div>

<br>
<br>

<div id="upcomingEvents" class ="row-fluid">
    <p class="breadcrumb">Upcoming Events</p>
    <div class="row">
        <?php
        if (!empty($upcomingEvents)) {
            foreach ($upcomingEvents as $event) {
                ?>
                <a href="events/view/<?php echo $event['id']; ?>">
                    <div class="col-sm-2 col-md-2">
                        <div class="thumbnail">
        <?php echo $this->Html->image('default_event_image.png'); ?>
                            <div class="caption">
                                Event Name: <?php echo $event['name']; ?><br>
                                Event start: <?php echo $event['start_date']; ?>
                            </div>
                        </div>
                    </div>
                </a>

                <?php
            }
        } else {
            ?>
            <div class="row" style="margin-left: 5px;margin-right: 5px;">
                <div class="thumbnail">
                    <center>No Events found</center>
                </div>
            </div>
<?php } ?>
    </div>

</div>

<br>
<br>

<div id="interestEvents" class ="row-fluid">
    <p class="breadcrumb">Events you might be interested in</p>
    <div class="row">
<?php
if (!empty($interestingEvents)) {
    foreach ($interestingEvents as $event) {
        ?>
                <a href="events/view/<?php echo $event['id']; ?>">

                    <div class="col-sm-2 col-md-2">
                        <div class="thumbnail">
        <?php echo $this->Html->image('default_event_image.png'); ?>
                            <div class="caption">
                                Event Name: <?php echo $event['name']; ?><br>
                                Event start: <?php echo $event['start_date']; ?>
                            </div>
                        </div>
                    </div>
                </a>

        <?php
    }
} else {
    ?>
            <div class="row" style="margin-left: 5px;margin-right: 5px;">
                <div class="thumbnail">
                    <center>No Events found</center>
                </div>
            </div>
<?php } ?>
    </div>

</div>

<br>
<br>

<div id="pastEvents" class ="row-fluid">
    <p class="breadcrumb">Past Events</p>
    <div class="row">
<?php
if (!empty($pastEvents)) {
    foreach ($pastEvents as $event) {
        ?>
                <a href="events/view/<?php echo $event['id']; ?>">
                    <div class="col-sm-2 col-md-2">
                        <div class="thumbnail">
        <?php echo $this->Html->image('default_event_image.png'); ?>
                            <div class="caption">
                                Event Name: <?php echo $event['name']; ?><br>
                                Event start: <?php echo $event['start_date']; ?>
                            </div>
                        </div>
                    </div>
                </a>

        <?php
    }
} else {
    ?>
            <div class="row" style="margin-left: 5px;margin-right: 5px;">
                <div class="thumbnail">
                    <center>No Events found</center>
                </div>
            </div>
<?php } ?>

    </div>
</div>