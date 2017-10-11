<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Nutrition (Under Development)');
?>

<div class="container">
    <div class="row mynutrition">
        <div class="col-lg-6 col-md-6">
            <div class="myhealth_page_dash_tile">
                <?php echo $this->element('Dashboard/profile_tile', array('showFeeling' => true)); ?>
            </div>
            <div>
                <div class="my_health_personal_info my_plan">My Meal Plan</div>
                <div class="my_health_personal_info daily_plan">Daily Food Journal</div>
                <div class="my_health_personal_info dietary_symptoms">Dietary Symptoms</div>
                <div class="my_health_personal_info favorite_recipes">My Favorite Recipes</div>
                <div class="my_health_personal_info vitamins">Vitamins/Supplements</div>
                <div class="my_health_personal_info scd_diet">SCD Diet</div>
                <div class="my_health_personal_info low_residue_diet">Low Residue Diet</div>
                <div class="my_health_personal_info vegan_diet">Vegan Diet</div>
            </div>
            <div class="health_graph_container nutrition_graph">
                <h2>Weekly Nutrition Indicators</h2>
                <div class="health_graph">
                    <div class="health_graph_list pull-left">
                        <div class="graph_arow active">
                           <div class="graph_text">Weight</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Calorie Intake</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Excercises</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Medication Side-effects</div>
                           <div class="graph_right_arrow"></div>                                
                        </div> 
                    </div>
                    <img class="pull-right" src="/theme/App/img/tmp/health_graph_2.png">
                </div>
            </div>
            <div class="mynutrition_video">
                <img src="/theme/App/img/tmp/nutrition_video_1.png">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="health_details ">
                <h2>Current Health & Nutrition Status</h2>
                <div class="row">
                    <div class="col-lg-4 col-xs-4 col-sm-4 col-md-3" id="current_weight">
                        <p >Weight</p>
                        <span id="weight_display_value">143.3</span> <span id="weight_display_unit">lbs</span>
                        <div class="add_status">
                            <button type="button" data-toggle="modal" data-target="#readWeight" data-backdrop="static" data-keyboard="false" class="btn"><img src="/theme/App/img/plus_icon.png" alt=""></button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 col-sm-4 col-md-3" id="current_height">
                        <p>BMI</p>
                        <span>25</span>
                        <div class="add_status">
                            <button type="button" data-toggle="modal" data-target="#readHeight" data-backdrop="static" data-keyboard="false" class="btn"><img src="/theme/App/img/plus_icon.png" alt=""></button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-4 col-sm-4 col-md-6" id="current_bp">
                        <p>Daily Calorie Intake</p>
                        <span>1834/2550 kcal</span>
                        <div class="add_status">
                            <button type="button" data-toggle="modal" data-target="#readBp" data-backdrop="static" data-keyboard="false" class="btn"><img src="/theme/App/img/plus_icon.png" alt=""></button>
                        </div>
                    </div>                    
                </div> 
            </div>
            <div class="health_graph_container vitamin_graph">
                <h2>Vitamin Trackers</h2>
                <div class="health_graph">
                    <div class="health_graph_list pull-left">                        
                        <div class="graph_arow active">
                           <div class="graph_text">Vitamin A</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Vitamin B 12</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Vitamin B 16</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Vitamin D</div>
                           <div class="graph_right_arrow"></div>                                
                        </div> 
                    </div>
                    <img class="pull-right" src="/theme/App/img/tmp/health_graph_3.png">
                </div>
            </div>
            <div class="health_graph_container">
                <h2>Mineral Trackers</h2>
                <div class="health_graph">
                    <div class="health_graph_list pull-left">
                        <div class="graph_arow active">
                           <div class="graph_text">Potassium</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Manganese</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Iodine</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>
                        <div class="graph_arow ">
                           <div class="graph_text">Calcium</div>
                           <div class="graph_right_arrow"></div>                                
                        </div>                        
                    </div>
                    <img class="pull-right" src="/theme/App/img/tmp/health_graph.png">
                </div>
            </div>
            <div class="body_pain_slider_div">
                <h2>Food Symptom Tracker</h2>
        <div class="body_pain_slider">
            <span class="pain_type fd_coffe pull-left">Coffee</span>
            <div class="pain_slider_container pull-right">                
                 <span>0</span><input id="Coffee_tracker"  data-slider-id='Coffee_tracker' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="3"/><span>10</span>
            </div>
        </div>
         <div class="body_pain_slider">
            <span class="pain_type fd_wheat pull-left">Wheat</span>
            <div class="pain_slider_container pull-right">                
                 <span>0</span><input id="Wheat_tracker"  data-slider-id='Wheat_tracker' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="3"/><span>10</span>
            </div>
        </div>  
         <div class="body_pain_slider">
            <span class="pain_type fd_meat pull-left">Meat</span>
            <div class="pain_slider_container pull-right">                
                 <span>0</span><input id="Meat_tracker"  data-slider-id='Meat_tracker' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="3"/><span>10</span>
            </div>
        </div>
         <div class="body_pain_slider">
            <span class="pain_type fd_chicken pull-left">Chicken</span>
            <div class="pain_slider_container pull-right">                
                <span>0</span> <input id="Chicken_tracker"  data-slider-id='Chicken_tracker' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="3"/><span>10</span>
            </div>
        </div>
         <div class="body_pain_slider">
            <span class="pain_type fd_fruits pull-left">Fruits</span>
            <div class="pain_slider_container pull-right">                
                <span>0</span><input id="Fruits_tracker"  data-slider-id='Fruits_tracker' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="3"/> <span>10</span>
            </div>
        </div>
    </div>
              <div class="mynutrition_video">
                <img src="/theme/App/img/tmp/nutrition_video_1.png">
            </div>
        </div>
    </div>
</div>
<?php
if(Configure::read('App.comingSoon') == true) {
	echo $this->element('User.coming_soon');
}
echo $this->AssetCompress->script('bootstrap-slider');
echo $this->AssetCompress->css('bootstrap-slider.custom');
echo $this->AssetCompress->css('dashboard.css');
?>
<script>
$('#Coffee_tracker').slider({});
$('#Wheat_tracker').slider({});
$('#Meat_tracker').slider({});
$('#Chicken_tracker').slider({});
$('#Fruits_tracker').slider({});
$(document).ready(function() {
	$("#comingSoon").modal('show');
});

</script>