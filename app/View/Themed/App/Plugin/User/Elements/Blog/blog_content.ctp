<?php

if ($isOwnProfile) {
    $profileLink = '/profile';
}
else {
    $profileLink = '/profile/'.$user_details['username'];
}

?>
<div class='blog_section'>
    <?php
        if(!empty($latestpost_blog)) {    
     ?>
    
    <div class='blog_header'>
        <h1 class="latest_blog_title"><?php echo $latestpost_blog['title']; ?></h1>
        <div class='last_activites clearfix'>
            <div class='last_comment pull-left'><?php 
            echo isset($latestpost_blog['latestComments'][0]) ? 
            $latestpost_blog['latestComments'][0]['commentText'] : "No comments yet"; ?></div>
            <a href="<?php echo $profileLink."/blog?view=blog"; ?>"><div class='comment_time pull-right'><?php echo $latestpost_blog['postedTimeAgo']; ?></div></a>
        </div>
    </div>
    <?php
        } else {
    ?>
    <div class='blog_header'>
        <h1 class="latest_blog_title">No blog added yet!</h1>
        <div class='last_activites clearfix'>
            <div class='last_comment pull-left'></div>
            <a href="<?php echo $profileLink."/blog?view=blog"; ?>"><div class='comment_time pull-right'>&nbsp;</div></a>
        </div>
    </div>    
    <?php
        }
    ?>
    <div class='blog_media'>
        <div class='clearfix'>
            <div class='blog_photos pull-left'>
                <div class='recent_tag'>
                    <a href="<?php echo $profileLink."/photo"; ?>">Recent Photos</a>
                </div>    

                    <?php 
                    if(is_array($recentPhotos) && !empty($recentPhotos) ) {
                    ?>
                    <div class='photo_div'>
                    
                    <?php
                        
                    $photoUrlPath = Configure::read('App.UPLOAD_PATH_URL') . '/post_photos/';
                    $previewFilePath = Configure::read('App.POST_IMG_PATH') . DIRECTORY_SEPARATOR;
                    
                    $count = 1;
                    foreach ($recentPhotos as $photo) {
                        ++$count;
                        if($count > 5) break;
                        
                        $photoFileName = $photo['Photo']['file_name'];
                        $originalPhoto = $photoUrlPath . $photoFileName;
                        $previewFile = $previewFilePath . 'preview_' . $photoFileName;
                        if (file_exists($previewFile)) {
                                $previewPhoto = $photoUrlPath . 'preview_' . $photoFileName;
                        } else {
                                $previewPhoto = $originalPhoto;
                        }
                    ?>
                    
                    <div class="media_box_<?php echo $count; ?>"><img class="media-object img-responsive image_gallery"
                        src="<?php echo $previewPhoto; ?>" alt="<?php echo $photoFileName; ?>" /></div>
                    <?php
                       
                    }  
                    ?>                    
                    </div>
                    <?php
                    } else {
                        
                    ?>
                    <div class="" style="margin:150px 0px 0px 100px;color: #FFF;font-size: 14px; display: none;">
                        No Images added by this user
                    </div>
                    <?php
                       
                    }  
                    
                    ?>
                
<!--                <div class='recent_tag_inverse'>
                    Happy time with Family
                </div>-->
               
            </div>
            <div class='blog_videos pull-right'>
                <div class='recent_tag'>
                    <a href="<?php echo $profileLink."/video"; ?>">Recent Videos</a>
                </div>
                <div class='video_div'>
<?php
    if(is_array($recentVideos) && !empty($recentVideos) ) {
            $count = 1;
           foreach ($recentVideos as $video) {
               ++$count;
                if($count > 5) break;
            $video = json_decode($video)
        ?>
                    <div class="media_box_<?php echo $count; ?>">
                        
                    <?php
                    if (isset($video->thumbnail_url)) {
                        echo $this->Html->image($video->thumbnail_url, array(
                            'class' => 'img-responsive media-object',
                            'alt' => '...'
                        ));
                    }
                    ?>
                    </div>

        <?php

        }
        } 

        ?>                
                

                </div>
            </div>
        </div>
        
    <div class='blog_chat'>
        <div class='recent_tag'>
            <a href="<?php echo $profileLink."/blog?view=ecard"; ?>">What your friends say</a>
        </div>        
<?php
    
    if(isset($latestpost_ecard) && !empty($latestpost_ecard)) {
?>        

        <div class='clearfix'>
            <div class='chat_detail '>
                <h3><?php echo $latestpost_ecard['postedUserName'] ?> says:</h3>
                <div class='blog_comment clearfix'>
                    <div class='pull-left'>
                       <p><?php echo $latestpost_ecard['title'] ?></p>
                        <span><?php echo $latestpost_ecard['postedTimeAgo'] ?></span> 
                    </div>                    
                    <ul class="ecard_templates ecard_view">
                    <?php 
                    $ecards = $latestpost_ecard['ecards'];
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
            </div>
            <div class='chat_reply'>
                <?php if(isset($latestpost_ecard['latestComments'][0])): ?>
                <p><?php echo $latestpost_ecard['latestComments'][0]['commentedUserLink'] ?>: 
                    <?php echo $latestpost_ecard['latestComments'][0]['commentText'] ?></p>
                <?php endif; ?>
            </div>
        </div>
    
<?php

    } else {
?>    
        <div class='clearfix'>
            <div class='chat_detail '>
                <h3 style="padding: 25px 0px;">No e-cards received!</h3>
             </div>  
        </div>  
<?php

    }
?>      </div>     
    </div>    
</div>