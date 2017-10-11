<div class="my_community">
    <a href="/community" class="dashboard_header_link">
        <div class="dashboard_header"><?php echo __('Communities'); ?></div>
    </a>
    <div class="tile_content my_communities_tile">
            <?php
                if (! empty ( $myCommunity )) {
                    $i = 0;
                    foreach ( $myCommunity as $community ) {

            ?>              
                        <div class="media" <?php if($i >= 3) echo 'style="display:none"'; ?> >
                            <a class="pull-left " href="<?php echo '/community/details/index/' . $community['Community']['id']; ?>">
                                    <?php echo $this->Html->image(Common::getCommunityThumb($community['Community']['id']), array('class' => 'media-object', 'height'=>40)); ?>
                            </a>
                            <div class="media-body">
                                <h5><a href="<?php echo '/community/details/index/' . $community['Community']['id']; ?>"><?php echo __( h( $community['Community']['name']));?></a></h5>
                                <p><?php echo __('Members') . " (" . $community['Community']['member_count'] . ")";  ?>
                                </p>
                            </div>
                        </div>

            <?php 
                        $i++;

                    } // end  foreach
                    if(isset($myCommunity[3])) { ?>
                        <a href = "/community" class="dashboard_more pull-right"><?php echo __('more'); ?></a>
            <?php   }             
                } else {
            ?>
                <div class="media">
                    <h4> <?php echo __('There are no community, create one or join');?> </h4>
                </div>
            <?php 
                } 
            ?>            
    </div>
</div>

<script type="text/javascript">
    $('#dashboard_communities_more').on('click', function() {
        $('.my_community .my_communities_tile .media').show();
        $('#dashboard_communities_more').remove();
    });
</script>