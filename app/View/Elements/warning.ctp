<?php
$class = 'alert alert-warning';
echo $this->element('alert', compact('message', 'id', 'style', 'class', 'hideCloseBtn'));