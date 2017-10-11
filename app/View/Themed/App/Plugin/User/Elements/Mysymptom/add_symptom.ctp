<!-- Modal -->
<div class="modal fade" id="addSymptom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Create your Symptom</h4>
            </div>
            <div class="modal-body">
                <div class="row health_status_editor">
                    <div class="clearfix form-group">
                    	<div class=""> 
                            <div class="col-lg-4" style="text-align: center;">
                            <span>
                                Symptom 
                            </span>
                        </div>
                        <div class="col-lg-7">
                            <input id="symptom-search" name="" class="form-control" type="text" maxlength="100" >
                            <input id="symptoms_id_hidden" name="" class="form-control symptoms_id_hidden" type="hidden" >
                            <div class="no_result_msg" style="display: none;"><?php echo __('No results found'); ?></div>
                            <span id="symptom_error_message" style="display: none; color: red;"> Please enter valid symptom.</span>
                        </div>
                        </div>                        
                    </div>                 


                    

                    
                    <div id="symptom_search_error_message" class="alert alert-error" style="display: none;"></div>
                </div>
                
                <?php if ( isset ( $is_tile_page )) { ?>
                    <div class="row health_status_editor">
                        <h4>How is your severity today ?</h4>                    

                        <div class="btn-toolbar" role="toolbar">
                            <div class="condition_popup_container">

                                <div class="condition_indicator condition_none_header">
                                    <label class="condition_none ">
                                        <span class="name">None</span>

                                        <input name="symptomHistoryRadio" type="radio" value="1">
                                    </label>
                                </div>
                                <div class="condition_indicator condition_mild_header">
                                    <label class="condition_mild ">
                                        <span class="name">Mild</span>

                                        <input name="symptomHistoryRadio" type="radio" value="2">
                                    </label>
                                </div>
                                <div class="condition_indicator condition_moderate_header">
                                    <label class="condition_moderate ">
                                        <span class="name">Moderate</span>

                                        <input name="symptomHistoryRadio" type="radio" value="3">
                                    </label>
                                </div>
                                <div class="condition_indicator condition_severe_header">
                                    <label class="condition_severe ">
                                        <span class="name">Severe</span>

                                        <input name="symptomHistoryRadio" type="radio" value="4">
                                    </label>
                                </div>
                                <span id="symptom_history_error_message" style="display: none; color: red;"> Please select valid severity.</span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E5E5;">
                <button id="symptom_submit_button" type="button" class="btn btn_active ladda-button" data-style="expand-right" onclick = ""><span class="ladda-label">Save</span><span class="ladda-spinner"></span></button>
                <button id="close_symptom_submit_button" type="button" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
$(document).on('focus', '#symptom-search', function() {
            var minLength = 1;
	    initSymptomAddAutoComplete(this, minLength);
        });
    $('#addSymptom').on('hidden.bs.modal', function () { 
        $('#addSymptom .no_result_msg').hide();
        resetAddSymptomModal();
    })


    function resetAddSymptomModal() {
        $('#addSymptom #symptom-search').val('');
        $('#addSymptom #symptoms_id_hidden').val('');
        $('#addSymptom .condition_indicator label').removeClass('on');
        $('input[name=symptomHistoryRadio]').prop('checked', false);
        $('#symptom_error_message').html('');
    }
</script>
