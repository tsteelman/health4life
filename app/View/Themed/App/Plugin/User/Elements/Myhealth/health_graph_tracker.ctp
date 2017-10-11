<div class="clearfix">
    <div style="float:left; width: 240px;"><h2>Health Indicators</h2></div>
<a href="<?php echo Common::getUserProfileLink( $user_details['username'], TRUE); ?>/charts" class="view_more" style="float:right;margin-top:0px;margin-bottom: 20px;">View graphs</a>
</div>
<div class="health_graph clearfix">
  <div class="health_graph_list pull-left">
      <div class="graph_arow active clearfix" id="status_graph">
        <div class="graph_text"> Health Status</div>
        <div class="graph_right_arrow"></div>                                
     </div>
    <div class="graph_arow clearfix" id="weight_graph">
        <div class="graph_text">Medication Side-Effect</div>
        <div class="graph_right_arrow"></div>                                
     </div>   
    <div class="graph_arow clearfix" id="bp_graph">
        <div class="graph_text">Medication Side-Effect</div>
        <div class="graph_right_arrow"></div>                                
     </div>
    <div class="graph_arow clearfix" id="temp_graph">
        <div class="graph_text">Medication Side-Effect</div>
        <div class="graph_right_arrow"></div>                                
     </div>    

  </div>
  <input type="hidden" id="selected_tab" value='1'/>
  <div id="graph_container" class="graph_wraper" style="height: 168px; width: 340px; float:right;"></div>
  <!--<img class="pull-right" src="/theme/App/img/tmp/health_graph.png"-->
  <!--<img class="pull-right" src="/theme/App/img/tmp/health_graph.png"-->
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.health_graph_list .graph_arow').click(function() {
      $('.graph_arow').removeClass('active');
      $(this).addClass('active');
     
      var is_same_user = "<?php echo ($is_same)? '' : $user_details['username']; ?>";
      switch ($(this).attr('id')) {
        case "weight_graph":
          load_graph(1, is_same_user);
          break;
        case "status_graph":
          load_graph(2, is_same_user);
          break;
        case "status_graph":
          load_graph(2, is_same_user);
          break;
        case "bp_graph":
          load_graph(3, is_same_user);
          break;
        case "temp_graph":
          load_graph(4, is_same_user);
          break;
        default:
          load_graph(2, is_same_user);
          break;
      }
      if(app.show_site_notfications) {
            showSiteNotification('Updated health status graphs', 'info');
        }
      return false;
    });
    $('#status_graph').click();
  });
  

  /**
   * Extract x and y cordinates from input
   * 
   */
  function getGraphCordinates(data) {
    var x_values = new Array();
    var y_values = new Array();
    var i = 0;
    var previous_reading = 0;
    var patt = new RegExp('^[0-9]+\/[0-9+]');
    for (x in data) {
      if (patt.test(data[x])) {
        return get_bp_readings(data);
      }
      else if (data[x] * 1 != 0) {
        x_values[i] = x;
        y_values[i++] = data[x] * 1;
        previous_reading = data[x] * 1;
      }
      else if (previous_reading != 0) {
        x_values[i] = x;
        y_values[i++] = previous_reading;
      }
    }
    return xy = {x: x_values, y: y_values};
  }

  /**
   * function to plot chart with params given
   */
  function plot_week_graph(x_values, y_values, series_name, options) {
    readings = new Array();
    var i = 0;
    for (x in y_values) {
      if (y_values[x] instanceof Array) {
        readings.push({
          name: (i==0)? 'Systolic' : 'Diastolic',
          data: y_values[x],
          color: (i++==0)? '#1aadce' : '#8bbc21'
        })
      }
      else {
        readings.push({
          name: series_name,
          data: y_values,
          tooltip: {
            valueDecimals: 2
          }
        });
        break;
      }
    }
    var defaultOptions = {
      chart: {
        type: 'line',
        renderTo: 'graph_container',
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
        categories: x_values,
      },
      yAxis: {
        title: {
          floating: true,
          style: {display: 'none;'}
        }
      },
      plotOptions: {
        series: {
          name: series_name,
          color: '#8bbc21'
        }
      },
      navigation: {
        buttonOptions: {
          enabled: false
        }
      },
      series: readings,
      lang: {
        noData: "No data available"
      },
      noData: {
        style: {
          fontWeight: 'normal',
          fontSize: '15px',
          color: '#303030'
        }
      }
    };
    
    if (options) {
      $.extend(defaultOptions, options)
    }
    
    chart = new Highcharts.Chart(defaultOptions);
  }


  /**
   * Sets status graph options
   */
  function status_graph_options() {
    return {yAxis: {
        startOnTick: true,
        endOnTick:true,
        maxPadding: 0,
        min: 1,
        max: 5,
        title: {
          floating: true,
          style: {display: 'none;'}
        },
        labels: {
          useHTML: true,
          align: "right",
          y: 5,
          formatter: function() {
            return getHealthLabel(this.value, 2);
          },
        },
        tickInterval: 1
      },  
      tooltip: {
        useHTML: true,
        backgroundColor: "rgba(255,255,255,1)",
        shadow:false,
        formatter: function() {
          return getHealthLabel(this.y, 1);
        }
      }
    };
  }
  
/**
 * Extract diastolic and systolic readings seperate
 */
function get_bp_readings(data) {
  var x_values = new Array();
  var first_series = new Array();
  var next_series = new Array();
    for (x in data) {
      bp = data[x].split('/');
      if (bp[0] * 1 != 0) {
        x_values.push(x);
        first_series.push(bp[0] * 1);
        next_series.push(bp[1] * 1);
      }
    }
    y_values = [first_series, next_series];
    return xy = {x: x_values, y: y_values};
}

</script>
