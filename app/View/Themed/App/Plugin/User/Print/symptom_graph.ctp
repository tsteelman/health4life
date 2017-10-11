<div class="container">
	<div class="comingsoon_text p4l_text">
		<img src="/theme/App/img/logo_120.png">
	</div>
	<h3><?php echo $userName; ?></h3>
	<h5>Health record <?php if(!empty($startDate)) { ?>
		from <?php echo $startDate; ?> to <?php echo $endDate; ?> 
		<?php } ?> 
	</h5>
	<h5>Created at <?php echo $date; ?></h5>
	<div class="graph_container">
        <div>
            <?php foreach ($graphTitles['title'] as $key => $title) { ?>
				<div id="tracker_type_graph_container_<?php echo ($key); ?>" class="col-lg-12 pain_graph_container graph_container">
					<h3><?php echo $title; ?></h3>
					<div id="tracker_container_<?php echo ($key); ?>"></div>  
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
    var readings_values = <?php echo json_encode($healthValues); ?>;
	var no_of_graphs = <?php echo count($graphTitles['title']); ?>;
    var painData = new Array();
    $(document).ready(function() {
        var series_name = 'Tracker Graph';
        var container = "#tracker_container_1";
        var container_temp = "#tracker_container_";
        var options = setSymptomGraphOptions();
        $(document).on('click', '.pain_graph_navigator', function() {
            var graph_index = $(this).data('graph-index');
            var graph_offset = $("#tracker_type_graph_container_" + graph_index).offset();
            var header_height = 100; //px
            var scroll_to = parseInt(graph_offset.top) - header_height;
            $("html, body").animate({scrollTop: scroll_to + 'px'}, 500, "easeInOutQuart");
        });
        /*To create array of values*/
        for (var i = 1; i <= no_of_graphs; i++) {
            if (typeof readings_values[i] != 'undefined') {
                painData[i] = [];
                    if (typeof readings_values[i] != 'undefined') {
                        painData[i] = $.map(readings_values[i], function(value, index) {
                            return [[parseInt(index) * 1000, value]]
                        });
                    } else {
                        painData[i] = null;
                    }
            } else {
                painData[i] = null;
            }
        }
		
        for (var x = 1; x <= no_of_graphs; x++) {
            var container_now = container_temp + x;
            plot_stock_graph(container_now, painData[x], series_name, options);
        }

        function plot_stock_graph(container, readings, series_name, options) {
			var is_marker = false;
			if (readings.length <= 1) {
				is_marker = true;
			}
            defaultOptions = {
                credits: {
                    enabled: false
                },
                navigation: {
                    buttonOptions: {
                        enabled: false
                    }
                },
                title: {
                    floating: true,
                    style: {display: 'none'}
                },
                rangeSelector: app.graph_settings.range_selector,
                navigator: app.graph_settings.graph_navigator,
                scrollbar: app.graph_settings.graph_scrollbar,
				plotOptions: {
                series: {
                    color: '#469fdd',
                    lineWidth: 3,
                    marker: {
                        enabled: is_marker
						}
					}
				},
				 series: [{
                    data: readings,
                    tooltip: {
                        valueDecimals: 2
                    }
                }],
				tooltip: {
					enabled: false
				},
				lang: {
					noData: "No data available"
				},
				noData: {
					position: {
						verticalAlign: 'bottom'
					},
					style: {
						fontWeight: 'normal',
						fontSize: '15px',
						color: '#303030'
					}
				}
            };
            if (options) {
                $.extend(defaultOptions, options);
            }

            $(container).highcharts('StockChart', defaultOptions);           
        }
        
		function setSymptomGraphOptions() {
			return {yAxis: {
						allowDecimals: false,
						title: {
							floating: true,
							style: {display: 'block;'}
						},
						labels: {
							useHTML: true,
							align: "right",
							x: 0,
							y: -28,
							formatter: function() {
								return getSeverityGraphLabel(this.value, 1);
							}
						},
						plotBands: getSymptomPlotBandSettings(),
						max: 5,
						min: 1,
						gridLineColor: '#e1e1e1',
						showLastLabel: true,
						endOnTick: true,
						tickInterval: 1
				 },
				 xAxis: {
					ordinal: false           
				 },
				 tooltip: {
					enabled: false
				}
			};
		}
    });

</script>