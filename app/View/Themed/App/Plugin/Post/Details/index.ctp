<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
}
if (isset($breadCrumbList) && !empty($breadCrumbList)) {
	foreach ($breadCrumbList as $breadCrumb) {
		$this->Html->addCrumb($breadCrumb['text'], $breadCrumb['href']);
	}
}
$this->Html->addCrumb('Post');
?>
<div class="container">
	<div class="row post_detail">
		<input type="hidden" name="data[Post][posted_in_room]" value="<?php echo $room; ?>" id="PostPostedInRoom">
		<input type="hidden" name="data[Post][posted_in]" value="<?php echo $postId; ?>" id="PostPostedIn">
		<div class="col-lg-9">
                    <div id="post_list" class="<?php echo $containerClass; ?>"><?php echo $this->element($element); ?></div>
		</div>
		<?php echo $this->element('layout/rhs', array('list' => true)); ?>
	</div>
</div>
<div id="loading_dialog" class="hide">
    <div class="row content">
        <div class="text-center"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
    </div>
</div>
<?php
echo $this->element('Post.report_abuse_comment_dialog');
echo $this->element('Post.report_abuse_post_dialog');
$this->AssetCompress->addScript(array('vendor/jquery.timeago.js', 'post.js', 'poll.js'), 'post_detail.js');