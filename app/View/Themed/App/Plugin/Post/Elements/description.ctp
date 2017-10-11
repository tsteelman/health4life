<?php
if(isset($isBlog) && $isBlog){
    $description = html_entity_decode($description);
    $truncatedDescription = html_entity_decode($truncatedDescription);
}

if (!empty($description)):
	if (!empty($truncatedDescription)):
		?>
		<div class="truncated_text wordwrap">
		<?php echo $truncatedDescription; ?>
			<a class="more_text"><?php echo __('more...'); ?></a>
		</div>
		<div class="hide full_text wordwrap">
                    <?php echo $description; ?>
                     <a class="less_text"><?php echo __('less...'); ?></a>
                </div>
	<?php else: ?>
		<div class="wordwrap"><?php echo $description; ?></div>
	<?php
	endif;
endif;