<?php
$count = 0;
$imageArray = array();
$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/post_photos/';

if(isset($postPhotos) && !empty($postPhotos)) {
    foreach($postPhotos as $photos) {
        $imageArray[] = $photoPath . $photos['Photo']['file_name'];
    }
    $count = count($imageArray);
}
?>

<div class="posting_area" id="post_<?php echo $postId; ?>">
    <?php echo $this->element('Post.post_icon'); ?>
    <div class="media">
        <?php echo $this->element('Post.profile_img'); ?>
        <?php echo $this->element('Post.remove_from_library_btn'); ?>
        <div class="media-body">
            <?php echo $this->element('Post.username'); ?>
            <?php echo $this->element('Post.posted_in_details'); ?>
            <?php echo $this->element('Post.description'); ?>
            <div id="links" class="video_upload row">
                <?php
                for ($i = 0; $i < $count; $i++) {
                    if ($count == 1) {
                        ?>
                        <a href="<?php echo $imageArray[$i]; ?>" 
                           data-gallery="#modal_<?php echo $postId; ?>">
                            <img class="media-object img-responsive image_gallery" src="<?php echo $imageArray[$i]; ?>"
                                 alt="<?php echo $imageArray[$i]; ?>">
                        </a>
                        <?php
                    } else {
                        if ($i <= 3 ) {
                            if ($i != 2 || $count != 3) {
                                ?>
                                <a href="<?php echo $imageArray[$i]; ?>" 
                                   data-gallery="#modal_<?php echo $postId; ?>" class="col-lg-5">
                                    <img class=" media-object img-responsive image_gallery"
                                         src="<?php echo $imageArray[$i]; ?>" alt="<?php echo $imageArray[$i]; ?>">
                                </a>
                                <?php
                            } else {
                                ?>
                                <a href="<?php echo $imageArray[$i]; ?>" 
                                   data-gallery="#modal_<?php echo $postId; ?>" class="col-lg-5 hidden">
                                </a>
                                <?php
                            }
                        } else {
                            ?>
                            <a href="<?php echo $imageArray[$i]; ?>" 
                               data-gallery="#modal_<?php echo $postId; ?>" class="col-lg-5 hidden">
                            </a>
                            <?php
                        }
                    }
                    ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>