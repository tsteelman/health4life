<?php
if (!empty($posts)) {
	foreach ($posts as $post) {
                      //  debug($post);
		$element = $post['element'];
		unset($post['element']);
		$this->set($post);
		echo $this->element($element);
	}
	unset($post);
	if ($this->Paginator->hasNext()) {
		if (isset($this->params['diseaseId'])):
			$page = isset($this->params['named']['page']) ? $this->params['named']['page'] : 1;
			$nextPage = $page + 1;
			$diseaseId = $this->params['diseaseId'];
			$conditionUrl = Configure::read('Url.condition');
			$nextPageUrl = "{$conditionUrl}index/{$diseaseId}/forum/page:{$nextPage}";
			$nextPageLink = $this->Html->link('', $nextPageUrl, array('style' => 'display:none;', 'rel' => 'next'));
			?>
			<span class="next"><?php echo $nextPageLink; ?></span>
			<?php
		elseif (isset($this->params['teamId'])):
			$page = isset($this->params['named']['page']) ? $this->params['named']['page'] : 1;
			$nextPage = $page + 1;
			$teamId = $this->params['teamId'];
			$nextPageUrl = "/myteam/{$teamId}/discussion/page:{$nextPage}";
			$nextPageLink = $this->Html->link('', $nextPageUrl, array('style' => 'display:none;', 'rel' => 'next'));
			?>
			<span class="next"><?php echo $nextPageLink; ?></span>
			<?php
		else:
			echo $this->Paginator->next(
					'SHOW MORE DISCUSSIONS', array('style' => 'display:none;')
			);
		endif;
	}
}
?>
<div id="loading_dialog" class="hide">
    <div class="row content">
        <div class="text-center"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...')); ?></div>
    </div>
</div>