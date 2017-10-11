<?php
    echo $this->AssetCompress->css('dashboard');
	echo $this->AssetCompress->script('dashboard.js');
?>
<div class="container home_page">
    <div class="home_top_section">
        <div class="row">
            <div class="col-lg-9 col-md-9">
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="dashboard_profile patient_profile">                            
                            <div class="profile_details" style="cursor: pointer">
                                <div class="media">
                                    <a href="/profile/patient" class="pull-left">
                                        <img src="http://qa.patients4life.qburst.com/uploads/user_profile/072b030ba126b2f4b2374f342be9ed44_medium.jpg?1401808683" class="border_patient  user_medium_thumb user_medium_thumb profile_brdr_5">
                                        <h5>Patient</h5>
                                    </a>
                                    <div class="media-body">
                                        <h3 class="owner">
                                            <a href="/profile/patient" class="">patient</a>
                                        </h3>                                       
                                        <p><span class="pull-left">Feeling</span><span class="pull-left feeling_condition feeling_very_bad my_health_add"></span>
                                            <span id="feeling_date_status" class="pull-left"></span>
                                        </p>
                                        <span class="dashboard_profile_disease">Crohn's Disease</span>
                                        <span class="dashboard_profile_disease">Remicade IV</span>                
                                        <p title="">Patients4Life Ambassador</p>
                                    </div>
                                </div>
                            </div>
                            <div class="home_profile_location clearfix">
                                 <h4 class="pull-left" >
                                    <span style="cursor: pointer">Jidd Hafs,	Al Asimah,	Bahrain</span>                                    
                                </h4>
                                <h3 class="weather">
                                    <span class="pull-left"><img title="Sunny" src="/theme/App/img/tmp/sample_weather_icon.png"></span> 62&deg;F
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7">
                        <div class="calender_events">
                            <div class="dashboard_calender pull-left">
                                <div id="zabuto_calendar_s8j"><div class="zabuto_calendar" id="zabuto_calendar_s8j"><table class="table"><tbody><tr class="calendar-month-header"><th><div class="calendar-month-navigation" id="zabuto_calendar_s8j_nav-prev"><span><img src="/theme/App/img/calendar_prev.png"></span></div></th><th colspan="5"><a href="/calendar" class="calendar-header">August 2014</a></th><th><div class="calendar-month-navigation" id="zabuto_calendar_s8j_nav-next"><span><img src="/theme/App/img/calendar_next.png"></span></div></th></tr><tr class="calendar-dow-header"><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr><tr class="calendar-dow"><td></td><td></td><td></td><td></td><td></td><td id="zabuto_calendar_s8j_2014-08-01" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-01_day" class="day">01</div></td><td id="zabuto_calendar_s8j_2014-08-02" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-02_day" class="day">02</div></td></tr><tr class="calendar-dow"><td id="zabuto_calendar_s8j_2014-08-03" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-03_day" class="day">03</div></td><td id="zabuto_calendar_s8j_2014-08-04" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-04_day" class="day">04</div></td><td id="zabuto_calendar_s8j_2014-08-05" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-05_day" class="day">05</div></td><td id="zabuto_calendar_s8j_2014-08-06" class="dow-clickable event"><div id="zabuto_calendar_s8j_2014-08-06_day" class="day">06</div></td><td id="zabuto_calendar_s8j_2014-08-07" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-07_day" class="day">07</div></td><td id="zabuto_calendar_s8j_2014-08-08" class="dow-clickable event"><div id="zabuto_calendar_s8j_2014-08-08_day" class="day">08</div></td><td id="zabuto_calendar_s8j_2014-08-09" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-09_day" class="day">09</div></td></tr><tr class="calendar-dow"><td id="zabuto_calendar_s8j_2014-08-10" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-10_day" class="day">10</div></td><td id="zabuto_calendar_s8j_2014-08-11" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-11_day" class="day">11</div></td><td id="zabuto_calendar_s8j_2014-08-12" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-12_day" class="day">12</div></td><td id="zabuto_calendar_s8j_2014-08-13" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-13_day" class="day">13</div></td><td id="zabuto_calendar_s8j_2014-08-14" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-14_day" class="day">14</div></td><td id="zabuto_calendar_s8j_2014-08-15" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-15_day" class="day">15</div></td><td id="zabuto_calendar_s8j_2014-08-16" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-16_day" class="day">16</div></td></tr><tr class="calendar-dow"><td id="zabuto_calendar_s8j_2014-08-17" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-17_day" class="day">17</div></td><td id="zabuto_calendar_s8j_2014-08-18" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-18_day" class="day">18</div></td><td id="zabuto_calendar_s8j_2014-08-19" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-19_day" class="day"><span class="badge badge-today">19</span></div></td><td id="zabuto_calendar_s8j_2014-08-20" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-20_day" class="day">20</div></td><td id="zabuto_calendar_s8j_2014-08-21" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-21_day" class="day">21</div></td><td id="zabuto_calendar_s8j_2014-08-22" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-22_day" class="day">22</div></td><td id="zabuto_calendar_s8j_2014-08-23" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-23_day" class="day">23</div></td></tr><tr class="calendar-dow"><td id="zabuto_calendar_s8j_2014-08-24" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-24_day" class="day">24</div></td><td id="zabuto_calendar_s8j_2014-08-25" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-25_day" class="day">25</div></td><td id="zabuto_calendar_s8j_2014-08-26" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-26_day" class="day">26</div></td><td id="zabuto_calendar_s8j_2014-08-27" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-27_day" class="day">27</div></td><td id="zabuto_calendar_s8j_2014-08-28" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-28_day" class="day">28</div></td><td id="zabuto_calendar_s8j_2014-08-29" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-29_day" class="day">29</div></td><td id="zabuto_calendar_s8j_2014-08-30" class="dow-clickable event"><div id="zabuto_calendar_s8j_2014-08-30_day" class="day">30</div></td></tr><tr class="calendar-dow"><td id="zabuto_calendar_s8j_2014-08-31" class="dow-clickable"><div id="zabuto_calendar_s8j_2014-08-31_day" class="day">31</div></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table><div class="legend" id="zabuto_calendar_s8j_legend"></div></div></div>
                                <input type="hidden" id="events" value="[{&quot;date&quot;:&quot;2014-08-06&quot;,&quot;name&quot;:&quot;test 001&quot;,&quot;time&quot;:&quot;02:00 AM&quot;,&quot;link&quot;:&quot;/event/details/index/831&quot;},{&quot;date&quot;:&quot;2014-08-08&quot;,&quot;name&quot;:&quot;asdaasdasd&quot;,&quot;time&quot;:&quot;01:00 AM&quot;,&quot;link&quot;:&quot;/event/details/index/827&quot;},{&quot;date&quot;:&quot;2014-08-30&quot;,&quot;name&quot;:&quot;Community Event 1&quot;,&quot;time&quot;:&quot;02:00 AM&quot;,&quot;link&quot;:&quot;/event/details/index/835&quot;}]">
                            </div>
                            <div class="dashboard_events pull-right">
                                <div class="dashboard_header">My Reminder</div>
                                <div id="calendar-details" class="tile_content"><div class="event_time"><p>No events on 08-19-2014</p></div><div><a class="dashboard_more pull-right" href="/calendar/2014-08-19">Go to Aug 19 2014</a></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="my_team">
                            <a href="/community" class="dashboard_header_link">
                                <div class="dashboard_header">My Team</div>
                            </a>
                            <div class="slimScrollDiv">
                                <div class="tile_content my_communities_tile">
                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/160">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/b73ce398c39f506af761d2277d853a92.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/160">Coeds With Crohn's</a></h5>
                                            <p>Members (7)                                            </p>
                                        </div>
                                    </div>


                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/217">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/63dc7ed1010d3c3b8269faf0ba7491d4.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/217">May day community-Site wide_edit</a></h5>
                                            <p>Members (10)                                            </p>
                                        </div>
                                    </div>


                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/179">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/8f53295a73878494e9bc8dd6c3c7104f.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/179">Patients4Life Development Team</a></h5>
                                            <p>Members (23)                                            </p>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" id="dashboard_communities_more" class="dashboard_more pull-right">more</a>


                                </div><div class="slimScrollBar ui-draggable" style="width: 8px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; z-index: 99; right: 6px; height: 214px; background: rgba(225, 225, 225, 0.2);"></div><div class="slimScrollRail" style="width: 8px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; opacity: 0.2; z-index: 90; right: 6px; background: rgb(51, 51, 51);"></div></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="my_events">
                            <a href="/event/view" class="dashboard_header_link"> 
                                <div class="dashboard_header">My Events</div>
                            </a>
                            <div class="slimScrollDiv">
                                <div class="tile_content my_communities_tile">
                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/160">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/b73ce398c39f506af761d2277d853a92.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/160">Coeds With Crohn's</a></h5>
                                            <p>Members (7)                                            </p>
                                        </div>
                                    </div>


                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/217">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/63dc7ed1010d3c3b8269faf0ba7491d4.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/217">May day community-Site wide_edit</a></h5>
                                            <p>Members (10)                                            </p>
                                        </div>
                                    </div>


                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/179">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/8f53295a73878494e9bc8dd6c3c7104f.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/179">Patients4Life Development Team</a></h5>
                                            <p>Members (23)                                            </p>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" id="dashboard_communities_more" class="dashboard_more pull-right">more</a>


                                </div><div class="slimScrollBar ui-draggable" style="width: 8px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; z-index: 99; right: 6px; height: 214px; background: rgba(225, 225, 225, 0.2);"></div><div class="slimScrollRail" style="width: 8px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; opacity: 0.2; z-index: 90; right: 6px; background: rgb(51, 51, 51);"></div></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="my_community">
                            <a href="/community" class="dashboard_header_link">
                                <div class="dashboard_header">My Communities</div>
                            </a>
                            <div class="slimScrollDiv">
                                <div class="tile_content my_communities_tile">
                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/160">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/b73ce398c39f506af761d2277d853a92.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/160">Coeds With Crohn's</a></h5>
                                            <p>Members (7)                                            </p>
                                        </div>
                                    </div>


                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/217">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/63dc7ed1010d3c3b8269faf0ba7491d4.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/217">May day community-Site wide_edit</a></h5>
                                            <p>Members (10)                                            </p>
                                        </div>
                                    </div>


                                    <div class="media">
                                        <a class="pull-left " href="/community/details/index/179">
                                            <img src="http://qa.patients4life.qburst.com/uploads/community_image/8f53295a73878494e9bc8dd6c3c7104f.jpg" class="media-object" height="40" alt="">                                    </a>
                                        <div class="media-body">
                                            <h5><a href="/community/details/index/179">Patients4Life Development Team</a></h5>
                                            <p>Members (23)                                            </p>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" id="dashboard_communities_more" class="dashboard_more pull-right">more</a>


                                </div><div class="slimScrollBar ui-draggable" style="width: 8px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; z-index: 99; right: 6px; height: 214px; background: rgba(225, 225, 225, 0.2);"></div><div class="slimScrollRail" style="width: 8px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; opacity: 0.2; z-index: 90; right: 6px; background: rgb(51, 51, 51);"></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="hashtag_list">
                    <div class="dashboard_header">
                        <input type="text" class="form-control" placeholder="Search Hashtags">
                    </div>
                    <div class="hashtag_container">
                        <div class="hashtag_div rating_1"> 
                            <a href="/hashtag?tag=brrbrr">#brrbrr</a>
                        </div> 
                        <div class="hashtag_div rating_1"> 
                            <a href="/hashtag?tag=brrbrr">#brrbrr</a>
                        </div> 
                        <div class="hashtag_div rating_2"> 
                            <a href="/hashtag?tag=brrbrr">#brrbrr</a>
                        </div> 
                        <div class="hashtag_div rating_2"> 
                            <a href="/hashtag?tag=brrbrr">#brrbrr</a>
                        </div> 
                        <div class="hashtag_div rating_3"> 
                            <a href="/hashtag?tag=brrbrr">#brrbrr</a>
                        </div>
                        <div class="hashtag_div"> 
                            <div class="media">
                                <a class="pull-left posted_user_thumb">
                                    <img src="theme/App/img/user_default_1_x_small.png?" class="border_patient  user_x_small_thumb media-object normal_thumb">
                                    <span class="pull-right feeling_condition feeling_neutral" title="neutral"></span>
                                </a>
                                <div class="media-body">
                                    <h5>Name</h5>
                                    <p>Remicade is <a>#effective</a></p>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="home_middle_section">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="home_video">
                    <img src="/theme/App/img/tmp/home_video_bg.png">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="home_myphoto">
					<?php echo $this->element('Dashboard/my_photos_tile') ?>
                    <!--<img src="/theme/App/img/tmp/home_myphoto_bg.png">-->
                </div>
            </div>
        </div>
    </div>
    <div class="home_bottom_section">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class=" my_pmr">
                    <a href="" class="dashboard_header_link" onclick="window.open('http://pmr.qburst.com/', '_blank');
                            return false;">
                        <div class="dashboard_header">My Medical Records</div>
                    </a>
                    <div class="apple_img">
                        <div class="pmr_login"><p>Login To My PMR</p><button class="btn" onclick="window.open('http://pmr.qburst.com/', '_blank');">Log In</button></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <a href="/profile/mynutrition" class="dashboard_header_link">
                    <div class="my_nutrition">
                       <div class="dashboard_header clearfix">
                        <div class="my_health_dashboard_header">My Health </div>
                         <div class="health_dashboard_enter align-right">Enter</div>   
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="my_activity">
                    <a href="/profile/mycondition" class="dashboard_header_link">
                        <div class="dashboard_header">My Conditions</div>
                    </a>
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 214px;"><div class="tile_content my_condition_tile" style="overflow: hidden; width: auto; height: 214px;">
                            <div class="media">
                                <div class="media-body">
                                    <h5><a href="/condition/index/538">Abdominal Incisions and Sutures in Gynecologic Oncological Surgery</a></h5>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <h5><a href="/condition/index/2364">Dupuytren Contracture</a></h5>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-body">
                                    <h5><a href="/condition/index/1165">FDA Pregnancy Categories for Antiretroviral Therapy</a></h5>
                                </div>
                            </div>
                        </div><div class="slimScrollBar ui-draggable" style="width: 8px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; z-index: 99; right: 6px; height: 214px; background: rgba(225, 225, 225, 0.2);"></div><div class="slimScrollRail" style="width: 8px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; opacity: 0.2; z-index: 90; right: 6px; background: rgb(51, 51, 51);"></div></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <a class="dashboard_header_link" href="/profile/myhealth">                                       
                    <div id="my_health_chart" class="my_health">
                        <div class="dashboard_header clearfix">
                        <div class="my_health_dashboard_header">My Health </div>
                         <div class="health_dashboard_enter align-right">Enter</div>   
                        </div>
                        <div class="my_health_add">
                            <span class="pull-left">Health Status</span>
                        <button class="btn pull-left">Update</button>
                    </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>