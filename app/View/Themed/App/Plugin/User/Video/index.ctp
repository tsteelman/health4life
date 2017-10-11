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
$this->Html->addCrumb('Videos', '');
?>
<?php $this->extend('Profile/view'); ?>
<div class="event_wraper">

    <div id="grid-gallery" class="grid-gallery">
        <section class="grid-wrap">
            <ul class="grid">
                <li class="grid-sizer"></li><!-- for Masonry column width -->

                
<?php


if(is_array($allVideos) && !empty($allVideos) ) {

    

        foreach ($allVideos as $video) {
            $video = json_decode($video)
        ?>
        
            <li>
                <figure>
                    
                    <?php
                    if (isset($video->thumbnail_url)) {
                        echo $this->Html->image($video->thumbnail_url, array(
                            'class' => 'img-responsive media-object',
                            'alt' => '...'
                        ));
                    }
                    ?>
                

                    
                </figure>
            </li>        

        <?php

        }
        } else {

        ?>
        <div class="alert alert-warning"><div class="message">No videos added by this user!</div>
        </div>            
        <?php

        }  

?>             
            </ul>
        </section><!-- // grid-wrap -->
        <section class="slideshow">
                <ul>
                <?php


if(is_array($allVideos) && !empty($allVideos) ) {

    

        foreach ($allVideos as $video) {
            $video = json_decode($video)
        ?>
        
             <li>
                <figure>
                    
                <div class="video_upload play_video" data-video_id="<?php echo $video->video_id; ?>">
                    <?php
                    if (isset($video->thumbnail_url)) {
                        echo $this->Html->image($video->thumbnail_url, array(
                            'class' => 'img-responsive media-object',
                            'alt' => '...'
                        ));
                    }
                    ?>
                </div>                    

                    
                </figure>
            </li>        

        <?php

        }
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
    
    /**
     * Function to play post video
     */
    $(document).on('click', '.play_video', function() {
            var $img = $(this).find('img.img-responsive');
            var $width = $img.width();
            var $height = $img.height();
            var $videoId = $(this).attr('data-video_id');
            $(this).embedVimeoPlayer($videoId, $width, $height);
    });    
</script>