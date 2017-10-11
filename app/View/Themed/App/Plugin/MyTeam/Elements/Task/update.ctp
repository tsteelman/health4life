<?php
/*
 * If update permission is present
 */
if ($has_updatePermission || $has_selfAssignPermission) {
    
    ?>
    <?php
    echo $this->Form->create('TaskUpdation', array(
        'id' => 'careCalendarTaskUpdationForm',
        'method' => 'POST',
        'label' => false,
        'div' => false,
        //'class' => 'form-control',
        'enctype' => 'multipart/form-data'
    ));
    ?>

    <div class="patient_details update_form hidden">
        <div class="media">                            
            <div class="media-body">
                <div class="row">
                    <div class="col-lg-5">
                    <h2><?php echo __('Update'); ?></h2>
                </div>
                
                <div class="col-lg-7">
                    <?php
                    if( $has_updatePermission ) {
                ?>
                        <div class="form-group clearfix">
                            <div class="col-lg-5 pull-right">
                                <?php
                                echo $this->Form->input('completed', array(
                                    'type' => 'checkbox',
                                    'onclick' => 'disableAssigne()',
                                    'div' => FALSE,
                                    'label' => FALSE
                                ));
                                ?>
                                <span><?php echo __('Mark as completed'); ?></span>
                            </div>
                        </div>
                <?php                 
                    } 
                ?>
                </div></div>
                
            </div>
        </div>

        <div class="task_detail task_update">

            <div class="form-group clearfix">
                <div class="col-lg-3">
                    <p><?php echo __('Assigned To: '); ?></p>
                </div>
                <div class="col-lg-5">
                    <?php
                    
                    if ( empty($task_details['CareCalendarEvent']['assigned_to'] )  &&
                            $has_updatePermission) {
                        $emptyValue = array(0 => 'Select Assignee');
                    } else {
                        $emptyValue = array();
                    }
                    echo $this->Form->input('assigned_to', array(
                        'type' => 'select',
                        'class' => 'form-control',
                        'label' => FALSE,
                        'options' => $assigneeOptions,
                        'selected' => $task_details['CareCalendarEvent']['assigned_to'],
                        'empty' => $emptyValue 
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group clearfix">
                <div class="col-lg-3">
                    <p><?php echo __('Notes'); ?><span class="red_star_span"> *</span></p>
                </div>
                <div class="col-lg-5">
                    <?php
                    echo $this->Form->input('note', array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'placeholder' => __('Please let the team know why you are changing the status of this task!'),
                        'div' => FALSE,
                        'label' => FALSE,
                        
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group clearfix">
                <div class="col-lg-3" style="display: block"></div>
                <div class="col-lg-6">
                    <?php
                    echo $this->Form->input('save', array(
                        'type' => 'button',
                        'class' => 'btn btn-next',
                        'label' => FALSE,
                        'div' => FALSE
                    ));
                    ?>
                     <?php
                    echo $this->Form->button('Cancel', array(
                        'type' => 'reset',
                        'class' => 'btn btn-default btn-prev',
                        'label' => FALSE,
                        'div' => FALSE,
                        'onclick' => 'clearForm()'
                    ));
                    ?>
                </div>
                
            </div>
            <?php
            echo $this->Form->end();
            ?>
        </div>
    </div>

    <?php
}
?>
