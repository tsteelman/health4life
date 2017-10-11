<?php
if(isset($custom) && $custom === TRUE) {
?>
<h5 class="<?php echo isset($class) ? $class : 'pull-left'; ?>">
	<?php
	echo $postedUserLink;
	if (isset($postedInLink)) :
		?>
		<span>&nbsp;&gt;&nbsp;</span>
		<?php
		echo $postedInLink;
	endif;
	?> 
</h5>
<?php
} else {
?>   
<div class="po_details">
    <span class="po_user"><?php echo $postedUserLink; ?>,</span>&nbsp;
    <?php if (!empty($postedUserDiseaseName)): ?>
        <span class="color_63"><?php echo h($postedUserDiseaseName); ?>,</span>&nbsp;
    <?php endif; ?>
    <span class="color_95 timeago" datetime="<?php echo $postedTimeISO; ?>" 
          title="<?php echo $postCreatedTime; ?>"><?php echo $postedTimeAgo; ?></span>
</div>
<?php
}
