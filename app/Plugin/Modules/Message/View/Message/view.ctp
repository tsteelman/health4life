<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Messages');
?>
<div class="container">
	<div class="alert alert-success hide" id="message_success_element">
		<button type="button" class="close">×</button>
		<div class="message"></div>
	</div>
    <div class="messages">
        <div class="row">
            <div class="col-lg-9">
                <div class="page-header">
                    <h3>Inbox</h3> 
                </div>
                <div class="message_search_field">
					<form id="message_search_form">
						<div class="col-lg-5 pull-left">
							<div class="form-group">
								<input maxlength="50" type="text" placeholder="Search" id="message_search_input" class="form-control search_icon ui-autocomplete-input">  
							</div>
						</div>
					</form>
                    <div class="col-lg-6 pull-right">
                        <button  type="button" class="btn btn_normal pull-right" disabled="disabled" id="delete_msg_btn">Delete</button>
                        <button  type="button" class="btn btn_normal pull-right" disabled="disabled" id="save_msg_btn">Save Message</button>
                        <button  id="compose-message-button" type="button" class="btn btn_active pull-right " data-toggle="modal" data-target="#composeMessage" data-backdrop="static" data-keyboard="true" >Compose</button>
                    </div>
                </div>
                <div class="message_container">
                    <div class="message_type">
                        <ul class="pull-left" id="message_tabs">
                            <li>
                                <a class="active" id="inbox_link">Inbox<?php if($inboxMessagesCount > 0): ?><span>(<span id="unread_message_count"><?php echo $inboxMessagesCount; ?></span>)</span><?php endif; ?></a>
                            </li>
                            <li>
                                <a id="sent_link">Sent</a>
                            </li>
                            <li>
                                <a id="saved_link">Saved</a>
                            </li>
<!--                            <li>
                                <a href="#">Chat</a>
                            </li>-->
                        </ul>
                        <span class="pull-right hide" id="message_select_box">
                            <span>Select :</span>
                            <span id="select_all_messages"><a class="owner">All,</a></span>
                            <span id="select_read_messages"><a class="owner">Read,</a></span>
                            <span id="select_unread_messages"><a class="owner">Unread,</a></span>
                            <span id="unselect_messages"><a class="owner">None</a></span>
						</span>
                    </div>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
            <?php echo $this->element('layout/rhs', array('list' => true)); ?>
        </div>
    </div>
</div>