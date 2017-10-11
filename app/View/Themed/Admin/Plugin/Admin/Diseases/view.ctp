<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Diseases', '/admin/Diseases');
$this->Html->addCrumb(__(h($disease_details['Disease']['name'])));
?>
<div id="disease_view" class="page-content">
    <div class="page-header position-relative"><!--.page-header-->
        <h1>
            <?php echo __("Disease Details"); ?>
        </h1>
    </div><!--/.page-header-->
    <div class="row">
        <div class="span3">
<!--            <div class="span2">
                <?php // echo __('Image: '); ?>
            </div>-->
            <!--<div class="span8">-->
                <img src="<?php echo $diseaseImage ?>" id="disease_image"> 
            <!--</div>-->
        </div>
        <div class="span7">
            <div class="row">
        <div class="span11">
            <div class="span2">
                <?php echo __('Disease name: '); ?>
            </div>
            <div class="span8">
                <?php echo __(h($disease_details['Disease']['name'])); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span11">
            <div class="span2">
                <?php echo __('Events: '); ?>

            </div>
            <div class="span8">
                <?php
                if ($events_count != NULL) {
                    echo __($events_count);
                } else {
                    echo 0;
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span11">
            <div class="span2">
                <?php echo __('Communities: '); ?>

            </div>
            <div class="span8">
                <?php
                if ($communities_count != NULL) {
                    echo __($communities_count);
                } else {
                    echo 0;
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span11">
            <div class="span2">More Details:</div>
            <div class="span8">
                <a href="/condition/index/<?php echo $disease_details['Disease']['id']; ?>" target="_blank">
                    <?php echo __('View') . " " . h($disease_details['Disease']['name']) . " page in "; echo Configure::read('App.name'); ?>
                </a>
            </div>
        </div>
    </div>
        </div>
    </div>

    <div class="page-content">
        <div class="page-header position-relative"><!--.page-header-->
            <h1>
                <?php echo __("Analytics"); ?>
            </h1>
        </div><!--/.page-header-->
        <div id="user" class="table-header">
            <?php
            echo __('Users with ' . $disease_details['Disease']['name']);
            if (isset($users_count[0]['users'])) {
                echo __(' - Total Users: ' . $users_count[0]['users']);
            } else {
                echo __(' - Total Users: 0');
            }
            ?>
        </div>
        <?php
        if (isset($users_count[0]['users'])) {
            ?>
            <div class="row">
                <div id="disease_user_gender_graph"></div>
                <div id="disease_user_age_graph"></div>
            </div>

            <div class="table-header">
                <?php
                echo __('Treatment Analytics for ' . $disease_details['Disease']['name']);
                ?>
            </div>
            <div id="treatment_user_graph"></div>
            <div id="disease_user_treatment_graph"></div>

            <div class="table-header">
                <?php
                echo __('Countries where ' .
                        $disease_details['Disease']['name'] . ' is prevalent');
                ?>
            </div>
            <div id="disease_country_graph"></div>
            <div class="table-header" style="margin-bottom: 10px">
                <?php
                echo __('Location of users with ' . $disease_details['Disease']['name']);
                ?>
            </div>
            <div id='map-canvas' style="height: 300px;">
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script src = "/theme/Admin/js/vendor/highstock.js"></script>
<script src="/theme/Admin/js/vendor/exporting.js"></script>
<script src="/theme/Admin/js/vendor/no-data.js"></script>
<script src='https://maps.google.com/maps/api/js?sensor=true' type='text/javascript'></script>
<script>
    var width = ($("#user").width() / 2) - 4;
    var disease_id = <?php echo $disease_details['Disease']['id']; ?>;
    var is_user = <?php
        if (isset($users_count[0]['users'])) {
            echo 'true;';
        } else {
            echo 'false;';
        }
        ?>
    var total_users = <?php echo $users_count[0]['users']; ?>;
    if (is_user) {
        var graph_data = <?php echo ($graph_data); ?>;
        var age_group_data = <?php echo $treatment_analytics; ?>;
        var gender_seriesOptions = [{
                type: 'pie',
                name: 'of users are',
                data: JSON.parse(graph_data.gender)
            }];
        var age_seriesOptions = [{
                type: 'pie',
                name: 'of users are',
                data: JSON.parse(graph_data.age)
            }];
        var treatment_seriesOptions = [{
                type: 'pie',
                name: 'of users take',
                data: JSON.parse(graph_data.treatment)
            }];
        var county_categories = JSON.parse(graph_data.top_country).categories;
        var country_users = JSON.parse(graph_data.top_country).users;
        
        $('#disease_user_gender_graph').css('width', width);
        $('#disease_user_gender_graph').css('float', 'left');
        $('#disease_user_age_graph').css('width', width);
        $('#disease_user_age_graph').css('float', 'left');
        $('#treatment_user_graph').css('width', width + 100);
        $('#treatment_user_graph').css('float', 'left');
        
        function createGenderChart(seriesOption) {
            $('#disease_user_gender_graph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    style: {
                        float: 'left'
                    },
                    width: width,
                    height: 250
                },
                title: {
                    text: 'Gender Analytics'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '{point.percentage:.1f} % <b>{point.name}</b>'
                        }
                    }
                },
                series: seriesOption,
                credits: {
                    enabled: false
                }
            });
        }

        function createAgeChart(seriesOption) {
            $('#disease_user_age_graph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    style: {
                        float: 'left'
                    },
                    width: width,
                    height: 250
                },
                title: {
                    text: 'Age Analytics'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '{point.percentage:.1f} % <b>{point.name}</b>'
                        }
                    }
                },
                series: seriesOption,
                credits: {
                    enabled: false
                }
            });
        }

        function createCountryChart(categories, data) {
            $('#disease_country_graph').highcharts({
                chart: {
                    type: 'column',
                    margin: [50, 50, 100, 80]
                },
                title: {
                    text: ''
                },
                exporting: {
                    enabled: false
                }, credits: {
                    enabled: false
                },
                xAxis: {
                    categories: categories,
                    labels: {
                        rotation: -45,
                        align: 'right',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                plotOptions: {
                    column: {
                        pointWidth: 50
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'No. of users'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>',
                },
                series: [{
                        name: 'Population',
                        data: data,
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            x: 4,
                            y: 8,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif',
                                textShadow: '0 0 3px black'
                            }
                        }
                    }]
            });
        }

        function createTreatmentChart(seriesOption) {
            $('#treatment_user_graph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    style: {
                        float: 'left'
                    },
                    width: width + 100,
                    height: 300
                },
                title: {
                    text: 'Treatment Analytics'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '{point.percentage:.1f} % <b>{point.name}</b>'
                        }
                    }
                },
                series: seriesOption,
                lang: {
                    noData: 'No treatment found'
                },
                noData: {
                    position: {
                        verticalAlign: "middle"
                    }
                },
                credits: {
                    enabled: false
                }
            });
        }
        
        function createAgeGroupGraph(series) {
            $('#disease_user_treatment_graph').highcharts({
                chart: {
                    type: 'column',
                    width: width - 100,
                    height: 300
                },
                title: {
                    text: 'Age Group Treatment Analytics'
                },
                exporting: {
                    enabled: false
                },
                colors: [
                    '#39b549',
                    '#8263a2'
                ],
                xAxis: {
                    categories: [
                        '0-18',
                        '19-25',
                        '25-35',
                        '35-60',
                        '60+'
                    ]
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                legend: {
                    borderWidth: 0
                },
                credits: {
                    enabled: false
                },
                series: series
            });
        }
        
        createGenderChart(gender_seriesOptions);
        createAgeChart(age_seriesOptions);
        createCountryChart(county_categories, country_users);
        createTreatmentChart(treatment_seriesOptions);
        createAgeGroupGraph(age_group_data);

        function createGenderChart(seriesOption) {
            $('#disease_user_gender_graph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    style: {
                        float: 'left'
                    },
                    width: width,
                    height: 250
                },
                title: {
                    text: 'Gender Analytics'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '{point.percentage:.1f} % <b>{point.name}</b>'
                        }
                    }
                },
                series: seriesOption,
                credits: {
                    enabled: false
                }
            });
        }

        function createAgeChart(seriesOption) {
            $('#disease_user_age_graph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    style: {
                        float: 'left'
                    },
                    width: width,
                    height: 250
                },
                title: {
                    text: 'Age Analytics'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '{point.percentage:.1f} % <b>{point.name}</b>'
                        }
                    }
                },
                series: seriesOption,
                credits: {
                    enabled: false
                }
            });
        }

        function createCountryChart(categories, data) {
            $('#disease_country_graph').highcharts({
                chart: {
                    type: 'column',
                    margin: [50, 50, 100, 80]
                },
                title: {
                    text: ''
                },
                exporting: {
                    enabled: false
                }, credits: {
                    enabled: false
                },
                xAxis: {
                    categories: categories,
                    labels: {
                        rotation: -45,
                        align: 'right',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                plotOptions: {
                    column: {
                        pointWidth: 50
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'No. of users'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>',
                },
                series: [{
                        name: 'Population',
                        data: data,
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: '#FFFFFF',
                            align: 'right',
                            x: 4,
                            y: 8,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif',
                                textShadow: '0 0 3px black'
                            }
                        }
                    }]
            });
        }

        function createTreatmentChart(seriesOption) {
            $('#treatment_user_graph').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    style: {
                        float: 'left'
                    },
                    width: width + 100,
                    height: 300
                },
                title: {
                    text: 'Treatment Analytics'
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y} user(s)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '{point.percentage:.1f} % <b>{point.name}</b>'
                        }
                    }
                },
                series: seriesOption,
                lang: {
                    noData: 'No treatment found'
                },
                noData: {
                    position: {
                        verticalAlign: "middle"
                    }
                },
                credits: {
                    enabled: false
                }
            });
        }
        
        function createAgeGroupGraph(series) {
            $('#disease_user_treatment_graph').highcharts({
                chart: {
                    type: 'column',
                    width: width - 100,
                    height: 300
                },
                title: {
                    text: 'Age Group Treatment Analytics'
                },
                exporting: {
                    enabled: false
                },
                colors: [
                    '#39b549',
                    '#8263a2'
                ],
                xAxis: {
                    categories: [
                        '0-18',
                        '19-25',
                        '25-35',
                        '35-60',
                        '60+'
                    ]
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                legend: {
                    borderWidth: 0
                },
                credits: {
                    enabled: false
                },
                series: series
            });
        }

        function initializeMap() {
            var infowindow = new google.maps.InfoWindow();
            var myOptions = {
                zoom: 2,
                mapTypeControl: false,
                streetViewControl: false,
                center: new google.maps.LatLng(25, 0),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            diseaseMap = new google.maps.Map(document.getElementById("map-canvas"), myOptions); // GLOBAL

            $.each(JSON.parse(graph_data.location), function(i, value) {
                addMarker(value.lat, value.lng, value.type);
            });

            function disableDragging() {
                // Quick hack to disable dragging on mobile map
                var mobileFlag = $(window).width() <= 480;
                if (mobileFlag) {
                    crohnologyMap.setOptions({draggable: false});
                }

                // if they turn their phone...
                $(window).resize(function() {
                    var w = $(window).width();
                    if (!mobileFlag && w <= 480) {
                        crohnologyMap.setOptions({draggable: false});
                        mobileFlag = true;
                    }
                    else if (mobileFlag && w >= 480) {
                        crohnologyMap.setOptions({draggable: true});
                        mobileFlag = false;
                    }
                });
            }
            disableDragging();

            function addMarker(lat, lng, img) {
                // adding markers on the map
                var latlng = new google.maps.LatLng(lat, lng);
                switch (img) {
                    case 1 :
                        var marker = new google.maps.Marker({
                            map: diseaseMap,
                            position: latlng,
                            icon: '/theme/App/img/map_icons/patient.png'
                        });
                        break;
                    case 2 :
                        var marker = new google.maps.Marker({
                            map: diseaseMap,
                            position: latlng,
                            icon: '/theme/App/img/map_icons/family.png'
                        });
                        break;
                    case 3 :
                        var marker = new google.maps.Marker({
                            map: diseaseMap,
                            position: latlng,
                            icon: '/theme/App/img/map_icons/caregiver.png'
                        });
                        break;
                    case 4 :
                        var marker = new google.maps.Marker({
                            map: diseaseMap,
                            position: latlng,
                            icon: '/theme/App/img/map_icons/friend.png'
                        });
                        break;
                }

            }
        }

        initializeMap();
    }
    $("ul.nav-list li").removeClass('active');
    $("#disease-list-li").addClass('active');
</script>