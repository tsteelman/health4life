<?php $this->Html->addCrumb('Events'); ?>

<div id="disease_events_list">
<!--    <div class="page-header">
        <h3>Events</h3>
    </div>-->
    <div id="event_list">
        <div class="text-center"><?php //echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
        <?php echo $this->element('Disease.events_row'); ?>
    </div>
    <?php $nextPage = $this->Paginator->param('nextPage'); 
        $pageCount = $this->Paginator->param('pageCount');
        if(empty($nextPage) || !isset($nextPage)) { 
                if(isset($pageCount) && $pageCount > 1) {
                    $nextPage = 2; 
                }
            }
            if(!empty($nextPage)) {
        ?>
            <div id="more_button" class="block" style="margin-right:15px;">
                <a href="javascript:load_events(<?php echo $diseaseId; ?>, <?php echo $this->Paginator->param('page')+1;?>, '<?php echo Configure::read('Url.condition'); ?>')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED">
                        <span class="ladda-label"><?php echo __('More') ; ?></span>
                </a>
            </div>
        <?php
        }
    ?>
</div>

<script>
    $(function() {
        //load_events(<?php echo $diseaseId; ?>, '1', <?php echo Configure::read('Url.condition'); ?>);        
    });
</script>