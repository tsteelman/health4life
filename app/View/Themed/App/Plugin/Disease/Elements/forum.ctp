<div class="col-lg-4 disease_discussion" id="disease_posting">
	<?php echo $this->element('Post.post_content'); ?>
</div>
<div class="col-lg-4 disease_discussion disease_discription" >
    <div class="start_area">
        <div class="event_wraper">
            <h4 class=""><?php echo __('Ask a question, get an answer'); ?></h4>
            <div class="media">
                <div class="media-body">
                    <div class="row">
						<?php
						echo $this->Form->create('Post', array(
							'id' => 'question_form',
							'inputDefaults' => $inputDefaults,
							'method' => 'POST'
						));
						echo $this->Form->hidden('posted_in');
						?>
                        <div class="col-lg-8 col-sm-8 col-md-8">
							<?php echo $this->Form->textarea('question', array('class' => 'disease_question form-control', 'placeholder' => __("What’s Your Question?"))); ?>
						</div>
                        <div class="col-lg-4 col-sm-4 col-md-4">
							<button id="add_question_btn" type="button" data-style="slide-right" data-spinner-color="#3581ED" class="btn btn_active pull-right ladda-button">
								<span class="ladda-label"><?php echo __('Add Question'); ?></span>
								<span class="ladda-spinner"></span>
							</button>
						</div>
                        <div class="col-lg-12" id="question_form_error">
						</div>
						<?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div id="new_questions_notification" class="alert alert-info hide"></div>
    <div id="questions_list"></div>
	<div id="questions_loading">
		<span>
			<?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
			<label>Loading, please wait...</label>
		</span>
	</div>
</div>
