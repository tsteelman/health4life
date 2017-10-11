<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('My Library');
?>
<?php $this->extend('Profile/view'); ?>
<div id="my_library_container">
    <?php if (isset($isLibray) && $isLibray != NULL) { ?>
        <input type="hidden" value="<?php echo $isLibray; ?>" id="isLibray">
    <?php } ?>
    <?php // echo $this->element('Post.content'); ?>
    <?php echo $this->element('Post.display_library_posts'); ?>
</div>

<?php
$this->AssetCompress->script('post', array('block' => 'scriptBottom'));
?>