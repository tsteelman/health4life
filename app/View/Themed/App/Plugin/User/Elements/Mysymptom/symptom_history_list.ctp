 <div class="symptom_history_filter_option" <?php if (empty($symptomFilterYears)): ?> style="display:none;" <?php endif; ?>>
            <div class="btn-toolbar">
                <div class="btn-group pull-right">
                    <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">                                                            
                        <div class="filter"><?php echo __('Filter Option'); ?></div>
                    </button>

                    <ul class="dropdown-menu" id="symptom_history_filter">
                        <?php foreach ($symptomFilterYears as $symptomFilterYear) : ?>
                            <li><a data-filter_value="<?php echo $symptomFilterYear['UserSymptom']['record_year']; ?>"><?php echo $symptomFilterYear['UserSymptom']['record_year']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            </div>
 </div>
<?php
if (!empty($historyResult)) {
foreach ($historyResult as $key => $value) {    
    ?>
    <div class="col-lg-12 symptom-history-div" data-severity-name="<?php echo $severityTypes[$value]['name'];?>" data-severity="<?php echo $value; ?>" data-id="<?php echo $key;  ?>" data-date="<?php echo __(CakeTime::nice($key, null, '%m/%d/%Y')); ?>" data-symptom-id="<?php echo $symptomHistories['UserSymptom']['symptom_id']; ?>">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <p class="pull-left">
                <?php     
                echo __(CakeTime::format($key, '%B %e, %Y'));                
                ?>          
            </p>
            <p class="pull-right" style="width: 100px;"><span class=" mood_<?php echo $severityTypes[$value]['name'] ?>">
    <?php
    echo $severityTypes[$value]['label'];
    ?>
                </span></p>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 pull-right">                  
            <?php if($isOwner): ?>
            <button   class="pull-right btn symptom-history-delete" >Delete</button>                

            <button data-toggle="modal"  class="pull-right btn symptom-history-edit" >Edit</button>
            <?php endif; ?>
        </div>
    </div>
    <?php
}}
else {
    echo "<center>No records found for current year</center>";
}
?>