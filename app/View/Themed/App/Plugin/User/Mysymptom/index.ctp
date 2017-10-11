<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
    $this->Html->addCrumb('My Health', '/profile/myhealth');
} else {
    $this->Html->addCrumb($user_details['username'] . "'s Health",  Common::getUserProfileLink($user_details['username'], true) . '/myhealth');
}

$this->Html->addCrumb('My Symptoms');
?>
<div class="container">
    <input type="hidden" name="graphUpdatedInRoom" value="<?php echo $graphRoom; ?>" id="graphUpdatedInRoom">
    <div class="mysymptoms">
        <div class="row">
            <div class="col-lg-6 col-md-5 col-sm-5">
                <h2> 
                    <?php if ($is_same): 
                           echo __( 'My Symptoms (Last 5 updates)' );
                          else:
                        echo ucfirst($user_details['username']) . "'s Symptoms";
                        ?>
                    <?php endif; ?>
                </h2>
            </div>
            <div class="col-lg-6 col-md-7 col-sm-7">
                <input type="hidden" id="symptomTilePage">
                <input type="hidden" id="symptomUserId" value="<?php echo $user_details['id']; ?>">
                <?php if ($is_same): ?>
					<button class="btn print_btn pull-right print_button" data-toggle="modal" data-target="#printGraph" data-backdrop="static" data-keyboard="false" style="margin-left: 10px;">Print</button>
                    <button data-toggle="modal" data-target="#addSymptom" class="pull-right btn create_button " ><?php echo __('Create new Symptom'); ?></button>
                    <input type="hidden" name="data[mySymptoms][SymptomDate]" id="symptomDatepicker" class="pull-right btn create_button " >
					<input type="hidden" id="symptomTilePage">
                    <button <?php if (empty($symptoms)): ?> style="display:none;" <?php endif; ?> onClick="return false" id="add_new_score_button" class="pull-right btn create_button " ><?php echo __('Add Severity'); ?></button>
                <?php endif; ?>       
            </div>
        </div>

		<div id="symptom_tiles">
        <?php        
        if (!empty($symptoms)) {
            $i = 0;
            ?>
            <div class="row symptom_graph_rep">
                <?php
				$symptomNames = array();
                foreach ($symptoms as $symptom) {
					$symptomNames[$symptom['id']] = $symptom['name'];
					
                    if ($i == 3) {
                        echo '</div><div class="row symptom_graph_rep">';
                        $i = 0;
                    }
                    ?>
                   <?php echo $this->element('User.Mysymptom/symptom_graph_block',
						   array('symptom' => $symptom)); ?> 
                    <?php
                    $i++;
                } // foreach 
                ?>
            </div>
            <?php
        } else {
            echo __('No symptoms found');
        }// end if
        ?>  
		</div>
    </div>
</div>

<?php echo $this->element('User.Mysymptom/add_symptom'); ?>
<?php echo $this->element('User.Myhealth/graph_printer', array('printData' => $symptomNames, 'isSymptomPrint' => true)); ?>

<?php
echo $this->AssetCompress->script('chart.js');
echo $this->AssetCompress->css('graph');
?>

<script type="text/javascript">

                    $("#add_new_score_button").click(function() {
                        $("#symptomDatepicker").datepicker('show');
                    });

                    $(document).ready(function() {
                        renderWeeklyGraphs();

                        $('#symptomDatepicker').datepicker({
                            minDate: "-2y",
                            maxDate: getUserNow(),
                            defaultDate: getUserNow(),
                            onSelect: function(dateText) {
                                window.location.href = "/profile/mysymptom?date=" + dateText;
                            }
                        });
                    });


</script>