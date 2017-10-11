<div class="form-group hide">
    <div class="col-lg-3"><label><?php echo __('Event Type'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-4 padding-10">
        <div class="style_input"> <input type="radio" name="data[Event][repeat]" value="0" class="repeat_radio" checked="checked" > <label class="lbl"> One day event
            </label> </div>
        <!--        <label class="radio-inline">
              <input type="radio" name="data[Event][repeat]" value="0" class="repeat_radio" checked="checked" />
                One day event
                </label>-->
    </div>
    <div class="col-lg-4 padding-10 hide">                               
        <input type="radio" name="data[Event][repeat]" value="1" class="repeat_radio" /> <?php echo __('Repeat event'); ?>         
    </div>
</div>
<div id="one_day_event_fields">
    <?php echo $this->element('Event.one_day_event_form'); ?>   
</div>
<div class="hide" id="repeat_event_fields">
    <?php echo $this->element('Event.repeating_event_form'); ?>
</div>     
<div class="form-group">
    <div class="col-lg-3"><label><?php echo __('Timezone'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8"> 
        <?php echo $this->Form->input('timezone', array('options' => $timeZones, 'empty' => 'Select Timezone', 'default' => $defaultTimeZone)); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3"><label><?php echo __('Event Location'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8"> 
        <?php echo $this->Form->input('virtual_event', array('options' => $eventLocations, 'empty' => 'Select Location')); ?>
    </div>
</div>
<div class="form-group <?php echo $onlineEventFieldsVisibilityClass; ?>" id="online_event_fields">
    <div class="col-lg-3"><label> <?php echo __('Online details'); ?> <span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8">
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
<div class="form-group hide">
    <div class="col-lg-3"><label><?php echo __('Tags'); ?></label></div>
    <div class="col-lg-8 tags">
        <?php echo $this->Form->input('tags', array('placeholder' => 'Type tags and press spacebar or enter key')); ?>
    </div>
</div>
<div class="form-group">
    <!--<div class="col-lg-3"><label> </label></div>-->
    <div class="col-lg-11">   
        <?php echo $this->Form->input('guest_can_invite', array('type' => 'checkbox', 'class' => 'input_chckbx', 'hiddenField' => FALSE)); ?>
        <span><?php echo __('Allow guests to invite other users?'); ?></span>
    </div>
</div>
<!--<div class="form-group">
    <div class="col-lg-3"><label> </label></div>
    <div class="col-lg-8">
        <div class=" flt_lft btn_area">
            <button type="button" class="btn btn-next">Next  </button>
        </div>
    </div>
</div>-->
<?php if (isset($isEditing)) { ?>
    <script>
        $(document).ready(function() {
            grabVideoUrl();
        });
    </script>
    <?php
}?>