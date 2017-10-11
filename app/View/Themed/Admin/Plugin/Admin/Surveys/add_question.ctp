<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Surveys', '/admin/Surveys');
    $this->Html->addCrumb(__(h($surveyName)));
?>
<div class="page-content">
  <div class="page-header position-relative">
    <h1>
      <?php echo __(h("Add Questions - ".$surveyName)); 
        if($status == 0 && !empty($question_list)) { ?>
            <button class="btn btn-primary" id="publish_survey" style='float: right;' data-backdrop="static" data-keyboard="false">
                      <?php echo __("Publish"); ?>  
            </button>
      <?php } ?>
    </h1>
  </div><!--/.page-header-->
  <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

  <div class="row-fluid">
    <div class="span12">
         <?php
          echo $this->Form->input("surveyId", array("type" => "hidden", "value" => $surveyId, "id" => "surveyId"));
          if($status == 0) { ?>
      <!--PAGE CONTENT BEGINS-->
      <div class="control-group">
        <div class="controls">
            <?php
                echo $this->Form->button('Add new Question', array('class' => 'btn btn-primary',
                'label' => 'Add new Question',
                'div' => false,
                'id' => 'add_new_question',
                'onclick' => 'showQuestionBox();',
                'style' => 'width:150px;'));
            ?>
        </div>
      </div>
        <?php } ?>
      <br>
      <?php
      if (isset($question_list) && $question_list != NULL) {
            $i = 1;
            foreach ($question_list as $row) {
                 $answers = $row['SurveyQuestion']['answers'];
                 $options = json_decode($answers, true);
                 ?>
              <!--<div style="border:2px solid #a1a1a1; border-radius:25px;">-->
                <div>
                    <span style="font-size: 16px; color: #00008B;"> <?php echo __(h('Q'.$i.'.')) ?> <?php echo __(h($row['SurveyQuestion']['question_text'])) ?> </span>
                    <span style="font-size: 24px; color: #CA1E34"><?php if($options['required'] == 1) { echo __('*'); } ?></span>
                    <?php if($status == 0) { ?>
                        <a class="green" href="/admin/Surveys/EditQuestion/<?php echo $row['SurveyQuestion']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="Edit Question" questionId="<?php echo $row['SurveyQuestion']['id']; ?>">
                              <i class="icon-pencil bigger-130"></i>
                        </a>
                        <a href="/admin/Surveys/deleteQuestion/<?php echo $row['SurveyQuestion']['id']; ?>/<?php echo $row['SurveyQuestion']['survey_id']; ?>" class="red" data-rel="tooltip" title="Delete Question" onclick="return confirm('Are you sure you want to delete this question?');">
                              <i class="icon-trash bigger-130"></i>
                        </a>
                    <?php } ?>
                </div>
                <br/> 
                       <?php   if($options['type'] == 0) { ?>        
                                <form> 
                                        <?php  foreach ($options['options'] as $row) { ?>
                                    
                                            <div> <input type="radio"> <label class="lbl"> <?php echo __(h($row)); ?> </label> </div>
                                        
                                        <?php  } ?>
                                </form>
                        <?php } else if($options['type'] == 1) { ?>
                                <form>
                                        <?php  foreach ($options['options'] as $row) { ?>

                                            <div> <input type="checkbox"> <label class="lbl"> <?php echo __(h($row)) ?> </label> </div>

                                        <?php  } ?>
                                </form>
                        <?php } else if($options['type'] == 2) { ?>
                                <form>

                                            <div> <input type="text" placeholder="<?php echo __(h($options['placeHolder'])) ?>" /> </div>

                                </form>
                        <?php } else if($options['type'] == 3) { ?>
                                <form>

                                             <div><textarea placeholder="<?php echo __(h($options['placeHolder'])) ?>"></textarea></div>

                                </form>
                        <?php } else if($options['type'] == 4) { ?>
                                <form>
                                        <select>
                                                <?php if(!empty($options['placeHolder'])) { ?>
                                                            <option value="" disabled="disabled" selected="selected"><?php echo $options['placeHolder'] ?></option>
                                                <?php } ?>
                                                <?php foreach ($options['options'] as $row) { ?>
                                                        <option> <?php echo __(h($row)) ?> </option>
                                                <?php } ?>
                                        </select>
                                </form>
           <?php } $i++; ?>
              <!--</div>-->
    <?php } }?>
     
      <hr/>
      
      <div class="pagination pagination-small" style='float: right;'>
                    <ul>
                        <?php
                        if ($this->Paginator->numbers()) {
                            echo $this->Paginator->prev(__('<<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                            echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
                            echo $this->Paginator->next(__('>>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                        }
                        ?>
                    </ul>
      </div>
      <?php echo $this->Form->end(); ?>

    </div><!--/.page-content-->
  </div> 
</div>

<div id="question-add-form" class="modal hide" tabindex="-1">
    <?php
    echo $this->Form->create('Survey', array(
        'action' => 'addQuestion',
        'class' => 'form-horizontal',
        'label' => false,
        'div' => false,
        'onsubmit' => 'return disableButton()'
    ));
    ?>
    <div class="modal-header header-color-blue">
        <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="white bigger"><?php echo __('Add Question'); ?></h4>
    </div>
    <div class="modal-body overflow-visible">

     <div class="control-group">
        <label class="control-label">Question Text <span style="font-size: 22px; color: #CA1E34">*</span></label>
        <div class="controls">
          <?php
            echo $this->Form->input('question_text', array('class' => 'form-control',
            'id' => 'question_text',
            'label' => false,
            'div' => false,
            'style' => 'width:310px;'));
            echo $this->Form->input("id", array("type" => "hidden", "value" => $surveyId));
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
                'options' => array('Multiple choice','Multiple choice with more than 1 answers','Text Box','Text Area','Drop down'),
                'empty' => '(Select Type)',
                'label' => false,
                'div' => false,
                'style' => 'width:325px;'));   
            ?>
        </div>
      </div>
      <div class="control-group hide" id="answer_option">
        <label class="control-label">Answer Options (Enter each option on a separate line) <span style="font-size: 22px; color: #CA1E34">*</span></label>
        <div class="controls">
          <?php
          echo $this->Form->textarea('answer_option', array('class' => 'form-control',
          'id' => 'answer_options',  
          'label' => false,
          'div' => false,
          'style' => 'width:312px; height:100px;'));
          ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Answer Required?</label>
        <div class="controls">
            <?php
                echo $this->Form->input('required', array(
                'id' => 'required',
                'class' => 'form-control select_type',
                'options' => array('No','Yes'),
                'label' => false,
                'div' => false,
                'style' => 'width:325px;'));   
            ?>
        </div>
      </div>
      <div class="control-group hide" id="place_holder">
        <label class="control-label">Place Holder (Optional)</label>
        <div class="controls">
          <?php
          echo $this->Form->input('place_holder', array('class' => 'form-control',
          'id' => 'placeHolder',  
          'label' => false,
          'div' => false,
          'style' => 'width:310px;'));
          ?>
        </div>
      </div>
      <div class="controls hide" id="error_msg" style="color: red; font-size: 16px;"></div>
    </div>
    <div class="modal-footer">
        <div class="modal-footer">
            <?php $options = array('label' => 'Save', 'class' => 'btn btn-primary question_save'); ?>
            <?php echo $this->Form->end($options); ?>
        </div>
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
      $('#editor1').html($('#DiseaseDescription').val());
      $("ul.nav-list li").removeClass('active');
      $('#survey-list-li').addClass('active');
      
      $( ".select_type" ).change(function() {
            type = $('#question_type').val();
            if(type == 0 || type == 1 || type == 4) {
                $("#answer_option").show();
            } else {
                $("#answer_option").hide();
            }
            if(type == 2 || type == 3 || type == 4) {
                 $("#place_holder").show();
            } else {
                $("#place_holder").hide();
            }
      });
    });

   function showQuestionBox() {
     $("#question-add-form").modal("show");
     $('#question_text').val('');
     $('#question_type').val('');
     $('#answer_options').val('');
     $("#placeHolder").val('');
   }
   
   function disableButton() { 
       $(".question_save").attr("disabled","disabled");
   }
   
   $(document).on('click', '.question_save', function() {
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
   
   $(document).on('click', '#publish_survey', function() {
        var survey_id = $('#surveyId').val();
        $.ajax({
            url: '/admin/surveys/publishSurvey',
            type: 'POST',
            dataType: 'json',
            data : { 'surveyId' : survey_id },
            success: function(result) {
                 if (result.success == true) {
                     window.location.reload(true);
                 }
            }
        });
   });
   
  </script>


