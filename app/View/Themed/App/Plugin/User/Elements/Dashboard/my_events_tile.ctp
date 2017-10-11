<div class="my_events">
    <a href="/event" class="dashboard_header_link"> 
        <div class="dashboard_header"><?php echo __('Events'); ?></div>
    </a>
    <div class="tile_content my_communities_tile">
	<?php
            if (! empty ( $myEvents )) {
                $i = 0;
                foreach ( $myEvents as $event ) {
	?>
                    <div class="media" <?php if($i >= 3) echo 'style="display:none"'; ?>>
                        <a class="pull-left " href="<?php echo '/event/details/index/' . $event['Event']['id']; ?>">
                                <?php echo $this->Html->image(Common::getEventThumb($event['Event']['id']), array('class' => 'media-object', 'height'=>40)); ?>
                        </a>
                        <div class="media-body">
                            <h5><a href="<?php echo '/event/details/index/' . $event['Event']['id']; ?>"><?php echo __( h( $event['Event']['name']));?></a></h5>
                            <p><?php 
                            if (isset($event['Event']['start_date'])) {
                             echo __(CakeTime::nice($event['Event']['start_date'], $timezone, ' %b %e, %l:%M %p'));
                            }
                            ?>
                            </p>
                        </div>
                    </div>
			
	<?php                $i++;
                } // end  foreach
                    if(isset($myEvents[3])) { ?>
                        <a href = "/event"  class="dashboard_more pull-right"><?php echo __('more'); ?></a>
        <?php       }           
		} else {
	?>
		<div class="media">
			<h4> <?php echo __('There are no events, create one or join');?> </h4>
		</div>
	<?php 
		} 
	?>
    </div>
</div>


<script type="text/javascript">
    $('#dashboard_events_more').on('click', function() {
        $('.my_events .my_events_tile .media').show();
        $('#dashboard_events_more').remove();
    });
</script>