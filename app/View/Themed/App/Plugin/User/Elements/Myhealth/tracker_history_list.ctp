 <div class="symptom_history_filter_option" <?php if (empty($filterYears)): ?> style="display:none;" <?php endif; ?>>
            <div class="btn-toolbar">
                <div class="btn-group pull-right">
                    <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">                                                            
                        <div class="filter"><?php echo __('Filter Option'); ?></div>
                    </button>

                    <ul class="dropdown-menu" id="symptom_history_filter">
                        <?php foreach ($filterYears as $symptomFilterYear) : ?>
                            <li><a data-filter_value="<?php echo $symptomFilterYear['HealthReading']['record_year']; ?>"><?php echo $symptomFilterYear['HealthReading']['record_year']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
 </div>
<?php
if (!empty($historyResult)) {
foreach ($historyResult as $key => $value) {    
    ?>
    <div class="col-lg-12 tracker-history-div" data-severity-name="<?php echo $trackerValues[$value]['name'];?>" data-severity="<?php echo $value; ?>" data-id="<?php echo $key;  ?>" data-date="<?php echo __(CakeTime::nice($key, null, '%m/%d/%Y')); ?>" data-record-type="<?php echo $histories['HealthReading']['record_type']; ?>">
        <div class="col-lg-4">
            <p class="pull-left">
                <?php     
                echo __(CakeTime::format($key, '%B %e, %Y'));                
                ?>          
            </p>
            <p class="pull-right tracker_condition" >
                <span class="pull-left"><?php echo $trackerValues[$value]['label']; ?></span>
				<span class="feeling_condition pull-right <?php echo $trackerValues[$value]['name'] ?>_smile"></span>
            </p>
        </div>
        <div class="col-lg-6 pull-right">                  
            <?php if($isOwner): ?>
            <button  class="pull-right btn tracker-history-delete">Delete</button>                
            <?php endif; ?>
        </div>
    </div>
    <?php
}}
else {
    echo "<center>No records found for current year</center>";
}
?>