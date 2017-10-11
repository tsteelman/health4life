<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb(h('My Health'), '/profile/myhealth');
if(!empty($notAttendedQuestions)) {
    $this->Html->addCrumb(__(h($notAttendedQuestions[0]['Survey']['name'])));
} else {
    $this->Html->addCrumb(__(h($attendedQuestions[0]['Survey']['name'])));
}
?>

<div class="container">
    <div class="survey">
        <div id="attended_questions_area">
            <?php if($completedStatus) { ?>
                <h3 class="owner" style="text-align: center">You have completed this survey.</h3>
            <?php } else { ?>
                <?php if (isset($attendedQuestions) && $attendedQuestions != NULL) { ?>
                                            <!--  Survey Form -->
                                                <?php echo $this->element('survey_ques_attended'); ?>
                                            <!--  Survey Form -->
                <?php  } ?>
        </div>
        <div id="new_question">
                <?php if($attendedQuestions == NULL){ ?>
                    <h2 class="owner"><?php echo __(h($notAttendedQuestions[0]['Survey']['name'])) ?> </h2>
                <?php } if (isset($notAttendedQuestions) && $notAttendedQuestions != NULL) { ?>
                                        <!--  Survey Form -->
                                            <?php echo $this->element('survey_ques_new', array('question' => $notAttendedQuestions)); ?>
                                        <!--  Survey Form -->
                <?php } } ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.answer_submit', function() {
       var type = $(this).siblings(".type_value").val();
       var question_id = $(this).siblings(".question_id").val();
       var survey_id = $(this).siblings(".survey_id").val();
       var last = $(this).siblings(".last_que").val();
       var value;
       if(type == 0) {
            if($("input[type='radio'].optionsRadios").is(':checked')) {
                 value = $(this).parent().siblings().find($("input[type='radio'].optionsRadios:checked")).val();
            }
       } else if(type == 1) {
             var value = [];
             $(this).parent().siblings().find($('input:checkbox[class=optionsCheckbox]')).each(function()
             {    
                  if($(this).is(':checked')) {
                  value.push($(this).val());
                  }
             });
       } else if(type == 2) {
             value = $(this).parent().siblings().find($(".textbox_content")).val();
       } else if(type == 3) {
             value = $(this).parent().siblings().find($(".textarea_content")).val();
       } else if(type == 4) {
             value = $(this).parent().siblings().find($(".selectBox_content option:selected")).val();
       }
       if(value == undefined || value.length == 0){
           $('#survey_error_message').text("Please answer this question.").show();
           return false;
       }
       $.ajax({
            url: '/survey/survey/saveSurveyResult',
            type: 'POST',
            dataType: 'json',
            //data: $('.survey_form').serialize(),
            data : { 'questionId' : question_id , 'optionSelected' : value, 'surveyId' : survey_id, 'lastStatus' : last},
            success: function(result) {
                 if (result.success == true) {
                     if(last == true) {
                         window.location.href = '/profile/myhealth';
                     }
                     $("#new_question").html(result.newContent);
                     $("#attended_questions_area").html(result.attendedContent);
                     var scrollBottom = $(window).scrollTop() + $(window).height();
                     $("html, body").animate({ scrollTop: scrollBottom }, 500, "easeInOutQuart" );
//                     $(".survey_section").removeClass('survey_section_not_attended');
//                     $(".survey_section").addClass('survey_section_radio');
//                     $(".answer_submit").text('Update');
//                     $(".dnt_know").hide();
                     
                 } 
            }
        });
    });
    
    $(document).on('click', '.dnt_know', function(event) {
        event.preventDefault();
        var question_id = $(this).siblings(".question_id").val();
        var survey_id = $(this).siblings(".survey_id").val();
        var value = " ";
        var last = $(this).siblings(".last_que").val();
        $.ajax({
            url: '/survey/survey/saveSurveyResult',
            type: 'POST',
            dataType: 'json',
            data : { 'questionId' : question_id , 'optionSelected' : value, 'surveyId' : survey_id, 'lastStatus' : last},
            success: function(result) {
                 if (result.success == true) {
                    $("#new_question").html(result.newContent);
                    $("#attended_questions_area").html(result.attendedContent);
                    var scrollBottom = $(window).scrollTop() + $(window).height();
                    $("html, body").animate({ scrollTop: scrollBottom }, 500, "easeInOutQuart" );
                    if(last == true) {
                         window.location.href = '/profile/myhealth';
                    }
                 } 
            }
        });
    });
    
    $(document).on('click', '.update_option', function(event) {
         event.preventDefault();
         updateStatusRotate = Ladda.create(this);
         var result_id = $(this).siblings(".result_id").val();
         var type = $(this).siblings(".type_value").val();
         var value;
            if(type == 0) {
                 if($("input[type='radio'].optionsRadios").is(':checked')) {
                      value = $(this).parent().siblings().find($("input[type='radio'].optionsRadios:checked")).val();
                 }
            } else if(type == 1) {
                  var value = [];
                  $(this).parent().siblings().find($('input:checkbox[class=optionsCheckbox]')).each(function()
                  {    
                       if($(this).is(':checked')) {
                       value.push($(this).val());
                       }
                  });
            } else if(type == 2) {
                  value = $(this).parent().siblings().find($(".textbox_content")).val();
            } else if(type == 3) {
                  value = $(this).parent().siblings().find($(".textarea_content")).val();
            } else if(type == 4) {
                  value = $(this).parent().siblings().find($(".selectBox_content option:selected")).val();
            }
            $.ajax({
            url: '/survey/survey/updateOption',
            type: 'POST',
            dataType: 'json',
            data : { 'resultId' : result_id, 'optionUpdated' : value },
            beforeSend: function() {
                updateStatusRotate.start();
            },
            success: function(result) {
                 if (result.success == true) {
                     updateStatusRotate.stop();
                     $(".answer_submit").focus();
                     var scrollBottom = $(window).scrollTop() + $(window).height();
                     $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                 } 
            }
        });
        
    });
    
</script>
       