<!-- Modal -->
<div class="modal fade health_reading_popup" id="readWeight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add your weight</h4>
            </div>
            <div class="modal-body">
                <div class="row health_status_editor">
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Weight 
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id="weight_value" name="" class="form-control read_field" type="text" value="" maxlength="6">
                            <span id="weight_value_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                        </div>
                        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                            <span id="read_weight_unit">
                                <?php
                                if ($unitSettings['weight_unit'] == 2) {
                                    echo 'Kg';
                                } else {
                                    echo 'lbs';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="clearfix form-group">
                        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                            <span>
                                Date
                            </span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <input id ="weight_date" name="" class="form-control current_health_date read_field" type="text" 
                            		value="<?php if(isset( $date_today )) { echo $date_today; }?>" readonly>
                            <span id="weight_date_error_message" style="display: none; color: red;"> Please enter valid date.</span>
                        </div>
                    </div>

<?php // echo $this->element('invite_friends');  ?>

                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Added Weight successfully.
                        </div>
                    </div>
                    <div id="weight_error_message" class="alert alert-error health_reading_error_message" style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;">                
                <button id="weight_submit_button" type="button" class="btn btn_active ladda-button" data-style="expand-right" onclick = ""><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

