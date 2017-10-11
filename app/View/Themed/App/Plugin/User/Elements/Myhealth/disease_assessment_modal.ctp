<div class="modal fade" id="disease_assessment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Disease Assessment</h4>
      </div>
      <div class="modal-body">
            <?php echo $this->element('User.Myhealth/health_survey_widget'); ?>
      </div>      
    </div>
  </div>
</div>