<?php
if ($this->Paginator->hasNext()) {
	?>
	<center>
		<?php echo $this->Paginator->next('Show older messages', array('id' => 'next_conversations_link')); ?>
	</center>
	<?php
}
$lastMsgIndex = count($messages) - 1;
foreach ($messages as $index => $message) {
	$isLast = ($index === $lastMsgIndex) ? true : false;
	echo $this->element('details_row', array('message' => $message, 'isLast' => $isLast));
}