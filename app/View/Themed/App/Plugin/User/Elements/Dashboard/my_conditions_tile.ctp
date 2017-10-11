<div class="my_activity">
    <a href="/profile/mycondition" class="dashboard_header_link">
        <div class="dashboard_header">Conditions</div>
    </a>
    <div class="tile_content my_condition_tile">
                <?php
                    if (!empty($myDiseases)) {
                        $i = 0;
                        foreach ($myDiseases as $disease) {
                            ?>
                    <div class="media"  <?php if($i >= 3) echo 'style="display:none"'; ?> >
                                    <div class="media-body">
                                        <h5><a href= "<?php echo Configure::read('Url.condition') ?>index/<?php echo $disease['Diseases']['id']; ?>"><?php echo __(h($disease['Diseases']['name'])); ?></a></h5>
                                    </div>
                                </div>
                         <?php $i++;                                 
                         } if(isset($myDiseases[3])) { ?>
                                <a href = "javascript:void(0);" id="conditions_more" class="dashboard_more pull-right"><?php echo __('more'); ?></a>                                    
                        <?php } 
                            }else { ?>
                                <div class="media">
                                    <h4> <?php echo __('There are no conditions');?> </h4>
                                </div>
                <?php } ?>              

        </div>
</div>     	 
<script type="text/javascript">
    $('#conditions_more').on('click', function() {
        $('.my_condition_tile .media').show();
        $('#conditions_more').remove();
    });
</script>