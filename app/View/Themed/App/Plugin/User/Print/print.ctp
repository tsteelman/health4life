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
				<div id="pain_type_graph_container_<?php echo ($key); ?>" class="col-lg-12 pain_graph_container graph_container">
					<h3><?php echo $title; ?></h3>
					<div id="pain_graph_container_<?php echo ($key); ?>"></div>  
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
        var series_name = 'Pain Graph';
        var container = "#pain_graph_container_1";
        var container_temp = "#pain_graph_container_";
        var options = setPainGraphOptions();
        $(document).on('click', '.pain_graph_navigator', function() {
            var graph_index = $(this).data('graph-index');
            var graph_offset = $("#pain_type_graph_container_" + graph_index).offset();
            var header_height = 100; //px
            var scroll_to = parseInt(graph_offset.top) - header_height;
            $("html, body").animate({scrollTop: scroll_to + 'px'}, 500, "easeInOutQuart");
        });
        /*To create array of values*/
        for (var i = 1; i <= no_of_graphs; i++) {
            if (typeof readings_values[i] != 'undefined') {
                painData[i] = [];
                for (var j = 1; j <= 7; j++) {
                    painData[i][j] = [];
                    if (typeof readings_values[i][j] != 'undefined') {
                        painData[i][j] = $.map(readings_values[i][j], function(value, index) {
                            return [[parseInt(index) * 1000, value]]
                        });
                    } else {
                        painData[i][j] = null;
                    }
                }
            } else {
                painData[i] = null;
            }
        }


//        plot_stock_graph(container, read, series_name, options);
        for (var x = 1; x <= no_of_graphs; x++) {
            var container_now = container_temp + x;
            plot_stock_graph(container_now, painData[x], series_name, options);
        }


        function plot_stock_graph(container, readings, series_name, options) {
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
//                    text: title,
                    floating: true,
                    style: {display: 'none'}
                },
                legend: {
                    enabled: true,
                    align: 'right', backgroundColor: '#FCFFC5',
                    borderColor: 'black', borderWidth: 2,
                    layout: 'vertical',
                    verticalAlign: 'top',
                    y: 20,
                    shadow: true
                },
                rangeSelector: app.graph_settings.range_selector,
                navigator: app.graph_settings.graph_navigator,
                scrollbar: app.graph_settings.graph_scrollbar,
                series: [{
                        name: 'Numbness',
                        color: '#00C8FF',
                        data: (readings != null) ? readings[1] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    },
                    {
                        name: 'Pins & Needles',
                        color: '#C69C6D',
                        data: (readings != null) ? readings[2] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    },
                    {
                        name: 'Burning',
                        color: '#ED1C24',
                        data: (readings != null) ? readings[3] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    },
                    {
                        name: 'Stabbing',
                        color: '#FF8A00',
                        data: (readings != null) ? readings[4] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    },
                    {
                        name: 'Throbbing',
                        color: '#DB76E0',
                        data: (readings != null) ? readings[5] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    },
                    {
                        name: 'Aching',
                        color: '#920F14',
                        data: (readings != null) ? readings[6] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    },
                    {
                        name: 'Cramping',
                        color: '#27a348',
                        data: (readings != null) ? readings[7] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
                    }
                ],
                noData: {
//                    style: {
//                        fontWeight: 'bold',
//                        fontSize: '15px',
//                        color: '#303030'
//                    },
                    position: {
                        verticalAlign: 'bottom'
                    }
                }
            };
            if (options) {
                $.extend(defaultOptions, options);
            }

            $(container).highcharts('StockChart', defaultOptions);           
        }
        function getPainPlotBandSettings() {
            return  [{from: 0, to: 1, color: '#ffffff'},
                {from: 1, to: 2, color: '#ffffff'},
                {from: 2, to: 3, color: '#ffffff'},
                {from: 3, to: 4, color: '#ffffff'},
                {from: 4, to: 5, color: '#ffffff'},
                {from: 5, to: 6, color: '#ffffff'},
                {from: 6, to: 7, color: '#ffffff'},
                {from: 7, to: 8, color: '#ffffff'},
                {from: 8, to: 9, color: '#ffffff'},
                {from: 9, to: 10, color: '#ffffff'}];
        }
        /**
         * Settings only for health status graph          */


        function setPainGraphOptions() {
            return {yAxis: {
                    labels: {
//                        useHTML: true,
                        align: "right", x: -2,
                        y: 15,
                        formatter: function() {
                            return this.value;
                        }
                    },
                    plotBands: getPainPlotBandSettings(),
                    max: 10,
                    min: 0,
                    title: {
                        text: 'Severity'
                    },
                    showLastLabel: true,
                    endOnTick: true,
                    tickInterval: 1
                },
             tooltip: {
              enabled: false
            }
           };
        }
    });

</script>