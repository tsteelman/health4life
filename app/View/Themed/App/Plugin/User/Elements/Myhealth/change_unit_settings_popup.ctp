<!-- Modal -->
<div class="modal fade unit_settings_popup health_reading_popup" id="unitSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Unit Settings</h4>
            </div>
            <div class="modal-body">
                <!--<div class="row health_status_editor">-->




                <?php
                echo $this->Form->create($unitSettingsModel, array('id' => $unitSettingsModel, 'default' => FALSE));
                ?>

                <div class="measeurement_units">
                    <h4 class="sub-head"><?php // echo __('Unit Settings');   ?></h4>
                    <div class="form-group clearfix">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <label><?php echo __('Height'); ?> </label>
                        </div>
                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-7 active_label">
                            <div id="height" class="btn-group">
                                <?php
                                echo $this->Form->input('height', array(
                                    'type' => 'radio',
                                    'legend' => false,
                                    'label' => array('class' => 'btn btn-default changeUnit'),
                                    'div' => false,
                                    'name' => 'data[NotificationSetting][height]',
                                    'options' => array(1 => 'Imperial', 2 => 'Metric'),
                                    'value' => $unitSettings['height_unit']
                                ));
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <label><?php echo __('Weight'); ?> </label>
                        </div>
                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-7  active_label">
                            <div id="weight" class="btn-group">
                                <?php
                                echo $this->Form->input('weight', array(
                                    'type' => 'radio',
                                    'legend' => false,
                                    'label' => array('class' => 'btn btn-default changeUnit'),
                                    'div' => false,
                                    'name' => 'data[NotificationSetting][weight]',
                                    'options' => array(1 => 'Imperial', 2 => 'Metric'),
                                    'value' => $unitSettings['weight_unit']
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <label><?php echo __('Temperature'); ?> </label>
                        </div>
                        <div class="col-lg-7 col-sm-7 col-md-7 col-xs-7  active_label">
                            <div id="temp" class="btn-group">
                                <?php
                                echo $this->Form->input('temp', array(
                                    'type' => 'radio',
                                    'legend' => false,
                                    'label' => array('class' => 'btn btn-default changeUnit'),
                                    'div' => false,
                                    'name' => 'data[NotificationSetting][temp]',
                                    'options' => array(1 => '&deg;C', 2 => '&deg;F'),
                                    'value' => $unitSettings['temp_unit']
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--<button type="submit" class="btn btn-next"><?php // echo __('Save');    ?></button>-->
                    <!--                        <div class="col-lg-5 col-lg-offset-3">
                    <?php
//                            echo $this->Form->button(__('Save'), array('type' => 'submit',
//                                'class' => 'btn btn-next'));
//                            echo $this->Form->end();
                    ?>
                                            </div>-->
                </div>  






                <!--</div>-->
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
                <button id="unit_settings_submit_button" type="button" class="btn btn_active ladda-button" data-style="expand-right" onclick = ""><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $('.measeurement_units label').attr('for', function(i, attr) {
        if (typeof (attr) != 'undefined') {
            return attr.replace(/NotificationSettingFormNotificationSettingForm/, 'NotificationSettingForm');
        }

    });
    $('#unit_settings_submit_button').click(function() {

        saveUnitSettings();
    });
    function saveUnitSettings() {
        var formData = $("#NotificationSettingForm").serializeArray();
        var height_unit = formData[1].value;
        var weight_unit = formData[2].value;
        var temp_unit = formData[3].value;
        var ladda_finish_button = Ladda.create(document.querySelector('#unit_settings_submit_button'));
        $.ajax({
            type: 'POST',
            url: '/user/api/updateUnitSettings',
            data: {
                'height': height_unit, //bp
                'weight': weight_unit,
                'temperature': temp_unit
            },
            dataType: 'json',
            beforeSend: function() {
                ladda_finish_button.start();
            },
            success: function(result) {
//                ladda_finish_button.stop();
                if (result.success == true) {
                    window.location.reload();
                } else {
                    showServerErrorAlert('Alert', 'Some error occured. Please try again later', true);
                }
            }
        });
    }
</script>

<?php
echo $this->jQValidator->validator();
?>