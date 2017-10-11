<div id="add_appoinment_form" class="tab-pane fade ">
    <div style="clear: both">         
    </div>        
    <div class="infocontainer" style="padding: 5px 25px 10px 25px;"> 
        <?php
            echo $this->Form->create('AppoinmentForm', array(
                'id' => 'AppoinmentForm',
                'inputDefaults' => $inputDefaults,
                'method' => 'POST',
                'enctype' => 'multipart/form-data'                
            ));
           
        ?>
        <div class="form-group">
            <div class="col-lg-3">
                <label>Doctor name<span class="red_star_span"> *</span></label>
            </div>
            <div id="calendarcolor">
            </div>
            <div class="col-lg-9">
                 <?php 
                        echo $this->Form->input('doctor_name', array(
                                'type' => 'text',
                                'id' => 'doctor_name',
                                'class' => 'required safe form-control',
                                'placeholder' => 'Doctor name'
                                
                        ));
                        echo $this->Form->hidden('id');
                 ?>
                
            </div> 
                      
        </div> 
        
        <div class="form-group">
            <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Event Type'); ?><span class="red_star_span"> *</span></label></div>
            <div class="col-lg-4 padding-10 col-md-4 col-sm-4">
                <div class="style_input"> <input type="radio" name="data[AppoinmentForm][repeat]" id="appt_no_repeat_chk" checked="checked" value="0" class="repeat_radio" >
                    <label class="lbl">  <?php echo __('One Time'); ?> </label>
                </div>
            </div>
            <div class="col-lg-4 padding-10 col-md-4 col-sm-4">                               
                <div class="style_input"> <input type="radio" name="data[AppoinmentForm][repeat]" id="appt_repeat_chk" value="1" class="repeat_radio" >
                    <label class="lbl">  <?php echo __('Recurring'); ?> </label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-lg-3">
                <label>                    
                    Date<span class="red_star_span"> *</span>
                </label> 
            </div>
            <div class="col-lg-4 form-group-col">
                <?php 
                        echo $this->Form->input('appoinment_date', array(
                                'type' => 'text', 
                                'id' => 'appoinment_date',
                                'readonly' => 'readonly',
                                'class' => 'required date datepicker form-control'
                        )); 
                ?>
                <!--<input MaxLength="10" readonly class="required date datepicker form-control" id="appoinment_date" name="appoinment_date" type="text" value=""/>-->
            </div>
            <div class="col-lg-4">
                <div class="date_hint"> (MM/DD/YYYY) </div>
            </div> 
        </div> 

        <div class="form-group">
            <div class="col-lg-3">
                <label>                    
                    Time<span class="red_star_span"> *</span>
                </label> 
            </div>                 
            <div id="eventDuration1">  

                <div class="col-lg-3 form-group-col">
                    <?php 
                        echo $this->Form->input('appoinment_time', array(
                                'type' => 'text', 
                                'id' => 'appoinment_time',
                                'class' => 'required calendar_timepicker bbit-dropdown form-control start setApptErrorDiv',
                                'placeholder' => 'Time'
                        )); 
                    ?>
                    <!--<input MaxLength="5" class="required calendar_timepicker bbit-dropdown form-control start" id="appoinment_time" name="appoinment_time" type="text" value="" placeholder="Time" style="padding: 6px 12px;height: 32px;"/>-->
                    <input type="hidden" id="stparttime24" value=""/>

                </div>
                           
            </div>  
            <div class="col-lg-12 col-md-12 col-sm-12 ">
                <div class="col-lg-3 col-md-3 col-sm-3"></div>
                <div id="appt_time_error_wrapper" class="time_error_wrapper col-lg-9 col-md-9 col-sm-9"></div>        
            </div>
            <span id="time_error_message"  class="error_span_add_cal"></span>

        </div>
        <div class="hide" id="appt_repeat_event_fields">
                <?php echo $this->element('Calendar.repeat_appointment_form'); ?>
        </div>


        <div class="form-group">
            <div class="col-lg-3">
                <label class="lh_28">                    
                    Reason for appoinment
                </label> 
            </div> 
            <div class="col-lg-9">
                <?php 
                        echo $this->Form->input('appoinment_reason', array(
                                'type' => 'textarea', 
                                'id' => 'appoinment_reason',                                
                                'class' => 'form-control',
                                'placeholder' => 'Reason for appoinment',
                                'cols' => 20,
                                'rows' => 2
                        )); 
                ?>
                <!--<textarea cols="20" id="appoinment_reason" class="form-control" name="appoinment_reason" rows="2" placeholder="Reason for appoinment"></textarea>-->                

            </div>              
        </div>              
        <?php
            echo $this->Form->end();
        ?>
    </div>   
</div>
<?php 
//    echo $this->jQValidator->validator();
?>