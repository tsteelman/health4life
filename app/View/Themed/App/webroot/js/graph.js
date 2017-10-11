
var MIN_COMMON = 0;

var MAX_WEIGHT_POUNDS = 1000;
var MAX_WEIGHT_KG = 454;//500

var MAX_HEIGHT_CM = 335;//1000
var MAX_HEIGHT_FEET = 11;

var MAX_HEIGHT_INCH = 12;

var MAX_TEMPERATURE_F = 300;
var MIN_TEMPERATURE_F = 33;

var MAX_TEMPERATURE_C = 149;

var MAX_BP_DIASTOLIC = 200;
var MIN_BP_DIASTOLIC = 0;

var MAX_BP_SYSTOLIC = 250;
var MIN_BP_SYSTOLIC = 0;

var error_message = '';
/**
 * Function retrieve status labels and image urls for displaying in graph
 * @param {int} value
 * @param {int} type
 * @returns {String}
 */
function getHealthLabel(value, type) {
    var name = "";
    var img = "";
    switch (Math.ceil(value)) {
        case 5:
            name = "Very Good";
            img = "<div class='graph_smiley very_happy_smile'></div>";
            break;
        case 4:
            name = "Good";
            img = "<div class='graph_smiley good_smile'></div>";
            break;
        case 3:
            name = "Neutral";
            img = "<div class='graph_smiley neutral_smile'></div>";
            break;
        case 2:
            name = "Bad";
            img = "<div class='graph_smiley bad_smile'></div>";
            break;
        case 1:
            name = "Very Bad";
            img = "<div class='graph_smiley very_bad_smile'></div>";
            break;
    }
    if (type == 1) {
        return 'Feeling <b>' + name + '</b>';
    }
    else {
        return img;
    }
}

/**
 * get plot bands settings
 */
function getHealthPlotBandSettings() {
    return  [{from: 0, to: 1, color: '#ffd4d5'},
        {from: 1, to: 2, color: '#ffe2d8'},
        {from: 2, to: 3, color: '#fffae7'},
        {from: 3, to: 4, color: '#edffd6'},
        {from: 4, to: 5, color: '#e6ffe9'},
        {from: 5, to: 6, color: '#ffffff'}];
}

function validateHealthInputValue(type, values) {
    var unit;
    var error_min = false;
    var error_max = false;
//  var healthValuesArray = new Array();
//  healthValuesArray[0]
    error_message = '';
    values[0] = parseFloat(values[0]);
    values[1] = parseFloat(values[1]);
    if (values[0] <= 0) {
        error_min = true;
        error_message = 'Please enter a value greater than 0';
    } else if(values[0].toFixed(2) <= 0){
        error_min = true;
        error_message = 'Please enter a valid value';
    }
    switch (type) {
        case 1:
            unit = $.trim($("#read_weight_unit").text());
            if (unit == 'Kg') {
                if (values[0] > MAX_WEIGHT_KG) {
                    error_max = true;
                    error_message = 'Please enter a value less than ' + MAX_WEIGHT_KG + unit;
                }
            } else if (unit == 'lbs') {
                if (values[0] > MAX_WEIGHT_POUNDS) {
                    error_max = true;
                    error_message = 'Please enter a value less than ' + MAX_WEIGHT_POUNDS + unit;
                }
            }
            break;
        case 2:
            unit = $.trim($("#read_height_unit").text());
            if (unit == 'cm') {
                if (values[0] > MAX_HEIGHT_CM) {
                    error_max = true;
                    error_message = 'Please enter a value less than ' + MAX_HEIGHT_CM + ' cm.';
                }
            } else if (unit == 'feet') {
                if (values[0] > MAX_HEIGHT_FEET) {
                    error_max = true;
                    error_message = 'Please enter a value less than ' + MAX_HEIGHT_FEET + ' feet';
                } else if (values[1] > MAX_HEIGHT_INCH) {
                    error_max = true;
                    error_message = 'Please enter a value less than 12 inches';
                }
            }
            break;
        case 3:
            if (values[0] <= MIN_BP_SYSTOLIC) {
                error_max = true;
                error_message = 'Please enter a systolic value greater than ' + MIN_BP_SYSTOLIC;
            } else if (values[1] <= MIN_BP_DIASTOLIC) {
                error_max = true;
                error_message = 'Please enter a diastolic value greater than ' + MIN_BP_DIASTOLIC;
            } else if (values[0] > MAX_BP_SYSTOLIC) {
                error_max = true;
                error_message = 'Please enter a systolic value less than ' + MAX_BP_SYSTOLIC;
            } else if (values[1] > MAX_BP_DIASTOLIC) {
                error_max = true;
                error_message = 'Please enter a diastolic value less than ' + MAX_BP_DIASTOLIC;
            } else if (values[1] > values[0]) {
                error_max = true;
                error_message = 'Systolic pressure should be greater than diastolic pressure.';
            }
            break;
        case 4:
            unit = $.trim($("#read_temperature_unit").text());
            if (unit == '°C') {
                if (values[0] > MAX_TEMPERATURE_C) {
                    error_max = true;
                    error_message = 'Please enter a value less than ' + MAX_TEMPERATURE_C;
                }
            } else if (unit == '°F') {
                if (values[0] < MIN_TEMPERATURE_F) {
                    error_max = true;
                    error_message = 'Please enter a value grater than ' + MIN_TEMPERATURE_F;
                }
                if (values[0] > MAX_TEMPERATURE_F) {
                    error_max = true;
                    error_message = 'Please enter a value less than ' + MAX_TEMPERATURE_F;
                }
            }
            break;
    }
    return (error_max == true || error_min == true) ? true : false;
}

/**
 * Comment
 */
function validate_bp_details() {
    $('#bp_value1_error_message, #bp_value2_error_message, #bp_date_error_message, #bp_error_message').hide();

    var value1 = $.trim($('#bp_value1').val());
    var value2 = $.trim($('#bp_value2').val());
    var bp_date = $.trim($('#bp_date').val());
    var bp_time = $.trim($('#bp_time').val());
    var validateValues = new Array();
    validateValues[0] = value1;
    validateValues[1] = value2;
    if (!$.isNumeric(value1)) {
        $('#bp_error_message').text('Please enter valid systolic value').show();
        return false;
    } else if (!$.isNumeric(value2)) {
        $('#bp_error_message').text('Please enter valid diastolic value').show();
        return false;
    } else if (validateHealthInputValue(3, validateValues)) {
        $('#bp_error_message').text(error_message).show();
        return false;
    } else if (bp_date == '' || bp_date == null) {
        $('#bp_error_message').text('Please enter valid date').show();
        return false;
    } else if (bp_time == '' || bp_time == null || !bp_time.match(/^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/)) {
        $('#bp_error_message').text('Please enter valid time').show();
        return false;
    } else {
        var ladaBp = Ladda.create(document.querySelector('#bp_submit_button'));
        ladaBp.start();
        $.ajax({
            type: 'POST',
            url: '/user/api/addHealthRecord',
            data: {
                'type': 3, //bp
                'value1': value1,
                'value2': value2,
                'date': bp_date,
                'time': bp_time
            },
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(result) {
                ladaBp.stop();
                if (result.success == true) {
                    socket.emit('my_health_update', {
                        room: $('#graphUpdatedInRoom').val(),
                        type: 'current_health'
                    });
                    if ($('#pressure_graph_container').length != 0) {
                        load_stock_graph();
                    }
                    $("#bp_display_value").html(result.latest_value_string);
                    $('#bp_updated_time').attr('title', 'Last updated on:' + result.latest_updated_time);
                    if (result.bmi != null) {
                        $("#bmi_value").html(result.bmi);
                    } else {
                        $("#bmi_value").html('-');
                    }
                } else {

//                    bootbox.alert('<div class="server_errormsg">' + result.error_message + '</div>', function() {
//                        window.location.reload();
//                        window.scrollTo(0, 0);
//                    });
                    showServerErrorAlert('Alert', result.error_message, true);
                }
                $("#readBp").modal('hide');
                $('#bp_value1, #bp_value2, #bp_date').val('');
            }
        });
    }
}


function showServerErrorAlert(title, message, isReload) {
    bootbox.dialog({
        message: message,
        title: title,
        buttons: {
            Ok: {
                label: "ok",
                className: "btn-primary",
                callback: function() {
                    if (isReload == true) {
                        window.location.reload();
                        window.scrollTo(0, 0);
                    }
                }
            }
        }
    });
}

/**
 * function loads the graph for the selected tab in my health
 */
function load_graph(graph_type, username) {
    $.ajax({
        async: true,
        type: "post",
        data: {'graph_type': graph_type, 'username': username},
        dataType: 'json',
        success: function(data) {
            switch (graph_type) {
                case 1:
                    cordinates = getGraphCordinates(data.reading);
                    plot_week_graph(cordinates.x, cordinates.y, 'Weight(' + data.unit + ')');
                    break;
                case 2:
                    cordinates = getGraphCordinates(data.status);
                    options = status_graph_options()
                    plot_week_graph(cordinates.x, cordinates.y, 'Feeling', options);
                    break;
                case 3:
                    cordinates = getGraphCordinates(data.reading);
                    plot_week_graph(cordinates.x, cordinates.y, 'BP');
                    break;
                case 4:
                    cordinates = getGraphCordinates(data.reading);
                    plot_week_graph(cordinates.x, cordinates.y, 'Temp(' + data.unit + ')');
                    break;
            }
        },
        url: '/user/api/getWeeklyHealthValues'
    });
}

/**
 * Comment
 */
function validate_weight_details() {
    $('#weight_value_error_message, #weight_date_error_message, #weight_error_message').hide();
    var value = $.trim($('#weight_value').val());
    var weight_date = $.trim($('#weight_date').val());
    var validateValues = new Array();
    validateValues[0] = value;
    if (!$.isNumeric(value)) {
        $('#weight_error_message').text('Please enter a valid weight.').show();
        return false;
    } else if (validateHealthInputValue(1, validateValues)) {
        $('#weight_error_message').text(error_message).show();
        return false;
    } else if (weight_date == '' || weight_date == null) {
        $('#weight_error_message').text('Please enter a valid date.').show();
        return false;
    } else {
        var ladaWeight = Ladda.create(document.querySelector('#weight_submit_button'));
        ladaWeight.start();
        $.ajax({
            type: 'POST',
            url: '/user/api/addHealthRecord',
            data: {
                'type': 1, //weight
                'value1': value,
                'value2': null,
                'date': weight_date
            },
            dataType: 'json',
            success: function(result) {
                ladaWeight.stop();
                if (result.success == true) {
                    socket.emit('my_health_update', {
                        room: $('#graphUpdatedInRoom').val(),
                        type: 'current_health'
                    });
                    if ($('#weight_graph_container').length != 0) {
                        load_stock_graph();
                    }
                    $("#weight_display_value").html(result.latest_value_string);
                    $('#weight_updated_time').attr('title', 'Last updated on:' + result.latest_updated_time);
                    if (result.bmi != null) {
                        $("#bmi_value").html(result.bmi);
                    } else {
                        $("#bmi_value").html('-');
                    }
                } else {
//                    bootbox.alert('<div class="server_errormsg">' + result.error_message + '</div>', function() {
//                        window.location.reload();
//                        window.scrollTo(0, 0);
//                    });
                    showServerErrorAlert('Alert', result.error_message, true);
                }
                $("#readWeight").modal('hide');
                $('#weight_value, #weight_date').val('');
                if ($('#graph_container').length == 1) {
                    load_graph(1);
                }
            }
        });
    }
}

/**
 * Comment
 */
function validate_temperature_details() {
    $('#temperature_value_error_message, #temperature_date_error_message, #temperature_error_message').hide();
    var value = $.trim($('#temperature_value').val());
    var temperature_date = $.trim($('#temperature_date').val());
    var temperature_time = $.trim($('#temperature_time').val());
    var validateValues = new Array();
    validateValues[0] = value;
    if (!$.isNumeric(value)) {
        $('#temperature_error_message').text('Please enter a valid value').show();
        return false;
    } else if (validateHealthInputValue(4, validateValues)) {
        $('#temperature_error_message').text(error_message).show();
        return false;
    } else if (temperature_date == '' || temperature_date == null) {
        $('#temperature_error_message').text('Please enter a valid date.').show();
        return false;
    } else if (temperature_time == '' || temperature_time == null || !temperature_time.match(/^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/)) {
        $('#temperature_error_message').text('Please enter a valid time.').show();
        return false;
    } else {
        value = Math.round(value * 100) / 100; // round value
        var ladaTemp = Ladda.create(document.querySelector('#read_temperature_button'));
        ladaTemp.start();
        $.ajax({
            type: 'POST',
            url: '/user/api/addHealthRecord',
            data: {
                'type': 4, //temperature
                'value1': value,
                'value2': null,
                'date': temperature_date,
                'time': temperature_time
            },
            dataType: 'json',
            success: function(result) {
                ladaTemp.stop();
                if (result.success == true) {
                    socket.emit('my_health_update', {
                        room: $('#graphUpdatedInRoom').val(),
                        type: 'current_health'
                    });
                    if ($('#temp_graph_container').length != 0) {

                        load_stock_graph();
                    }
                    $("#temperature_display_value").html(result.latest_value_string);
                    $('#temp_updated_time').attr('title', 'Last updated on:' + result.latest_updated_time);
                    if (result.bmi != null) {
                        $("#bmi_value").html(result.bmi);
                    } else {
                        $("#bmi_value").html('-');
                    }
                } else {
//                    bootbox.alert('<div class="server_errormsg">' + result.error_message + '</div>', function() {
//                        window.location.reload();
//                        window.scrollTo(0, 0);
//                    });
                    showServerErrorAlert('Alert', result.error_message, true);
                }
                $("#readTemperature").modal('hide');
                $('#temperature_value').val('');
                $('#temperature_date').val($('#date_today').val());
            }
        });
    }
}


function getSymptomSeverityGraph(itemId) {
    $.ajax({
        type: 'POST',
        url: '/user/api/getSymptomSeverityDetails',
        data: {
            'symptomName': $("#" + itemId).data('symptom_name'),
            'username' : $("#" + itemId).data('username')
        },
        dataType: 'json',
        success: function(result) {

            plotSymptomSeverityDetailGraph(result, itemId);
        }
    });
}

/**
 * function to plot chart with params given
 */
function plotSymptomSeverityDetailGraph(result, itemId) {
    var readings = new Array();
    var is_marker = false;
    for (x in result) {
        readings.push([(x * 1000), parseInt(result[x]) + 0.5]);

    }

    if ( readings.length == 1) {
        is_marker = true;
    }

    var defaultOptions = {
        chart: {
            type: 'line',
            renderTo: itemId,
            borderWidth: 1,
            borderRadius: 0,
            borderColor: '#e1e1e1',
            marginTop: 20,
            marginRight: 2,
            marginLeft: 5

        },
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        navigator: app.graph_settings.graph_navigator,
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        title: {
            floating: true,
            style: {display: 'none;'}
        },
        yAxis: {
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
                    return getSeverityGraphLabel(this.value, 2);
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
        scrollbar: app.graph_settings.graph_scrollbar,
        rangeSelector: app.graph_settings.range_selector,
        plotOptions: {
            series: {
                color: '#469fdd',
                lineWidth: 3,
                marker: {
                    enabled: is_marker
                }
            }
        },
        tooltip: {
            useHTML: true,
            formatter: function() {
                return getSeverityGraphLabel(this.y - 0.5, 1);
            }
        },
        series: [{data: readings}],
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

    $("#" + itemId).highcharts('StockChart', defaultOptions);
     if(app.show_site_notfications) {
            showSiteNotification('Updated symptom main graphs', 'info');
        }
}

/**
 * Function to get band color for symptom severity graph
 * Used in symptom listing page and symptom details page
 * @returns {Array}
 */
function getSymptomPlotBandSettings() {
    return  [
        {from: 1, to: 2, color: '#e6ffe9'},
        {from: 2, to: 3, color: '#fffcdf'},
        {from: 3, to: 4, color: '#fff3e7'},
        {from: 4, to: 5, color: '#ffe2d8'} ];
}


function getSeverityGraphLabel(value, type) {
    var name = "";
    var img = "";
    switch (value) {
        case 1:
            name = "None";
            img = "<div class='severity_detail_band severity_none_band'></div>";
            break;
        case 2:
            name = "Mild";
            img = "<div class='severity_detail_band severity_mild_band'></div>";
            break;
        case 3:
            name = "Moderate";
            img = "<div class='severity_detail_band severity_moderate_band'></div>";
            break;
        case 4:
            name = "Severe";
            img = "<div class='severity_detail_band severity_severe_band'></div>";
            break;
    }
    if (type == 1) {
        return '<b>' + name + '</b>';
    }
    else {
        return img;
    }
}


/**************** symptom detilas page *************/

/**
 * Function to get weekly symptom severity value 
 * and ploat a graph with the value
 * @param {string} itemId : symptom name
 * @param {string} userId : user Id
 */
function getSymptomWeeklySeverityGraph(itemId, userId) {
    $.ajax({
        type: 'POST',
        url: '/user/api/getWeeklySymptomSeverity',
        data: {
            'symptomName': itemId.replace(/[_]/g, " "),
			'userId' : userId
        },
        dataType: 'json',
        success: function(result) {
            if (result.length !== 0) {
                plotSymptomWeeklySeverityGraph(result, itemId);
            } else {
                showSymptomWeeklySeverityGraphError(itemId);
            }
        }
    });
}

/**
 * function to plot chart with params given
 * @param {array} result 
 * @param {string} itemId : graph div name 
 */
function plotSymptomWeeklySeverityGraph(result, itemId) {
    readings = new Array();
    var x_values = new Array();
    var y_values = new Array();


    for (x in result) {
        x_values.push(x);
        y_values.push(parseInt(result[x]) + 0.5);
    }
    /* height fix for graph label*/
    if ( x_values.length > 3){
        $('#' + itemId + '_label').addClass('severiy_boder_clearfix');
    }
    var defaultOptions = {
        chart: {
            type: 'line',
            renderTo: itemId,
            marginLeft: 0,
            marginRight: 0
                    // height:  chartHeight

        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        title: {
            floating: true,
            style: {display: 'none;'}
        },
        xAxis: {
            categories: x_values
        },
        yAxis: {
            allowDecimals: false,
            title: {
                floating: true,
                style: {display: 'none;'}
            },
            labels: {
                useHTML: true,
                align: "right",
                x: 0,
                y: -19,
                formatter: function() {
                    return getSeverityLabel(this.value, 2);
                }
            },
            plotBands: getSymptomPlotBandSettings(),
            max: 5,
            min: 1,
            //height:125,
            gridLineColor: '#e1e1e1',
            showLastLabel: true,
            endOnTick: true,
            tickInterval: 1
        },
        plotOptions: {
            series: {
                color: '#469fdd',
                lineWidth: 3
            }
        },
        tooltip: {
            useHTML: true,
            formatter: function() {
                return getSeverityLabel(this.y - 0.5, 1);
            }
        },
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },
        series: [{data: y_values}],
        lang: {
            noData: "No data available"
        },
        noData: {
            position: {
                verticalAlign: 'bottom'
            },
            style: {
                fontWeight: 'normal',
                fontSize: '14px',
                color: '#303030'
            }
        }
    };

    chart = new Highcharts.Chart(defaultOptions);
    if(app.show_site_notfications) {
        showSiteNotification('Updated Symptoms graphs', 'info')
    }
}

function showSymptomWeeklySeverityGraphError(itemId) {
    $('#' + itemId).html('<div class="nodata">No data available</div>');
}

/**
 * Function retrieve status labels and image urls for displaying in graph
 */
function getSeverityLabel(value, type) {
    var name = "";
    var img = "";
    switch (value) {
        case 1:
            name = "None";
            img = "<div class='severity_band severity_none_band'></div>";
            break;
        case 2:
            name = "Mild";
            img = "<div class='severity_band severity_mild_band'></div>";
            break;
        case 3:
            name = "Moderate";
            img = "<div class='severity_band severity_moderate_band'></div>";
            break;
        case 4:
            name = "Severe";
            img = "<div class='severity_band severity_severe_band'></div>";
            break;
    }
    if (type == 1) {
        return '<b>' + name + '</b>';
    }
    else {
        return img;
    }
}

/************** Gauge chart default settings ***************************/




var gaugeChartSettings = {
    chart: {
        type: 'gauge',
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    tooltip: {
        enabled: false
    },
    label: {
        style: {display: 'none;'}
    },
    legend: {
        enabled: false
    },
    credits: {
        enabled: false
    },
    title: {
        floating: true,
        style: {display: 'none;'}
    },
    loading: {
        labelStyle: {
            top: '60%'
        }
    },
    navigation: {
        buttonOptions: {
            enabled: false
        }
    },
    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
                backgroundColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, '#FFF'],
                        [1, '#666']
                    ]
                },
                borderWidth: 0,
                outerRadius: '114%'
            }, {
                backgroundColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, '#666'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 1,
                outerRadius: '110%'
            }, {
                // default background
            }, {
                backgroundColor: '#DDD',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
    },
    plotOptions: {
        gauge: {
            cursor: 'pointer'
        }
    }
};


function updateSeverityGauge(container, severityValue, params) {
    var dialValue = 0;
    var severityClass = 'dial_condition_' + params.severity;
    var severityName = 'No Data';

    switch (severityValue) {
        case 1:
            dialValue = 25;
            severityName = 'None';
            break;
        case 2:
            dialValue = 75;
            severityName = 'Mild';
            break;
        case 3:
            dialValue = 125;
            severityName = 'Moderate';
            break;
        case 4:
            dialValue = 175;
            severityName = 'Severe';
            break;
    }

    var callback_params = {
        'container': container, // for updating dial
        'dialValue': dialValue, // for updating dial

        /* for call back functions */
        'date': getUserNow(),
        'lastUpdatedDate': '',
        'newSeverity': severityName.toLowerCase(),
        'severityClass': severityClass,
        'symptom_div_id': params.symptom_div_id,
        'severityName': severityName

    };
    /*
     * Save severity
     * @param updateGaugeSeverity : call back funciton name after save
     */
    saveSymtomSeverity ( params.symptom_id, params.date, severityValue, callback_saveSymptomSeverity, callback_params ) ;
}

function renderGaugeChart() {
    /*
     * Loading the gauege graph
     */
    $('.health_dial_indicator .col-lg-3').each(function(index, dial) {
        var symptomDialId = $(dial).attr("id") + "_gauge_graph";
        var symptom_id = $('#' + symptomDialId).data('symptom_id');
        var severity = $('#' + symptomDialId).data('severity');
        var symptom_div_id = $('#' + symptomDialId).parent().attr('id');
        var dialValue = getDialValue(severity);

        var params = {
            'symptom_id': symptom_id,
            'date': '',
            'lastUpdatedDate': '',
            'callback_function': '',
            'severity': severity,
            'dialValue': dialValue,
            'symptom_div_id': symptom_div_id
        };

        ploatGaugeChart(symptomDialId, params);

    });
}

/*
 * Printing Medical summary
 */
$(document).on('change', '#select_print_title0', function() {
    var optionsList = $('input[class="health_options"]');
    if ($(this).is(':checked')) {
        optionsList.prop('checked', true);
    }
    else {
        optionsList.prop('checked', false);
    }
});

$(document).on('change', '#select_print_title1', function() {
    var optionsList = $('input[class="tracker_options"]');
    if ($(this).is(':checked')) {
        optionsList.prop('checked', true);
    }
    else {
        optionsList.prop('checked', false);
    }
});

$(document).on('change', '#select_print_title2', function() {
    var optionsList = $('input[class="body_options"]');
    if ($(this).is(':checked')) {
        optionsList.prop('checked', true);
    }
    else {
        optionsList.prop('checked', false);
    }
});

$(document).on('change', '#select_print_title3', function() {
    var optionsList = $('input[class="symptom_options"]');
    if ($(this).is(':checked')) {
        optionsList.prop('checked', true);
    }
    else {
        optionsList.prop('checked', false);
    }
});