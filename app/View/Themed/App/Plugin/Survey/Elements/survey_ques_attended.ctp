<h2 class="owner" ><?php echo __(h($attendedQuestions[0]['Survey']['name'])) ?> </h2>

                        <?php foreach ($attendedQuestions as $row) { 
                                $resultId = $row['Survey_result']['id'];
                                $answers = $row['Survey_result']['selected_answers'];
                                $options = json_decode($answers, true); ?>
                                <form>
                                    <div class="survey_section survey_section_radio">
                                        <h3><?php echo __(h($row['Survey_question']['question_text'])) ?>
                                            <?php if($options['required'] == 1) { ?>
                                                <span class ="red_star_span"> *</span>
                                            <?php } ?>
                                        </h3>
                                        <div class="survey_options">
                                        <?php if($options['type'] == 0) { ?>        
                                               <?php  foreach ($options['options'] as $row) { ?>
                                                             <div class="radio">
                                                                <label>
                                                                   <?php $row = str_replace("\r","",$row); 
                                                                         $selected = str_replace("\n","",$options['selected_answer']);
                                                                   ?>
                                                                        <input type="radio" name="optionsRadios" class="optionsRadios" value="<?php echo __(h($row)) ?>"
                                                                           <?php if($row == $selected) { ?> checked <?php } ?> >
                                                                              <?php echo __(h($row)) ?>
                                                                </label>
                                                             </div>
                                               <?php  } ?>

                                        <?php } else if($options['type'] == 1) { ?>
                                                <?php  foreach ($options['options'] as $row) { ?>
                                                              <div class="checkbox">
                                                                 <label>
                                                                    <?php $row = str_replace("\r","",$row); 
                                                                          $selected = str_replace("\n","",$options['selected_answer']);
                                                                    ?>
                                                                            <input type="checkbox" value="<?php echo __(h($row)) ?>" class="optionsCheckbox" 
                                                                               <?php if(!empty($selected) && $selected != " ") {     
                                                                                    if (in_array($row, $selected)) { ?> checked <?php } } ?> >
                                                                                  <?php echo __(h($row)) ?>
                                                                 </label>
                                                               </div> 
                                                <?php  } ?>

                                        <?php } else if($options['type'] == 2) { ?>
                                                 <div class="row clearfix form-group">
                                                        <div class="col-lg-4">
                                                            <input class="form-control textbox_content" value="<?php echo $options['selected_answer'] ?>" placeholder="<?php echo $options['placeHolder'] ?>">
                                                        </div>
                                                 </div>

                                        <?php } else if($options['type'] == 3) { ?>
                                                 <div class="row clearfix form-group">
                                                        <div class="col-lg-5">
                                                            <textarea class="form-control textarea_content" placeholder="<?php echo $options['placeHolder'] ?>"><?php echo $options['selected_answer']?></textarea>
                                                        </div>
                                                 </div>

                                        <?php } else if($options['type'] == 4) { ?>
                                                        <select name="" class="form-control selectBox_content" required="required" data-original-title="" title="">
                                                            <?php if(!empty($options['placeHolder'])) { ?>
                                                                    <option value="" disabled="disabled" selected="selected"><?php echo $options['placeHolder'] ?></option>
                                                            <?php }
                                                                    foreach ($options['options'] as $row) { 
                                                                         $row = str_replace("\r","",$row); 
                                                                         $selected = str_replace("\n","",$options['selected_answer']);
                                                                   ?>
                                                                <option value="<?php echo __(h($row)) ?>" <?php if($row == $selected) { ?> selected <?php } ?> > 
                                                                    <?php echo __(h($row)) ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                        <?php } ?>

                                        <div class="block">
                                            <button class="btn btn_active pull-left update_option ladda-button" data-style="expand-right">Update</button>
                                            <input class="type_value" type ="hidden" value ="<?php echo $options['type'] ?>">
                                            <input class="result_id" type ="hidden" value ="<?php echo $resultId ?>">
                                        </div>
                                        </div>
                                        </div>
                                </form>
                    <?php } ?>