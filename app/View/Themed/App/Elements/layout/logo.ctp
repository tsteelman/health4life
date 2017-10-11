<?php
    $logo_image = $this->Html->image(Configure::read('App.logo'), array('class'=>'normal_logo'));
    $logo_image_small = $this->Html->image('responsive_logo.png', array('class'=>'responsive_logo'));
    $disease_logo = FALSE;
    if(isset($disease)){
        $home_page_content = json_decode($disease['dashboard_data'], TRUE);
        if ($home_page_content['profile_image'] != '') {
            $disease_logo = TRUE;
            $logo_image = $this->Html->image('/uploads/disease_logos/' . Common::getDiseaseLogo($home_page_content['profile_image']));
        }
    }
?>
 <a class="navbar-brand <?php echo $disease_logo ? 'logo_div' : ''; ?>" href="/">
    <?php echo $logo_image; ?>
     <?php echo $logo_image_small; ?>
</a>