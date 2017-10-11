<div class="container print_container">
	<?php $graphCount = count($graphTitles['title']); ?>
	<div class="print_logo">
		<img src="/theme/App/img/logo_h4l_120.png">
	</div>
	<h3><?php echo $userName; ?></h3>
	<h5>Health record <?php if(!empty($startDate)) { ?>
		from <?php echo $startDate; ?> to <?php echo $endDate; ?> 
		<?php } ?> 
	</h5>
	<h5 style="margin-bottom: 30px;">Created at <?php echo $date; ?></h5>
            <?php foreach ($graphTitles['title'] as $key => $title) { ?>
				<div class="graph_container">
					<div id="health_graph_container_<?php echo ($key); ?>">
						<h3><?php echo $title; ?></h3>
						<div id="graph_container_<?php echo ($key); ?>">
							<?php if($title == "Medication Scheduler") {  
								if(empty($healthValues[$key])) { ?>
									<div class="print_table" style="text-align: center">No data to display</div>
								<?php } else {  ?>
									<div class="print_table">
										<table class="table">
												<thead>
													<tr>
														<th><?php echo __('Medication'); ?></th>
														<th><?php echo __('Dose'); ?></th>
														<th><?php echo __('Form'); ?></th>
														<th><?php echo __('Frequency'); ?></th>
														<th><?php echo __('Times(s)'); ?></th>
														<th><?php echo __('Number/Amount'); ?></th>
														<th><?php echo __('Route'); ?></th>
														<th><?php echo __('Additional Instructions'); ?></th>
														<th><?php echo __('Prescribing provider'); ?></th>
														<th><?php echo __('Indication'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($healthValues[$key] as $index => $medication) :
														$rowClass = ($index % 2 === 0) ? 'alternative_row' : '';
														?>
														<tr class="<?php echo $rowClass; ?>">
															<td class="medication_type"><?php echo h($medication['name']); ?></td>
															<td><?php echo h($medication['strength']); ?></td>
															<td><?php echo h($medication['form']); ?></td>
															<td><?php echo h($medication['frequency']); ?></td>
															<td><?php echo h($medication['time']); ?></td>
															<td><?php echo h($medication['amount']); ?></td>
															<td><?php echo h($medication['route']); ?></td>
															<td><?php echo h($medication['additional_instructions']); ?></td>
															<td><?php echo h($medication['prescribed_by']); ?></td>
															<td><?php echo h($medication['indication']); ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
							<?php } } ?>
						</div>  
					</div>	
				</div>
			<?php } ?>
</div>

<script type="text/javascript">
    var readings_values = <?php echo json_encode($healthValues); ?>;
	var graph_type = <?php echo json_encode($graphTitles['type']); ?>;
	var no_of_graphs = <?php echo count($graphTitles['title']); ?>;
    var painData = new Array();
    $(document).ready(function() {
        var series_name = 'Health Graph';
        var container = "#graph_container_1";
        var container_temp = "#graph_container_";
        //var options = setHealthGraphOptions();
        $(document).on('click', '.pain_graph_navigator', function() {
            var graph_index = $(this).data('graph-index');
            var graph_offset = $("#health_graph_container_" + graph_index).offset();
            var header_height = 100; //px
            var scroll_to = parseInt(graph_offset.top) - header_height;
            $("html, body").animate({scrollTop: scroll_to + 'px'}, 500, "easeInOutQuart");
        });
        /*To create array of values*/
        for (var i = 1; i <= no_of_graphs; i++) {
            if (typeof readings_values[i] != 'undefined') {
                painData[i] = [];
				if (typeof readings_values[i][0] != 'undefined' || typeof readings_values[i][1] != 'undefined') {
					for (var j = 1; j <= 7; j++) { // 7 pain data in body pain tracker
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
					if (typeof readings_values[i] != 'undefined') {
                        painData[i] = $.map(readings_values[i], function(value, index) {
                            return [[parseInt(index) * 1000, parseFloat(value)]]
                        });
                    } else {
                        painData[i] = null;
                    }
				}
            } else {
                painData[i] = null;
            }
        }
		
        for (var x = 1; x <= no_of_graphs; x++) {
			// bp graphs & pain graphs are multi dimensional
			var is_multi_dimensional = 0; 
            var container_now = container_temp + x;
			if(graph_type[x] == 'bp') {
				is_multi_dimensional = 1;
				plot_stock_graph(container_now, painData[x], series_name, setNormalHealthOptions(), is_multi_dimensional, x);
			} else if(graph_type[x] == 'tracker') {
				plot_stock_graph(container_now, painData[x], series_name, setHealthGraphOptions(), is_multi_dimensional, x);
			} else if(graph_type[x] == 'health') {
				plot_stock_graph(container_now, painData[x], series_name, setNormalHealthOptions(), is_multi_dimensional, x);
			} else if(graph_type[x] == 'pain') {
				is_multi_dimensional = 2;
				plot_stock_graph(container_now, painData[x], series_name, setPainGraphOptions(), is_multi_dimensional, x);
			} else if(graph_type[x] == 'symptom') {
				plot_stock_graph(container_now, painData[x], series_name, setSymptomGraphOptions(), is_multi_dimensional, x);
			} 
        } 
		
        function plot_stock_graph(container, readings, series_name, options, is_multi_dimensional, index) {
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
				legend: {
                    enabled: (is_multi_dimensional == 0) ? false : true ,
                    align: 'right', backgroundColor: '#FCFFC5',
                    borderColor: 'black', borderWidth: 2,
                    layout: 'vertical',
                    verticalAlign: 'top',
                    y: 20,
                    shadow: true
                },
                rangeSelector: app.graph_settings.range_selector,
				navigator : { enabled : false},
                scrollbar: app.graph_settings.graph_scrollbar,
				plotOptions: {
                series: {
                    color: '#469fdd',
                    lineWidth: 3,
                    marker: {
                        enabled: true
						}
					}
				},
				series : (is_multi_dimensional == 0) ? [ get_health_options(series_name, readings) ] : get_multi_dimensional_options(series_name, readings,is_multi_dimensional) ,
				tooltip: {
					enabled: false
				},
				lang: {
					noData: "No data to display"
				},
				noData: {
                    style: {
                        fontWeight: 'bold',
                        fontSize: '15px',
                        color: '#303030'
                    },
                    position: {
                        verticalAlign: 'bottom'
                    }
                },
				chart: {
					events: {
						load: function() {
							setTimeout(function (event) {
								if(index == no_of_graphs || graph_type[index+1] == 'scheduler' ) {
									window.print();
								}
							}, 1000);
						}
					}        
				}
            };
            if (options) {
                $.extend(defaultOptions, options);
            }

            $(container).highcharts('StockChart', defaultOptions);           
        }
        
		function setHealthGraphOptions() {
			return {yAxis: {
					labels: {
						useHTML: true,
						align: "right",
						x: -2,
						y: 15,
						formatter: function() {
							return getSmileyLabel(this.value);
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
					type: 'datetime',
					minTickInterval: 24 * 3600 * 1000
				},
				 tooltip: {
					enabled: false
				}
			};
		}
		
		function get_health_options(series_name, readings) {
			
				return {
						name: series_name,
						data: readings,
						tooltip: {
							valueDecimals: 2
						}
				};
		}
		
		function get_multi_dimensional_options(series_name, readings) {
			if(is_multi_dimensional == 1) {
											// options for bp graph
				return [{ 
						
                        name: 'systolic',
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
                        name: 'diastolic',
                        color: '#00C8FF',
                        data: (readings != null) ? readings[2] : null,
                        tooltip: {
                            valueDecimals: 2
                        },
                        marker: {
                            enabled: true
                        }
					  }];
				  
			} else if(is_multi_dimensional == 2) {
										// options for body pain tracker graph
					return [{
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
                    }];
			}
		}
		
		function setPainGraphOptions() {
            return {yAxis: {
                    labels: {
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
				xAxis: {
					ordinal: false,
					type: 'datetime',
					minTickInterval: 24 * 3600 * 1000
				},
             tooltip: {
              enabled: false
            }
           };
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
					ordinal: false,
					type: 'datetime',
					minTickInterval: 24 * 3600 * 1000
				 },
				 tooltip: {
					enabled: false
				}
			};
		}
		
		function setNormalHealthOptions() {
			return {
				 xAxis: {
					ordinal: false 
				 }
			};
		}
		
		function getSmileyLabel(value) {
			var img = "";
			switch (Math.ceil(value)) {
				case 5:
					img = "<img src='/theme/App/img/very_good_smiley.png'>";
					break;
				case 4:
					img = "<img src='/theme/App/img/good_smiley.png'>";
					break;
				case 3:
					img = "<img src='/theme/App/img/neutral_smiley.png'>";
					break;
				case 2:
					img = "<img src='/theme/App/img/bad_smiley.png'>";
					break;
				case 1:
					img = "<img src='/theme/App/img/very_bad_smiley.png'>";
					break;
			}
			return img;
		}
    });
	
</script>