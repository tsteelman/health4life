
<!-- Modal -->
<div class="modal fade" id="composeMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="composeMessageForm">
            <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('New Message'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 form-group-col"> 
                        <ul class="facelist">
                            <li class="token-input">
                                <?php
                              
                                echo $this->Form->input('message_to_username', array('class' => 'user_id_input', 'div' => false, 'label' => FALSE, 'id' => 'SendMessageByEmail', 'placeholder' => __('To: Name')));
                                echo $this->Form->hidden("User.id", array('class' => 'user_id_hidden'));
                                ?>

                            </li>
                        </ul>
                        <div class="result_list" style="display:none;"></div>
                    </div> 
                    <div class="col-lg-12">
                    <?php echo $this->Form->input('compose_message', array('type' => 'textarea', 'label' => FALSE, 'placeholder' => "Write a message.."));  ?></div>
                    
                    <div id="composeMessageResponse">
                       
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button data-style="expand-right" class="btn btn_active  ladda-button" type="button" id="message_send_button" onclick ="composeMessage();"><span class="ladda-label">Send</span><span class="ladda-spinner"></span></button>
                <button id="close_invite_button" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                
            </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    $(document).ready(function() {
        initFaceList();
        
    });
function initFaceList() {   
        
        $('#SendMessageByEmail').facelist('/FrontApp/searchUsername', properties = {
            matchContains: true,
            minChars: 2,
            selectFirst: false,
            intro_text: 'Type Name',
            no_result: 'No Names',
            result_field: 'UserId'
        });
}
</script>