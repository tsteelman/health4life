<?php foreach ($items as $id => $itemName) : ?>
	<div class="col-lg-4">
		<div class="checkbox">
			<?php
			$checkbox = $this->Form->input("{$modelName}.{$fieldName}.{$id}", array('value' => $id, 'type' => 'checkbox', 'class' => false));
			$labelText = $checkbox . $itemName;
			echo $this->Html->tag('label', $labelText, array('for' => "{$modelName}{$fieldName}{$id}"));
			?>
		</div>
	</div>
<?php endforeach; ?>