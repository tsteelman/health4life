<?php
if (!empty($comments)) {
	foreach ($comments as $comment) {
		//$comment['loggedIn'] = $loggedIn;
		echo $this->element('Post.comment_row', $comment);
	}
}