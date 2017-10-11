<div class="signup_map">
	<div class="img-responsive">
		<div class='span12 map-container'>
			<div id='google_map' style="height: 255px;">
			</div>
			<script src='https://maps.google.com/maps/api/js?sensor=true' type='text/javascript'></script>

			<script type = "text/javascript">
				// Google Map on Home page
				function initializeMap() {
					var infowindow = new google.maps.InfoWindow();
					var myOptions = {
						zoom: 1,
						center: new google.maps.LatLng(15,293),
						scrollwheel: false,
						mapTypeControl: false,
						streetViewControl: false,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					}
					crohnologyMap = new google.maps.Map(document.getElementById("google_map"), myOptions); // GLOBAL

					addMarker(33.5941759, -117.5730639, 3);
					addMarker(32.562483, -94.7070616, 2);
					addMarker(26.640628, -81.8723084, 2);
					addMarker(54.9966124, -7.3085748,3);
					addMarker(46.0543277, 14.1197241, 4);
					addMarker(41.8781136, -87.6297982, 1); 
					addMarker(-34.6037232, -58.3815931, 2);
					addMarker(65.256706,20.288084, 1);
					addMarker(-37.814107, 144.96328, 4);
					addMarker(47.6062095, -122.3320708, 4);
					addMarker(46.49, -81.01, 3);
					addMarker(40.3916172, -111.8507662, 1); 
					addMarker(49.8997541, -97.1374937,4); 
					addMarker(-43.5320544, 172.6362254, 3); 
					addMarker(39.92077, 32.85411, 2); 
					addMarker(-20.550509,130.415037, 1); 
					addMarker(18.32324,74.177856, 4); 
					addMarker(36.800488,105.411987, 2);
					addMarker(-5.441022,-75.776369, 1)
					addMarker(-18.229351,-55.649416, 3);
					addMarker(-10.401378,-42.905275, 1);
					addMarker(-12.983148,21.16699, 2);
					addMarker(-30.069094,25.122069, 3);
					addMarker(14.774883,24.755856, 1);
					addMarker(62.754726,94.365231, 3);

					function disableDragging() {
						// Quick hack to disable dragging on mobile map
						var mobileFlag = $(window).width() <= 480;
						if (mobileFlag) { crohnologyMap.setOptions( { draggable: false }); }

						// if they turn their phone...
						$(window).resize(function() {
							var w = $(window).width();
							if (!mobileFlag && w <= 480){
								crohnologyMap.setOptions( { draggable: false });
								mobileFlag = true;
							}
							else if (mobileFlag && w >= 480) {
								crohnologyMap.setOptions( { draggable: true });
								mobileFlag = false;
							}
						});
					}
					disableDragging();

					function addMarker(lat, lng, img) {
						// adding markers on the map
						var latlng = new google.maps.LatLng(lat, lng);
						switch(img) {
						case 1 :    var marker = new google.maps.Marker({
													map: crohnologyMap,
													position: latlng,
													icon : '/theme/App/img/map_icons/patient.png'
												});
									break;
						case 2 :    var marker = new google.maps.Marker({
													map: crohnologyMap,
													position: latlng,
													icon : '/theme/App/img/map_icons/family.png'
												});
									break;
						case 3 :    var marker = new google.maps.Marker({
													map: crohnologyMap,
													position: latlng,
													icon : '/theme/App/img/map_icons/caregiver.png'
												});
									break;
						case 4 :    var marker = new google.maps.Marker({
													map: crohnologyMap,
													position: latlng,
													icon : '/theme/App/img/map_icons/friend.png'
												});
									break;
						}

					}
				}

				initializeMap();
			</script>

		</div>
	</div>
</div>