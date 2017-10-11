<?php
    $defaultCoverPhoto = array( 
        "event" => array(
            '/theme/App/img/event_cover_bg.png',
            '/theme/App/img/event_cover_bg_1.png', 
        ),

        "profile" => array(
            '/theme/App/img/cover_bg.png',
            '/theme/App/img/cover_bg_1.png', 
        ), 

        "community" => array(
            '/theme/App/img/event_cover_bg.png',
            '/theme/App/img/event_cover_bg_1.png', 
        ),  

        "disease" => array(
            '/theme/App/img/disease_tile_bg.png',
            '/theme/App/img/disease_bg_1.png',
            '/theme/App/img/disease_bg_2.png', 
        )
    );


    $defaultPhoto = $defaultCoverPhoto[$coverType];
?>

<div id="cover_slideshow_container" class="hide">
    <input type="hidden" id="is_cover_slideshow_enabled" value="<?php echo $isSlideShowEnabled; ?>">
    <input type="hidden" id="cover_slide_2" value="<?php echo $defaultPhoto[1]; ?>">
	<ul class="bxslider">
		<?php if (!empty($photos)) : ?>
			<?php foreach ($photos as $photo): ?>
				<li><img src="<?php echo $photo['src']; ?>" /></li>
			<?php endforeach; ?>
                <?php else:
                        if(is_array($defaultPhoto)) {
                            foreach ($defaultPhoto as $coverphoto) {
                                echo '<li><img src="'.$coverphoto.'" /></li>';
                            }
                        } else {
                        ?>
                            <li><img src="<?php echo $defaultPhoto; ?>" /></li>
                        <?php
                        }
                    
                    ?>
		<?php endif; ?>
	</ul>
</div>
<script>
    
    $(document).ready(function(){
        var is_cover_slideshow_enabled = $('#is_cover_slideshow_enabled').val();
        if ( is_cover_slideshow_enabled === '1') {
                showSlideShow();
        }
    });
    
    var coverSlider = null;
    var slideShowOptions = {
            auto: true,
            minSlides: 1,
            maxSlides: 1,
            pager: false,
            speed: 500,
            pause: 3000,
            autoHover: true,
            mode: 'fade',
            controls: false
    };

    function showSlideShow() {
            $('#cover_image_container').addClass('hide');
            $('#cover_slideshow_container').removeClass('hide');
            var numberOfImages = $('.bxslider img').length;
            if( numberOfImages > 1) {
                coverSlider = $('.bxslider').bxSlider(slideShowOptions);
            }
    }
</script>