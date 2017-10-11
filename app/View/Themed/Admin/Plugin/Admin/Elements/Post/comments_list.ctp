<?php
if (!empty($comments)) {
	foreach ($comments as $comment) {
		echo $this->element('Admin.Post/comment_row', $comment);
	}
}