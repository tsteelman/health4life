<?php $this->extend('Message/view'); ?>
<div id="message_loading_container" class="hide">
	<center id="message_loader"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></center>
</div>
<div class="message_list_container">
	<?php
	if (!empty($messages)):
		?>
		<div id="message_details_list_parent" class="with_reply">
			<div class="message_details_list">
				<?php
				echo $this->element('details_list', array('messages' => $messages));
				?>
			</div>
		</div>
		<div class="message_details reply_div">
			<div class="message_typeing_area pull-right">                                    
				<?php
				echo $this->Form->input('other_user_id', array('type' => 'hidden', 'value' => $otherUserId));
				 echo $this->Form->input('message_page', array('type' => 'hidden','value' => $this->Paginator->current()));
                                echo $this->Form->input('reply_message', array('type' => 'textarea', 'label' => FALSE, 'class' => 'form-control'));
				?>
                            
				<div class="form-group clearfix"> 
                                    <div class="reply_message_response pull-left">
                                 <?php
                                    if ($showMessage)
                                    {
                                     ?>
                                    <div class ='alert alert-success'><?php echo __("Your messages have been successfully sent"); ?></div>
                                    <?php
                                    }
                                    ?>
				</div>
					<button  type="button" class="message_detail_reply btn btn_active pull-right " onclick ="replyMessage();">Reply</button> 
					<!--				<button  type="button" class="btn btn_normal pull-right">Attach</button>-->
				</div>
				
			</div>                                
		</div>
		<?php
	else:
		echo $this->Html->tag('br');
		echo $this->element('warning', array('message' => __('No messages!'), 'hideCloseBtn' => true));
	endif;
	?>
</div>