<?php
    $year = date('Y');
    $prev_year = $year - 1;
?>
<div class="sticky-stopper"> </div>
<footer>
    <div class="footer_container container">
        <span><?php echo ('Copyright &copy; ' .$prev_year."-" .$year." " . Configure::read('App.name') . ' All Rights Reserved (Patent pending).'); ?></span>
        <ul class="footer_links">
            <?php if ($this->here != "/") { ?>
                <li><a href="/">Home</a></li>
            <?php } ?>            
            
            <li><a href="/pages/about">About</a></li>
            <li><a href="/pages/terms_of_service">Terms of Service</a></li>
            <li><a href="/pages/contact_us">Contact</a></li>
            <li><?php //echo $this->element('layout/translate'); ?></li>       
        </ul>
         <ul class="footer_social_icons">
            <li><a class="fb_link" href="<?php echo Configure::read('App.fbLink'); ?>" target="_blank"></a></li>
            <li><a class="twitter_link" href="<?php echo Configure::read('App.twitterLink'); ?>" target="_blank"></a></li>
            <li><a class="google_link" href="<?php echo Configure::read('App.googleLink'); ?>" target="_blank"></a></li>          
            <li><a class="linkedin_link" href="<?php echo Configure::read('App.linkedInLink'); ?>" target="_blank"></a></li>
            <li><a class="youtube_link" href="<?php echo Configure::read('App.youtubeLink'); ?>" target="_blank"></a></li>
        </ul>
    </div>
</footer>