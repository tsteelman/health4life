<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Team', $module_url);
$this->Html->addCrumb($team['name'], $module_url . '/' . $team['id']);
$this->Html->addCrumb('Calendar', $module_url . '/' . $team['id'] . '/calendar');
$this->Html->addCrumb( $title );
$eventTypeHintList = array();
?>
<div class="container">
    <div class="row team_discussion">       
		<?php echo $this->element('lhs'); ?>
        <div class="col-lg-9">
            <div class="page-header">
                    <h3><?php echo __( $title ); ?></h3> 
            </div>
            <div class="thumbnail care-calendar-event-form">
                <?php   echo $this->Form->create('Event', array(
                                    'id' => 'careCalendarEventForm',                                    
                                    'type' => 'POST',
                                    'inputDefaults' => $inputDefaults,
                                    'enctype' => 'multipart/form-data'
                                )); 
                        echo $this->Form->input('id', array('type' => 'hidden'));
                 ?>
                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Title'); ?><span class="red_star_span"> *</span></label></div>
                    <div class="col-lg-8">
                        <?php echo $this->Form->input('name'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Type of need'); ?><span class="red_star_span"> *</span> </label></div>
                    <div class="col-lg-8 type_tooltip">
                        <?php
                        echo $this->Form->input('type', array('options' => $eventTypes, 'empty' => array('' => 'Please select need')));
                        ?>
                    </div>
                </div>  
               
                <div class="form-group" id="additionalNotes" 
                      <?php 
                            if( !isset( $additional_notes )) {
                                    echo 'style="display: none"';                                    
                            } 
                      ?>
                     >
                    <div class="col-lg-3"><label><?php echo __('additional Notes'); ?></label></div>
                    <div class="col-lg-8">
                        <?php
                        echo $this->Form->input('additional_notes');
                        ?>
                    </div>
                </div>        
                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Description'); ?></label></div>
                    <div class="col-lg-8">
                        <?php echo $this->Form->textarea('description', array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Start date'); ?><span class="red_star_span"> *</span></label></div>
                    <div class="col-lg-4">
                        <?php echo $this->Form->input('start_date', array('type' => 'text', 'readonly' => 'readonly')); ?>
                        <div class="date_hint"> <?php echo Date::getDateFormatText(); ?> </div>
                    </div>
                </div>
                <div id="eventDuration" class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Time'); ?><span class="red_star_span"> *</span></label></div>
                    <div class="col-lg-3 form-group-col bootstrap-timepicker">
                        <?php
                        echo $this->Form->input('start_time', array('class' => 'form-control start time', 'type' => 'text', 'placeholder' => 'Start Time', 'data-default-time' => $startTime));
                        ?> 
                    </div>
                    <div class="col-lg-1"><label><?php echo __('to'); ?></label></div>
                    <div class="col-lg-3 form-group-col bootstrap-timepicker">
                        <?php echo $this->Form->input('end_time', array('class' => 'form-control end time', 'type' => 'text', 'placeholder' => 'End Time', 'data-default-time' => $endTime)); ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Time/Times per day'); ?></label></div>
                    <div class="col-lg-3">
                        
                        <?php echo $this->Form->input('times_per_day', array('default'=> 1)); ?>
                       
                    </div>
                </div> 

                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Assignee'); ?></label></div>
                    <div class="col-lg-8">
                        <?php echo $this->Form->input('assigned_to', array( 
                            'type' => 'select', 
                            'options' => $teamMemberList,
                            'empty' => array(0 => 'Select assignee')
                            )); ?>
                    </div>
                </div>         

                <div class="form-group">
                    <div class="col-lg-3"><label></label></div>
                    <div class="col-lg-9">
                        <div class=" flt_lft btn_area">
                            <?php
                            echo $this->Form->input('save', array('type' => 'hidden'));
                            echo $this->Form->input('Save', array('type' => 'button', 
                                'class' => 'btn btn-next', 
                                'name'=>'save' ,
                                'id' => 'task_save',
                                'value'=>'1')); ?>
                            <?php
                                
                                if ( !isset( $is_editing )) {
                                echo $this->Form->input('Save & Create New', array('type' => 'button', 
                                    'class' => 'btn btn_active', 
                                    'id' => 'task_save_and_new',
                                    'name'=>'save' , 
                                    'value'=>'2')); 
                                }
                                
                                echo $this->Form->button(
				'Cancel', array(
                                    'type' => 'button',
                                    'onclick' => "window.location='$redirectUrl'",
                                    'class' => 'btn btn_clear'                                    
                                        )
                                );
                            ?>
                           
                            
                        </div>

                    </div>
                </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>


<?php
    echo $this->jQValidator->validator();
    $this->AssetCompress->script('events', array('block' => 'scriptBottom'));
?>

<script type="text/javascript">
    
    $(document).on('change', '#EventType', function(){
        var eventType = $(this).val();
        
        if ( eventType == 'visit by other'){
            showAdditionalNotes ();
            showAdditionalNoteMessage('Specify the visitor');
        } else if ( eventType == 'other') {
            showAdditionalNotes ();
            showAdditionalNoteMessage('Specify the need');
        } else {
            hideAdditionalNotes ();
        }
    });
    
    $(document).ready(function() {
        
        /*
         * Start time datepicker
         */
        $('#EventStartDate').datepicker({            
            minDate: getUserNow(),            
            defaultDate: defaultCalendarDate
        });
        
        $('#careCalendarEventForm').submit( function(event) {

            if($( "#careCalendarEventForm" ).valid()) {
                    disableSaveButtons();
            }
        });
        
        $('#task_save').on('click', function(){
            $('#EventSave').val(1);
        });
        
         $('#task_save_and_new').on('click', function(){
            $('#EventSave').val(2);
        });
    });
    
    /**
     * Function to hide additional notes from group
     */
    function hideAdditionalNotes() {
        $('#additionalNotes').hide();
    }
    
    /**
     * Function to show additional notes from group
     */
    function showAdditionalNotes() {
        $('#additionalNotes').show();
    }
    
    function showAdditionalNoteMessage( message ){
        $('#additionalNotes .col-lg-3 label').html(message);
    }
    
    function disableSaveButtons() {
        $('#task_save').attr('disabled','disabled');
        $('#task_save_and_new').attr('disabled','disabled');
    }
</script>