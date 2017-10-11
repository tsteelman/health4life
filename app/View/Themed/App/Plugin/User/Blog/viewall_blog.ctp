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
$this->Html->addCrumb('All Blog', '');
?>
<?php $this->extend('Profile/view');

$maxLength = 100;

?>
    
    <div id="blog_container" class="js-masonry row team_list" 
         data-masonry-options='{ "columnWidth": 50, "itemSelector": ".blog_tem" }'>
    <?php 
    if(count($blogData) > 0) { 
        foreach ($blogData as $blog) {
            $blogUrl = '#';
			$lengthyContent = false;
			$description = html_entity_decode($blog['description']);
			if (strlen($description) > $maxLength) {
				$options = array('exact' => false, 'html' => true, 'ellipsis' => false);
				$shortDescription = String::truncate($description, $maxLength, $options);
				$lengthyContent = true;
			}
    ?>
    <div class="blog_item ">
            <div class="team_details">

		<h4>
                   <?php echo $blog['title']; ?>
                </h4>
                <p class="posted_ago">Posted <?php echo($blog['postedTimeAgo']); ?></p>
            </div>
            <div class="team_members_details">
				<?php if($lengthyContent == true) : ?>
					<span class="truncated_text">
						<?php echo $shortDescription; ?>
						<a class="read_more_text"><?php echo __('Read more'); ?></a>
					</span>
					<span class="hide full_text">
						<?php echo $description; ?>
						<a class="read_less_text"><?php echo __('less...'); ?></a>
					</span>
				<?php else: ?>
					<span class="truncated_text"><?php echo $description; ?></span>
				<?php
				endif; ?>
            </div>
        </div>
    <?php 
        }
    } else {
    ?>      
        <h3>No blog for this user</h3>
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