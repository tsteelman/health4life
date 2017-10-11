 <?php  if($question != NULL){
                                $questionId = $question[0]['Survey_question']['id'];
                                $surveyId = $question[0]['Survey']['id'];
                                $answers = $question[0]['Survey_question']['answers'];
                                $options = json_decode($answers, true); ?>
                                <div class="survey_section survey_section_not_attended">
                                    <h3><?php echo __(h($question[0]['Survey_question']['question_text'])) ?>
                                        <?php if($options['required'] == 1) { ?>
                                            <span class ="red_star_span"> *</span>
                                        <?php } ?>
                                    </h3>
                                    <div class="survey_options">
                                        <?php if($options['type'] == 0) { ?>        
                                               <?php  foreach ($options['options'] as $row) { ?>
                                                             <div class="radio">
                                                                <label>
                                                                  <input type="radio" name="optionsRadios" class="optionsRadios" value="<?php echo __(h($row)) ?>">
                                                                        <?php echo __(h($row)) ?>
                                                                </label>
                                                             </div>
                                               <?php  } ?>

                                        <?php } else if($options['type'] == 1) { ?>
                                                <?php  foreach ($options['options'] as $row) { ?>
                                                              <div class="checkbox">
                                                                 <label>
                                                                    <input type="checkbox" value="<?php echo __(h($row)) ?>" class="optionsCheckbox">
                                                                        <?php echo __(h($row)) ?>
                                                                 </label>
                                                               </div> 
                                                <?php  } ?>

                                        <?php } else if($options['type'] == 2) { ?>
                                                 <div class="row clearfix form-group">
                                                        <div class="col-lg-4">
                                                            <input class="form-control textbox_content" placeholder="<?php echo $options['placeHolder'] ?>">
                                                        </div>
                                                 </div>

                                        <?php } else if($options['type'] == 3) { ?>
                                                 <div class="row clearfix form-group">
                                                        <div class="col-lg-5">
                                                            <textarea class="form-control textarea_content" placeholder="<?php echo $options['placeHolder'] ?>"></textarea>
                                                        </div>
                                                 </div>

                                        <?php } else if($options['type'] == 4) { ?>
                                                        <select name="" class="form-control selectBox_content" required="required" data-original-title="" title="">
                                                            <?php if(!empty($options['placeHolder'])) { ?>
                                                                    <option value="" disabled="disabled" selected="selected"><?php echo $options['placeHolder'] ?></option>
                                                            <?php } ?>
                                                            <?php foreach ($options['options'] as $row) { ?>
                                                                <option value="<?php echo __(h($row)) ?>"> <?php echo __(h($row)) ?> </option>
                                                            <?php } ?>
                                                        </select>
                                        <?php } ?>
                                        <div class="block">
                                                <?php if(isset($question[1])) { ?>
                                                    <button id="answer_submit" class="btn btn_active pull-left answer_submit">Next</button>
                                                    <input class="last_que" type ="hidden" value ="<?php echo false ?>">
                                                <?php } else { ?>
                                                    <button id="answer_submit" class="btn btn_active pull-left answer_submit">Finish</button>
                                                    <input class="last_que" type ="hidden" value ="<?php echo true ?>">
                                                <?php } if($options['required'] == 0) { ?>
                                                    <a href="" class="pull-left dnt_know">Skip</a>
                                                <?php } ?>
                                                <input class="type_value" type ="hidden" value ="<?php echo $options['type'] ?>">
                                                <input class="question_id" type ="hidden" value ="<?php echo $questionId ?>">
                                                <input class="survey_id" type ="hidden" value ="<?php echo $surveyId ?>">
                                                <input class="required_value" type ="hidden" value ="<?php echo $options['required'] ?>">
                                        </div>
                                        <div id="survey_error_message" class="alert alert-error" style="display: none;"></div>
                                    </div>
                                </div>
<?php } ?>