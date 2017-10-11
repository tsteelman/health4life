<?php
$this->AssetCompress->addScript(array(
    'vendor/jquery.ui.touch-punch.min.js',
//    'vendor/jquery.maphilight.js'
        ), 'paintracker_touch.js');
echo $this->AssetCompress->includeJs();
?>
<div class="bodypain_tracker_container">
    <input type="hidden" name="graphUpdatedInRoom" value="<?php echo $graphRoom; ?>" id="graphUpdatedInRoom" />
    <div id="pain_tracker_container" class="pain_tracker">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <h2>Body Pain Tracker</h2>
            </div>
            <div class="col-lg-4 col-md-4">
                <a href="<?php echo Common::getUserProfileLink($user_details['username'], TRUE); ?>/paintracker" class="view_more">View graphs</a>
            </div>
        </div>
        <p id="pain_tracker_title_message">
            <?php
            if ($isOwner != true) {
                if ($latestPainDataDetails != null) {
                    echo 'Latest pain data added on ' . $latestTimeValueFormated;
                } else {
                    echo 'No pain information added.';
                }
            } else {
                echo 'Drag and drop any of the pain icon to the body part where you feel a sensation of pain and register your pain information.';
            }
            ?>
        </p>
        <?php //  if ($isOwner == TRUE) { ?>
        <!--<div id = "draggables_container_wraper" class = "draggables_container_wraper">-->

        <div id = "draggables_container" class="draggables_container" style = "<?php
        if ($isOwner != TRUE) {
            echo 'display:none';
        }
        ?>">
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Numbness</p>
                <img id = "drag_pain_icon1" data-pain-type = "1" src = "/theme/App/img/dragg_numbness_small.png" class = "drag_pain_icon1 drag_pain_icon">
            </div>
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Pins</p>
                <img id = "drag_pain_icon2" data-pain-type = "2" src = "/theme/App/img/dragg_pins_small.png" class = "drag_pain_icon2 drag_pain_icon">
            </div>
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Burning</p>
                <img id = "drag_pain_icon3" data-pain-type = "3" src = "/theme/App/img/dragg_burning_small.png" class = "drag_pain_icon3 drag_pain_icon">
            </div>
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Stabbing</p>
                <img id = "drag_pain_icon4" data-pain-type = "4" src = "/theme/App/img/dragg_stabbing_small.png" class = "drag_pain_icon4 drag_pain_icon">
            </div>
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Throbbing</p>
                <img id = "drag_pain_icon5" data-pain-type = "5" src = "/theme/App/img/dragg_throbbing_small.png" class = "drag_pain_icon5 drag_pain_icon">
            </div>
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Aching</p>
                <img id = "drag_pain_icon6" data-pain-type = "6" src = "/theme/App/img/dragg_aching_small.png" class = "drag_pain_icon6 drag_pain_icon">
            </div>
            <div class = "col-lg-2 col-xs-2 col-sm-2 col-md-2">
                <p class = "pin_name">Cramping</p>
                <img id = "drag_pain_icon7" data-pain-type = "7" src = "/theme/App/img/dragg_cramping_small.png" class = "drag_pain_icon7 drag_pain_icon">
            </div>
        </div>
        <!--</div>-->
        <?php if ($isOwner != TRUE) { ?>
            <div id="last_pain_details_container" style="position: absolute;margin-left: -20px;margin-top: -70px;">

            </div>
            <?php
        }
        ?>
        <?php
//       }
        ?>
        <div id="paintracker_div" class="paintracker_div"> <img id="skeleton_front_view" src="/theme/App/img/Skeleton-Sketch-all.jpg" draggable="true" class="human_body" usemap ="#s-1-map"></div>
        <!--<div id="paintracker_div" class="paintracker_div"> <img id="skeleton_front_view" src="/theme/App/img/Skeleton-Sketch-all-old.jpeg" draggable="true" class="human_body" usemap ="#s-1-map"></div>-->

        <map name="s-1-map" id="s-1-map">
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="1" data-toggle="popover" data-enabled="true" id="male_front_head" coords="78,23,120,23,120,78,78,78"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="1" data-toggle="popover" data-enabled="true" id="male_back_head" coords="340,23,380,23,380,78,340,78"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="1" data-toggle="popover" data-enabled="true" id="male_right_head" coords="217,29,233,22,255,25,266,54,263,74,250,75,245,85,236,89,214,85,221,76,220,54,215,47"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="1" data-toggle="popover" data-enabled="true" id="male_left_head" coords="505,87,481,87,470,89,469,72,458,71,458,55,450,55,455,46,461,27,477,21,487,22,502,28,503,39,499,54,496,70"/>

            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="2" data-toggle="popover" data-enabled="true" id="male_front_chest" coords="67,104,77,78,121,78,133,113,124,161,121,158,101,128,96,128,76,163,67,142"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="2" data-toggle="popover" data-enabled="true" id="male_right_chest" coords="237,90,239,97,239,119,252,131,266,129,266,122,260,106,246,86"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="2" data-toggle="popover" data-enabled="true" id="male_left_chest" coords="481,87,470,89,464,94,453,113,453,125,464,128,479,120"/>

            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="3" data-toggle="popover" data-enabled="true" id="male_front_abdomen" coords="73,161,79,161,96,129,102,129,121,161,120,161,125,161,126,181,108,201,88,201,70,182"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="3" data-toggle="popover" data-enabled="true" id="male_right_abdomen" coords="238,118,251,130,265,128,264,205,249,184,239,178,227,178,217,187,221,163,216,146"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="3" data-toggle="popover" data-enabled="true" id="male_left_abdomen" coords="452,126,463,129,478,121,484,120,499,145,496,161,496,179,482,177,473,181,460,193,453,205"/>

            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="4" data-toggle="popover" data-enabled="true" id="male_front_pelvis" coords="70,182,88,201,108,201,126,181,133,212,98,233,66,212"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="4" data-toggle="popover" data-enabled="true" id="male_back_buttecks" coords="328,177,344,176,353,187,363,187,376,176,387,180,392,204,392,219,362,236,324,219"/>

            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="6" data-toggle="popover" data-enabled="true" id="male_front_lefthand" coords="71,85,67,124,59,173,47,203,49,220,41,246,18,245,16,228,23,211,33,206,41,155,45,139,47,97"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="6" data-toggle="popover" data-enabled="true" id="male_front_righthand" coords="124,81,148,93,152,146,159,162,165,206,172,208,181,228,174,243,156,244,151,213,140,178,132,138"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="6" data-toggle="popover" data-enabled="true" id="male_back_lefthand" coords="332,85,326,117,326,140,320,177,308,210,302,246,281,246,277,229,286,207,292,203,307,130,309,96"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="6" data-toggle="popover" data-enabled="true" id="male_back_righthand" coords="390,85,405,91,411,122,414,151,423,205,430,207,441,232,439,244,417,246,409,211,396,171,391,122"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="6" data-toggle="popover" data-enabled="true" id="male_right_righthand" coords="215,84,237,88,239,95,239,117,212,151,212,177,208,211,216,227,213,231,213,242,196,242,194,223,194,205,191,149,210,110,210,95"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="6" data-toggle="popover" data-enabled="true" id="male_left_lefthand" coords="504,87,480,87,478,94,478,116,504,150,504,176,512,205,501,226,503,230,503,241,520,241,526,222,525,204,525,148,510,114"/>

            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="7" data-toggle="popover" data-enabled="true" id="male_front_rightleg" coords="65,209,97,230,94,301,95,320,94,346,100,367,92,390,83,402,70,389,82,368,78,335,72,315"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="7" data-toggle="popover" data-enabled="true" id="male_front_leftleg" coords="98,230,133,209,131,241,121,285,125,311,115,362,127,397,107,402,98,357,102,349"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="7" data-toggle="popover" data-enabled="true" id="male_right_leg" coords="264,201,249,180,239,174,227,174,217,183,214,205,216,216,223,227,228,284,216,327,221,346,226,374,218,401,278,401,272,392,243,379,264,257"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="7" data-toggle="popover" data-enabled="true" id="male_left_leg" coords="496,175,482,173,473,177,460,189,453,201,453,252,460,282,459,294,467,301,467,328,475,378,442,394,437,401,499,400,496,388,492,377,498,328,499,318,492,293,490,280,497,218,504,204"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="7" data-toggle="popover" data-enabled="true" id="male_back_rightleg" coords="360,235,393,216,384,288,384,326,376,355,381,398,363,408,359,377,359,366"/>
            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="7" data-toggle="popover" data-enabled="true" id="male_back_leftleg" coords="326,216,360,235,357,377,353,405,332,399,332,393,341,366"/>


            <area shape="poly" alt="human_body_map" class="map1" data-body-part ="5" data-toggle="popover" data-enabled="true" id="male_back_back" coords="329,84,338,79,378,79,391,86,393,121,387,151,388,182,377,178,364,189,354,189,345,178,329,179,330,145,326,133,325,115"/>

        </map>
        
        <div id="pain_reading_form_container" class="hide">

            <div  style='width:250px;'>
                <div>
                    <div class="body_pain_slider">
                        <div class="pain_slider_container">                
                            <span>0</span><input id="select_severity" class="select_severity_slider" type="text"/><span>10</span>
                        </div>
                    </div>
                </div>
                <div class="popover_btn_container">
                    <button type="button" 
                            id ="add_pain_data"
                            class="btn_active ladda-button btn"
                            data-style="slide-left"
                            data-spinner-color="#3581ED"
                            <span class="ladda-spinner"></span><?php echo __('Add pain') ?>
                    </button>
                    <input type="button" id="cancel_pain_data" class="btn btn_clear" value="cancel">
                </div>
            </div>
        </div>
        <div id="pain_reading_update_form_container" class="hide">

            <div  style='width:250px;'>
                <div>
                    <div class="body_pain_slider">
                        <div class="pain_slider_container">                
                            <span>0</span><input id="select_severity" class="select_severity_slider" type="text"/><span>10</span>
                        </div>
                    </div>
                </div>
                <div class="update_popover_btn_container">
                    <button type="button" 
                            id ="update_pain_data"
                            class="btn_active ladda-button btn"
                            data-style="slide-left"
                            data-spinner-color="#3581ED"
                            <span class="ladda-spinner"></span><?php echo __('Update') ?>
                    </button>
                    <button type="button" 
                            id ="delete_pain_data"
                            class="btn_active ladda-button btn"
                            data-style="slide-left"
                            data-spinner-color="#3581ED"
                            <span class="ladda-spinner"></span><?php echo __('Delete') ?>
                    </button>
                    <button type="button" 
                            id ="cancel_update"
                            class="btn_clear ladda-button btn"
                            data-style="slide-left"
                            data-spinner-color="#3581ED"
                            <span class="ladda-spinner"></span><?php echo __('cancel') ?>
                    </button>
                </div>
            </div>
        </div>

        <?php if ($isOwner == TRUE) { ?>
            <div id="finish_btn_container" class="finish_btn_container">
                <div id="latest_pain_message_container" class="pull-left" style="<?php
                if ($latestTimeValueFormated == NULL) {
                    echo 'display:none';
                }
                ?>">
                    Last updated on <?php echo __($latestTimeValueFormated); ?>                  
                </div>
                <div id="finish_success_message_container" class="pull-left"  style="display: none;">
                    <div id="finish_success_message">Your pain details has been updated successfully.</div>
                </div>
                <div id="save_pain_finish_btn_container" class="pull-right">
                    <button id="clear_all_fisrt"
                            class="btn btn_active pull-right" 
                            <?php echo (empty($latestPainDataDetails) || $latestPainDataDetails == NULL || $latestPainDataDetails == '[]') ? "disabled = 'disabled'" : ''; ?>>
                        Clear All
                    </button>
                    <button id="clear_all_pain_data"
                            class="btn btn_active pull-right ladda-button"
                            style="display: none;"
                            data-spinner-color="#3581ED"
                            data-style="expand-right"
                            <?php echo (empty($latestPainDataDetails) || $latestPainDataDetails == NULL || $latestPainDataDetails == '[]') ? "disabled = 'disabled'" : ''; ?>>
                        Finish
                    </button>
					<button id="cancel_clear_pain_data"
                            class="btn btn_active pull-right ladda-button"
                            style="display: none;"
                            data-spinner-color="#3581ED"
                            data-style="expand-right"
                            <?php echo (empty($latestPainDataDetails) || $latestPainDataDetails == NULL || $latestPainDataDetails == '[]') ? "disabled = 'disabled'" : ''; ?>>
                        Cancel
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    var latestPainDataDetails = null;
    var latestTimeValueFormated = null;
<?php
/*
 * On page loading we load the last padin data details and
 * past time value formated to the js variables
 */
if ($latestPainDataDetails != NULL) {
    ?>
        latestPainDataDetails = <?php echo $latestPainDataDetails ?>;
    <?php
}

if ($latestTimeValueFormated != NULL) {
    ?>
        latestTimeValueFormated = "<?php echo $latestTimeValueFormated ?>";
    <?php
}

/*
 * When a pain data is modified the 'all_pain_array' and 'save_pain_array' array
 * will send back to server to record the current modification
 * 
 * @var all_pain_array : contains all the previously saved pain data details
 * @var save_pain_array : contains all the new modification pain details
 */
?>

    var selected_body_main_part = 1;
    var selected_pain_type = 1;
    var all_pain_array = new Array();
    var save_pain_array = new Array();
    var new_pain_obj = {};
    var append_pin = false;
    var bodyPartsArray = <?php echo $bodyPartsArray; ?>;
    var bodySubPartsArray = <?php echo $bodySubPartsArray; ?>;

    var x_pos_image = 0;
    var y_pos_image = 0;
    var id_counter = 0;
    var current_fixed_pin_id = 0;

    var is_touch_device = 'ontouchstart' in document.documentElement;

    $(document).ready(function() {
        $('.popover').css('z-index', '2000 !important');
        var img = $('#skeleton_front_view');
<?php
/*
 * If the logged in user is visiting his own data
 */
if ($isOwner == true) {
    ?>

/**
 * plugin to help in change the image map and redrow.
 **/

//            img.mapster({
//                mapKey: 'id',
//                //                fill: false,
//                fill: true,
//                fillColor: '9999ff',
//                stroke: true,
//                //                stroke: false,
//                singleSelect: true,
//                onConfigured: function() {
//                    img.siblings().css('z-index', '0');
//                    img.css('z-index', '10');
//                }
//            });


/**
 * plugin to help in change the image map and redrow.
 **/
//            img.maphilight({
//                fill: true,
//                fillColor: 'ff0000',
//                fillOpacity: 0.2,
//                stroke: true,
//                strokeColor: 'ff0000',
//                strokeOpacity: 1,
//                strokeWidth: 1,
//                alwaysOn: true
//            });
            var x1, y1 = 0;
            var area = []; //set of area objects
            var myDropTarget = 'invalid';

            function dropTarget(dropX, dropY) {
                var target = 'invalid';
                for (i = 0; i < area.length; i++) { //iterate through all of our area objects
                    if (pnpoly(area[i].x.length, area[i].x, area[i].y, dropX, dropY)) {
//                        for (ix = 0; ix < area[i].x.length; ix++) {
//                            console.log(area[i].x[ix] + ', ' + area[i].y[ix]);
//                        }
                        target = area[i].id;
                        break;
                    }
                }
                return target;
            }

            function pnpoly(nvert, vertx, verty, testx, testy) {
                var i, j, c = false;
                for (i = 0, j = nvert - 1; i < nvert; j = i++) {
                    if (((verty[i] > testy) != (verty[j] > testy)) &&
                            (testx < (vertx[j] - vertx[i]) * (testy - verty[i]) / (verty[j] - verty[i]) + vertx[i])) {
                        c = !c;
                    }
                }
                return c;
            }
            
            
//this creates an array of area polygon objects so that we can test when an item has been dropped inside of one

            $('map area').each(function(i) {                
                area[i] = {}; // creates a new object which will have properties for id, x coordinates, and y coordinates
                area[i].id = $(this).attr("data-body-part");
                area[i].x = [];
                area[i].y = [];
                var coords = JSON.parse('[' + $(this).attr("coords") + ']');
                var totalPairs = coords.length / 2;
                var coordCounter = 0; //variable to double iterate
                for (ix = 0; ix < totalPairs; ix++) { //fill arrays of x/y coordinates for pnpoly

                    area[i].x[ix] = coords[coordCounter];
                    area[i].y[ix] = coords[coordCounter + 1];
                    coordCounter += 2;
                }
            });

            function getUpdateFormHtml() {
                return $("#pain_reading_update_form_container").html();
            }

            function getFormHtml() {
                return $("#pain_reading_form_container").html();
            }

            function showPopover() {
                $('.dragging_pin').popover({
                    placement: 'auto',
                    trigger: 'manual',
                    title: 'Choose Pain Severity',
                    animation: false,
                    html: true,
                    content: function() {
                        return getFormHtml();
                    },
                    container: 'body'
                });
                $('.dragging_pin').popover('show');
            }

            function showUpdatePainPopover() {
                $('.fixed_pin').popover({
                    placement: 'auto',
                    trigger: 'manual',
                    title: 'Update pain severity',
                    animation: false,
                    html: true,
                    content: function() {
                        return getUpdateFormHtml();
                    },
                    container: 'body'
                });
            }

            function setPopoverData() {
                $('.popover .select_severity_slider').slider({
                    max: 10,
                    disabled: false,
                    min: 1,
                    step: 1
                });
                $('.popover .select_severity_slider').slider('enable');
            }

/**
 * funtion to set body name in popover
 */
            function setSelectedPartNameInPopover() {
                $('.popover #body_main_part_display').text(bodyPartsArray[selected_body_main_part]);
            }
/**
 *  Seting severity in popover for existing pins
 */            
            $(document).on('shown.bs.popover', '.fixed_pin', function() {
                setPopoverData();
                var value = all_pain_array[$(this).attr('id')];
                $('.popover .select_severity_slider').slider('setValue', value.severity);
            });

            $(document).on('shown.bs.popover', '.dragging_pin', function() {
                setPopoverData();
            });

            function setFinishBar() {
                var isDataPresent = false;
                $.each(all_pain_array, function(index, value) {
                    if (typeof value != 'undefined') {
                        if (value.severity != null && parseInt(value.severity) > 0) {
                            isDataPresent = true;
                            return false; // break the loop
                        }
                    }
                });

                if (isDataPresent) {
                    if ($(".fixed_pin").is(':visible')) {
                        $("#save_pain_finish_btn_container #clear_all_fisrt").removeAttr('disabled').show();
                        $("#save_pain_finish_btn_container #clear_all_pain_data").removeAttr('disabled').hide();
						$("#save_pain_finish_btn_container #cancel_clear_pain_data").removeAttr('disabled').hide();
                    } else {
                        $("#save_pain_finish_btn_container #clear_all_fisrt").attr('disabled', 'disabled').show();
                    }
                } else {
                    $("#save_pain_finish_btn_container #clear_all_pain_data").attr('disabled', 'disabled').hide();
					$("#save_pain_finish_btn_container #cancel_clear_pain_data").attr('disabled', 'disabled').hide();
                    $("#save_pain_finish_btn_container #clear_all_fisrt").attr('disabled', 'disabled').show();
                }

                $("#finish_success_message_container").show();
                window.setTimeout(function() {
                    $("#finish_success_message_container").hide();
                }, 3000);

                $("#latest_pain_message_container").hide();
                $("#draggables_container").show();
            }



            $(document).on('click', '.fixed_pin', function(e) {
                current_fixed_pin_id = $(this).attr('id');
                $('.popover').remove();
                $('.dragging_pin').removeClass('dragging_pin').remove();
                $(this).popover('show');
            });

            $(document).on('click', '#cancel_update', function(e) {
                $('.fixed_pin').popover('hide');
            });

            $(document).on('click', '#delete_pain_data', function(e) {
                var ladda_button = Ladda.create(this);
                $("#" + current_fixed_pin_id).fadeOut(10, function() {
                    $(this).remove();
                });

                // save deleted nodes
                if (latestPainDataDetails != null) {
                    if (current_fixed_pin_id in latestPainDataDetails) {
                        save_pain_array [ current_fixed_pin_id ] = all_pain_array [ current_fixed_pin_id ];
                        save_pain_array [ current_fixed_pin_id ].severity = 0; // shows as deleted
                    } else {
                        if (save_pain_array != null) {
                            if (current_fixed_pin_id in save_pain_array) {
                                delete save_pain_array [ current_fixed_pin_id ];
                            }
                        }
                    }
                } else {
                    if (save_pain_array != null) {
                        if (current_fixed_pin_id in save_pain_array) {
                            delete save_pain_array [ current_fixed_pin_id ];
                        }
                    }
                }

                delete all_pain_array [ current_fixed_pin_id ];
                var isDataPresent = false;
                $.each(save_pain_array, function(index, value) {
                    if (value != undefined) {
                        isDataPresent = true;
                        return false; // break the loop
                    }
                });
                if (isDataPresent) {
                } else {
                    disableFinishButton();
                }
                savePainData(ladda_button);
            });

            $(document).on('click', '#clear_all_fisrt', function(e) {
                $('.fixed_pin').popover('hide');
                $(".fixed_pin").fadeOut(10, function() {
                    $('#clear_all_pain_data').show();
					$('#cancel_clear_pain_data').show();
                    $('#clear_all_fisrt').hide();
                });
            });
			
			$(document).on('click', '#cancel_clear_pain_data', function(e) {
                $('.fixed_pin').fadeIn(10, function() {
                    $('#clear_all_pain_data').hide();
					$('#cancel_clear_pain_data').hide();
                    $('#clear_all_fisrt').show();
                });
            });

            $(document).on('click', '#clear_all_pain_data', function(e) {
                var ladda_button = Ladda.create(this);
                // save deleted nodes
                save_pain_array = [];
                if (all_pain_array != null) {
                    $.each(all_pain_array, function(i, val) {
                        if (typeof val != 'undefined') {
                            val.severity = 0; // shows as deleted
                        }
                    });
                } else {

                }
                save_pain_array = all_pain_array;

                savePainData(ladda_button);
                $(".fixed_pin").fadeOut(10, function() {
                    $(this).remove();
                });

            });

            $(document).on('click', '#update_pain_data', function(e) {
                var ladda_button = Ladda.create(this);
                var severity = $('.popover #select_severity').slider('getValue');
                all_pain_array[current_fixed_pin_id].severity = severity;
                save_pain_array[current_fixed_pin_id] = all_pain_array[current_fixed_pin_id];
                savePainData(ladda_button);
            });

            $(document).on('click', '#cancel_pain_data', function(e) {
                $('.dragging_pin').popover('hide');
                $('.dragging_pin').removeClass('dragging_pin').remove();
                $('.drag_pain_icon').draggable('enable');
                $('.popover').remove();
            });

            $(document).on('click', '#add_pain_data', function(e) {
                var ladda_button = Ladda.create(this);
                $('.dragging_pin').removeClass('dragging_pin');
                $('.dropped_pin').removeClass('dropped_pin').removeClass('drag_pain_icon').addClass('fixed_pin');
                $('.drag_pain_icon').draggable('enable');
                new_pain_obj.severity = $('.popover #select_severity').slider('getValue');
                new_pain_obj.pain_type = selected_pain_type;
                new_pain_obj.selected_body_main_part = selected_body_main_part;
                new_pain_obj.pos_x = x_pos_image;
                new_pain_obj.pos_y = y_pos_image;
                all_pain_array[id_counter] = new_pain_obj;
                save_pain_array[id_counter] = new_pain_obj;

                showUpdatePainPopover();
                savePainData(ladda_button);//saving data after eack save button press

                id_counter++;
                selected_pain_type = null;
                x_pos_image = null;
                y_pos_image = null;
                selected_body_main_part = null;
                new_pain_obj = {};

            });

            $(document).on('click', '#cancel_all_pain_data', function(e) {
                hideAllPainData();
                $('.popover').remove();
                appendlatestPainDetails();
                showUpdatePainPopover();
                disableFinishButton();
            });

            function enableFinishButton() {
                $("#save_pain_data").removeAttr('disabled');
            }


            function disableFinishButton() {
                $("#save_pain_data").attr('disabled', 'disabled');
            }

            function removeAllPainData() {
                $('.fixed_pin, .latest_pain_pins').each(function() {
                    $(this).fadeOut(10, function() {
                        $(this).remove();
                    });
                });

                clearVariablesAndData();
            }

            function hideNewShowSaved() {
                $('.fixed_pin').each(function() {
                    $(this).fadeOut(10, function() {
                        $(this).hide();
                    });
                });
                $('.latest_pain_pins').each(function() {
                    $(this).show();
                });
            }

            function savePainData(ladda_button) {
                var sendData = save_pain_array;
                var lastData = 'empty';

                $.each(all_pain_array, function(index, value) {
                    if (value != undefined) {
                        lastData = all_pain_array;
                        return false; // break the loop
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/user/api/addPainTrackerValues',
                    data: {
                        'save_pain_array': sendData,
                        'last_pain_array': lastData
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        ladda_button.start();
                    },
                    success: function(result) {
                        if (result.success == true) {
                            ladda_button.stop();
                            
                            /*
                             * remove all draggaing pin
                             */
                            $('.dragging_pin').removeClass('dragging_pin').remove();
                            $('.popover').remove();
                            
                            /*adding code to reset pins after cancel click.*/
                            latestPainDataDetails = all_pain_array;
                            save_pain_array = new Array();
                            setFinishBar();
                            socket.emit('my_health_update', {
					room:$('#graphUpdatedInRoom').val(),
                                        type: 'pain_tracker'
				});
                        } else {
                            bootbox.alert("Some error occured. Please try again.");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        bootbox.alert("Some error occured. Please try again.");
                        location.reload();
                    }
                });
            }

            img.droppable({
                drop: function(event, ui) {
                    $(ui.helper[0]).css('z-index', '20');
                    $(ui.helper[0]).addClass('dropped_pin');
                    append_pin = true;
                    selected_pain_type = $(ui.draggable).data('pain-type');
                    var dropped_pin_offset = $(".dropped_pin").offset();
                    var offset = $(this).offset();
                    x_pos_image = dropped_pin_offset.left - offset.left;
                    y_pos_image = dropped_pin_offset.top - offset.top;
                    x1 = x_pos_image + ($(".dropped_pin").width() / 2); //establishes the center of the object to use for testing x,y
                    y1 = y_pos_image + ($(".dropped_pin").height() / 2);
                    var result = dropTarget(x1, y1); //returns id of area or 'invalid'
                    //logic to validate answers
                    if (result === 'invalid') {                        
                        append_pin = false;
                    } else { //evaluate for answer correctness
                        selected_body_main_part = result;
                        setSelectedPartNameInPopover();
                        showPopover();
                    }
                }
            });

            $('.drag_pain_icon').draggable({
                revert: 'invalid',
                helper: "clone",
                scroll: true,
                cursor: "move",
                start: function(event, ui) {
                    if ($('.popover').length > 0) {
                        $('.popover').remove();
                        $('.dragging_pin').removeClass('dragging_pin').remove();
                    }
                    append_pin = false;
                    $(ui.helper[0]).addClass('dragging_pin');
                    $(ui.helper[0]).removeClass('dropped_pin');
                },
                drag: function(e, ui) {
                    $(ui.helper[0]).css('z-index', '5');
                },
                stop: function(event, ui) {
                    if (append_pin) {
                        $(this).after($(ui.helper[0]).clone().attr('id', id_counter));
                        append_pin = false;
                    }
                }
            });
            appendlatestPainDetails();
            showUpdatePainPopover();
            $("#draggables_container").show();

<?php } else { ?>
            appendlatestPainDetailsFixed();
<?php }
?>
        setTrackersSlider('painTracker');
        setTrackersSlider('qualityOfLife');
        setTrackersSlider('sleepingHabits');

        function setTrackersSlider(elemnt_id) {
            var slider_val = 1;
            var tracker_value = 1;
            switch (elemnt_id) {
                case 'painTracker':
                    tracker_value = (trackerDataArray.pain_tracker != null) ? parseInt(trackerDataArray.pain_tracker) : 5;
                    break;
                case 'qualityOfLife':
                    tracker_value = (trackerDataArray.quality_of_life != null) ? parseInt(trackerDataArray.quality_of_life) : 5;
                    break;
                case 'sleepingHabits':
                    tracker_value = (trackerDataArray.sleeping_habits != null) ? parseInt(trackerDataArray.sleeping_habits) : 5;
                    break;
            }

            slider_val = Math.abs(parseInt(6 - parseInt(tracker_value)));

            $('#' + elemnt_id).slider({
                max: 5,
                disabled: true,
                min: 1,
                step: 1,
                formater: function(value) {
                    return setTrackersSliderFormater(value);
                }
            });
<?php if ($isOwner == TRUE) { ?>
                $('#' + elemnt_id).on('slideStop', function(e) {
                    SaveTrackerValue($(this).slider('getValue'), $(this).attr('id'));
                });
                $('#' + elemnt_id).slider('enable');
                $(document).on('click', '.tracker_img', function(e) {
                    if (e.target.className === 'tracker_img') {
                        var valObj = {};
                        valObj.f_value = 1;
                        valObj.data = e.target.getBoundingClientRect();
                        valObj.totalBoundary = (valObj.data.left + valObj.data.width) / 2;
                        valObj.ex = e.pageX;
                        valObj.element = $(e.target.getElementsByClassName('trackerInput'));
                        if (valObj.ex > valObj.totalBoundary) {
                            valObj.f_value = 5;
                        }
                        valObj.element.slider('setValue', valObj.f_value);
                        SaveTrackerValue(valObj.f_value, valObj.element.attr('id'));
                    }
                });
<?php } else { ?>
                $('#' + elemnt_id).slider('disable');
                $('.slider-track').css('background', 'transparent');
<?php } ?>
            $('#' + elemnt_id).slider('setValue', slider_val);
        }

        function setTrackersSliderFormater(value) {
            var text = "";
            switch (value) {
                case 1:
                    text = "Very Good";
                    break;
                case 2:
                    text = "Good";
                    break;
                case 3:
                    text = "Normal";
                    break;
                case 4:
                    text = "Bad";
                    break;
                case 5:
                    text = "Very Bad";
                    break;
            }
            return text;
        }
<?php if ($isOwner == TRUE) { ?>
            function SaveTrackerValue(value, id) {
                var tracker_value = Math.abs(parseInt(6 - parseInt(value)));
                var success_message_span = $('#' + id + '_success_msg');
                var updated_date_span = $('#' + id + '_updated_date');
                var type = 7;
                switch (id) {
                    case 'painTracker':
                        type = 7;
                        break;
                    case 'qualityOfLife':
                        type = 8;
                        break;
                    case 'sleepingHabits':
                        type = 9;
                        break;

                }
                $.ajax({
                    type: 'POST',
                    url: '/user/api/addHealthRecord',
                    data: {
                        'type': type, //weight
                        'value1': tracker_value,
                        'value2': null
//                    'date': null
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        success_message_span.html('Saving');
                        success_message_span.show();
                    }, success: function(result) {
                        if (result.success == true) {
                            socket.emit('my_health_update', {
					room:$('#graphUpdatedInRoom').val(),
                                        type: 'tracker'
				});
                            success_message_span.html('Saved successfully!');
                            updated_date_span.html('Last updated on ' + result.latest_updated_time);
                            setTimeout(function() {
                                success_message_span.hide();
                            }, 3000);
                        } else {
                            success_message_span.html('Some error occured.');
                            showServerErrorAlert('Alert', result.error_message, true);
                        }
                    }
                });
            }
<?php } ?>
    });

//    /*
// *  Used in body pain tracker.
// *  Simple code. Can be exapanded to loop through all area tags with simple changes.
// *  To Find the new co-ordinates of area tags after resizing of maping image.
// *  id : id of the area tag.
// *  
// */
//function getXYPoints(id) {
//    var img_exp_rate = 1.22; //means the image expandes w.r.t. old image is 122 percentage. 
//    var x_change = -52;      // If its a group of images, order/re arrangements may happen.
//    var y_change = -5;       // then we can use the change in x, y values here to fix the problem
//    areas = document.getElementById(id),
//            coords = [],
//            coords = areas.coords.split(',');
//
//    for (m = 0; m < coords.length; m++) {
//        if (m % 2 == 0) { // x co-ordinate.
//            coords[m] = Math.round((coords[m] * img_exp_rate) + x_change);
//        } else {          // y co-ordinate.
//            coords[m] = Math.round((coords[m] * img_exp_rate) + y_change);
//        }
//    }
//    areas.coords = coords.join(',');
//    return areas.coords; // returning the new x, y co-ordinate in coma seperated form.
//}

</script>
