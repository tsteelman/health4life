<?php if (!isset($weather)) { ?>
                <div style="padding:120px 30px 80px 30px;color: #FFF;text-align: center;font-weight: bold;">Weather info is not available for this location now</div> 
         <?php } else { ?>
                <div class="current_temp"><?php echo $weather['currentTemperature'] ?>&deg;<span style="display: inline;font-size: 35px;position: relative;top: -26px;"><?php if($tempUnit == 1) { echo __('C'); } else { echo __('F'); } ?></span>
                    <img title="<?php echo $weather['weatherDesc'] ?>" src="/theme/App/img/weather_icons/<?php echo $weather['weatherIcon'] ?>_lg.png"></div>
                <div class="current_city"><?php echo $weather['city']; ?>, <?php echo $weather['state']; ?>, <?php echo $weather['country']; ?></div>
                <?php
                        $currentDate = CakeTime::nice(date('Y-m-d H:i:s'), $timezone, '%Y-%m-%d'); 
                        $apiCurrentDate = $weather['temperature'][0]['date'];
                ?>
                <div class="current_date"><?php echo date("l", strtotime($currentDate)); ?>,<?php echo date("dS", strtotime($currentDate)); ?> <?php echo date("F", strtotime($currentDate)); ?> <?php echo date("Y", strtotime($currentDate)); ?></div>
                        <ul class="dates">
                            <li><?php echo date("D", strtotime($currentDate)); ?></li>
                            <li><?php echo date("D", strtotime('+1 day', strtotime($currentDate))); ?></li>
                            <li><?php echo date("D", strtotime('+2 day', strtotime($currentDate))); ?></li>
                        </ul>
                    <?php if(strtotime($currentDate) == strtotime($apiCurrentDate)) { ?>
                        <ul class="temperature">
                            <li><img title="<?php echo $weather['temperature'][0]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][0]['weatherIcon']; ?>_small.png"></li>
                            <li><img title="<?php echo $weather['temperature'][1]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][1]['weatherIcon']; ?>_small.png"></li>
                            <li><img title="<?php echo $weather['temperature'][2]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][2]['weatherIcon']; ?>_small.png"></li>
                        </ul>
                        <ul class="temperature_values">
                            <li><span class="pull-left"><?php echo $weather['temperature'][0]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][0]['max'] ?>&deg;</span></li>
                            <li><span class="pull-left"><?php echo $weather['temperature'][1]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][1]['max'] ?>&deg;</span></li>
                            <li><span class="pull-left"><?php echo $weather['temperature'][2]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][2]['max'] ?>&deg;</span></li>
                        </ul>
                    <?php } else if(strtotime($currentDate) > strtotime($apiCurrentDate)) { ?> 
                           <!--After a day change (after 12.00 AM), if weather data we retrieving from table is yesterday's data; then we will pick next indexed data([1],[2] & [3]) from API -->
                        
                        <ul class="temperature">
                            <li><img title="<?php echo $weather['temperature'][1]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][1]['weatherIcon']; ?>_small.png"></li>
                            <li><img title="<?php echo $weather['temperature'][2]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][2]['weatherIcon']; ?>_small.png"></li>
                            <li><img title="<?php echo $weather['temperature'][3]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][3]['weatherIcon']; ?>_small.png"></li>
                        </ul>
                        <ul class="temperature_values">
                            <li><span class="pull-left"><?php echo $weather['temperature'][1]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][1]['max'] ?>&deg;</span></li>
                            <li><span class="pull-left"><?php echo $weather['temperature'][2]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][2]['max'] ?>&deg;</span></li>
                            <li><span class="pull-left"><?php echo $weather['temperature'][3]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][3]['max'] ?>&deg;</span></li>
                        </ul>
                    <?php } else if(strtotime($currentDate) < strtotime($apiCurrentDate)) { ?>
                           
                        <ul class="temperature">
                            <li><img title="<?php echo $weather['yesterdayData']['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['yesterdayData']['weatherIcon']; ?>_small.png"></li>
                            <li><img title="<?php echo $weather['temperature'][0]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][0]['weatherIcon']; ?>_small.png"></li>
                            <li><img title="<?php echo $weather['temperature'][1]['weatherDesc']; ?>" src="/theme/App/img/weather_icons/<?php echo $weather['temperature'][1]['weatherIcon']; ?>_small.png"></li>
                        </ul>
                        <ul class="temperature_values">
                            <li><span class="pull-left"><?php echo $weather['yesterdayData']['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['yesterdayData']['max'] ?>&deg;</span></li>
                            <li><span class="pull-left"><?php echo $weather['temperature'][0]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][0]['max'] ?>&deg;</span></li>
                            <li><span class="pull-left"><?php echo $weather['temperature'][1]['min'] ?>&deg;</span><span class="pull-right"><?php echo $weather['temperature'][1]['max'] ?>&deg;</span></li>
                        </ul>
                           
                    <?php } ?>       
        <?php } ?>