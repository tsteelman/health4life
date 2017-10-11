<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($isOwnProfile) {
    $profileLink = '/profile';
    $blogLink = '/profile/blog';
}
else {
    $profileLink = '/profile/'.$user_details['username'];
    $blogLink = $profileLink.'/blog';
}
$this->Html->addCrumb('My Profile', $profileLink);
$this->Html->addCrumb('Blog', $blogLink);
$this->Html->addCrumb('e-Card', '');
?>
<?php $this->extend('Profile/view'); ?>
    
    <div id="blog_container" class="js-masonry row team_list" 
         data-masonry-options='{ "columnWidth": 50, "itemSelector": ".blog_tem" }'>
    <?php 
    if(count($eCardData) > 0) { 
        foreach ($eCardData as $blog) {
            $blogUrl = '#';
    ?>
    <div class="blog_item ">
            <div class="team_details">
                <ul class="ecard_templates ecard_view">
                    <?php 
                    $ecards = $blog['ecards'];
                    if(isset($ecards) && !empty($ecards)) {
                        foreach ($ecards as $cardName) { 
                    ?>
                    <li>
                        <?php echo $this->Html->image($cardName, array( 'alt' => '', 'class' => 'preview_img')); ?>
                    </li>            
                    <?php
                        }
                    }
                    ?>

                    <?php
                    ?>
                </ul> 
            </div>
            <div class="team_members_details">
                <h4>
                    <?php echo $blog['title']; ?>
                </h4>
                <p>Sent by <?php echo $blog['postedUserLink']; ?>,&nbsp;
                <?php echo $blog['postedTimeAgo']; ?></p>
            </div>
        </div>
    <?php 
        }
    } else {
    ?>      
        <h3>No e-Card for this user</h3>
    <?php

    }
    ?>
</div>
<?php
    $this->AssetCompress->script('grid_gallery', array('block' => 'scriptBottom'));
	$this->AssetCompress->script('blog_posting', array('block' => 'scriptBottom'));
?>
<script>
$(document).ready(function() {
	$(window).load(function() {
		var container = document.querySelector('#blog_container'); 
		var twoColumn = new Masonry( container, {
			columnWidth: 10
		});
		twoColumn.layout();
	});
 });
 </script>