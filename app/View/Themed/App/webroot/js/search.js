/**
 * File to handle the search page functionality
 * @param page int page no
 */
function load_more_items(page) {
	 var l = Ladda.create(document.querySelector('#load-more'));
     l.start();
     setTimeout(function() {
         $.ajax({
        	 url: '/search/search/index/page:' + page,
        	 data:{ajax:$("#search_string").val(),
            	 	type:$("#search_type").val()},
             dataType: 'json',
             success: function(result) {
                 $('#searchList').append(result.htm_content);
                 
                 if (result.paginator.nextPage == true) {
                     $('#searchPageList').append('<div id="more_button' +(result.paginator.page + 1) + '" class="block">' +
                             '<a href="javascript:load_more_items(' + (result.paginator.page + 1) + ')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a>' +
                             '</div>');
                 }   
             }
         }).always(function() {
             l.stop();
             $("#more_button"+ page).remove();
         });
     }, 1000);
}
/**
 * Function to paginate all search result page.
 */
function load_more_all_items(page, type) {
    type = type.toLowerCase();
    if (type === "community") {
        var c = Ladda.create(document.querySelector('#load-community-more'));
        c.start();
    }
    else if (type === "people") {
        var l = Ladda.create(document.querySelector('#load-user-more'));
        l.start();
    }//create only object of ladda when needed.
    else if (type === "disease") {
        var d = Ladda.create(document.querySelector('#load-disease-more'));
        d.start();
    } else if (type === "hashtag") {
        var d = Ladda.create(document.querySelector('#load-hashtag-more'));
        d.start();
    }
    
    setTimeout(function() {
        $.ajax({
            url: '/search/search/index/page:' + page,
            data: {ajax: $("#search_string").val(),
                type: type},
            dataType: 'json',
            success: function(result) {

                if (type === "community") {
                    $('#searchCommunityList').append(result.htm_content);
                    if (result.paginator.nextPage == true) {
                        $('#searchCommunityPageList').append('<div id="more_community_button' + (result.paginator.page + 1) + '" class="block">' +
                                '<a href="javascript:load_more_all_items(' + (result.paginator.page + 1) + ',\'community\')" id="load-community-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="c" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a>' +
                                '</div>');
                    }
                }
                else if (type === "people") {
                    $('#searchUserList').append(result.htm_content);
                    if (result.paginator.nextPage == true) {
                        nextId = result.paginator.page + 1;

                        $('#searchUserPageList').append('<div id="more_user_button' + (result.paginator.page + 1) + '" class="block">' +
                                '<a href="javascript:load_more_all_items(' + (result.paginator.page + 1) + ',\'people\')" id="load-user-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a>' +
                                '</div>');

                    }
                }
                else if (type === "disease") {
                    $('#searchDiseaseList').append(result.htm_content);
                    if (result.paginator.nextPage == true) {

                        $('#searchDiseasePageList').append('<div id="more_disease_button' + (result.paginator.page + 1) + '" class="block">' +
                                '<a href="javascript:load_more_all_items(' + (result.paginator.page + 1) + ',\'disease\')" id="load-disease-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More </span></a>' +
                                '</div>');

                    }
                }
                else if (type === "hashtag") {
                    $('#searchHashtagList').append(result.htm_content);
                    if (result.paginator.nextPage == true) {

                        $('#searchHashtagList').append('<div id="more_hashtag_button' + (result.paginator.page + 1) + '" class="block">' +
                                '<a href="javascript:load_more_all_items(' + (result.paginator.page + 1) + ',\'hashtag\')" id="load-hashtag-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More </span></a>' +
                                '</div>');

                    }
                }


            }
        }).always(function() {
            Ladda.stopAll();
            if (type === "people") {
                $("#more_user_button" + page).remove();
            }
            else if (type === "community") {
                $("#more_community_button" + page).remove();
            }
            else if (type === "disease") {
                $("#more_disease_button" + page).remove();
            }
            else if (type === "hashtag") {
                $("#more_hashtag_button" + page).remove();
            }

        });
    }, 1000);
}

/**
 * Function to paginate posts
 */
function paginateRecommendedFriendData() {

	if ($.autopager) {
		$.autopager({
			// a selector that matches a element of next page link
			link: 'span.next a',
			// a selector that matches page contents
			content: '#recommendedFriendList',
			// where contents would be appended.
			appendTo: '#recommendedFriendPageList .row',
			// a callback function to be triggered when loading start 
			start: function(current, next) {

				// show loading status
				$("#post_loading").removeClass('hide');
			},
			// a function to be executed when next page was loaded. 
			load: function(current, next) {

				if (typeof next.url === "undefined")
				{
					$('a[rel=next]').attr('href', '');
				}
				else {
					$('a[rel=next]').attr('href', next.url);
				}
				// hide loading status
				$("#post_loading").addClass('hide');


			}
		});
	}
}

//Function for select the search category
$(document).ready(function(e) {

	$('.event_search .dropdown-menu li a').removeClass('search_list_active');
	var selected_class = $("#search_class").val();
	if (selected_class) {
		$('.event_search .dropdown-menu .' + selected_class).addClass('search_list_active');
	}
	load_recommended_friends(1);
	initDiagnosisFaceList();
	initTreatmentFaceList();
	initSymptomFaceList();
	initCityFaceList();
});

function initDiagnosisFaceList() {
    $('#keyword_disease').facelist('/api/searchDiseaseNames', properties = {
        matchContains: true,
        minChars: 2,
        max: 50,
        selectFirst: false,
        intro_text: 'Type disease name',
        result_field: 'search_disease_id'
    });
}
function initTreatmentFaceList() {
    $('#keyword_treatment').facelist('/api/searchTreatments', properties = {
        matchContains: true,
        minChars: 2,
        max: 50,
        selectFirst: false,
        intro_text: 'Type treatment name',
        result_field: 'search_treatment_id'
    });
}
function initSymptomFaceList() {
    $('#keyword_symptom').facelist('/api/searchSymptomNames', properties = {
        matchContains: true,
        minChars: 2,
        max: 50,
        selectFirst: false,
        intro_text: 'Type symptom name',
        result_field: 'search_symptom_id'
    });
}
function initCityFaceList() {
    $('#keyword_location').facelist('/api/searchCityNames', properties = {
        matchContains: true,
        minChars: 2,
        max: 50,
        selectFirst: false,
        intro_text: 'Type city name',
        result_field: 'search_city_id'
    });
}

$(document).on('blur', '#keyword_location, #keyword_disease, #keyword_treatment, #keyword_symptom', function() {
	$(this).val('');
});

/**
 * Function to get advanced search data
 * 
 * @returns {getAdvancedSearchData.searchData}
 */
function getAdvancedSearchData() {
	var searchData = {}, disease = [], location = [], symptoms = [], treatment = [];

	if ($('#search_disease_id').val() !== '') {
		var diseaseIdList = $('#search_disease_id').val();
		var diseaseArray = diseaseIdList.split(',');
		diseaseArray.forEach(function(diseaseId) {
			if (diseaseId !== '') {
				disease.push(diseaseId);
			}
		});
		if (disease.length !== 0) {
			searchData['disease'] = disease;
		}
	}
	
	if ($('#search_city_id').val() !== '') {
		var cityIdList = $('#search_city_id').val();
		var cityArray = cityIdList.split(',');
		cityArray.forEach(function(cityId) {
			if (cityId !== '') {
				location.push(cityId);
			}
		});
		if (location.length !== 0) {
			searchData['location'] = location;
		}
	}

	if ($('#search_symptom_id').val() !== '') {
		var symptomIdList = $('#search_symptom_id').val();
		var symptomArray = symptomIdList.split(',');
		symptomArray.forEach(function(symptomId) {
			if (symptomId !== '') {
				symptoms.push(symptomId);
			}
		});
		if (symptoms.length !== 0) {
			searchData['symptoms'] = symptoms;
		}
	}

	if ($('#search_treatment_id').val() !== '') {
		var treatmentIdList = $('#search_treatment_id').val();
		var treatmentArray = treatmentIdList.split(',');
		treatmentArray.forEach(function(treatmentId) {
			if (treatmentId !== '') {
				treatment.push(treatmentId);
			}
		});
		if (treatment.length !== 0) {
			searchData['treatment'] = treatment;
		}
	}

	if ($('#keyword_name').val() !== '') {
		searchData['name'] = $('#keyword_name').val();
	}

	if ($('#keyword_age').val() !== '') {
		searchData['age'] = $('#keyword_age').val();
	}

	if ($('#keyword_gender').val() !== '') {
		searchData['gender'] = $('#keyword_gender').val();
	}

	return searchData;
}
 
$(window).scroll(function() {    
     paginateRecommendedFriendData();
});
/**
 * Function to load recommended friend section
 * @param page int page no recommended_users_list
 */
function load_recommended_friends(page) {
    $.ajax({
        url: '/search/search/recommendedFriends/page:' + page,
        success: function(result) {
            $('.recommended_users_list').append(result);
			// hide loading status
			$("#recommended_user_loading").addClass('hide');
		}
    });
}
/**
 * Function to load more pending friends requests
 * @param page int page no
 */
function load_more_pending_friends(page) {
	var l = Ladda.create(document.querySelector('#load-more'));
    l.start();
    setTimeout(function() {
        $.ajax({
       	 url: '/search/search/pendingInvites/page:' + page,
       	 data:{ajax:'true'},
            dataType: 'json',
            success: function(result) {
                $('#searchList').append(result.htm_content);
                
                if (result.paginator.nextPage == true) {
                    $('#searchPageList').append('<div id="more_button' +(result.paginator.page + 1) + '" class="block">' +
                            '<a href="javascript:load_more_pending_friends(' + (result.paginator.page + 1) + ')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a>' +
                            '</div>');
                }   
            }
        }).always(function() {
            l.stop();
            $("#more_button"+ page).remove();
        });
    }, 1000);
}


/**
 * File to handle the advanced search page functionality
 * @param page int page no
 * 
 */
function load_more_items_advanced(page) {
	var l = Ladda.create(document.querySelector('#load-more'));
	l.start();
	var searchData = getAdvancedSearchData();
	searchData['ajax'] = true;
	searchData['type'] = 'people';
	setTimeout(function() {
		$.ajax({
			url: '/search/search/index/page:' + page,
			data: searchData,
			dataType: 'json',
			success: function(result) {
				$('#searchList').append(result.htm_content);

				if (result.paginator.nextPage == true) {
					$('#searchPageList').append('<div id="more_button' + (result.paginator.page + 1) + '" class="block">' +
							'<a href="javascript:load_more_items_advanced(' + (result.paginator.page + 1) + ')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a>' +
							'</div>');
				}
			}
		}).always(function() {
			l.stop();
			$("#more_button" + page).remove();
		});
	}, 1000);
}