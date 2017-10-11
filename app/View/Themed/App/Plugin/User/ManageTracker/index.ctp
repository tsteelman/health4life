<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Health', '/profile/myhealth');
$this->Html->addCrumb('Tracker', '/profile/'.$user_details['username'].'/tracker');
$this->Html->addCrumb('Manage Tracker');
?>

<div class="container">
    <div class="mysymptoms">
		<!--<h2><?php echo __(h($tableTitle)) ?></h2>-->   
		<div class="graph_container">
        
			<div class="history_container">    
				<div class="row"> 
					<div class="col-lg-6">
						<h3>
							<?php echo __(h($tableTitle)) ?>
						</h3>
					</div>
				</div> 
				<br/>
				<div class="row">
					<div class="col-lg-11 col-sm-11 col-xs-11 col-md-11" id="tracker_graph_container" data-username="<?php echo $user_details['username']; ?>" style="height: 298px;">

					</div>    
				</div>
	
				<div class="tracker_detail  row">    	 
					<div class="col-lg-12"> 
						<h5><?php echo __(h($tableTitle)) ?> History</h5>
					</div>
					<div id="tracker_history_row" ></div>
				</div>
		</div>
     </div>
   </div>
</div>

<?php
	echo $this->AssetCompress->script('chart.js');
	echo $this->AssetCompress->css('graph');
?>

<script type="text/javascript">
    $(function() {
        load_tracker_graph();
		load_tracker_history();
    });

    /**
     * Load stock graph
     * 
     * @param Integer graph_type
     * @returns {undefined}
     */
    function load_tracker_graph(graph_type) {
        var username = "<?php echo ($is_same) ? '' : $user_details['username']; ?>";
		var recordType = "<?php echo $record_type; ?>";
        $.ajax({
            async: true,
            type: "post",
            data: {'graph_type': graph_type, 'username': username},
            dataType: 'json',
            success: function(data) {

                var tracker_result = [];
				if(recordType == 1) {
					for (var i in data.pain_tracker)
						tracker_result.push([(i * 1000), (parseInt(data.pain_tracker[i]) * 1) - .5]);
                                            vysak = data.pain_tracker;
				} else if(recordType == 2) {
					for (var i in data.life_quality_tracker)
						tracker_result.push([(i * 1000), (parseInt(data.life_quality_tracker[i]) * 1) - .5]);
				} else if(recordType == 3) {
					for (var i in data.sleeping_tracker)
						tracker_result.push([(i * 1000), (parseInt(data.sleeping_tracker[i]) * 1) - .5]);
				}
                
                // Plot health chart
                var options = setHealthGraphOptions();

                plot_stock_graph('#tracker_graph_container', tracker_result, 'Pain', options);
            },
            url: '/user/api/getHealthReadings'
        });
    }

    /**
     * Function to plots graph
     */
    function plot_stock_graph(container, readings, series_name, options) {
        var is_marker = false;
        if (readings.length <= 1) {
            is_marker = true;
        }
        defaultOptions = {credits: {
                enabled: false
            },
            navigation: {
                buttonOptions: {
                    enabled: false
                }
            }, title: {
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
                    name: series_name,
                    data: readings,
                    tooltip: {
                        valueDecimals: 2
                    }
                }]
        };
        if (options) {
            $.extend(defaultOptions, options);
        }

        $(container).highcharts('StockChart', defaultOptions);
        if(app.show_site_notfications) {
            showSiteNotification('Updated Current health graphs', 'info');
        }
    }

    /**
     * Settings only for health status graph
     */
    function setHealthGraphOptions() {
        return {yAxis: {labels: {
                    useHTML: true,
                    align: "right",
                    x: -2,
                    y: 15,
                    formatter: function() {
                        return getHealthLabel(this.value, 2);
                    }
                },
                plotBands: getHealthPlotBandSettings(),
                max: 5,
                min: 0,
                showLastLabel: true,
                endOnTick: true,
                tickInterval: 1,
                gridLineColor: '#e1e1e1'
            },
            xAxis: {
                ordinal: false           
            },
            tooltip: {
                useHTML: true,
                formatter: function() {
                    return getHealthLabel(this.y, 1);
                }
            }
        };
    }

	/**
     * Function to display history
     */
	function load_tracker_history() {
		var recordType = "<?php echo $record_type; ?>";
		var username = $("#tracker_graph_container").data('username');
		$.ajax({
			url: '/tracker/history/list',
			cache: false,
			type: 'POST',
			data: {'type': recordType, 'username': username},
			success: function(result) {
				$('#tracker_history_row').append(result);
			}
		});
	}

</script>
