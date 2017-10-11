<div id="add_calendar_event_form" class="tab-pane fade in active">
    <div style="clear: both">         
    </div>      
    <?php
            echo $this->Form->create('CalendarForm', array(
                'id' => 'CalendarForm',
                'inputDefaults' => $inputDefaults,
                'method' => 'POST',
                'enctype' => 'multipart/form-data'                
            ));
           
        ?>
    <div class="infocontainer" style="padding: 5px 25px 10px 25px;">   <!--/*height: 343px; */-->
        <div class="form-group">
            <div class="col-lg-3">
                <label>Subject<span class="red_star_span"> *</span></label>
            </div>
            <div id="calendarcolor">
            </div>
            <div class="col-lg-9">
                <input class="form-control" id="Subject" name="data[CalendarForm][Subject]" type="text" value="" placeholder="Subject"/>                     
            </div> 
            <span id="subject_error_message" class="error_span_add_cal"></span>
            <input id="colorvalue" name="colorvalue" type="hidden" value="" />                
            <input id="event_id" name="data[CalendarForm][event_id]" type="hidden" value="" />                
        </div> 
        <div class="form-group">
            <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Event Type'); ?><span class="red_star_span"> *</span></label></div>
            <div class="col-lg-4 padding-10 col-md-4 col-sm-4">
                <div class="style_input"> <input type="radio" name="data[CalendarForm][repeat]" id="no_repeat_chk" checked="checked" value="0" class="repeat_radio" >
                    <label class="lbl">  <?php echo __('One Time'); ?> </label>
                </div>
            </div>
            <div class="col-lg-4 padding-10 col-md-4 col-sm-4">                               
                <div class="style_input"> <input type="radio" name="data[CalendarForm][repeat]" id="repeat_chk" value="1" class="repeat_radio" >
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
                <input readonly class=" date datepicker form-control" id="stpartdate" name="data[CalendarForm][stpartdate]" type="text" value=""/>
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
                    <input class="setRemErrorDiv time calendar_timepicker bbit-dropdown form-control start" id="stparttime" name="data[CalendarForm][stparttime]" type="text" value="" placeholder="Start time" style="padding: 6px 12px;height: 32px;"/>
                    <input type="hidden" id="stparttime24" value=""/>

                </div>
                <div class="col-lg-1">
                    <label>                    
                        To
                    </label> 
                </div>  
                <div class="col-lg-3 form-group-col">
                    <input class="setRemErrorDiv time calendar_timepicker bbit-dropdown form-control end" id="etparttime" name="data[CalendarForm][etparttime]" type="text" value=""  placeholder="End time" style="padding: 6px 12px;height: 32px;" />                  
                    <input type="hidden" id="etparttime24" value=""/>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12 ">
                    <div class="col-lg-3 col-md-3 col-sm-3"></div>
                    <div id="rem_time_error_wrapper" class="time_error_wrapper col-lg-9 col-md-9 col-sm-9"></div>        
                </div>
                </div>  
            <span id="time_error_message"  class="error_span_add_cal"></span>
           
        </div> 
        <div class="hide" id="repeat_event_fields">
                <?php echo $this->element('Calendar.repeat_reminder_form'); ?>
        </div>

       
        <div class="form-group">
            <div class="col-lg-3">
                <label>                    
                    Description
                </label> 
            </div> 
            <div class="col-lg-9">
                <textarea cols="20" id="Description" class="form-control" name="data[CalendarForm][Description]" rows="2" placeholder="Description" >
                </textarea>                
            </div>  
             <span id="description_error_message" class="error_span_add_cal"></span>
        </div>              
        <input id="timezone" name="timezone" type="hidden" value="" />           
    </div>   
       <?php
            echo $this->Form->end();
        ?>
</div>  