<?php
if (isset($notificationData) && !empty($notificationData)) {
	$moreLinkClass = '';
} else {
	$moreLinkClass = 'hidden';
}
?>
<img src="/theme/App/img/notifictn_arrow.png" class="notfctn_arrow" />
<ul class="notification_scroll_header">
    <li class="notfctn_header keep_open" >
		<?php echo __('Notifications'); ?>
        <a class="pull-right more <?php echo $moreLinkClass; ?>" href="/notification" ><?php echo __('more'); ?></a>
    </li>
</ul>
<ul class="notification_scroll">
    
	<?php
	if (isset($notificationData) && !empty($notificationData)) :
		echo $this->element('Notification.notification_items');
	else :
		?>
		<div class="col-lg-12 keep_open" id="no_unread_notifications">
			<?php echo __('No notifications.'); ?>
		</div>
<?php endif; ?>
</ul>