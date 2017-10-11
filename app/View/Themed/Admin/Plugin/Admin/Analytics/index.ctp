<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Analytics');
?>
<div id="analytics" class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(' Analytics'); ?>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>
    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->
            <div class="row-fluid">
                <div id="user" class="table-header">
                    <?php echo __('User Analytics - '); ?>
                    <?php echo __('Total Users: ' . $totalUsers); ?>
                </div>
                <div id="user_registration_container"></div>
                <div id="event" class="table-header">
                    <?php echo __('Event Analytics - '); ?>
                    <?php echo __('Total Events: ' . $totalEvents); ?>
                </div>
                <div id="events_container"></div>
                <div id="community" class="table-header">
                    <?php echo __('Community Analytics - '); ?>
                    <?php echo __('Total Communities: ' . $totalCommunities); ?>
                </div>
                <div id="community_container"></div>
                <div class="span6">
                    <div id="disease" class="table-header">
                        <?php echo __('Top Diseases'); ?>
                    </div>
                    <div id="top_diseases_container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center"><?php echo __('Si. No.'); ?></th>
                                    <th class="center"><?php echo __('Disease Name'); ?></th>
                                    <th class="center"><?php echo __('No. of Users'); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($topDiseases as $diseases) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td class="center"><?php echo __($i); ?></td>
                                        <td>
                                            <a href="Diseases/view/<?php echo $diseases['id']; ?>">
                                                <?php echo __($diseases['name']); ?>
                                            </a>
                                        </td>
                                        <td class="center"><?php echo __($diseases['users']); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="span6">
                    <div id="country" class="table-header">
                        <?php echo __('Top Countries'); ?>
                    </div>
                    <div id="top_countries_container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center"><?php echo __('Si. No'); ?></th>
                                    <th class="center"><?php echo __('Country'); ?></th>
                                    <th class="center"><?php echo __('No. of users'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($topCountries as $country) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td class="center"><?php echo __($i); ?></td>
                                        <td><?php echo __($country['name']); ?></td>
                                        <td class="center"><?php echo __($country['users']); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="span11">
                    <div id="country" class="table-header">
                        <?php echo __('Top Treatments'); ?>
                    </div>
                    <div id="top_countries_container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center"><?php echo __('Si. No'); ?></th>
                                    <th class="center"><?php echo __('Treatment'); ?></th>
                                    <th class="center"><?php echo __('No. of users'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($topTreatments as $treatment) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td class="center"><?php echo __($i); ?></td>
                                        <td><?php echo __($treatment['treatment']); ?></td>
                                        <td class="center"><?php echo __($treatment['users']); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script src="/theme/Admin/js/vendor/highstock.js"></script>
<script src="/theme/Admin/js/vendor/exporting.js"></script>
<script type="text/javascript">

    var user_graph_data = <?php echo $userDetails; ?>;
    var event_graph_data = <?php echo $eventDetails; ?>;
    var community_graph_data = <?php echo $communityDetails; ?>;
    var j = 0;
    var seriesOptions = [],
            names = [],
            colors = Highcharts.getOptions().colors;
    $.each(user_graph_data, function(i, values) {
        names.push(i);
        seriesOptions[j] = {
            name: i,
            data: values
        };
        j++;
    });
    j = 0;
    var eventSeriesOptions = [],
            eventNames = [],
            eventColors = Highcharts.getOptions().colors;

    $.each(event_graph_data, function(i, values) {
        eventNames.push(i);
        eventSeriesOptions[j] = {
            name: i,
            data: values
        };
        j++
    });
    j = 0;
    var communitySeriesOptions = [],
            communityNames = [],
            communityColors = Highcharts.getOptions().colors;
    $.each(community_graph_data, function(i, values) {
        communityNames.push(i);
        communitySeriesOptions[j] = {
            name: i,
            data: values
        };
        j++
    });

    $(document).ready(function () {
	    createChart('users');
	    createChart('events');
	    createChart('community');
    });

	
    // create the chart when all data is loaded
    function createChart(type) {
        switch (type) {
            case 'users':
                {
                    $('#user_registration_container').highcharts('StockChart', {
                        chart: {
                            events: {
                                load: function() {
                                    var chart = $('#user_registration_container').highcharts('StockChart');
                                    var series = chart.series;
                                    series[1].hide();
                                    series[2].hide();
                                }
                            }
                        },
                        rangeSelector: {
                            inputEnabled: $('#user_registration_container').width() > 480,
                            buttons: app.graph_settings.range_selector.buttons
                            
                        },
                        scrollbar : app.graph_settings.graph_scrollbar,
                        yAxis: {
                            labels: {
                                enabled: true
                            },
                            plotLines: [{
                                    value: 0,
                                    width: 2,
                                    color: 'silver'
                                }]
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                            valueDecimals: 2
                        },
                        credits: {
                            enabled: false
                        },
                        exporting: {
                            enabled: false,
                            filename: 'UserRegistrationChart'
                        },
                        legend: {
                            enabled: true
                        },
                        series: seriesOptions,
                        export: {
                            enabled: false
                        }
                    });
                    break;
                }
            case 'events':
                {
                    $('#events_container').highcharts('StockChart', {
                        chart: {
                            events: {
                                load: function() {
                                    var chart = $('#events_container').highcharts('StockChart');
                                    var series = chart.series;
                                    series[1].hide();
                                    series[2].hide();
                                }
                            }
                        },
                        rangeSelector: {
                            inputEnabled: $('#events_container').width() > 480,
                            buttons: app.graph_settings.range_selector.buttons
                        },
                        scrollbar : app.graph_settings.graph_scrollbar,
                        yAxis: {
                            labels: {
                                enabled: true
                            },
                            plotLines: [{
                                    value: 0,
                                    width: 2,
                                    color: 'silver'
                                }]
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                            valueDecimals: 2
                        },
                        credits: {
                            enabled: false
                        },
                        exporting: {
                            enabled: false,
                            filename: 'Events Chart'
                        },
                        legend: {
                            enabled: true
                        },
                        series: eventSeriesOptions,
                    });
                    break;
                }
            case 'community':
                {
                    $('#community_container').highcharts('StockChart', {
                        chart: {
                            events: {
                                load: function() {
                                    var chart = $('#community_container').highcharts('StockChart');
                                    var series = chart.series;
                                    series[1].hide();
                                    series[2].hide();
                                }
                            }
                        },
                        rangeSelector: {
                            inputEnabled: $('#community_container').width() > 480,
                            buttons: app.graph_settings.range_selector.buttons
                        },
                        scrollbar : app.graph_settings.graph_scrollbar,
                        yAxis: {
                            labels: {
                                enabled: true
                            },
                            plotLines: [{
                                    value: 0,
                                    width: 2,
                                    color: 'silver'
                                }]
                        },
                        tooltip: {
                            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                            valueDecimals: 2
                        },
                        credits: {
                            enabled: false
                        },
                        exporting: {
                            enabled: false,
                            filename: 'Community Chart'
                        },
                        legend: {
                            enabled: true
                        },
                        series: communitySeriesOptions
                    });
                    break;
                }
        }
    }
</script>