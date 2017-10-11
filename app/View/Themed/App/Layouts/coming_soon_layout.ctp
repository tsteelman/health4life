<?php echo $this->Html->docType('html5'); ?> 
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <link type="text/css" rel="stylesheet" id="arrowchat_css" media="all" href="/arrowchat/external.php?type=css" charset="utf-8" />
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout . ' - ' . Configure::read('App.name'); ?> </title>
        <?php
        echo $this->Html->meta('favicon.ico', '/favicon.ico?12345', array('type' => 'icon'));

        echo $this->Html->meta('description', Configure::read('App.name') . ' is a niche social network and life management tool for people with chronic illnesses. People who live with diseases need emotional support, tools and resources to help them understand their diseases/treatments/side effects/symptoms as well as tools and resources to help them manage their lives as they live with these diseases. Engaging with Patients 4 Life provides them a platform to help them manage their lives and build their support community.');
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1'));
        echo $this->fetch('meta');
        ?>
        
        <!--  meta tag for share    -->
        
        <meta property="og:type" content="website" />   
        <meta property="og:url" content="<?php echo Router::url( $this->here, true ); ?>" /> 
        <meta property="og:site_name" content="<?php echo Configure::read('App.name'); ?>" />
        <?php if ( isset( $meta_og_title ) &&  ( !empty( $meta_og_title )) ) { ?>
                    <meta property="og:title" content="<?php echo $meta_og_title . ' - ' . Configure::read('App.name');?>" />
        <?php }
               
               if ( isset( $meta_og_disc ) &&  ( !empty( $meta_og_disc )) ) { ?>
                    <meta property="og:description" content="<?php echo h($meta_og_disc); ?>" />
        <?php }
               
               if ( isset( $meta_og_image ) &&  ( !empty( $meta_og_image )) ) { ?>
                    <meta property="og:image" content="<?php echo $meta_og_image; ?>"/>
        <?php } else { ?>
                    <meta property="og:image" content="<?php echo Router::Url ( '/', TRUE ); ?>theme/App/img/logo_blue_1200.png"/>
        <?php } ?>
					
        <?php
            echo $this->Html->css('bootstrap.min');
            echo $this->Html->css('bootstrap-theme.min');
            echo $this->Html->css('coming-soon');
            
            echo $this->Html->script('vendor/html5shiv');
            echo $this->Html->script('vendor/modernizr-2.6.2-respond-1.1.0.min');
            echo $this->Html->script('vendor/jquery-1.10.1.min');
            echo $this->Html->script('vendor/bootstrap.min');
            echo $this->Html->script('vendor/jquery.validate.min');
            echo $this->Html->script('vendor/jquery.browser');
        ?>
       
        
        
    </head>
    <body>


       <div class="main_container">            
                <div class="container">
                    <div id="header-alert" class="alert" style="display:none;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="alert-content"></div>
                    </div>	
                    <?php echo $this->Session->flash(); ?>
                </div>           

            <?php echo $content_for_layout; ?>
            
        </div>
        <!-- /main_container -->

        

	
    </body>
</html>