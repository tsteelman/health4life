<div class="home_video">
    <img src="/theme/App/img/tmp/home_video_bg.png">
	<div class="dashboard_video_play pull-right"></div>
            <div class="video_minimize_icon hide" id="profile_video_minimize_icon"><img src="/theme/App/img/minimize_icon.png" /></div>
            <div id="profile_video_container_wrapper" >                    
                <div id="profile_video_container">&nbsp;</div>
			</div>
			
             <script type='text/javascript'>
                    <?php
                    if (isset($currentUserDetails['advertisementVideos']) && !empty($currentUserDetails['advertisementVideos'])) {
                        ?>
                        var jwFile = '<?php echo $currentUserDetails['advertisementVideos'][0]; ?>';
                        <?php
                    } else {
                        ?>
                        var jwFile = 'https://www.youtube.com/watch?v=XHXohR0EEDs';
                        <?php
                    }
                    ?>

            </script>
</div>
<script>
    $(document).ready(function(){
             
        initJWPlyer();
        
        if (isIE () == 9 || isIE11()) {
                $('#profile_video_container_wrapper').hide();
        }
    });
    
    function initJWPlyer() {
        jwplayer('profile_video_container').setup({
                    file: jwFile,
                    image: '/theme/App/img/tmp/home_video_bg.png',
                    width: 565, 
                    height: 317,
                    displaytitle: false,
                    stretching: 'exactfit'
        });
    }
</script>
<?php
$this->Html->script(array('//jwpsrv.com/library/+wt_PpJBEeOk_yIACmOLpg.js'), array('inline' => false));
