<?php
if (isset($postedUserLink)) :
	echo $this->Html->tag('h5', $postedUserLink, compact('class'));
endif;