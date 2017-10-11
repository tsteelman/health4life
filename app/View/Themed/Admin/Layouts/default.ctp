<?php echo $this->Html->docType('html5'); ?> 
<html>
    <head>
		<?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout; ?> - <?php echo Configure::read('App.name'); ?> Admin</title>
        <link rel="shortcut icon" href="/<?php echo Configure::read('App.favicon'); ?> " type="image/x-icon" />

		<?php
		echo $this->Html->meta(
				'description', 'enter any meta keyword here'
		);
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		?>

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<?php
		// Load common JS files compressed
		echo $this->AssetCompress->script('admin_common.js');
		
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('bootstrap-wysiwyg.min');
		?>

        <!--basic styles-->
		<?php
		echo $this->AssetCompress->css('bootstrap.min');
		echo $this->AssetCompress->css('bootstrap-responsive.min');
		echo $this->AssetCompress->css('font-awesome.min');
		?>

        <!--[if IE 7]>
		<?php echo $this->AssetCompress->css('font-awesome-ie7.min'); ?>
        <![endif]-->

        <!--ace and application specific styles compressed-->
		<?php echo $this->AssetCompress->css('admin_common.css'); ?>

        <!--[if lte IE 8]>
		<?php echo $this->AssetCompress->css('ace-ie.min'); ?>
        <![endif]-->

        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

    <body class="<?php if (!$loggedIn) echo 'login-layout'; ?>">

		<?php
		if ($loggedIn) {
			echo $this->element('layout/logged_in');
		} else {
			echo $this->element('layout/logged_out');
		}
		?>

        <!--inline scripts related to this page-->
        <script type="text/javascript">
			$(function() {


				var $tooltip = $("<div class='tooltip top in hide'><div class='tooltip-inner'></div></div>").appendTo('body');

				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();

					var off2 = $source.offset();
					var w2 = $source.width();

					if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
						return 'right';
					return 'left';
				}


			});

        </script>	
        <script type="text/javascript">
			var app = {
				loggedIn: '<?php echo $loggedIn; ?>',
				site_url: '<?php echo Router::url('/', true); ?>',
				socket_url: '<?php echo Configure::read('SOCKET.URL'); ?>',
				loggedInUserId: '<?php echo $userId; ?>',
                                show_site_notfications: false,
				graph_settings: {
					range_selector :{
			        	buttons: [{
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
			        	}]
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
		echo $this->fetch('scriptBottom');
		echo $this->AssetCompress->script('admin_bottom.js');
		?>
    </body>
</html>