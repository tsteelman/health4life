<div class="form-group btns_row">

	<?php if (isset($backUrl)): ?>
		<button type="button" class="btn btn-prev pull-left" id="back_btn" data-href="<?php echo $backUrl; ?>">
			<img src="/theme/App/img/back_arow.png" alt="<" />
			<?php echo __('Back'); ?>  
		</button>
	<?php endif; ?>

	<button type="submit" class="btn btn-next pull-left">
		<?php if (isset($isLast)) : ?>
			<?php echo __('Save'); ?>  
		<?php else: ?>
			<?php echo (isset($isLast)) ? __('Save') : __('Continue'); ?>  
			<img src="/theme/App/img/nxt_arow.png" alt=">" />
		<?php endif; ?>
	</button>

</div>