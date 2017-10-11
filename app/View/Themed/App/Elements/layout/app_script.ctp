<script type="text/javascript">
            var app = {
                loggedIn: '<?php echo $loggedIn; ?>',
                site_url: '<?php echo Router::url('/', true); ?>',
                user_timezone: '<?php echo $timezoneOffset; ?>',
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