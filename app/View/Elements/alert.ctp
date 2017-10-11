<?php echo $this->Html->tag('div', null, compact('id', 'style', 'class')); ?>
<?php if(!isset($hideCloseBtn)): ?>
	<button data-dismiss="alert" class="close" type="button" aria-hidden="true" >×</button>
<?php endif; ?>
<div class="message"><?php echo h($message); ?></div>
</div>