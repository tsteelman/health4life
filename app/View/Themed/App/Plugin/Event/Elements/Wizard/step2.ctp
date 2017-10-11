<div class="form-group">

    <?php
//    $eventData = isset($this->request->data['Event']) ? $this->request->data['Event'] : NULL; 
    $eventData = isset($eventEditData) && !empty($eventEditData) ? $eventEditData : NULL; 
//    debug($eventData);
    
    ?>
    <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Event Type'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-4 padding-10 col-md-4 col-sm-4">
        <div class="style_input"> <input type="radio" name="data[Event][repeat]" value="0" class="repeat_radio" <?php echo (!isset($eventData['repeat'])) ? "checked='checked'" : "" ?> >
            <label class="lbl"> One Time event </label>
        </div>
<!--        <label class="radio-inline">
      <input type="radio" name="data[Event][repeat]" value="0" class="repeat_radio" checked="checked" />
        One day event
        </label>-->
    </div>

    <div class="col-lg-4 padding-10 col-md-4 col-sm-4">                               
        <!--<input type="radio" name="data[Event][repeat]" value="1" class="repeat_radio" />--> <?php // echo __('Repeat event'); ?>         
        <div class="style_input"> <input type="radio" name="data[Event][repeat]" value="1" class="repeat_radio" <?php echo (isset($eventData['repeat']) && $eventData['repeat'] == 1) ? "checked='checked'" : "" ?> >
            <label class="lbl">  <?php echo __('Recurring event'); ?> </label>
        </div>
    </div>
</div>
<div id="one_day_event_fields" class="<?php echo (!isset($eventData['repeat'])) ? "" : "hide" ?>">
    <?php echo $this->element('Event.one_day_event_form'); ?>   
</div>
<div class="<?php echo (isset($eventData['repeat']) && $eventData['repeat'] == 1) ? "" : "hide" ?>" id="repeat_event_fields">
    <?php echo $this->element('Event.repeating_event_form'); ?>
</div>     
<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Timezone'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8 col-md-8 col-sm-8"> 
        <?php echo $this->Form->input('timezone', array('options' => $timeZones, 'empty' => 'Select Timezone', 'default' => $defaultTimeZone)); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label><?php echo __('Location'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-4 col-md-4 col-sm-4"> 
        <?php echo $this->Form->input('virtual_event', array('options' => $eventLocations, 'empty' => 'Select Location')); ?>
    </div>
</div>
<div class="form-group <?php echo $onlineEventFieldsVisibilityClass; ?>" id="online_event_fields">
    <div class="col-lg-3 col-md-3 col-sm-3"><label> <?php echo __('Online details'); ?> <span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
			 <div id="event_vido_url_form" class="posting_options_form" style="display:block;">
			    <div class="form-group" id="link_input_section">
			        <?php echo $this->Form->input('online_event_details', array('placeholder' => __('Enter a video url'), 'class' => 'form-control', 'escape' => 'false')); ?>
			        <button type="button" id="event_grab_video_url_btn" class="btn btn_add pull-right event_video_add_btn" disabled="disabled" value="Add">Add</button>       
			    </div>
			    <div id="preview" class="block " style="display: none;">
			        <div id="previewImages" class="pull-left">
			            <div id="previewImage"></div>
			            <div id="previewImagesNav" style="display: none;">			                
			            </div>
			        </div>
			        <div id="closeOnlineEventPreview" title="Remove" class="pull-right">
			            <button class="close" type="button">×</button>
			        </div>
			        <div id="previewContent" class="pull-left" >
			            <div id="previewTitle" class="owner"></div>
			            <div id="previewUrl"></div>
			            <div id="previewDescription"></div>
			        </div>
			    </div>
			</div>
			<div class="toolTip">
		    	You can enter one of the following :
				<ul>
					<li>A video link ( YouTube, Vimeo etc ), or link to Ustream.tv channel(s) ( for event broadcasting ).</li>
					<li>Brief description for the Online Event.</li>
				</ul>
		    </div>
    </div>
    
</div>
<div class="<?php echo $onsiteEventFieldsVisibilityClass; ?>" id="onsite_event_fields">
    <?php echo $this->element('Event.onsite_event_form'); ?>    
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label> </label></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
        <div class=" flt_lft btn_area">
            <button type="button" class="btn btn-default btn-prev"><img src="/theme/App/img/back_arow.png" alt="Back"> Back</button>
            <button type="button" class="btn btn-next">Next  </button>
        </div>
    </div>
</div>
<?php if(isset($isEditing)){?>
		<script>
			$( document ).ready(function() {
				grabVideoUrl();
			});
		</script>
<?php }?>