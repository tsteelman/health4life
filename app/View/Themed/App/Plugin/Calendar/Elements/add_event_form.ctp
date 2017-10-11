<div id="add_event_form" class="create_common tab-pane fade">
    <div class="row">
        <!--<div class="col-lg-9">-->
        <!--            <div class="page-header">           
                        <h3>
                            <span><?php // echo 'Create Event';     ?></span>
                        </h3>       
                    </div>-->
        <div class="thumbnail">
            <!--<button type="button" class="close pull-right" id="close_event_wizard">&times;</button>-->
            <!--                <div class="page-header">      
                            </div>-->
            <?php
            echo $this->Form->create('Event', array(
                'id' => $formId,
                'inputDefaults' => $inputDefaults,
                'method' => 'POST',
                'enctype' => 'multipart/form-data',
                'data-backurl' => $backUrl
            ));
            echo $this->Form->hidden('id');
            echo $this->Form->hidden('old_name');
            if (isset($refer)) {
                echo $this->Form->hidden('refer', array('value' => $refer));
            }
            ?>
            <?php echo $this->Form->hidden('image'); ?>
            <?php echo $this->Form->hidden('description'); ?>
            <div class="wizard" id="eventWizard">
                <!--                    <ul class="steps">
                                        <li data-target="#step1" class="active"></li>
                                        <li data-target="#step2"></li>
                                        <li data-target="#step3"></li>
                                    </ul>-->
                <div class="form-group">
                    <div class="col-lg-3"><label><?php echo __('Name'); ?><span class="red_star_span"> *</span></label></div>
                    <div class="col-lg-8">
                        <?php echo $this->Form->input('name', array(
                            'placeholder'=>'Event name'
                        )); ?>
                    </div>
                </div>

                <?php if (isset($eventTypes)): ?>
                    <div class="form-group">
                        <div class="col-lg-3"><label><?php echo __('Event type'); ?> </label></div>
                        <div class="col-lg-8 type_tooltip">
                            <?php
                            echo $this->Form->input('event_type', array('options' => $eventTypes));
                            ?>
                        </div>
                        <?php
                        echo $this->Html->image('/img/symptom_info.png', array(
                            'alt' => '?',
                            'id' => 'event_type_help',
                            'data-content' => $this->Html->nestedList($eventTypeHintList)
                        ));
                        ?>
                        <div id="event_type_popover"></div>
                    </div>
                    <?php
                else:
                    echo $this->Form->hidden('event_type', array('value' => $eventType));
                endif;
                ?>

                <?php echo $this->element("Calendar.add_event_form_step2"); ?>
                <?php // echo $this->element("Calendar.add_event_invite_friend"); ?>

                <script type="text/javascript">
                    var friendList = <?php echo $friendsListJson; ?>;
                </script>
                <?php // echo $this->element('Event.Wizard/step3'); ?>
            </div>
        </div>
    </div>                      
    <?php echo $this->Form->end(); ?>
</div>
<!--</div>-->
<?php // echo $this->element('layout/rhs'); ?>
<!--</div>
</div>-->
<?php
$this->jQValidator->printCountryZipRegexScriptBlock();
//echo $this->jQValidator->validator();
echo $this->Html->scriptBlock(
        "var form = '#{$formId}';"
);
$this->AssetCompress->script('events', array('block' => 'scriptBottom'));
