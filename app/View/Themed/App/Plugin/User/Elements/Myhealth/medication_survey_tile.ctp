<!-- Modal -->
<div class="modal fade" id="medicationSurveyList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">You have following questionaire(s) to be completed regarding your Medications:</h4>
            </div>
            <div class="modal-body">
                <div class="disease_info_popup">    
                    <?php foreach($medicationSurvey as $survey) { ?>
                            <div class="clearfix">
                                <a <?php if($survey['completedStatus'] == false){ ?>
                                    href ="/survey/index/<?php echo $survey['surveyKey'] ?>" <?php } ?>>
                                    <div class="disease_div">
                                        <span>
                                            <?php echo $survey['name']; ?> 
                                        </span>
                                    </div>
                                </a>
                                <?php if($survey['completedStatus'] == true){ ?>
                                    <div class="disease_status_div disese_info_completed" title="Completed"> </div>                           
                                <?php } else { ?>
                                    <div class="disease_status_div disese_info_notcompleted" title="Pending"> </div>                           
                                <?php } ?>
                            </div> 
                    <?php } ?>

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

