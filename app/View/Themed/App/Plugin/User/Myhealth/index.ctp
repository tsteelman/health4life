<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');

$this->Html->addCrumb('My Health');

echo $this->AssetCompress->css('my_health.css');
echo $this->AssetCompress->css('dashboard.css');
?>

<input id="date_today" type='hidden' value="<?php
if (isset($date_today)) {
    echo $date_today;
}
?>">
<input id="userDateOfBirth" type='hidden' value="<?php
if (isset($userDateOfBirth)) {
    echo $userDateOfBirth;
}
?>">

<div class="container">
    <div class="row myhealth">
        <div class="col-lg-6 col-md-6">
            <div class="myhealth_page_dash_tile">
                <?php echo $this->element('Dashboard/profile_tile', array('showFeeling' => true)); ?>
            </div>           
            <div >
                <a href="/health_record/records/personal">
                    <div class="my_health_personal_info personal_information">My Health <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?></div>
                </a>
                <div class="my_health_personal_info Disease_Assessment_information " data-toggle="modal" data-target="#disease_assessment_modal" >
                    Disease Assessment                    
                </div>
				<div class="my_health_personal_info Family_History_information under_development">
                    My Health & Family History
                    <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?>
                </div>
                <div class="my_health_personal_info disease_information" data-toggle="modal" <?php if (!empty($diseaseSurvey)) { ?> data-target="#surveyList" <?php } ?> data-backdrop="static" data-keyboard="false">Disease
                    Information <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?></div> 
                <div class="my_health_personal_info Medications_information" data-toggle="modal" <?php if (!empty($medicationSurvey)) { ?> data-target="#medicationSurveyList" <?php } ?> data-backdrop="static" data-keyboard="false">Medications & Treatments
                    <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?></div>
				<div class="my_health_personal_info Treatments_information holistic_under_devolopment">Holistic Health <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?></div>
				<div class="my_health_personal_info Qualityl_information quality_under_devolopment under_development">Quality of
                    Life
                    <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?>
                </div>
				<div class="my_health_personal_info Health_Plan_information personal_under_devolopment under_development">
                    Preventive Health
                    <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?>
                </div>
				<div class="my_health_personal_info Health_Sensors_information coordinate_under_devolopment under_development">
                    Co-ordinate My Care
                    <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?>
                </div>
				<div class="my_health_personal_info clinical_trial under_development" >
                    Clinical Trials <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?>
                </div>
				<div class="my_health_personal_info care_information care_under_devolopment under_development">
                    Connected Care
                    <?php echo $this->Html->image('/img/tmp/under_devolopment.png', array('class' => 'pull-right')); ?>
                </div>
                <div class="my_health_personal_info Medical_Summary_information" data-toggle="modal" data-target="#medical_summary_modal">Print
                    your Medical Summary</div>
            </div>
            <div class="health_graph_container">
                <?php echo $this->element('User.Myhealth/health_graph_tracker'); ?>
            </div>
            <div class="bodypain_tracker_container">
                <div class="pain_slider row" >
                    <div style="float:left; width: 110px;">
                        <h2>Tracker</h2>
                    </div>
                    <div style="float:right; width:110px;">
                        <a href="<?php echo Common::getUserProfileLink($user_details['username'], TRUE); ?>/tracker" class="view_more" style="float:right;margin-top:22px;">View graphs</a>
                    </div>
                </div>
                <div class="track_info_indicator">
                    <div class="tracker_container">
                        <div class="row">
                            <div class="col-lg-8 col-md-7">
                                <div class="track_info_head pull-left">Pain Tracker </div>
                                <div class="tracker_updates_msg pull-left">
                                    <span id="painTracker_success_msg" class="saving_msg">Saved successfully</span>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-5 tracker_updates_msg" style="text-align: center;">
                                <span id="painTracker_updated_date" class="tracker_last_updated_date">
                                    <?php
                                    if (isset($latestTrackerTime['pain_tracker']) && $latestTrackerTime['pain_tracker'] != NULL) {
                                        echo 'Last updated on ' . $latestTrackerTime['pain_tracker'];
                                    } else {
                                        echo 'No data added';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="tracker_img">
    <!--                        <input id="painTracker" data-slider-id='painTracker' type="text"
                                   data-slider-min="0" data-slider-max="4" data-slider-step="1"
                                   data-slider-value="2" />-->
                            <input id="painTracker" class="trackerInput" type="text"/>
                        </div>
                        <ul>
                            <li class="feeling_very_good feeling_condition "></li>
                            <li class="feeling_good feeling_condition "><span></span></li>
                            <li class="feeling_neutral feeling_condition "></li>
                            <li class="feeling_bad feeling_condition "></li>
                            <li class="feeling_very_bad feeling_condition "></li>
                        </ul>
                    </div>

                </div>
                <div class="track_info_indicator">
                    <div class="tracker_container" >
                        <div class="row">
                            <div class="col-lg-8 col-md-7">
                                <div class="track_info_head pull-left">Quality Of Life </div>
                                <div class="tracker_updates_msg pull-left">
                                    <span  id="qualityOfLife_success_msg" class="saving_msg">Saved successfully</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 tracker_updates_msg" style="text-align: center;">

                                <span id="qualityOfLife_updated_date" class="tracker_last_updated_date">
                                    <?php
                                    if (isset($latestTrackerTime['quality_of_life']) && $latestTrackerTime['quality_of_life'] != NULL) {
                                        echo 'Last updated on ' . $latestTrackerTime['quality_of_life'];
                                    } else {
                                        echo 'No data added';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="tracker_img">
                            <input id="qualityOfLife" class="trackerInput" type="text" />
    <!--                        <input id="qualityOfLife" data-slider-id='qualityOfLife'
                                   type="text" data-slider-min="0" data-slider-max="4"
                                   data-slider-step="1" data-slider-value="3" />-->
                        </div>
                        <ul>
                            <li class="feeling_very_good feeling_condition "></li>
                            <li class="feeling_good feeling_condition "><span></span></li>
                            <li class="feeling_neutral feeling_condition "></li>
                            <li class="feeling_bad feeling_condition "></li>
                            <li class="feeling_very_bad feeling_condition "></li>
                        </ul>
                    </div>  

                </div>
                <div class="track_info_indicator">
                    <div class="tracker_container">
                        <div class="row">
                            <div class="col-lg-8 col-md-7">
                                <div class="track_info_head pull-left">Sleeping Habits</div>
                                <div class="tracker_updates_msg pull-left">
                                    <span id="sleepingHabits_success_msg" class="saving_msg">Saved successfully</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-5 tracker_updates_msg" style="text-align: center;">
                                <span id="sleepingHabits_updated_date" class="tracker_last_updated_date">
                                    <?php
                                    if (isset($latestTrackerTime['sleeping_habits']) && $latestTrackerTime['sleeping_habits'] != NULL) {
                                        echo 'Last updated on ' . $latestTrackerTime['sleeping_habits'];
                                    } else {
                                        echo 'No data added';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="tracker_img">
                            <input id="sleepingHabits" class="trackerInput" type="text"/>
    <!--                        <input id="sleepingHabits" data-slider-id='sleepingHabits'
                                   type="text" data-slider-min="0" data-slider-max="4"
                                   data-slider-step="1" data-slider-value="4" />-->
                        </div>
                        <ul>
                            <li class="feeling_very_good feeling_condition "></li>
                            <li class="feeling_good feeling_condition "><span></span></li>
                            <li class="feeling_neutral feeling_condition "></li>
                            <li class="feeling_bad feeling_condition "></li>
                            <li class="feeling_very_bad feeling_condition "></li>
                        </ul>
                    </div>

                </div>
            </div>
            <input id="logged_in_user_id" type="hidden"
                   value="<?php echo $user_details['id']; ?>">
            <!--          
            <?php // echo $this->element('User.Myhealth/health_survey_widget');        ?>
                        <div class="event_wraper">
                            <div class="profile_container">
                                <div class="row">
                                    <div class="col-lg-3 ">
            <?php echo Common::getUserThumb($user_details['id'], $user_details['type'], 'medium', 'profile_brdr_5'); ?>

            <?php
            if ($is_same) {
                ?>
                                                                                                                                                                                                                    <a href="/user/edit" class="btn upload_btn">Edit
                                                                                                                                                                                                                    Profile</a>
                <?php
            }
            ?>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="about_person">
                                            <h3 pull="left">
            <?php echo __(Common::getUsername($user_details['username'], $user_details['first_name'], $user_details['last_name'], 'username'));
            ?>                                          
                                            </h3>
            
            <?php
            if ($user_details['type'] === '3') {
                ?>
                                                                                                                                                                                                                    <p>
                                                                                                                                                                                                                    <span class="representing">representing 
                <?php
                echo __($user_details['patient']);
                ?>
                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                    </p> 
                <?php
            }
            ?>
            
                                            <p>
            <?php
            if ($user_details['gender'] === 'M') {
                $gender = 'Male';
            } else {
                $gender = 'Female';
            }
            echo __($gender) . ', ' . __($user_details['age']) . ' yrs, ' . __($user_details['country']);
            ?>                                            
                                            </p>
                                            <span>
            <?php
            echo __('Member since ') . CakeTime::nice($user_details['created'], $timezone, '%B %e, %Y');
            ?>                                                
                                            </span>
                                        </div>
            <?php echo $this->element('User.Myhealth/feeling_indicator'); ?>
            <?php if (isset($user_details['disease'])) {
                ?> 
                                                                                                                                                                                                                    <div class="disease_info">
                                                                                                                                                                                                                    <h6>
                                                                                                                                                                                                                    Disease name<span>: &nbsp;<?php echo __($user_details['disease']); ?></span>
                                                                                                                                                                                                                    </h6>
                                                                                                                                                                                                                    <h6>
                                                                                                                                                                                                                    Treatment<span>: &nbsp;<?php
                if (isset($user_details['treatment'])) {
                    echo __($user_details['treatment']);
                }
                ?></span>
                                                                                                                                                                                                                    </h6>
                                                                                                                                                                                                                    </div>
                <?php
            }
            ?>
                                        <p><?php echo __($user_details['about_me']); ?></p>
                                    </div>
            
                                </div>
            
                            </div>
                        </div>
                        <div class="health_info_row">
                            <div class="col-lg-5 personal_info">
                                <div class="dashboard_header">Personal Information</div>
                            </div>
                            <div class="col-lg-5 treatment_info">
                                <div class="dashboard_header">My Treatments & Therapy</div>
                            </div>
                        </div>
                        <div class="health_info_row">
                            <div class="col-lg-5 health_info" id="health_history_tile">
                                <div class="dashboard_header">Health History</div>
                            </div>
                            <div class="col-lg-5 health_plan_info">
                                <div class="dashboard_header">My Personal Heath Plan</div>
                            </div>
                        </div>
                        <div class="health_info_row">
                            <div class="col-lg-5 cuurnet_health">
                                <div class="dashboard_header">Current Health</div>
                            </div>
                            <div class="col-lg-5 my_pmr">
                                <div class="dashboard_header">My Medical Records</div>
                                <div class="pmr_login">
                                    <p>Login To My PMR</p>
                                    <button class="btn"
                                            onclick="window.open('http://pmr.qburst.com/', '_blank');">Login</button>
                                </div>
                            </div>
                        </div>
                        <div class="health_info_row">
                            <div class="col-lg-5 my_nutrition">
                                <div class="dashboard_header">My Nutrition</div>
                            </div>
                            <div class="col-lg-5 sensor_info">
                                <div class="dashboard_header">My Sensors</div>
                            </div>
                        </div>-->

            <?php echo $this->element('User.Scheduler/medication_tile'); ?>
        </div>

        <div class="col-lg-6 col-md-6">
            <!--            <div class="my_health_video">
                <div id='playerXDjWaTHLndHm'></div>
                <script type='text/javascript'>
                                    jwplayer('playerXDjWaTHLndHm').setup({
                                        file: 'https://www.youtube.com/watch?v=9QscURRuF0g',
                                        width: '100%',
                                        image: '/theme/App/img/tmp/dasboard_my_videos.png',
                                        displaytitle: false

                                    });
                </script>
            </div>-->

            <?php echo $this->element('User.Myhealth/current_health_widget'); ?>

            <!-- health indicator -->
            <div id="health_indicator">
                <?php
                echo $this->element('User.Myhealth/health_indicator_widget', array('username' => $user_details['username'], 'is_same' => $is_same)
                );
                ?>
            </div>
            <!-- /health indicator -->

            <!-- bodypain_tracker -->
            <?php echo $this->element('User.Myhealth/body_pain_tracker_widget'); ?>
            <!-- /bodypain_tracker -->


        </div>
    </div>
</div>
</div>

<?php
echo $this->element('User.Myhealth/read_weight');
echo $this->element('User.Myhealth/read_bp');
echo $this->element('User.Myhealth/read_height');
echo $this->element('User.Myhealth/read_temperature');
echo $this->element('User.Myhealth/change_unit_settings_popup');
echo $this->element('User.Myhealth/personal_info_survey_widget');
echo $this->element('User.Myhealth/health_history_survey_widget');
echo $this->element('User.Myhealth/survey_list');
echo $this->element('User.Myhealth/medication_survey_tile');
echo $this->element('User.Mysymptom/add_symptom');
echo $this->element('User.Myhealth/disease_assessment_modal');
echo $this->element('User.Myhealth/medical_summary_print');
if(Configure::read('App.comingSoon') == true) {
	echo $this->element('User.coming_soon');
}
$this->AssetCompress->script('my_health.js', array('block' => 'scriptBottom'));
$this->Html->script(array('//jwpsrv.com/library/+wt_PpJBEeOk_yIACmOLpg.js'), array('inline' => false));
?>






<script type="text/javascript">
    /**
     $(document).on('click', '.personal_information', function() {
     event.preventDefault();
     var left = ($(window).width() / 2) - (880 / 2);
     var height = $(window).height();
     
     window.open("https://www.surveygizmo.com/s3/1545068/p4l", "popupWindow", "width=880,height=" + height + ",scrollbars=yes,toolbar=no,left=" + left);
     // 	if ($('#personalInfoSurveyModal').length > 0) {
     // 		$('#personalInfoSurveyModal').modal('show');
     // 		$('#personalSurveyWizard').wizard();
     // 	}
     });
     **/
    var trackerDataArray = null;
    trackerDataArray = <?php echo $latestTrackerValues; ?>;
    $(document).on('click', '.disease_information', function() {
        //event.preventDefault();
        var left = ($(window).width() / 2) - (880 / 2);
        var height = $(window).height();

        // window.open("https://edu.surveygizmo.com/s3/1551032/p4l-health", "popupWindow", "width=880,height=" + height + ",scrollbars=yes,toolbar=no,left=" + left);
        //         if ($('#healthHistorySurveyModal').length > 0) {
        //             $('#healthHistorySurveyModal').modal('show');
        //             $('#healthHistorySurveyWizard').wizard();
        //         }
    });
    $(document).on('click', '.Medications_information', function() {
        //event.preventDefault();
        var left = ($(window).width() / 2) - (880 / 2);
        var height = $(window).height();

        // window.open("http://sgiz.mobi/s3/Patients4Life-Medications", "popupWindow", "width=880,height=" + height + ",scrollbars=yes,toolbar=no,left=" + left);
        //         if ($('#healthHistorySurveyModal').length > 0) {
        //             $('#healthHistorySurveyModal').modal('show');
        //             $('#healthHistorySurveyWizard').wizard();
        //         }
    });
    $(document).on('click', '.Treatments_information', function() {
        event.preventDefault();
        var left = ($(window).width() / 2) - (880 / 2);
        var height = $(window).height();

        window.open("http://sgiz.mobi/s3/Crohn-s-Assessment", "popupWindow", "width=880,height=" + height + ",scrollbars=yes,toolbar=no,left=" + left);
        //         if ($('#healthHistorySurveyModal').length > 0) {
        //             $('#healthHistorySurveyModal').modal('show');
        //             $('#healthHistorySurveyWizard').wizard();
        //         }
    });

    //    var MIN_COMMON = 0;
    //    var MAX_WEIGHT_POUNDS = 1000;
    //    var MAX_WEIGHT_KG = 500;
    //    var MAX_HEIGHT_CM = 1000;
    //    var MAX_HEIGHT_FEET = 10;
    //    var MAX_HEIGHT_INCH = 12;
    //    var MAX_TEMPERATURE_F = 300;
    //    var MAX_TEMPERATURE_C = 150;
    //    var MAX_BP_DIASTOLIC = 200;
    //    var MAX_BP_SYSTOLIC = 200;
    //    var error_message = '';



    $(".current_health_date").datepicker({
        minDate: new Date($('#userDateOfBirth').val()),
        maxDate: new Date($('#date_today').val()),
        onSelect: function(date) {
            if (date == today) {
                $('#temperature_time').timepicker('setTime', now());
                $('#bp_time').timepicker('setTime', now());
            }
        }
    });

    $(document).on('keyup', '#weight_value, #bp_value1, #bp_value2, #height_value1, #height_value2, #temperature_value', function(e) {
        //        console.log("hi");
        var txb = this;
        txb.value = txb.value.replace(/[^\0-9]/ig, "");
    });
    $(document).on('click', '#weight_submit_button', function(e) {
        validate_weight_details();
    });
    $(document).on('click', '#bp_submit_button', function(e) {
        validate_bp_details();
    });

    $(document).on('click', '#read_temperature_button', function(e) {
        validate_temperature_details();
    });

    $(document).on('click', '#height_submit_button', function(e) {
        $('#height_value1_error_message, #height_value2_error_message, #height_error_message').hide();
        var value1 = $.trim($('#height_value1').val());
        var validateValues = new Array();
        validateValues[0] = value1;
        if ($('#height_value2').length > 0) {
            var value2 = $.trim($('#height_value2').val());
            validateValues[1] = value2;
        } else {
            var value2 = 'metric';
        }
        var currentdate = new Date();
        if (!$.isNumeric(value1)) {
            //            $('#height_value1_error_message').show();
            $('#height_error_message').text('Please enter a valid height.');
            $('#height_error_message').show();
            return false;
        } else if ((!$.isNumeric(value2) && value2 != 'metric' && value2 != "")) {
            //            $('#height_value2_error_message').show();
            $('#height_error_message').text('Please enter a valid height.');
            $('#height_error_message').show();
            return false;
        } else if (validateHealthInputValue(2, validateValues)) {
            //            $('#height_value1_error_message').text(error_message);
            //            $('#height_value1_error_message').show();
            $('#height_error_message').text(error_message);
            $('#height_error_message').show();
            return false;
        } else {
            var ladaheight = Ladda.create(document.querySelector('#height_submit_button'));
            ladaheight.start();
            $.ajax({
                type: 'POST',
                url: '/user/api/addHealthRecord',
                data: {
                    'type': 2, //height
                    'value1': value1,
                    'value2': value2
                },
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(result) {
                    ladaheight.stop();
                    if (result.success == true) {
                        $("#height_display_value").html(result.latest_value_string);
                        $('#height_updated_time').attr('title', 'Last updated on:' + result.latest_updated_time);
                        if (result.bmi != null) {
                            $("#bmi_value").html(result.bmi);
                            //                            $("#bmi_unit").removeClass('hidden');
                            //                            $("#bmi_unit").addClass('show');
                        } else {
                            $("#bmi_value").html('-');
                        }
                    } else {
                        //                        bootbox.alert('<div class="server_errormsg">'+result.error_message+'</div>', function() {
                        //                            window.location.reload();
                        //                            window.scrollTo(0, 0);
                        //                        });
                        showServerErrorAlert('Alert', result.error_message, true);
                    }
                    $("#readHeight").modal('hide');
                    $('#height_value1').val('');
                    $('#height_value2').val('');
                    //                    console.log('result', result);
                }
            });
        }

    });

    //    var MIN_COMMON = 0;
    //    var MAX_WEIGHT_POUNDS = 1000;
    //    var MAX_WEIGHT_KG = 500;
    //    var MAX_HEIGHT_CM = 1000;
    //    var MAX_HEIGHT_FEET = 10;
    //    var MAX_TEMPERATURE_F = 300;
    //    var MAX_TEMPERATURE_C = 150;
    //    var MAX_BP_DIASTOLIC = 250;
    //    var MAX_BP_SYSTOLIC = 200;

	$(document).ready(function() {
		$("#comingSoon").modal('show');
	});
</script>
