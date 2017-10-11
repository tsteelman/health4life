<?php foreach ($fields as $field): ?>
	<div class="control-group">
		<?php $labelFor = $modelName . Inflector::camelize($field['name']); ?>
		<label for="<?php echo $labelFor; ?>" class="control-label">
			<?php if (isset($field['required'])) : ?>
				<span class="red_star_span"> *</span>
			<?php endif; ?>
			<span><?php echo $field['label']; ?></span>
		</label>
		<div class="controls">
			<?php
			$fieldType = isset($field['type']) ? $field['type'] : 'text';
			$fieldOptions = array('type' => $fieldType, 'autocomplete' => 'off');
			if (isset($field['options'])) {
				$fieldOptions['options'] = $field['options'];
				$fieldOptions['empty'] = '-Select-';
				$fieldOptions['class'] = 'chosen-select';
			}
			echo $this->Form->input($field['name'], $fieldOptions);
			?>
		</div>
	</div>
<?php endforeach; ?>