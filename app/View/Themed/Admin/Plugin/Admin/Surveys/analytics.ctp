<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Surveys','/admin/Surveys');
    $this->Html->addCrumb(__(h($surveyName)));
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(h("Survey Analytics - ".$surveyName)); ?>
        </h1>
        <div style="padding-left: 10px;"><h4>Total Number of users attended the survey : <?php echo $attendedUsers ?></h4></div>
    </div>
    <?php if($attendedUsers != 0) { ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <?php foreach ($analyticData as $key => $data) { ?>
                    <div class="table-header">
                        <?php echo __(h($data['questionText'])); ?>
                    </div>
                    <div id="survey_graph<?php echo $key ?>"></div>
 <script type="text/javascript">
    $(function () {
        $('#survey_graph<?php echo $key ?>').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: '<?php echo addslashes(h($data['questionText'])); ?>'
            },
            subtitle: {
                text: 'Attended: <?php echo $data['answered']; ?> , skipped: <?php echo $data['skipped']; ?> ',
                align: 'left'
            },
            xAxis: {
                categories: [<?php echo "'" .implode("', '", $data['options']) . "'" ?>],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                max:100,
                title: {
                    text: 'Percentage (%)',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                Value : 5,
                valueSuffix: '%'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'User Percentage',
                color: 'green',
                data : [ <?php echo rtrim(implode(',', $data['percentage']), ',') ?> ]
            }
        ]
        });
  });
</script>
                <?php } ?>
            </div>
        </div>
    </div>
   <?php } ?>
</div>
<script src="/theme/Admin/js/vendor/highstock.js"></script>
<script src="/theme/Admin/js/vendor/exporting.js"></script>
<script>
    $("ul.nav-list li").removeClass('active');
    $('#survey-list-li').addClass('active');
</script>

        