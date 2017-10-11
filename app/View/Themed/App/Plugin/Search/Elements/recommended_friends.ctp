<?php
if ($this->Paginator->hasNext()) {
	$this->AssetCompress->script('jquery.autopager', array('block' => 'scriptBottom'));
}
?>
<div id="recommendedFriendPageList" class="content">
    <div class="row">
		<?php
		if (!empty($recommended_users)) {
			?>
			<div class="recommended_friend_header">
				<p class="pull-left">People you may know</p>					    
			</div>
			<?php
			//people you may know section
			foreach ($recommended_users as $newuser) {
				echo $this->element('users_row_recommended', array('newuser' => $newuser));
			}
		}
		?>
    </div> 
</div>
<?php
if ($this->request->params['paging']['User']["nextPage"]) {
	echo $this->Paginator->next(
			'SHOW MORE USERS', array('style' => 'display:none;')
	);
}
?>
<div id="post_loading" class="hide">
    <span>
		<?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
        <label>Loading, please wait...</label>
    </span>
</div>
