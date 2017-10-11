<?php $username = $user_details['username']; ?>
<div class="health_indicator_div">
    <div class="row">
        <div class="col-lg-8 col-md-6"><h2>Daily Health Indicators <?php ?></h2>            
        </div>
		
        <div class="col-lg-4">
			<?php if($isOwner): ?>
			<button data-target="#addSymptom" data-toggle="modal" type="button" class="pull-right btn btn_add_nes_symptom" >
                <?php echo __('Create new Symptom'); ?>
                <img alt="" src="/theme/App/img/create_plus.png">
            </button> 
			<?php else: ?>
			<a href="<?php echo Common::getUserProfileLink($username, TRUE); ?>/mysymptom" class="view_more" style="float: right;">	View graphs</a>
			<?php endif; ?>
		</div>
		
    </div> 
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-lg-12">
            <?php if (isset($dailyHealthIndicator) && !empty($dailyHealthIndicator)) { ?>
            <div class="row"> 
					<div class="col-lg-8" id="dailyhealth_widget_content"> 
						<?php if ($isOwner): ?>
							Click on the dials to update and track your symptoms.                              
						<?php endif; ?>
					</div> 
					<div class="col-lg-4">
						<?php if ($isOwner): ?>
							<a href="<?php echo Common::getUserProfileLink($username, TRUE); ?>/mysymptom" class="view_more" style="float: right;">	View graphs</a>  
						<?php endif; ?>
					</div>			
				</div>
            <?php } else { ?>
                <span>
			<p>
            <?php 
			if($isOwner) {
				echo __('No health records found. Please add new symptom.');
			} else {
				echo __('No health records found.');	
			}			
			?>
			
                </span>
                
            <?php }?>
        </div>
        
    </div>
	<?php if (isset($dailyHealthIndicator) && !empty($dailyHealthIndicator)) { ?>
    
    <div class="severity-label-container">
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3"><span class="symptom-label-box symptom-label-none"></span><p>None</p></div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3"><span class="symptom-label-box symptom-label-mild"></span><p>Mild</p></div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3"><span class="symptom-label-box symptom-label-moderate"></span><p>Moderate</p></div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3"><span class="symptom-label-box symptom-label-severe"></span><p>Severe</p></div>
    </div>
    
        
        <div class="row health_dial_indicator">
            <?php
            $i = 0;
            $count = 0;
            $symtomNameMaxlength = 20;
            foreach ($dailyHealthIndicator as $healthIndicator) {
                $symtomNameLength = strlen($healthIndicator['name']);
                // break row for each four elements
                if ($i == 4) {
                    if ($count == 8) {
                        ?>
                    </div>
                    <div class="row health_dial_indicator">
                        <a href="javascript:void(0)" id="btn_daily-health-load-more" class="view_more"  style="float:right ;margin-right: 15px;">more</a>            
                    </div>
                    <div class="row health_dial_indicator hidden">
                        <?php
                    } else if ($count > 8) {
                        echo '</div><div class="row health_dial_indicator hidden">';
                    } else {
                        echo '</div><div class="row health_dial_indicator">';
                    }
                    $i = 0;
                }
                ?>
                <div class="col-lg-3 dial_condition_<?php echo __(h(strtolower(str_replace(' ', '', $healthIndicator['severity'])))); ?>" 
                     id="<?php echo __(h(strtolower(str_replace(' ', '_', preg_replace('/[^A-Za-z0-9\-]/', ' ', $healthIndicator['name']))))); ?>" 
                     data-last-updated-date = "<?php echo __($healthIndicator['lastUpdated']) ?>">
                    
                    <div id="<?php echo __(h(strtolower(str_replace(' ', '_', preg_replace('/[^A-Za-z0-9\-]/', ' ', $healthIndicator['name']))))); ?>_gauge_graph" 
                        class="gauge_graph"
                        style="width: 120px; height: 140px; margin: 0 auto"
                        data-symptom_id="<?php echo __(h($healthIndicator['id'])); ?>" 
                        data-severity ="<?php echo __(h(strtolower($healthIndicator['severity']))); ?>"> 
                    </div>
                    
                    <h5><a <?php if ($symtomNameLength > $symtomNameMaxlength) {
                            echo 'title="' . $healthIndicator['name'] . '"';
                            } ?> href="/mysymptom/<?php echo $username; ?>/<?php echo urlencode(($healthIndicator['id'])) ?>" >
                            <?php if ($symtomNameLength > $symtomNameMaxlength) {
                                echo substr((__(h($healthIndicator['name']))), 0, $symtomNameMaxlength) . '...';
                            } else {
                                echo __(h($healthIndicator['name']));
                            } ?>
                        </a>
                    </h5>

                    <p  <?php if ( !is_null( $healthIndicator['lastUpdated'] )) { ?>
                        title="Last updated: <?php echo __(CakeTime::format($healthIndicator['lastUpdated'], '%B %e, %Y')); ?>"
                        <?php } ?>
                        >
                        <?php echo __(h($healthIndicator['severity'])); ?>
                    </p>
        <?php if ($is_same) { ?>
                        <div class="add_daily_health">
                            <button type="button" class="btn btn_add_symtom_severity" 
                                    data-symptom_name="<?php echo __(h(ucfirst(preg_replace('/[^A-Za-z0-9\-]/', ' ', $healthIndicator['name'])))); ?>" 
                                    data-symptom_id="<?php echo __(h($healthIndicator['id'])); ?>" 
                                    data-severity ="<?php echo __(h(strtolower($healthIndicator['severity']))); ?>"
                                    title="Add todays severity">
                                <img src="/theme/App/img/plus_icon.png" alt="">
                            </button>
                        </div>
                <?php } ?>
                </div>	
            <?php
            $i++;
            $count++;
        }
        ?>
        </div> 

<?php } ?>	
	<div class="row health_dial_indicator hidden">
		<a href="javascript:void(0)" id="btn_daily-health-load-less" class="view_more"  style="float:right ;margin-right: 15px;">less</a>            
	</div>
</div>
<div id="model_add_symptom_serveriy" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><span class="symptom-selected"></span> severity tracker</h4>
            </div>
            <div class="modal-body">

                <h4>Update the severity for '<span class="symptom-selected"></span>' <span id="modal_symptom_date_selected">today</span></h4>
                <input type="hidden" name="selected-date" id="modal_symptom_datepicker" >
                <input type="hidden" id="selectedSymptomId">

                <div class="btn-toolbar" role="toolbar">
                    <div class="condition_popup_container">

                        <div class="condition_indicator condition_none_header">
                            <label class="condition_none ">
                                <span class="name">None</span>

                                <input name="symptomHistoryRadio" type="radio" value="1">
                            </label>
                        </div>
                        <div class="condition_indicator condition_mild_header">
                            <label class="condition_mild ">
                                <span class="name">Mild</span>

                                <input name="symptomHistoryRadio" type="radio" value="2">
                            </label>
                        </div>
                        <div class="condition_indicator condition_moderate_header">
                            <label class="condition_moderate ">
                                <span class="name">Moderate</span>

                                <input name="symptomHistoryRadio" type="radio" value="3">
                            </label>
                        </div>
                        <div class="condition_indicator condition_severe_header">
                            <label class="condition_severe ">
                                <span class="name">Severe</span>

                                <input name="symptomHistoryRadio" type="radio" value="4">
                            </label>
                        </div>
                        <span id="symptom_history_error_message" style="display: none; color: red;"> Please select valid severity.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" id="btn_symptom_severity_save" class="btn btn_active" >Save</button>
                <button type="button" id="btn_symptom_severity_cancel" class="btn btn_clear" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

    $("#modal_symptom_date_selected").click(function() {
        $("#modal_symptom_datepicker").datepicker('show');
        $(".ui-datepicker").css('top', $(this).position().top + 100);
        $(".ui-datepicker").css('left', $(this).offset().left);
    });


    $(document).ready(function() {

		renderGaugeChart();       
        
        
        $('#modal_symptom_datepicker').datepicker({
            minDate: "-2y",
            maxDate: getUserNow(),
            defaultDate: getUserNow(),
            onSelect: function(dateText) {
                myDate = new Date(Date.parse(dateText));
                symptom_id = $.trim($('#model_add_symptom_serveriy #selectedSymptomId').val());
                $.ajax({
                    url: '/user/api/getUserSymptomSeverity',
                    data: {
                        'id': symptom_id,
                        'date': dateText
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        $('.condition_indicator label').removeClass('on');
                        $('.condition_indicator input').attr("checked", false);
                        id = '';
                        name = '';
                        $.map(result, function(item) {
                            id = item.severityId,
                                    name = item.name
                        });
                        if (name != '') //if already value present  
                        {
                            $('.condition_' + name).addClass('on');
                            $('.condition_' + name).find("input").prop('checked', true);
                            $('.condition_' + name).find("input").hecked = true; // for IE
                        }
                        $('#symptom_history_error_message').hide();
                        $('#symptom_conditions').modal('show');
                        bootbox.hideAll();
                    }
                });
                $('#modal_symptom_datepicker').val(dateText);
                $('#modal_symptom_date_selected').html(dateText);
            }
        });
    });
    
    var chartValue = new Array();
    
    function ploatGaugeChart ( container , params) {
      
            $( '#' +container).highcharts ({
                    chart : gaugeChartSettings.chart,
                    tooltip: gaugeChartSettings.tooltip,
                    label:  gaugeChartSettings.label,
                    legend: gaugeChartSettings.legend,
                    credits: gaugeChartSettings.credits,
                    title: gaugeChartSettings.title,
                    navigation: gaugeChartSettings.navigation,
                    pane: gaugeChartSettings.pane,
                    loading : gaugeChartSettings.loading,
                    yAxis: {
                        min: 0,
                        max: 200,

                        minorTickInterval: 'auto',
                        minorTickWidth: 1,
                        minorTickLength: 0,
                        minorTickPosition: 'inside',
                        minorTickColor: '#666',

                        tickPixelInterval: 50,
                        tickWidth: 2,
                        tickPosition: 'inside',
                        tickLength: 0,
                        tickColor: '#666',
                        labels: {
                             enabled : false             
                        },
                        plotBands: [{
                            from: 0,
                            to: 50,
                            color: '#70bf54',
//                            cursor: 'pointer',
                            events: {
                                <?php if ($is_same) { ?>
                                    click: function(e) {
                                        updateSeverityGauge( container, 1, params);
                                    }
                                <?php } ?>
                            }
                        }, {
                            from: 50,
                            to: 100,
                            color: '#ffc925',
//                            cursor: 'pointer',
                            events: {
                                <?php if ($is_same) { ?>
                                    click: function(e) {
                                        updateSeverityGauge( container, 2, params);
                                    }
                                <?php } ?>
                            }
                        }, {
                            from: 100,
                            to: 150,
                            color: '#ee8125',
//                            cursor: 'pointer',
                            events: {
                                <?php if ($is_same) { ?>
                                    click: function(e) {
                                        updateSeverityGauge( container, 3, params);
                                    }
                                <?php } ?>
                            }
                        }, {
                            from: 150,
                            to: 200,
                            color: '#ed1c24' ,
//                            cursor: 'pointer',
                            events: {
                                <?php if ($is_same) { ?>
                                    click: function(e) {
                                        updateSeverityGauge( container, 4, params);
                                    }
                                <?php } ?>
                            }
                        }],        
             },
                    plotOptions: gaugeChartSettings.plotOptions,
                    series: [{

                        data: [ params.dialValue ],
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {

                        }
                    }]

            },

            function (chart) {
                chartValue [ container ] = chart;               
            });
  }
</script>