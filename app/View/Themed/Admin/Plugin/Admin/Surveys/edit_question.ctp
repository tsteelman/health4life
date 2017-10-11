<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Surveys', '/admin/Surveys');
    $this->Html->addCrumb(__(h($survey['name'])));
?>
<div class="page-content">
  <div class="page-header position-relative">
    <h1>
      <?php echo __(h("Edit Question")); ?>
    </h1>
  </div><!--/.page-header-->
  <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

  <div class="row-fluid">
    <div class="span12">
      <!--PAGE CONTENT BEGINS-->

      <?php
      echo $this->Form->create('Survey', array(
      'action' => 'editQuestion',
      'class' => 'form-horizontal',
      'label' => false,
      'div' => false
      ));
      ?>
      
      <div class="control-group">
        <label class="control-label">Question Text <span style="font-size: 22px; color: #CA1E34">*</span></label>
        <div class="controls">
          <?php
            echo $this->Form->input('question_text', array('class' => 'form-control',
            'id' => 'question_text',
            'label' => false,
            'div' => false,
            'value' => $questionDetails['question_text'],
            'style' => 'width:310px;'));
            echo $this->Form->input("id", array("type" => "hidden", "value" => $questionDetails['id']));
            echo $this->Form->input("surveyId", array("type" => "hidden", "value" => $questionDetails['survey_id']))
          ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Question Type <span style="font-size: 22px; color: #CA1E34">*</span></label>
        <div class="controls">
            <?php
                echo $this->Form->input('question_type', array(
                'id' => 'question_type',
                'class' => 'form-control select_type',
                'value' => $options['type'],
                'options' => array('Multiple choice','Multiple choice with more than 1 answers','Text Box','Text Area','Drop down'),
                'empty' => '(Select Type)',
                'label' => false,
                'div' => false,
                'style' => 'width:325px;'));   
            ?>
        </div>
      </div>
      <div class="control-group <?php if($options['type'] == 2 || $options['type'] == 3) { ?> hide <?php } ?>" id="answer_option">
          <label class="control-label">Answer Options (Enter each option on a separate line) <span style="font-size: 22px; color: #CA1E34">*</span></label>
          <div class="controls">
              <textarea id ="answer_options" style ="width:312px; height:100px;" name="answer_options"><?php if(isset($options['options'])) { foreach ($options['options'] as $row){
                  echo $row;
                } } ?>
              </textarea>
          </div>
      </div>
      <div class="control-group">
        <label class="control-label">Answer Required?</label>
        <div class="controls">
            <?php
                echo $this->Form->input('required', array(
                'id' => 'required',
                'class' => 'form-control select_type',
                'value' => $options['required'],
                'options' => array('No','Yes'),
                'label' => false,
                'div' => false,
                'style' => 'width:325px;'));   
            ?>
        </div>
      </div>
      <div class="control-group  <?php if($options['type'] == 0 || $options['type'] == 1) { ?> hide <?php } ?>" id="place_holder">
        <label class="control-label">Place Holder (Optional)</label>
        <div class="controls">
          <?php
          echo $this->Form->input('place_holder', array('class' => 'form-control',
          'id' => 'placeHolder',  
          'value' => $options['placeHolder'],
          'label' => false,
          'div' => false,
          'style' => 'width:310px;'));
          ?>
        </div>
      </div>
      <div class="controls hide" id="error_msg" style="color: red; font-size: 16px;"></div>
      <hr/>
      
      <div class="modal-footer">
        <?php $options = array('label' => 'Save', 'class' => 'btn btn-primary question_edit'); ?>
        <?php echo $this->Form->end($options); ?>
      </div>
      
      <div style="display:none;" id="description_content">
        <?php echo $survey_details['description']; ?>
      </div>
      
    </div><!--/.page-content-->
  </div> 
</div>
  <script type="text/javascript" src="/theme/App/js/vendor/fineuploader-4.0.2.min.js"></script>
  <script type="text/javascript" src="/theme/Admin/js/image_upload.js"></script>
  <link rel="stylesheet" href="/theme/App/css/fineuploader-4.0.2.min.css"/>
  <script type="text/javascript">

    $(document).ready(function() {
      $('#editor1').ace_wysiwyg({
        speech_button: false,
        toolbar:
                [
                  'font',
                  null,
                  'fontSize',
                  null,
                  {name: 'bold', className: 'btn-info'},
                  {name: 'italic', className: 'btn-info'},
                  {name: 'strikethrough', className: 'btn-info'},
                  {name: 'underline', className: 'btn-info'},
                  null,
                  {name: 'insertunorderedlist', className: 'btn-success'},
                  {name: 'insertorderedlist', className: 'btn-success'},
                  {name: 'outdent', className: 'btn-purple'},
                  {name: 'indent', className: 'btn-purple'},
                  null,
                  {name: 'justifyleft', className: 'btn-primary'},
                  {name: 'justifycenter', className: 'btn-primary'},
                  {name: 'justifyright', className: 'btn-primary'},
                  {name: 'justifyfull', className: 'btn-inverse'},
                  null,
                  //{name: 'createLink', className: 'btn-pink'},
                  //{name: 'unlink', className: 'btn-pink'},
                  null,
                  null,
                  'foreColor',
                  null,
                  {name: 'undo', className: 'btn-grey'},
                  {name: 'redo', className: 'btn-grey'}
                ]
      }).prev().addClass('wysiwyg-toolbar center');
      initEventPhotoUploader();
      $('#editor1').html($('#description_content').text());
      $("ul.nav-list li").removeClass('active');
      $('#survey-list-li').addClass('active');
      
      $( ".select_type" ).change(function() {
            type = $('#question_type').val();
            if(type == 0 || type == 1 || type == 4) {
                $("#answer_option").show();
            } else {
                $("#answer_option").hide();
            }
      });
    });
    
    $(document).on('click', '.question_edit', function() {
      type = $('#question_type').val();
      option = $.trim($('#answer_options').val());
      if(type.length == 0) {
            $('#error_msg').text("Please select the question type").show();
            return false;
      }
      if(type == 0 || type == 1 || type == 4) {
        if(option.length == 0) {
            $('#error_msg').text("Please list the options").show();
            return false;
        }
        var multiple = /\r|\n/.exec(option); // checks whether there are more than one options.
        if (!multiple) {
              $('#error_msg').text("Please list more than one options").show();
              return false;
        }
      }
   });
    
  </script>


