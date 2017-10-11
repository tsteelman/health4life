<div id="posting_poll_form" class="posting_options_form"  style="display:none;">
    <div class="start_area row posting_option_box">
        <div  class="col-lg-12" id="ask_section">
            <div class="form-group">
                <?php echo $this->Form->textarea('poll_title', array('class' => 'form-control poll_title poll_question', 'placeholder' => 'Ask something...', 'id' => 'PollForm_title')); ?>
            </div>
            <div id="option_area" >
                <div class="poll_inputarea form-group input-prepend">
                    <input class="poll_input" placeholder="Option" name="data[Post][poll_options][]" type="text">                
                </div>
                <div class="poll_inputarea form-group">
                    <input class="poll_input" placeholder="Option" name="data[Post][poll_options][]" type="text">                
                </div>
                <div class="poll_inputarea  form-group">
                    <input class="poll_input" placeholder="+ Add New Option" name="data[Post][poll_options][]" type="text">                
                </div>             
            </div>
            <div class="profileFormError errorMessage" id="PollFormErrorMessage" style="display:none"></div>
        </div>
    </div>
</div>
<?php echo $this->jQValidator->validator(); ?>
<script>
    jQuery(window).on('load', function() {
        var poll_poll_widget = $("#ask_section").mypoll(Poll);
    });
</script>