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
$this->Html->addCrumb('Photos', '');
?>
<?php $this->extend('Profile/view'); ?>
<div class="event_wraper">

    <div id="grid-gallery" class="grid-gallery">
        <section class="grid-wrap">
            <ul class="grid">
                <li class="grid-sizer"></li><!-- for Masonry column width -->
                
<?php
if(is_array($allPhotos) && !empty($allPhotos) ) {

        $photoUrlPath = Configure::read('App.UPLOAD_PATH_URL') . '/post_photos/';
        $previewFilePath = Configure::read('App.POST_IMG_PATH') . DIRECTORY_SEPARATOR;

        foreach ($allPhotos as $photo) {
            $photoFileName = $photo['Photo']['file_name'];
            $originalPhoto = $photoUrlPath . $photoFileName;
            $previewFile = $previewFilePath . 'preview_' . $photoFileName;
            if (file_exists($previewFile)) {
                    $previewPhoto = $photoUrlPath . 'preview_' . $photoFileName;
            } else {
                    $previewPhoto = $originalPhoto;
    }
        ?>
        
            <li>
                <figure>
                       <img
                    src="<?php echo $previewPhoto; ?>" alt="<?php echo $photoFileName; ?>" />  
                    
                </figure>
            </li>        

        <?php

        }
        } else {

        ?>
        <div class="alert alert-warning"><div class="message">No Images added by this user!</div>
        </div>            
        <?php

        }  

?>             
            </ul>
        </section><!-- // grid-wrap -->
        <section class="slideshow">
                <ul>
                    
<?php
if(is_array($allPhotos) && !empty($allPhotos) ) {

        $photoUrlPath = Configure::read('App.UPLOAD_PATH_URL') . '/post_photos/';
        $previewFilePath = Configure::read('App.POST_IMG_PATH') . DIRECTORY_SEPARATOR;

        foreach ($allPhotos as $photo) {
            $photoFileName = $photo['Photo']['file_name'];
            $originalPhoto = $photoUrlPath . $photoFileName;
            $previewFile = $previewFilePath . 'preview_' . $photoFileName;
            if (file_exists($previewFile)) {
                    $previewPhoto = $photoUrlPath . 'preview_' . $photoFileName;
            } else {
                    $previewPhoto = $originalPhoto;
    }
        ?>
        
             <li>
                <figure>
                <img
                    src="<?php echo $originalPhoto; ?>" alt="<?php echo $photoFileName; ?>" />  
                </figure>
            </li>     

        <?php

        }
        } else {

        ?>
        <div class="" style="margin:150px 0px 0px 100px;color: #FFF;font-size: 14px;">
            No Images added by this user
        </div>
        <?php

        }  

?>                           
                       
            </ul>
            <nav>
                    <span class="icon nav-prev"></span>
                    <span class="icon nav-next"></span>
                    <span class="icon nav-close"></span>
            </nav>
            <div class="info-keys icon">Navigate with arrow keys</div>
        </section><!-- // slideshow -->
    </div><!-- // grid-gallery -->
    
    
    <?php 
        
        $this->AssetCompress->script('grid_gallery', array('block' => 'scriptBottom'));
        echo $this->AssetCompress->css('grid_gallery');
    ?>    
    
</div>
<script>
    $(document).ready(function() {
        new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
    });
</script>