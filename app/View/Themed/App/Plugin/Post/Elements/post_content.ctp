<?php
echo $this->element('Post.content');
echo $this->element('Post.report_abuse_comment_dialog');
echo $this->element('Post.report_abuse_post_dialog');

if ($this->Paginator->hasNext()) {
	$this->AssetCompress->script('paginated_posting.js', array('block' => 'scriptBottom'));
} else {
	$this->AssetCompress->script('posting.js', array('block' => 'scriptBottom'));
}