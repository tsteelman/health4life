<?php
if(isset($messages) && (!empty($messages)))
{
	foreach($messages as $message)
	{
		echo $this->element('Message.message_row', array('message' => $message['message']));
	}
}
else
{
	echo $this->Html->tag('br');
	echo $this->element('warning', array('message' => __('No messages!'), 'hideCloseBtn' => true));
}