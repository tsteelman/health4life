<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($isOwner) {
    $this->Html->addCrumb('My Health', Common::getUserProfileLink($profile_owner, true) . "/myhealth");
} else {
    $this->Html->addCrumb("$profile_owner's Health", Common::getUserProfileLink($profile_owner, true) . "/myhealth");
}
$this->Html->addCrumb('Tracker');
?>
<div class="container">
    <input type="hidden" name="graphUpdatedInRoom" value="<?php echo $graphRoom; ?>" id="graphUpdatedInRoom">
	<?php if ($isOwner): ?>
		<div style="overflow: hidden;">
			<button class="btn print_btn pull-right print_button" data-toggle="modal" data-target="#printGraph" data-backdrop="static" data-keyboard="false">Print</button>
		</div>
    <?php endif; ?>
	<div class="graph_container">
        <div>
            <div class="row"> 
                <?php
                if ($is_same) {
                    ?>
                    <div class="col-lg-6"><h2> Pain Tracker</h2></div>
                    <?php
                } else {
                    ?>
                    <div class="col-lg-6"><h2> Pain Tracker</h2></div>
                    <?php
                }
                ?>
                <div class="col-lg-6">
                    <?php if ($isOwner): ?>
                        <!--<button disabled="disabled" data-toggle="modal" id="add_today_status" class="pull-right btn create_button " >Add Now</button>-->
                        <a href="/user/manageTracker?record_type=1"><button type="button" class="pull-right btn btn_add " >Manage History</button></a>

                    <?php endif; ?>
                </div>      
            </div>
            <div id="status_graph_container"></div>      
        </div>
    </div>
    <div class="graph_container">
        <div>
            <div class="row"> 
                <?php
                if ($is_same) {
                    ?>
                    <div class="col-lg-6"><h2>Quality Of Life</h2></div>
                    <?php
                } else {
                    ?>
                    <div class="col-lg-6"><h2>Quality Of Life</h2></div>
                    <?php
                }
                ?>
                <div class="col-lg-6">
                    <?php if ($isOwner): ?>
                        <!--<button disabled="disabled" data-toggle="modal" data-target="#readWeight" data-backdrop="static" data-keyboard="false"  class="pull-right btn create_button " >Add now</button>-->
                        <a href="/user/manageTracker?record_type=2"><button type="button" class="pull-right btn btn_add ">Manage history</button></a>
                    <?php endif; ?>
                </div>      
            </div>
            <div id="weight_graph_container" ></div>
        </div>
    </div>
    <div class="graph_container">
        <div>
            <div class="row"> 
                <?php
                if ($is_same) {
                    ?>
                    <div class="col-lg-6"><h2>Sleeping Habits</h2></div>
                    <?php
                } else {
                    ?>
                    <div class="col-lg-6"><h2>Sleeping Habits</h2></div>
                    <?php
                }
                ?>
                <div class="col-lg-6">
                    <?php if ($isOwner): ?>
                        <!--<button disabled="disabled" data-toggle="modal" data-target="#readBp" data-backdrop="static" data-keyboard="false"  class="pull-right btn create_button " >Add now</button>-->
                        <a href="/user/manageTracker?record_type=3"><button type="button" class="pull-right btn btn_add ">Manage history</button></a>
                    <?php endif; ?>
                </div>      
            </div>
            <div id="pressure_graph_container" ></div>
        </div>
    </div>
</div>

<input id="date_today" type='hidden' value="<?php
if (isset($date_today)) {
    echo $date_today;
}
?>">
<input id="userDateOfBirth" type='hidden' value="<?php
       if (isset($userDateOfBirth)) {
           echo $userDateOfBirth;
       }
       ?>">

<?php $printData = array(5 =>'Pain Tracker', 6 => 'Quality Of Life', 7 => 'Sleeping Habits'); ?>
<?php echo $this->element('User.Myhealth/read_weight'); ?>
<?php echo $this->element('User.Myhealth/read_bp'); ?>
<?php echo $this->element('User.Myhealth/read_temperature'); ?>
<?php echo $this->element('health_status_selector'); ?>
<?php echo $this->element('User.Myhealth/graph_printer', array('printData' => $printData)); ?>

<?php
echo $this->AssetCompress->script('chart.js');
echo $this->AssetCompress->css('graph');
?>

<script type="text/javascript">
    $(function() {
        load_stock_graph();
//        $(".current_health_date").datepicker({minDate: new Date($('#userDateOfBirth').val()), maxDate: new Date($('#date_today').val())});
//        $('#add_today_status').click(function() {
//            $('#save_health_status_btn').prop('disabled', true);
//            $('#healthStatusSelectionModal').modal('show');
//        });
//        $('#bp_submit_button').click(function() {
//            validate_bp_details();
//        });
//
//        $('#weight_submit_button').click(function() {
//            validate_weight_details();
//        });
//
//        $('#read_temperature_button').click(function() {
//            validate_temperature_details();
//        });
    });

    /**
     * Load stock graph
     * 
     * @param Integer graph_type
     * @returns {undefined}
     */
    function load_stock_graph(graph_type) {
        var username = "<?php echo ($is_same) ? '' : $user_details['username']; ?>";
        $.ajax({
            async: true,
            type: "post",
            data: {'graph_type': graph_type, 'username': username},
            dataType: 'json',
            success: function(data) {

                var pain_tracker_result = [];
                var life_quality_tracker_result = [];
                var sleeping_tracker_result = [];

                for (var i in data.pain_tracker)
                    pain_tracker_result.push([(i * 1000), (parseInt(data.pain_tracker[i]) * 1) - .5]);
                
                for (var i in data.life_quality_tracker)
                    life_quality_tracker_result.push([(i * 1000), (parseInt(data.life_quality_tracker[i]) * 1) - .5]);
                
                for (var i in data.sleeping_tracker)
                    sleeping_tracker_result.push([(i * 1000), (parseInt(data.sleeping_tracker[i]) * 1) - .5]);

                // Plot health chart
                var options = setHealthGraphOptions();

                plot_stock_graph('#status_graph_container', pain_tracker_result, 'Pain', options);
                plot_stock_graph('#weight_graph_container', life_quality_tracker_result, 'Life Quality', options);
                plot_stock_graph('#pressure_graph_container', sleeping_tracker_result, 'Sleeping', options);
            },
            url: '/user/api/getHealthReadings'
        });
    }

    /**
     * Function plots graph
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
            showSiteNotification('Updated tracker graphs', 'info');
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
                    var s = Highcharts.dateFormat('%A, %b %e, %Y', this.x) + '<br />';
                    return s + getHealthLabel(this.y, 1);
                    
                }
            }
        };
    }

    /**
     * Additional params for Blood Pressure Graph
     */
    function  setBPGraphOptions(readings) {
        var first_series = new Array();
        var second_series = new Array();
        for (x in readings) {
            bp = readings[x].split('/');
            first_series.push([x * 1000, bp[0] * 1]);
            second_series.push([x * 1000, bp[1] * 1]);
        }

        setting = {series: [{name: 'Systolic', data: first_series},
                {name: 'Diastolic ', data: second_series}]};
        return setting;
    }

</script>

