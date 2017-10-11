<?php
    echo $this->AssetCompress->css('dashboard');
?>
<div class="container home_page">
    <div class="home_top_section">
        <div class="row">
            <div class="col-lg-9 col-md-9">
                <div class="row">
                    <!-- Dashboard Profile tile -->
                    <?php echo $this->element('Dashboard/profile_tile', array('isDashboard' => true, 'showFeeling' => TRUE)); ?>
                    
                     <!-- Dashboard Calendar tile -->
                    <div class="col-lg-7 col-md-7">
                        <?php echo $this->element('Dashboard/my_calendar'); ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <?php echo $this->element('Dashboard/my_team'); ?>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <!--  My Events -->
                        <?php echo $this->element('Dashboard/my_events_tile') ?>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <!--  My Community -->
                        <?php echo $this->element('Dashboard/my_community_tile') ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <!-- Trending tile -->
                <?php echo $this->element('Dashboard/trending_hashtag_tile') ?>
                
            </div>
        </div>
    </div>
    <div class="home_middle_section">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?php echo $this->element('Dashboard/video_advertisement') ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="home_myphoto">
                    <?php echo $this->element('Dashboard/my_photos_tile') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="home_bottom_section">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class=" my_pmr">
                    <a href="" class="dashboard_header_link" onclick="window.open('<?php echo Configure::read('App.pmrUrl');  ?>', '_blank');
                            return false;">
                        <div class="dashboard_header">Medical Records</div>
                    </a>
                    <div class="apple_img">
                        <div class="pmr_login"><p>Login To My PMR</p><button class="btn" onclick="window.open('<?php echo Configure::read('App.pmrUrl');  ?>', '_blank');">Log In</button></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <a href="/profile/mynutrition" class="dashboard_header_link">
                    <div class="my_nutrition">
                       <div class="dashboard_header clearfix">
                        <div class="my_health_dashboard_header">Nutrition</div>
                         <div class="health_dashboard_enter align-right">Enter</div>   
                        </div>
                    </div>
                </a>
            </div>            
            <div class="col-lg-3 col-md-3">
                <!--  My Conditions -->
                <?php echo $this->element('Dashboard/my_conditions_tile') ?>
            </div>
            <div class="col-lg-3 col-md-3">
                 <!--  My Health -->
                <a class="dashboard_header_link" href="/profile/myhealth">                                       
                    <div id="my_health_chart" class="my_health">
                        <div class="dashboard_header clearfix">
                        <div class="my_health_dashboard_header">My Health </div>
                         <div class="health_dashboard_enter align-right">Enter</div>   
                        </div>
                        <div class="my_health_add">
                            <span class="pull-left">Health Status</span>
                            <button class="btn pull-left" onclick="return false;">Update</button>
                    </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    var zabuto_today = <?php echo '"' . CakeTime::nice(date('Y-m-d H:i:s'), $timezone, '%b %d %Y') . '"'; ?>;
    var event_class_today = <?php echo '"' . CakeTime::format(date('Y-m-d H:i:s'), '%Y-%m-%d', NULL, $timezone) . '"'; ?>;
</script>
<?php
$this->AssetCompress->script('dashboard.js', array('block' => 'scriptBottom'));
?>