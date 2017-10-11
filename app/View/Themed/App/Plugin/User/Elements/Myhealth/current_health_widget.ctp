<div class="health_details">
    <h2 class='clearfix'>
        <span class="pull-left">Current Health</span>
        <!--<a href="/user/settings" title="Change unit settings" class="pull-left health_setting" style="margin-left: 20px;"></a>-->

        <a href="<?php echo Common::getUserProfileLink($user_details['username'], TRUE); ?>/healthtracker" class="view_more" style="float:right;margin-top:6px;">View graphs</a>
        <?php if ($isOwner == true) { ?>
        <a href="javascript:void(0)" title="Change unit settings" class="pull-left health_setting" style="margin-left: 20px;"data-toggle="modal"                    data-target="#unitSettings" data-backdrop="static"
                    data-keyboard="false" ></a>
<!--            <button title="Change unit settings" type="button" data-toggle="modal"
                    data-target="#unitSettings" data-backdrop="static"
                    data-keyboard="false" class="btn health_setting pull-left">
                <img src="/theme/App/img/health_setting.png" alt="">
            </button>-->
        <?php } ?>
    </h2>
    <div class="row">
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3" id="current_weight">
            <p>Weight <img src="/theme/App/img/calendar_tooltip_icon_small.png" alt=""  id="weight_updated_time" title="<?php echo "Last updated on: " . $latestHealthUpdateTime['weight']; ?>"></p>
            <span id="weight_display_value"><?php
                if (isset($userLatestWeight)) {
                    echo $userLatestWeight;
                }
                ?></span>
<!--            <span id="weight_display_unit">lbs</span>-->
            <?php if ($isOwner == true) { ?>
                <div class="add_status">
                    <button type="button" data-toggle="modal" data-target="#readWeight"
                            data-backdrop="static" data-keyboard="false" class="btn">
                        <img src="/theme/App/img/plus_icon.png" alt="">
                    </button>
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3" id="current_height">
            <p>Height <img src="/theme/App/img/calendar_tooltip_icon_small.png"  id="height_updated_time" alt="" title="<?php echo "Last updated on: " .$latestHealthUpdateTime['height']; ?>"></p>
            <span id="height_display_value"><?php
                if (isset($userLatestHeight)) {
                    echo $userLatestHeight;
                }
                ?></span>
            <?php if ($isOwner == true) { ?>
                <div class="add_status">
                    <button type="button" data-toggle="modal" data-target="#readHeight"
                            data-backdrop="static" data-keyboard="false" class="btn">
                        <img src="/theme/App/img/plus_icon.png" alt="">
                    </button>
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3" id="current_bp">
            <p>BP <img src="/theme/App/img/calendar_tooltip_icon_small.png" alt="" id="bp_updated_time" title="<?php echo "Last updated on: " . $latestHealthUpdateTime['bp']; ?>"></p>
            <span id="bp_display_value"><?php
                if (isset($userLatestBp)) {
                    echo $userLatestBp;
                }
                ?></span>
            <?php if ($isOwner == true) { ?>
                <div class="add_status">
                    <button type="button" data-toggle="modal" data-target="#readBp"
                            data-backdrop="static" data-keyboard="false" class="btn">
                        <img src="/theme/App/img/plus_icon.png" alt="">
                    </button>
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3" id="current_temperature">
            <p>Temperature <img src="/theme/App/img/calendar_tooltip_icon_small.png" id="temp_updated_time" alt="" title="<?php echo "Last updated on: " .$latestHealthUpdateTime['temp']; ?>"></p>
            <span id="temperature_display_value"><?php
                if (isset($userLatestTemperature)) {
                    echo $userLatestTemperature;
                }
                ?></span>
            <!--<span id="temperature_display_unit"><?php // echo $temp_unit;          ?></span>-->		
            <?php if ($isOwner == true) { ?>
                <div class="add_status">
                    <button type="button" data-toggle="modal"
                            data-target="#readTemperature" data-backdrop="static"
                            data-keyboard="false" class="btn">
                        <img src="/theme/App/img/plus_icon.png" alt="">
                    </button>
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-3 col-xs-3 col-sm-3 col-md-3" id="current_bmivalue">
            <p>BMI</p>
            <span id="bmi_value">
                <?php
                if (isset($bmi) && $bmi != NULL) {
                    echo $bmi;
//                    $class = 'show';
                } else {
                    echo ' - ';
//                    $class = 'hidden';
                }
                ?>
            </span>
            <!--<span id="bmi_unit" class="<?php // echo $class;   ?>"> kg/m<sup>2</sup> </span>-->
            <?php // if ($isOwner == true) {   ?>
            <!--                <div class="add_status">
                                <button type="button" class="btn">
                                    <img src="/theme/App/img/plus_icon.png" alt="">
                                </button>
                            </div>-->
            <?php // }   ?>
        </div>
    </div>
</div>



