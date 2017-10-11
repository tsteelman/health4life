<div class="col-lg-12">
    <input type="hidden" name="graphUpdatedInRoom" value="<?php echo $graphRoom; ?>" id="graphUpdatedInRoom">
    <div class="pain_graph_navigation" style="padding: 20px 0px;">
        <button class="btn print_btn pull-right print_button" data-toggle="modal" data-target="#printGraph" data-backdrop="static" data-keyboard="false">Print</button>
        Go to Graph : 
        <a class="pain_graph_navigator" data-graph-index="1" href="javascript:void(0)">Head Area</a> | 
        <a class="pain_graph_navigator" data-graph-index="2" href="javascript:void(0)">Chest Area</a> | 
        <a class="pain_graph_navigator" data-graph-index="3" href="javascript:void(0)">Abdomen</a> | 
        <a class="pain_graph_navigator" data-graph-index="4" href="javascript:void(0)">Pelvic Area</a> | 
        <a class="pain_graph_navigator" data-graph-index="5" href="javascript:void(0)">Back Area</a> | 
        <a class="pain_graph_navigator" data-graph-index="6" href="javascript:void(0)">Arm</a> | 
        <a class="pain_graph_navigator" data-graph-index="7" href="javascript:void(0)">Legs</a>  
        <!--        <a class="pain_graph_navigator" data-graph-index="1" href="javascript:void(0)">Front Head</a> | 
                <a class="pain_graph_navigator" data-graph-index="2" href="javascript:void(0)">Back Head</a> | 
                <a class="pain_graph_navigator" data-graph-index="3" href="javascript:void(0)">Chest</a> | 
                <a class="pain_graph_navigator" data-graph-index="4" href="javascript:void(0)">Abdomen</a> | 
                <a class="pain_graph_navigator" data-graph-index="5" href="javascript:void(0)">Pelvis</a> | 
                <a class="pain_graph_navigator" data-graph-index="6" href="javascript:void(0)">Right Arm</a> | 
                <a class="pain_graph_navigator" data-graph-index="7" href="javascript:void(0)">Left Arm</a> | 
                <a class="pain_graph_navigator" data-graph-index="8" href="javascript:void(0)">Right Leg</a> | 
                <a class="pain_graph_navigator" data-graph-index="9" href="javascript:void(0)">Left Leg</a> | 
                <a class="pain_graph_navigator" data-graph-index="10" href="javascript:void(0)">Back</a> | 
                <a class="pain_graph_navigator" data-graph-index="11" href="javascript:void(0)">Buttock</a> | -->
    </div>

    <div id="pain_type_graph_container_1" class="col-lg-12 pain_graph_container graph_container">
        <h3>Head Area</h3>
        <div id="pain_graph_container_1" class="pain_graph_area"></div>  

    </div>
    <div id="pain_type_graph_container_2" class="col-lg-12 pain_graph_container graph_container">
        <h3>Chest Area</h3>
        <div id="pain_graph_container_2" class="pain_graph_area"></div>  

    </div>
    <div id="pain_type_graph_container_3" class="col-lg-12 pain_graph_container graph_container">
        <h3>Abdomen</h3>
        <div id="pain_graph_container_3" class="pain_graph_area"></div>  

    </div>
    <div id="pain_type_graph_container_4" class="col-lg-12 pain_graph_container graph_container">
        <h3>Pelvic Area</h3>
        <div id="pain_graph_container_4" class="pain_graph_area"></div> 

    </div>
    <div id="pain_type_graph_container_5" class="col-lg-12 pain_graph_container graph_container">
        <h3>Back Area</h3>
        <div id="pain_graph_container_5" class="pain_graph_area"></div> 

    </div>
    <div id="pain_type_graph_container_6" class="col-lg-12 pain_graph_container graph_container">
        <h3>Arm</h3>
        <div id="pain_graph_container_6" class="pain_graph_area"></div> 

    </div>
    <div id="pain_type_graph_container_7" class="col-lg-12 pain_graph_container graph_container">
        <h3>Legs</h3>
        <div id="pain_graph_container_7" class="pain_graph_area"></div> 

    </div>
    <!--    <div id="pain_type_graph_container_8" class="col-lg-12 pain_graph_container graph_container">
            <h3>Right Leg</h3>
           <div id="pain_graph_container_8" class="pain_graph_area">
    <center><img class="notification_loader" width="30" height="30" src="/img/loader.gif" alt="Loading..."></center>
    </div> 
    
        </div>
        <div id="pain_type_graph_container_9" class="col-lg-12 pain_graph_container graph_container">
            <h3>Left Leg</h3>
            <div id="pain_graph_container_9" class="pain_graph_area"></div> 
    
        </div>
        <div id="pain_type_graph_container_10" class="col-lg-12 pain_graph_container graph_container">
            <h3>Back</h3>
            <div id="pain_graph_container_10" class="pain_graph_area"></div> 
    
        </div>
        <div id="pain_type_graph_container_11" class="col-lg-12 pain_graph_container graph_container">
            <h3>Buttock</h3>
            <div id="pain_graph_container_11" class="pain_graph_area"></div> 
    
        </div>-->
</div>
<?php $printData = array(8 => 'Head Area', 9 => 'Chest Area', 10 => 'Abdomen', 11 => 'Pelvic Area', 12 => 'Back Area', 13 => 'Arm', 14 => 'Legs'); ?>
<?php echo $this->element('User.Myhealth/graph_printer', array('printData' => $printData)); ?>

<script type="text/javascript">
    var my_health = true;
    var readings_values = <?php echo json_encode($arrayByBodyPart); ?>;
    var bodyPartsArray = <?php echo json_encode($bodyPartsArray); ?>;
    var username = "<?php echo ($is_same) ? '' : $user_details['username']; ?>";


    function refreshPainDataGraph() {

        $.ajax({
//                async: true,
            type: "post",
            data: {'username': username},
            dataType: 'json',
            success: function(data) {
                drawPainDataGraphs(data.arrayByBodyPart);
            },
            url: '/user/api/getPainTrackerGraphValues'
        });
    }
//    var painData = new Array();
    $(document).ready(function() {
        drawPainDataGraphs(readings_values);
        $(document).on('click', '.pain_graph_navigator', function() {
            var graph_index = $(this).data('graph-index');
            var graph_offset = $("#pain_type_graph_container_" + graph_index).offset();
            var header_height = 100; //px
            var scroll_to = parseInt(graph_offset.top) - header_height;
            $("html, body").animate({scrollTop: scroll_to + 'px'}, 500, "easeInOutQuart");
        });

    });

    function drawPainDataGraphs(readings_values) {
        var series_name = 'Pain Graph';
        var container = "#pain_graph_container_1";
        var container_temp = "#pain_graph_container_";
        var options = setPainGraphOptions();
        var painData = new Array();

        /*To create array of values*/
        for (var i = 1; i <= 7; i++) {
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

        // plot_stock_graph(container, read, series_name, options);
        for (var x = 1; x <= 7; x++) {
            var container_now = container_temp + x;
            plot_pain_stock_graph(container_now, painData[x], series_name, options);
            if(x === 7 && app.show_site_notfications === true) {
                showSiteNotification('Paintracker graphs updated', 'info');
            }
        }


    }






    function plot_pain_stock_graph(container, readings, series_name, options) {
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
            }
//            tooltip: {
//                useHTML: true,
//                formatter: function() {
//                    return 'sevearity : '+this.y;
////                    return getHealthLabel(this.y, 1);
//                }
//            }
        };
    }

</script>