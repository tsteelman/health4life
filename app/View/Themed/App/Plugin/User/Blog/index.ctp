<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($isOwnProfile) {
    $profileName = 'My Profile';
    $profileLink = '/profile';
    $blogLink = '/blog';
}
else {
    $profileName = $user_details['username']."'s Profile";
    $profileLink = '/profile/'.$user_details['username'];
    $blogLink = $profileLink.'/blog';
}
$this->Html->addCrumb($profileName, $profileLink);
$this->Html->addCrumb('Blog');
?>
<?php $this->extend('Profile/view');

$postFormOption = array(
    'isOwnProfile' => $isOwnProfile, 
    'isBlogPage' => true
);

if ((isset($viewBlog) && ($viewBlog === true)) || !isset($viewBlog)) {
    if ((isset($isOwnProfile) && ($isOwnProfile === true))) {
        echo $this->element('Blog/blog_form', $postFormOption);
    }
    echo $this->element('Post.new_post_notification');
    echo $this->element('Blog/blog_content');
}
$this->AssetCompress->script('blog_posting', array('block' => 'scriptBottom'));
?>