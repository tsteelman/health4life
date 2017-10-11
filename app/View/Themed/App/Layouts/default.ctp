<?php echo $this->Html->docType('html5'); ?> 
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout . ' - ' . Configure::read('App.name'); ?> </title>
        <link rel="shortcut icon" href="/<?php echo Configure::read('App.favicon'); ?> " type="image/x-icon" />
        <?php
        echo $this->Html->meta('description', Configure::read('App.name') . ' is a niche social network and life management tool for people with chronic illnesses. People who live with diseases need emotional support, tools and resources to help them understand their diseases/treatments/side effects/symptoms as well as tools and resources to help them manage their lives as they live with these diseases. Engaging with Patients 4 Life provides them a platform to help them manage their lives and build their support community.');
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1'));
        echo $this->fetch('meta');
        ?>
        
        <!--  Style for Arrowchat    -->
        <link type="text/css" rel="stylesheet" id="arrowchat_css" media="all" href="/arrowchat/external.php?type=css" charset="utf-8" />
        
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
		
		echo $this->AssetCompress->css('bootstrap.css');
		
		// Load common CSS files compressed
		if ($loggedIn):
			echo $this->AssetCompress->css('common_logged.css');
		else:
			echo $this->AssetCompress->css('common.css');
		endif;
		
        // Load CSS depending on page 
        echo $this->fetch('css');
		
		// Load common JS files compressed
		echo $this->AssetCompress->script('common.js');
		
        // Load JS depending on page
        echo $this->fetch('script');
        ?>
       
                    
    </head>
    <body class="<?php 
        if ($loggedIn): 
            echo "body_loggedin "; 
        else: 
            echo "body_loggedout "; 
        endif; 
        
        if($is_dashboard_page):
            echo " body_dash"; 
        endif;
    ?>">
      
      <?php if (isset($is_sharing_enabled) && $is_sharing_enabled):?>
        <?php echo $this->element('User.shareThis'); ?>
      <?php endif; ?>

        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <?php echo $this->element('layout/header'); ?>

        <!-- main_container -->
        <div class="main_container">
            <?php // if ($loggedIn): ?>
                <div class="container">
                    <div id="header-alert" class="alert" style="display:none;">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <div class="alert-content"></div>
                    </div>	
                    <?php echo $this->Session->flash(); ?>
                </div>
            <?php // endif; ?>

            <?php echo $content_for_layout; ?>
			<a class="back-to-top" href="#">Top</a>
        </div>
        <!-- /main_container -->

        <?php
        if ($loggedIn) {
            echo $this->element('medication_info_dialog');
            echo $this->element('compose_message');
            if (isset($promptHealthStatusUpdate) || isset($showHealthStatusSelector)) {
                echo $this->element('health_status_selector');
            }
        }
        echo $this->element('layout/footer');
        ?>
        <script type="text/javascript">
			var $appDateTime;
            var app = {
                loggedIn: '<?php echo $loggedIn; ?>',
                site_url: '<?php echo Router::url('/', true); ?>',
				appName : '<?php echo Configure::read ( 'App.name' ); ?>',
                user_timezone: '<?php echo $timezoneOffset; ?>',
				date_iso:'<?php echo date('c', time()); ?>',
				play_notification_sound : '<?php echo $notificationMusicSetting; ?>',
                imageExtensions: <?php echo json_encode(Configure::read('App.imageExtensions')); ?>,
                socket_url: '<?php echo Configure::read('SOCKET.URL'); ?>',
                show_site_notfications: false
                <?php if ($loggedIn) { ?>
                , loggedInUserId: '<?php echo $loggedin_userid; ?>'
                , loggedInUserName: '<?php echo $username; ?>'
                <?php } ?>,
                        
				graph_settings: {
					range_selector :{
			        	buttons: [, {
			        		type: 'week',
			        		count: 1,
			        		text: '1wk'
			        	}, {
			        		type: 'month',
			        		count: 1,
			        		text: '1m'
			        	}, {
			        		type: 'month',
			        		count: 3,
			        		text: '3m'
			        	}, {
			        		type: 'month',
			        		count: 6,
			        		text: '6m'
			        	}, {
			        		type: 'year',
			        		count: 1,
			        		text: '1y'
			        	}, {
			        		type: 'ytd',
			        		text: 'YTD'
			        	}],
			            buttonTheme: { // styles for the buttons

			                stroke: '#ccc',
			                style: {
			                    color: '#000',
			                    fill: '#fff',
			                    'stroke-width': 1,
			                    fontWeight: 'normal'
			                },
			                states: {
			                    hover: {
			                        fill: '#f0f0f0',
			                        style: {
			                            color: '#000'
			                        }
			                    },
			                    select: {
			                        fill: '#949494',
			                        stroke: '#575757',
			                        style: {
			                            color: 'white'
			                        }
			                    },
			                    enabled: {
			                        fill: 'white'
			                    }
			                }
			            },
			            selected: 1,
			            inputEnabled: false
			        },
			        graph_navigator: {
			            height: 30,            
			            series: {
			                type: 'areaspline',
			                color: '#C7C7C7',
			                fillOpacity: 0.4,
			                dataGrouping: {
			                    smoothed: true
			                },
			                lineWidth: 1,
			                lineColor: '#C7C7C7'

			            }
			        },
			        graph_scrollbar: {
			            barBackgroundColor: '#fff',
			            barBorderRadius: 0,
			            barBorderWidth: 1,
			            barBorderColor: '#cccccc',
			            buttonBackgroundColor: 'white',
			            buttonBorderWidth: 1,
			            buttonBorderColor: '#cccccc',
			            buttonArrowColor: '#b7b7b7',
			            buttonBorderRadius: 2,
			            trackBackgroundColor: '#ebebeb',
			            trackBorderWidth: 1,
			            trackBorderColor: '#cccccc',
			            trackBorderRadius: 2,
			            height: 20
			        }
				}
            };

        </script>

		<?php
		if ($loggedIn) :
			echo $this->AssetCompress->script('common_logged_bottom.js');
			?>
	        <script type="text/javascript" src="/arrowchat/external.php?type=djs" charset="utf-8"></script>
	        <script type="text/javascript" src="/arrowchat/external.php?type=js" charset="utf-8"></script>        
			<?php
		else:
			echo $this->AssetCompress->script('common_bottom.js');
		endif;
		
		echo $this->fetch('scriptBottom');
		echo $this->AssetCompress->includeJs();
		?>
                <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-continuous="false">
                    <!--The container for the modal slides -->
                   <div class="slides"></div>
                    <!--Controls for the borderless lightbox -->
                   <a class="prev">‹</a>
                   <a class="next">›</a>
                   <a class="close">×</a>
               </div>
                
    </body>
</html>