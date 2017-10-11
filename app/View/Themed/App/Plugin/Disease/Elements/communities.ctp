<?php $this->Html->addCrumb('Communities'); ?>

<div id="diseaseCommunity" class="event_list">
<!--    <div class="page-header">
        <h3 class="pull-left"><?php echo __('Communities'); ?></h3>
        <a id="createButton" href="/community/add"  class="pull-right btn create_button hidden"><?php echo __('Create a New Community'); ?></a>
    </div> -->


    <div id="diseaseCommunityList" class="group_list">
        <div class="text-center">
            <?php //echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?>
            <?php echo $this->element('Disease.community_row'); ?>
            
            
        </div>
    </div>
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
        <div id="more_button" class="block">
            <a href="javascript:load_disease_communities(<?php echo $disease_id;?>, <?php echo $this->Paginator->param('page')+1;?>, '/condition/')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED">
                <span class="ladda-label"><?php echo __('More') ; ?></span>
            </a>
        </div>
    <?php
    }
    ?>


<script>
    $(function() {
        //load_disease_communities(<?php echo $diseaseId; ?>, '1', <?php echo Configure::read('Url.condition'); ?>);
    });
</script>