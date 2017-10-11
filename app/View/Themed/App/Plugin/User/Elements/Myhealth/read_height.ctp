<!-- Modal -->
<div class="modal fade health_reading_popup" id="readHeight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add your Height</h4>
            </div>
            <div class="modal-body">
                <div class="row health_status_editor">
                    <div class="clearfix form-group">
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
                            <span>
                                Height 
                            </span>
                        </div>
                        <?php
                        if ($unitSettings['height_unit'] === '1') {
                            ?>
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <input id="height_value1" class="form-control read_field" type="text" value="" maxlength="6">
                                <span id="height_value1_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                            </div>
                            <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                                <span id="read_height_unit">feet</span>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <input id="height_value2" class="form-control read_field" type="text" value="" maxlength="4">
                                <span id="height_value2_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                            </div>
                            <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                                <span>inches</span>
                            </div>
                        <?php } else if ($unitSettings['height_unit'] === '2') {
                            ?>
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <input id="height_value1" class="form-control read_field" type="text" value="" maxlength="6">
                                <span id="height_value1_error_message" style="display: none; color: red;"> Please enter valid data.</span>
                            </div>
                            <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                                <span id="read_height_unit">cm</span>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php // echo $this->element('invite_friends'); ?>

                    <div id="success_message" class="hidden">
                        <div class="alert alert-success">
                            Added height successfully.
                        </div>
                    </div>
                    <div id="height_error_message" class="alert alert-error health_reading_error_message"  style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
                <button id="height_submit_button" type="button" class="btn btn_active ladda-button" data-style="expand-right"><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->