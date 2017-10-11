<?php

$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($this->request->referer(1) === '/notification') {
	$this->Html->addCrumb('Notifications', '/notification');
}
if (isset($section) && ($section !== 'forum')) {
	$this->Html->addCrumb(h($disease['Disease']['name']),$disease_detail_url);
} else {
	$this->Html->addCrumb(h($disease['Disease']['name']));
}
?>
<div class="container">
    <div class="disease">
        <div class="row">
            <!-- Details Box -->
            <div class="col-lg-12">
              <div class="event_list">  
                  
                  
                  
                  <div class="event_wraper">
                        <div class="profile_container">
                            <!--                            <div class="row">
                                                                                                                            </div>-->
                            <div class="row">
                                <div class="col-lg-4 profile_info">                                   
                                    <div class="response_tile">
                                    <div class="image_container">
										<img class="img-responsive" src="<?php echo $disease_image; ?>">
                                    </div>                                          

                                    
                                    <div class="event_name">
                                    <?php
                                    /*
                                     * Truncating the disease name
                                     */
                                    $modDiseaseName = Common::truncate($disease['Disease']['name'], 33);
                                    
                                    ?>
                                        <h3 title="<?php echo $modDiseaseName['title']; ?>"><?php 
                                        echo h($modDiseaseName['name']); ?></h3> 
												
                                        <?php
                                                       if (trim($disease['profile_video']) != "") {
                                                               $video_details = Common::getYoutubeDetails($disease['profile_video']);
                                                               $profile_video = $disease['profile_video'];
                                                               $profile_video_code = $video_details['embedcode'];
                                        ?>
                                                               <button class="btn disease_video_btn"
                                                                                               data-video="<?php  echo $profile_video; ?>">
                                                                               Watch Related video
                                                               </button>
                                        <?php }  else { 
                                                       $profile_video = 'https://www.youtube.com/watch?v=9QscURRuF0g';
                                                               $video_details = Common::getYoutubeDetails($profile_video);
                                                               $profile_video_code = $video_details['embedcode'];
                                        ?>
                                                               <button class="btn disease_video_btn"
                                                                                data-video="<?php  echo $profile_video; ?>">
                                                                       Watch Related video
                                                               </button>
                                        <?php } ?>
                                    <div class="event_type_notifier">
                                        <div class="follow_disease"> 
                                           
						<button class="btn disease_follow_btn btn_normal" <?php  if ($followStatus > 0) { ?> style="display:none;" <?php } ?> data-disease-id="<?php  echo $disease['Disease']['id']; ?>">
							Follow</button>
						<button class="btn disease_unfollow_btn btn_normal" <?php  if ($followStatus == 0) { ?> style="display:none;" <?php  } ?> data-disease-id="<?php echo $disease['Disease']['id']; ?>">
							Unfollow</button>
                                          
                                        </div>
                                    </div>
									
                                    </div></div>
                                </div>
                                <div class="col-lg-8 profile_cover_photo">
									<?php echo $this->element('cover_slideshow'); ?>
                                        <div id="cover_image_container" >
                                                <img src="<?php echo $defaultPhoto; ?>" />
                                        </div>
                                    <div>
										<div class="profile_video pull-right" ></div>
                                        <div class="minimize_icon hide" id="profile_video_minimize_icon"><img src="/theme/App/img/minimize_icon.png" /></div>
                                        <div id="profile_video_container_wrapper" >                    
                                            <div id="profile_video_container">&nbsp;</div>
                                        </div>
                                         <script type='text/javascript'>
                                                <?php
                                                if (isset($disease['advertisement_video']) && !empty($disease['advertisement_video'])) {
                                                    ?>
                                                    var file = '<?php echo $disease['advertisement_video']; ?>';
                                                    <?php
                                                } else {
                                                    ?>
                                                    var file = 'https://www.youtube.com/watch?v=9QscURRuF0g';
                                                    <?php
                                                }
                                                ?>
                                                jwplayer('profile_video_container').setup({
                                                    file: file,
                                                    width: 500,
                                                    image: '/theme/App/img/tmp/dasboard_my_videos.png',
                                                    displaytitle: false,
                                                    stretching: 'exactfit'

                                                });
                                        </script>
                                    </div>
                                </div>
<!--
                                <div class="col-lg-12 profile_cover_photo" id="cover">
                                        <?php echo $this->element('cover_slideshow'); ?>
                                        <div id="cover_image_container" >
                                                <img src="<?php echo $defaultPhoto; ?>" />
                                        </div>
                                    
                                        <div class="disease_name_container pull-left" >
                                            <h3><?php echo h($disease['Disease']['name']); ?></h3> 
                                            <div class="follow_disease pull-left"> 
                                           
						<button class="btn disease_follow_btn btn_normal" <?php if ($followStatus > 0) { ?> style="display:none;" <?php } ?> data-disease-id="<?php echo $disease['Disease']['id']; ?>">
							Follow</button>
						<button class="btn disease_unfollow_btn btn_normal" <?php if ($followStatus == 0) { ?> style="display:none;" <?php } ?> data-disease-id="<?php echo $disease['Disease']['id']; ?>">
							Unfollow</button>
                                          
                                        </div>
                                        </div>
                                        
                                        <div class="profile_video pull-right" ></div>
                                        <div class="minimize_icon hide" id="profile_video_minimize_icon"><img src="/theme/App/img/minimize_icon.png" /></div>
                                        <div id="profile_video_container_wrapper" >                    
                                            <div id="profile_video_container">&nbsp;</div>
                                        </div>
                                         <script type='text/javascript'>
                                                <?php
                                                if (isset($disease['advertisement_video']) && !empty($disease['advertisement_video'])) {
                                                    ?>
                                                    var file = '<?php echo $disease['advertisement_video']; ?>';
                                                    <?php
                                                } else {
                                                    ?>
                                                    var file = 'https://www.youtube.com/watch?v=9QscURRuF0g';
                                                    <?php
                                                }
                                                ?>
                                                jwplayer('profile_video_container').setup({
                                                    file: file,
                                                    width: '500px',
                                                    image: '/theme/App/img/tmp/dasboard_my_videos.png',
                                                    displaytitle: false,
                                                    stretching: 'exactfit'

                                                });
                                        </script>
                                    
                                </div>-->
                            </div>
                        </div>
                        <div class="group_options">
                            <nav class="navbar navbar-default" role="navigation">
                                 <div class="navbar-header">
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                                    <div class="group_catagories pull-left">
                                        <ul class="subtabs_list">
                                                <?php
                                                    if (!empty($section_list)) {
                                                        foreach ($section_list as $sec_url => $sec_name) {
                                                                $sel_class = $section == $sec_url ? "current" : "";
                                                                echo '<li  class="' . $sel_class . '"><a href="' . $disease_detail_url . '/' . $sec_url . '">' . $sec_name . '</a></li>';
                                                                $sel_class = "";
                                                        }
                                                    }
                                                ?>
                                        </ul>                            
                                    </div>
                                </div>
                            </nav>
                        </div>      <!--  /group_options -->

                </div>
                  
<!--                  
                  
                  
                  
                  
                  
                  
                  
				<div class="event_wraper">
					<div class="profile_container">                            
						<div class="row">
							<div class="col-lg-4 profile_info"> 
								<?php
								if (isset($disease_image) && $disease_image != '') {
								?>
								<div>
									<img title="Watch video" class="img-responsive"  
										 src="<?php echo $disease_image; ?>" />
								</div>
								<?php
							} ?>
								<h3><?php echo h($disease['Disease']['name']); ?></h3>                                   
								  
								<div>
									<?php
                                   if (trim($disease['profile_video']) != "") {
								$video_details = Common::getYoutubeDetails($disease['profile_video']);
								$profile_video = $disease['profile_video'];
								$profile_video_code = $video_details['embedcode'];
								?>
								<button class="btn disease_video_btn" 
										data-video="<?php echo $profile_video; ?>">
									Watch Related video
								</button>
								<?php
							}
							?> 
								</div>								

							</div>

							<div class="col-lg-8 profile_cover_photo" id="cover">
							
								<?php echo $this->element('cover_slideshow'); ?>
								<div id="cover_image_container" >
									<img src="<?php echo $defaultPhoto; ?>" />
								</div>
								   
								<div class="profile_video pull-right" ></div>
								<div class="minimize_icon hide" id="profile_video_minimize_icon"><img src="/theme/App/img/minimize_icon.png" /></div>
								<div id="profile_video_container_wrapper" class="hide">                    
                                                                    <div id="profile_video_container">&nbsp;</div>
                                                                </div>
                                                                 <script type='text/javascript'>
                                                                        <?php
                                                                        if (isset($disease['advertisement_video']) && !empty($disease['advertisement_video'])) {
                                                                            ?>
                                                                            var file = '<?php echo $disease['advertisement_video']; ?>';
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            var file = 'https://www.youtube.com/watch?v=9QscURRuF0g';
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        jwplayer('profile_video_container').setup({
                                                                            file: file,
                                                                            width: '500px',
                                                                            image: '/theme/App/img/tmp/dasboard_my_videos.png',
                                                                            displaytitle: false,
                                                                            stretching: 'exactfit'

                                                                        });
                                                                </script>
								
							</div>
						</div>
					</div>
					<?php
					if (trim($disease['Disease']['description']) != "") {
						?> 
						<div class="event_settings row">                            
							<span class="">Description:</span>
						<?php echo nl2br(h($disease['Disease']['description'])); ?>
						</div>
						<?php						
					}					
					?>
					<div class="event_settings row">
						<button class="btn disease_follow_btn" <?php if ($followStatus > 0) { ?> style="display:none;" <?php } ?> data-disease-id="<?php echo $disease['Disease']['id']; ?>">
							Follow</button>
						<button class="btn disease_unfollow_btn" <?php if ($followStatus == 0) { ?> style="display:none;" <?php } ?> data-disease-id="<?php echo $disease['Disease']['id']; ?>">
							Unfollow</button>
					</div>
					   
						 <div class="group_options">
					<nav class="navbar navbar-default" role="navigation">
						
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
							<div class="group_catagories pull-left">
								<ul>
									<?php
									if (!empty($section_list)) {
										foreach ($section_list as $sec_url => $sec_name) {
											$sel_class = $section == $sec_url ? "current" : "";
											echo '<li  class="' . $sel_class . '"><a href="' . $disease_detail_url . '/' . $sec_url . '">' . $sec_name . '</a></li>';
											$sel_class = "";
										}
									}
									?>
								</ul>                            
							</div>
						</div>
					</nav>
				</div>                         
								

				</div>-->

				             
				</div>
			  </div> 
            </div>

            <div class="row mr_0" id="group_element_container">
                <div class="col-lg-9">

                    <!-- Tab Details Box -->
                <?php if (!$loggedIn) { ?>
                    <div class="condition_login">
                        <h3>Tackle your disease in a better way.
                            Lead a better life by learning and sharing experiences.</h3>
                        <div class="row">
                            <div class="col-lg-9">
                                <ul>
                                    <li>A growing community of 500+ members.</li>
                                    <li>Ability to interact with similar people using a social platform.</li>
                                    <li>Create and partake in  various disease and health related events.</li>
                                    <li>Join communities, ask questions and learn from the experiences of others.</li>
                                    <li>Tools to track your health and conditions.</li>
                                    <li>And a lot more coming up !</li>                                
                                </ul>
                            </div>
                            <div class="col-lg-3">
                                <button class="btn joinus_btn">
                                    <a href="/register">Join Us</a></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                    <div>
                    <?php echo $this->element('Disease./' . $section); ?>
                    </div>

                </div>

                <!-- RHS Box -->
                <div class="col-lg-3" id="rhs">
                    <div class="event_lhs">
                        <div id="disease_members_online" class="member_list disease_rhs" data-disease-id="<?php echo $disease['Disease']['id']; ?>" >
                            <h4>Online Members (<?php echo $onlineMemberCount;?>)</h4>
                            <div class="row content details_container">
                            <?php echo $this->element('Disease.users_with_same_disease'); ?>
                                <!--<div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));                                              ?></div>-->
                            </div>
                        </div>
                     <div id="disease_friends_online" class="member_list disease_rhs">
                            <h4>Online Friends</h4>
                            <div class="row content details_container">
                                <?php echo $this->element('Disease.online_friends'); ?>                            
                            </div>
                    </div>
                    <div id="disease_ppl_mayknw_list" class="member_list disease_rhs">
                            <h4>Members You’d Like to Meet</h4>
                            <div class="row content details_container">
                                <?php echo $this->element('Disease.people_youmay_know_list'); ?>
                            <div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));                                              ?></div>
                    </div>
                    </div>
                    <?php
                    if (!empty($users_count) && $users_count[0]['users'] > 0) {
                        ?>
                        <div class="member_list disease_rhs age_group_graph">
                            <h4>
                                <?php echo __('Who has ' . h($disease['Disease']['name']) . ' on ' . Configure::read('App.name')); ?>
                            </h4>
                            <div class="content details_container">
                                <div id="disease_user_gender_graph"></div>
                            </div>
                        </div>

                        <div class="member_list disease_rhs age_group_graph">
                            <h4>
                                <?php echo __('People with ' . h($disease['Disease']['name'])); ?>
                            </h4>
                            <div class="content details_container">
                                <div id="disease_user_treatment_graph"></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                        <!--                    <div id="disease_ppl_mayknw_list" class="member_list disease_rhs">
                                                <h4>People You May Know</h4>
                                                <div class="row content details_container">
                    <?php //echo $this->element('Disease.people_youmay_know_list'); ?>
                                                    <div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));                                              ?></div>
                                                </div>
                                            </div>-->
                        <!--                    <div id="disease_friends_online" class="member_list disease_rhs">
                                                <h4>Online Friends</h4>
                                                <div class="row content details_container">
                    <?php //echo $this->element('Disease.online_friends'); ?>
                                                    <div class="text-center"><?php // echo $this->Html->image('loader.gif', array('alt' => 'Loading...'));                                              ?></div>
                                                </div>
                                            </div>-->
                    <?php echo $this->element('ads'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    $this->AssetCompress->addScript(array(
        'community.js',
        'events.js', 'vendor/highstock.js'), 'disease_view.js');
	$this->AssetCompress->script('events', array('block' => 'scriptBottom'));
    ?>
        <script>
            function load_events(disease_id, page, condition_url) {
                if (typeof (page) === "undefined") {
                    page = 1;
                }
                if (page != 1) {
                    var l = Ladda.create(document.querySelector('#load-more'));
                    l.start();
                }
                
                $.ajax({
                    url: condition_url + 'events/' + disease_id + '/page:' + page,
                    dataType: 'json',
                    success: function(result) {
                        $("#more_button").remove();
                        if (page == 1) {
                            $("#event_list").html(result['htm_content']);//                                                                        
                        } else {
                            $("#event_list").append(result['htm_content']);
                        }
                        if (result.paginator.nextPage == true) {
                            $('#disease_events_list').append('<div id="more_button" class="block">' +
                                    '<a href="javascript:load_events(' + disease_id + ',' + (result.paginator.page + 1) + ','+ condition_url + ');" id="load-more" class="btn btn_more pull-right ladda-button" data-style="expand-right" data-size="l" data-spinner-color="#3581ED" style="margin-right:15px;"><span class="ladda-label more-arrow"><?php echo __('More'); ?> </span></a>' +
                                    '</div>');
                        }
                        applyHoverEffect();
                    }
                }).always(function() {
                    if (page != 1) {
                        l.stop();
                    }
                });

            }
			
		$(document).on('click', '.disease_video_btn', function() {
		 var $height = $(window).height();
		 $height = $height - ((20 * $height) / 100) + 'px';
		 var $videoUrl = $(this).attr('data-video');
		 if ($videoUrl) {
		 var $embedCode = $(this).embedPlayer($videoUrl, '100%', $height, true);
		 bootbox.dialog({
		 message: $embedCode,
		 closeButton: true,
		 backdrop: true,
		 onEscape: function() {
		 },
		 animate: false,
		 className: 'video_modal'
		 });
		 }
		 });

            function load_disease_communities(disease_id, page, condition_url) {

                if (typeof (page) === "undefined") {
                    page = 1;
                }
                if (page != 1) {
                    var l = Ladda.create(document.querySelector('#load-more'));
                    l.start();
                }
                setTimeout(function() {
                    $.ajax({
                        url: condition_url + 'community/' + disease_id + '/page:' + page,
                        dataType: 'json',
                        success: function(result) {

                            if (page == 1) {
                                $("#more_button").remove();
                                $('#diseaseCommunityList').html(result.htm_content);

                            } else {
                                $('#diseaseCommunityList').append(result.htm_content);
                            }
                            if (result.paginator.nextPage == true) {
                                $('#diseaseCommunity').after('<div id="more_button' + (result.paginator.page + 1) + '" class="block">' +
                                        '<a href="javascript:load_disease_communities(' + disease_id + ', ' + (result.paginator.page + 1) + ','+condition_url+')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label"><?php echo __('More') ; ?></span></a>' +
                                        '</div>');
                            }
                        }
                    }).always(function() {
                        if (page != 1) {
                            l.stop();
                            $("#more_button" + page).remove();
                            $("#more_button").remove();
                        }
                    });
                }, 1000);
            }

        
//            // to add slim scroll to peopleYouMayKnow, friendsOnline, PatientsOnline sections.
//            $('.disease_rhs .details_container').each(function() {
//                if ($(this).height() > 400) {
//                    $(this).slimScroll({
//                        color: '#BBDAEC',
//                        railColor: '#EBF5F7',
//                        size: '12px',
//                        height: '450px',
//                        railVisible: true
//                    });
//                }
//            });

            //Drawing graphs
            var is_user = <?php
        if (isset($users_count[0]['users'])) {
            echo 'true;';
        } else {
            echo 'false;';
        }
        ?>
            var total_users = <?php
        if (isset($users_count[0]['users'])) {
            echo $users_count[0]['users'];
        } else {
            echo 0;
        }
        ?>;
            if (is_user) {
                var gender_graph_data = <?php echo ($gender_analytics); ?>;
                var age_group_data = <?php echo $treatment_analytics; ?>;
                var gender_seriesOptions = [{
                        type: 'pie',
                        name: 'of users are',
                        data: gender_graph_data
                    }];

                function createGenderChart(seriesOption) {
                    $('#disease_user_gender_graph').highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            width: 270,
                            height: 270
                        },
                        legend: {
                            layout: 'horizontal',
                            floating: true,
                            align: 'left',
                            verticalAlign: 'bottom',
                            shadow: false,
                            border: 0,
                            borderRadius: 0,
                            borderWidth: 0,
                            itemDistance: 140,
                            width: 500
                        },
                        colors: [
                            '#2c579e',
                            '#29abe1'
                        ],
                        title: {
                            text: 'Gender',
                            verticalAlign: 'bottom'
                        },
                        exporting: {
                            enabled: false
                        },
                        tooltip: {
                            pointFormat: '<b>{point.y} user(s)</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    color: '#FFFFFF',
                                    format: '{point.percentage:.1f} %',
                                    inside: true,
                                    distance: -45
                                },
                                showInLegend: true
                            }
                        },
                        series: seriesOption,
                        credits: {
                            enabled: false
                        }
                    });
                }
            }

            function createAgeGroupGraph(series) {
                $('#disease_user_treatment_graph').highcharts({
                    chart: {
                        type: 'column',
                        width: 290,
                        height: 290
                    },
                    title: {
                        text: ''
                    },
                    colors: [
                        '#39b549',
                        '#8263a2'
                    ],
                    xAxis: {
                        categories: [
                            '0-18',
                            '19-25',
                            '25-35',
                            '35-60',
                            '60+'
                        ]
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: null
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding-left:5px;"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    legend: {
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    series: series
                });
            }

            $(document).ready(function() {
                if (is_user) {
                    createGenderChart(gender_seriesOptions);
                    createAgeGroupGraph(age_group_data);
                }
                
                // to add slim scroll to peopleYouMayKnow, friendsOnline, PatientsOnline sections.
                $('.disease_rhs .details_container').each(function() {
                    if ($(this).height() > 400) {                    
                        applySlimScroll( $(this), '450px' );                    
                    }
                });
                
                if (isIE () == 9) {
                    if( $('#profile_video_container_wrapper').length ) {
                        $('#profile_video_container_wrapper').hide();
                    }
                }
        });

        </script>

    <?php
    $this->Html->script(array('//jwpsrv.com/library/+wt_PpJBEeOk_yIACmOLpg.js'), array('inline' => false));
    ?>
