/**
 * Disable right click in the website
 */
if (document.addEventListener) {
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        }, false);
    } else {
        document.attachEvent('oncontextmenu', function() {
            window.event.returnValue = false;
        });
    }
/**
 * Default date format
 */
var defaultDateFormat = 'mm/dd/yy';
var defaultTimeFormat = 'g:i A';/*'hh:mm tt';*/
var is_symptom_newscore_active = false;

$(document).ready(function() {
    /**
     * Update app time
     */
    $appDateTime = new Date(app.date_iso);
    setInterval(function() {
        $appDateTime = new Date($appDateTime.getTime() + 1000);
    }, 1000);

    /**
     * Reload the page if any of the ajax requests return 403 http status code
     */
    $.ajaxSetup({
        statusCode: {
            403: function() {
                location.reload();
            }
        }
    });

    if (app.loggedIn) {
        applyChosen();
        updateNotificationCounts();

        // update online friends tile
        var timer;
        function updateFriendsList() {
            if ($("#online_friends_list").length) {
                timer = setInterval(function() {
                    if (!$("#online_friend_search_key").is(":focus")) {
                        updateOnlineFriendsList();
                    }
                }, 10000);

                $('#online_friends_list').mouseover(function() {
                    clearInterval(timer);
                });
            }
        }
        updateFriendsList();
        $('#online_friends_list').mouseout(function() {
            updateFriendsList();
        });

        // update online friends list in profile
        if ($("#profile_friends_online").length) {
            setInterval(function() {
                //if (!$("#online_friend_search_key").is(":focus")) {
                updateProfileOnlineFriendsList();
                //}
            }, 10000);
        }
        // update video online friends list in profile
        if ($("#online_video_friends_list").length) {
            setInterval(function() {
                updateVideoProfileOnlineFriendsList();
            }, 10000);
        }

        if ($('#healthStatusSelectionModal').length > 0) {
            if ($('#healthStatusSelectionModal').hasClass('prompt')) {
                showHealthStatusSelectionModal();
            }
        }
         // update disease member list in disease page
        if ($("#disease_members_online").length) {
            setInterval(function() {
                updateDiseaseMemberList();
            }, 10000);
        }
        
        // update online friends list in disease
        if ($("#disease_friends_online").length) {
            setInterval(function() {                
                updateDiseaseOnlineFriendsList();                
            }, 10000);
        }
        // update online friends list in disease
        if ($("#community_members_online").length) {
            setInterval(function() {                
                updateCommunityMembersList();                
            }, 10000);
        }  
        
    } else {
        $(".home_tiles").popover(
                {
                    content: function() {
                        return $($(this).data('target')).html();
                    },
                    html: true,
                    placement: 'auto',
                    container: 'body'
                });
    }
    applySticky();
    $('#group-section-alert .close').on('click', function() {
        $('#group-section-alert .alert-content').html("");
        $(this).parent().hide();
    });

    $('#compose-message-button').on('click', function() {
        $(".arrowchat_closebox_bottom").click();
    });

    $(document).on('input', '.complete_location', function() {
        if ($(this).val() === "")
            $(this).parent().find('.location_id_hidden').val("");
        $('.location_id_hidden').each(function() {
            if ($(this).val() === '')
                $('#add_location').addClass('plus_disabled');
        });
    });

    $(document).on('click', '.chat-user', function() {
        if ($(this).data("id")) {
            jqac.arrowchat.chatWith($(this).data("id"));
        }
    });

    $(document).on('click', '.video-chat-user', function() {
        if ($(this).data("id")) {
            jqac.arrowchat.chatWith($(this).data("id"));
            b = $(this).data("id");
            $("#arrowchat_video_chat_" + b).click();
        }
    });

    $(document).on('input', '.complete_diagnosis', function() {
        if ($(this).val() === "")
            $(this).parent().find('.disease_id_hidden').val("");
        $('.disease_id_hidden').each(function() {
            if ($(this).val() === '')
                $('#add_diagnosis').addClass('plus_disabled');
        });
    });

    $(document).on('input', '.complete_symptom', function() {
        if ($(this).val() === "")
            $(this).parent().find('.symptoms_id_hidden').val("");
        $('.symptoms_id_hidden').each(function() {
            if ($(this).val() === '')
                $('#add_symptoms').addClass('plus_disabled');
        });
    });

    $(document).on('input', '.complete_treatment', function() {
        if ($(this).val() === "")
            $(this).parent().find('.treatment_id_hidden').val("");
        $('.treatment_id_hidden').each(function() {
            if ($(this).val() === '')
                $('#add_treatment').addClass('plus_disabled');
        });
    });
    // Commenting the hover functionality in the Events listing page
    applyHoverEffect();

    $('#SymptomDate').datepicker();

    $('#symptomHistoryDatepicker').datepicker({
        minDate: "-2y",
        maxDate: getUserNow(),
        defaultDate: getUserNow(),
        dateFormat: "yy-mm-dd",
        onSelect: function(dateText) {
            is_symptom_newscore_active = true;
            myDate = new Date(Date.parse(dateText));

            $('#date-selected').html(dateText); //date to display in popup     


            $('#selectedSymptomDate').val(dateText);//date to submit
            symptom_id = $.trim($('#symptom_id').val());
            $.ajax({
                url: '/user/api/getUserSymptomSeverity',
                data: {
                    'id': symptom_id,
                    'date': dateText
                },
                type: 'POST',
                dataType: 'json',
                success: function(result) {
                    id = '';
                    name = '';
                    $.map(result, function(item) {
                        id = item.severityId,
                                name = item.name
                    });
                    if (name != '') //if already value present  
                    {
                        $('.condition_' + name).addClass('on');
                        $('.condition_' + name).find("input").attr('checked', true);
                    }
                    $('#symptom_conditions').modal('show');
                    bootbox.hideAll();
                    is_symptom_newscore_active = false;

                }
            });

        }
    });

    //Function for searching communitys in header
    $("#header_search").focus(function() {
        var searchCategory = $('#search_icons').attr('class').replace("search_icons ", "");
        var Category;
        var type;
        switch (searchCategory) {
            case 'all'				:
                Category = 1;
                type = "all";
                break;
            case 'people_search' 	:
                Category = 2;
                type = "people";
                break;
            case 'event_search'		:
                Category = 3;
                type = "event";
                break;
            case 'community_search'	:
                Category = 4;
                type = "community";
                break;
            case 'disease_search'	:
                Category = 5;
                type = "disease";
                break;
            case 'hash_search'	:
                Category = 6;
                type = "hashtag";
                break;
            default:
                Category = 1;
                type = "all";
                break;
        }

        $("#header_search").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();

                var keyword = $("#header_search").val();
                if (!keyword == "") {

//                if (Category == 4 || Category == 2) {
                    window.location.replace("/search?type=" + type + "&keyword=" + encodeURIComponent(keyword));
//                }
                }
            }
        });		

        $(this).autocomplete({
            appendTo: $('.navbar-fixed-top'),
            source: function(request, response) {
                $.ajax({
                    url: '/search/search/getHeaderSearch',
                    dataType: 'json',
                    data: {
                        term: request.term,
                        category: Category
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                style: item.Style,
                                value: item.Name,
                                logoUrl: item.LogoUrl,
                                url: item.Url,
                                disc: item.Disc,
                                type: item.Type
                            };
                        }));
                    }
                });
            },
            open: function() {
                $(this).data("uiAutocomplete").menu.element.addClass("search_list");
				//$(this).data("uiAutocomplete").menu.element.removeAttr('style');
                $('.search_list li').removeClass('ui-menu-item');
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
			
				if (item.value == 'More') {
					if (item.url == '') {
						var inner_html = item.disc;
					} else {
						var inner_html = item.disc + '<a class="pull-right more" href="' + item.url + '">more</a>';
					}

					var element = "<li class = 'search_header'></li>";

				} else if (item.value != 'empty') {
					if (item.type == 'condition' || item.type == 'hashtag') {
						var inner_html = '<div class="media">';
						inner_html += '<div class="media-body"><h6 class="media-heading"><a href="' + item.url + '">' + item.value + '</a></h6> <p>' + item.disc + '</p></div></div>';
						var element = "<li></li>";
					} else {
						var inner_html = '<div class="media"><a class="pull-left" href="' + item.url + '"><img height="40"  src="' + item.logoUrl + '"  class = "' + item.style + '">';
						inner_html += '</a><div class="media-body"><h6 class="media-heading"><a href="' + item.url + '">' + item.value + '</a></h6> <p>' + item.disc + '</p></div></div>';
						var element = "<li></li>";
					}
				}
				if (item.value == 'empty') {
					var inner_html = '<div class="media">';
					inner_html += '<div class="media-body"><h6 class="media-heading">No search results</h6></div></div>';
					var element = "<li></li>";
				}
				return $(element)
						.data("item.autocomplete", item)
						.append(inner_html)
						.appendTo(ul);
        };
    });

    $(document).on('focus', '.bootstrap-tagsinput input', function() {
        $(this).parent().addClass('focus');
    });

    $(document).on('focusout', '.bootstrap-tagsinput input', function() {
        $(this).parent().removeClass('focus');
    });

    /*
     * Green border for medication input
     */
    $(document).on('focus', '.medication_input', function() {
        $(this).closest('.facelist').addClass('focus');
    });

    $(document).on('focusout', '.medication_input', function() {
        $(this).closest('.facelist').removeClass('focus');
    });

    /*
     * Green border for message compose box
     */
    $(document).on('focus', '#SendMessageByEmail', function() {
        $(this).closest('.facelist').addClass('focus');
    });

    $(document).on('focusout', '#SendMessageByEmail', function() {
        $(this).closest('.facelist').removeClass('focus');
    });
	
	/* Exiting from full screen mode while playing video */
	$(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
		var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
		var event = state ? 'FullscreenOn' : 'FullscreenOff';
		if(event == 'FullscreenOn') {
			$('.body_loggedin').css({ "overflow-y": 'auto'});
			$('#profile_video_container').removeClass('jwfullscreen'); 
		}
	});
});

/* Embed Video player */

(function($) {
    $.fn.embedPlayer = function(url, width, height, autoplay) {

        var $output = '';
        var youtubeUrl = url.match(/watch\?v=([a-zA-Z0-9\-_]+)/);
        var vimeoUrl = url.match(/^http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+).*$/);
        var aPlay = autoplay == "true" || autoplay ? true : false;

        if (youtubeUrl) {
            var url_ = 'https://www.youtube.com/embed/' + youtubeUrl[1] + '?rel=0;autohide=1';
            if (aPlay) {
                url_ += '&amp;autoplay=1';
            } else {
                url_ += '&amp;autoplay=0';
            }
            $output = '<iframe style="width: ' + width + '; height: ' + height + ';" src="' + url_ + '&wmode=opaque' + '" frameborder="0" allowfullscreen ></iframe>';


        } else if (vimeoUrl) {

            var url_ = 'https://player.vimeo.com/video/' + vimeoUrl[3] + '?title=0&byline=0&portrait=0';
            if (aPlay) {
                url_ += '&autoplay=1';
            } else {
                url_ += '&autoplay=0';
            }
            $output = '<iframe style="width: ' + width + '; height: ' + height + ';" src="' + url_ + '&wmode=opaque' + '"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

        } else {

            $output = '<p>No video url found - vimeo and youtube supported</p>';
        }

        return $output;
    };
})(jQuery);

var saveHealthStatusRotate;

$(document).on('click', '.health_status_box', function() {
    $('.health_status_box').removeClass('active');
    $(this).addClass('active');
    var healthStatus = $(this).attr('data-health_status');
    $('#health_status').val(healthStatus);
    $('#save_health_status_btn').prop('disabled', false);
    $('#health_status_comment_container').removeClass('hide');
});

$(document).on('click', '.settings_cancel', function() {
	window.location.href = '/profile';
});

$(document).on('click', '#save_health_status_btn', function() {
    saveHealthStatusRotate = Ladda.create(this);
    $.ajax({
        url: '/user/dashboard/saveUserHealthStatus',
        method: 'POST',
        dataType: 'json',
        data: $('#health_status_form').serialize(),
        beforeSend: function() {
            saveHealthStatusRotate.start();
        },
        success: function(result) {
            if ($('#status_graph_container').length == 1) {
                load_stock_graph();
            }
            socket.emit('my_health_update', {
                room: $('#graphUpdatedInRoom').val(),
                type: 'health_indicators'
            });

			emitHealthUpdateToNewsFeeds(result.postId);

            resetHealthStatusSelectionModal();
            $('#healthStatusSelectionModal').modal('hide');
            if (result.smileyClass !== null) {
                changeSmiley(result.smileyClass);
            }
            if ($("#post_container").length > 0) {
                location.reload();
            }
        }
    });
});

/**
 * Function to emit health update post of current user to newsfeed pages
 */
function emitHealthUpdateToNewsFeeds(postId) {
	$.ajax({
		type: 'POST',
		url: '/post/api/getCurrentUserHealthFollowingRooms',
		dataType: 'json',
		success: function(rooms) {
			rooms.forEach(function(room) {
				var data = {
					'room': room,
					'postId': postId
				};
				socket.emit('new_post', data);
			});
		}
	});
}

/**
 * Change 'how are you today' text after first time show.
 */
$('#healthStatusSelectionModal.prompt').on('hidden.bs.modal', function() {
    var heading = $('#healthStatusSelectionModal .heading h2').text();
    $('#healthStatusSelectionModal .heading h1').html(heading);
});

$(document).on('click', '#close_health_status_modal', function() {
    resetHealthStatusSelectionModal();
});

$(document).on('click', '.my_health_add', function(e) {
    e.stopPropagation();
    showHealthStatusSelectionModal();
});

function showHealthStatusSelectionModal() {
    if ($('#healthStatusSelectionModal').length > 0) {
        $('#save_health_status_btn').prop('disabled', true);
        $(".arrowchat_closebox_bottom").click();
        $('#healthStatusSelectionModal').modal('show');
    }
}

function resetHealthStatusSelectionModal() {
    $('.health_status_box').removeClass('active');
    $('#health_status').val('');
    $('#health_status_comment').val('');
    $('#save_health_status_btn').prop('disabled', true);
    $('#health_status_comment_container').addClass('hide');
    if (saveHealthStatusRotate !== undefined && saveHealthStatusRotate !== null) {
        saveHealthStatusRotate.stop();
    }
}

function changeSmiley(smileyClass) {
    $('.feeling_condition').removeClass('feeling_very_good');
    $('.feeling_condition').removeClass('feeling_bad');
    $('.feeling_condition').removeClass('feeling_neutral');
    $('.feeling_condition').removeClass('feeling_good');
    $('.feeling_condition').removeClass('feeling_very_bad');
    $('.feeling_condition').addClass(smileyClass);
    $('#feeling_date_status').html(' Today ');
}

function applyHoverEffect() {
//    $('.hover_element').contenthover({
//        effect: 'slide',
//        slide_speed: 300,
//        overlay_x_position: 'right',
//        overlay_y_position: 'bottom'
//    });
}

function applySticky() {
    // make the sticky block in RHS
    if ($('#rhs .sticky').length > 0) {
        var navbarHeight = $('.navbar').height();
        var topSpacing = (navbarHeight * 1.25);
        $('#rhs .sticky').sticky({
            topSpacing: topSpacing
        });
    }
}

function applyChosen() {
    $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
}

/**
 * Function to initialise disease search autocomplete
 */
var diseaseCache = {};
function initDiseaseAutoComplete(element, minLength) {
    $(element).autocomplete({
        minLength: minLength,
        source: function(request, response) {
            if (typeof diseaseJSON === 'undefined') {
                var searchTerm = $.trim(request.term);
                var responseData;
                if (searchTerm in diseaseCache) {
                    var cacheData = diseaseCache[searchTerm];
                    responseData = getFilteredDiseaseSearchResult(cacheData);
                    response(responseData);
                }
                else {
                    $.ajax({
                        url: '/api/searchDisease',
                        dataType: 'json',
                        data: {
                            term: searchTerm
                        },
                        success: function(data) {
                            diseaseCache[searchTerm] = data;
                            responseData = getFilteredDiseaseSearchResult(data);
                            response(responseData);
                        }
                    });
                }
            }
            else {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                data = $.grep(diseaseJSON, function(item) {
                    if (matcher.test(item.label)) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id
                        };
                    }
                });
                responseData = getFilteredDiseaseSearchResult(data);
                response(responseData);
            }
        },
        select: function(event, ui) {
            if (ui.item) {
                var diseaseId = ui.item.id;
                var diseaseIdField = $(element).parent().find('.disease_id_hidden');
                diseaseIdField.val(diseaseId);
                $('#add_diagnosis').removeClass('plus_disabled');
                $('.disease_id_hidden').each(function() {
                    if ($(this).val() === '')
                        $('#add_diagnosis').addClass('plus_disabled');
                });
            }
        },
        search: function(event) {
            if (event.ctrlKey === true) {
                return false;
            }
            var diseaseIdField = $(element).parent().find('.disease_id_hidden');
            diseaseIdField.val('');
            $('#add_diagnosis').addClass('plus_disabled');
        },
        change: function() {
            var diseaseIdField = $(element).parent().find('.disease_id_hidden');
            var diseaseId = diseaseIdField.val();
            if (($('#communityWizard').length > 0) || ($('#eventWizard').length > 0)) {
                if (!(diseaseId > 0)) {
                    $(element).val('');
                }
            } else {
                if (!(diseaseId >= 0)) {
                    $(element).val('');
                }
            }
        },
        response: function(event, ui) {
            var noResultmsg = $(element).parent().find('.no_result_msg');
            if (ui.content.length === 0) {
                noResultmsg.show();
                var diseaseIdField = $(element).parent().find('.disease_id_hidden');
                diseaseIdField.val('');
                $(element).val('');
            } else {
                noResultmsg.hide();
            }
        }
    });
}

/**
 * Function to get the final disease search result after filtering out already 
 * selected diseases
 */
function getFilteredDiseaseSearchResult(diseaseData) {
    var selectedDiseases = $('input.disease_id_hidden').map(function() {
        if (this.value !== "") {
            return this.value;
        }
    }).get();

    var selectedDiseaseNames = $('input.disease_search').map(function() {
        if (this.value !== "" && $(this).next('.disease_id_hidden').val() != "") {
            return this.value;
        }
    }).get();

    return $.map(diseaseData, function(item) {
        var itemId = item.id.toString();
        var itemValue = item.value.toString();
        if ($.inArray(itemId, selectedDiseases) == -1 || (itemId == 0 && ($.inArray(itemValue, selectedDiseaseNames) == -1))) {
            return {
                label: item.label,
                value: item.value,
                id: item.id
            };
        }
    });
}

/**
 * Make the disease id field value empty, on pressing delete key in disease field
 */
$(document).on('keyup', '.disease_search', function(event) {
    var keyCode = event.which;
    if ((keyCode === 46) || (keyCode === 8)) {
        var diseaseIdField = $(this).parent().find('.disease_id_hidden');
        diseaseIdField.val('');
    }
});

// user login
$(document).on('submit', '#UserLoginForm', function() {
    var loginForm = $(this);
    var loginUrl = loginForm.attr('action');
    var loginData = loginForm.serialize();
    var loginBtn = $(this).find('button[type="submit"]');

    $.ajax({
        type: 'POST',
        url: loginUrl,
        data: loginData,
        dataType: 'json',
        beforeSend: function() {
            // disable multiple clicks
            loginBtn.attr('disabled', 'disabled');
        },
        success: function(result) {
            if (result.success === true) {
                window.location.href = result.redirectUrl;
            }
            else if (result.error === true) {
                hideAlerts();
                $("#UserPassword").val('');
                showLoginAlert('login_flash_error', result.message);
                loginBtn.removeAttr('disabled');
            }
        }
    });
    return false;
});

// resend activation email
$(document).on('click', '#resend_activation_mail_link', function() {
    var username = $(this).data('username');
    $.ajax({
        type: 'POST',
        url: '/user/register/resendActivationMail',
        data: {
            'username': username
        },
        dataType: 'json',
        beforeSend: function() {
            hideAlerts();
            showAlert('login_flash_warning', 'Resending activation email. Please wait...');
        },
        success: function(result) {
            hideAlerts();
            if (result.success === true) {
                showAlert('login_flash_success', result.message);
            }
            else if (result.error === true) {
                showAlert('login_flash_error', result.message);
            }
        }
    });
    return false;
});

// hide alert messages
function hideAlerts() {
    $('.alert .message').html('');
    $('.alert').hide();
}

// show alert message
function showAlert(id, message) {
    $('#' + id + ' .message').html(message);
    $('#' + id).show();
}

//show login alert message
function showLoginAlert(id, message) {
    if ($('#' + id).length) {
        $('#' + id + ' .message').html(message);
    } else {
        $('.signup_fields').prepend('<div id="login_flash_error" style="display: none;" class="alert alert-error">' +
                '<button data-dismiss="alert" class="close" type="button" aria-hidden="true">×</button>' +
                '<div class="message">Invalid username or password</div></div>');
    }
    $('#' + id).show();

}

/*
 * Function to generate the State list dropdown
 */
function getStateList(elem) {
    var related = $(elem).attr("data-rel");
    related = new String(related).split("#");
    $("." + related[1]).html('<option value="">Loading State/Province...</option>');
    $("." + related[2]).html('<option value="">Select City</option>');
    $("." + related[2]).attr('disabled', 'disabled');
    $.ajax({
        url: "/api/getCountryStates/" + $(elem).val(),
        dataType: "json"
    }).done(function(data) {
        $("." + related[1]).removeAttr("disabled");
        var output = [];
        $.each(data, function(i, item) {
            output.push('<option value="' + i + '">' + item + '</option>');
        });
        $("." + related[1]).html(output.join(''));
        $("." + related[1]).prepend("<option value='' selected='selected'>Select State/Province</option>");
        $(".chosen-select").trigger("liszt:updated");
    });
    handleCountryZipValidation($(elem));
}

/*
 * function to generate the City list Dropdowns
 */
function getCityList(elem) {
    var related = $(elem).attr("data-rel");
    related = related.split("#");
    $("." + related[2]).empty();
    $("." + related[2]).html('<option value="">Loading City...</option>');
    $("." + related[2]).attr("disabled");
    $.ajax({
        url: "/api/getStateCities/" + $(elem).val(),
        dataType: "json"
    }).done(function(data) {
        $("." + related[2]).removeAttr("disabled");
        var output = [];
        $.each(data, function(i, item) {
            output.push('<option value="' + i + '">' + item + '</option>');
        });
        $("." + related[2]).html(output.join(''));
        $("." + related[2]).prepend("<option value='' selected='selected'>Select City</option>");
        $(".chosen-select").trigger("liszt:updated");
    });
}

/**
 * Function to handle zip code validation based on selected country
 */
function handleCountryZipValidation(countryField) {
    var related = countryField.attr('data-rel');
    related = new String(related).split('#');
    var zipField = $("." + related[3]);
	var cityField = $("." + related[2]).val();
	var stateField = $("." + related[1]).val();

    var selectedCountryId = countryField.val();
	
    var zipValidationRegex = '';	
    $.each(countryZipRegexJSON, function(countryId, countryZipRegex) {
		 if (countryId === selectedCountryId) {			
            zipValidationRegex = '^' + countryZipRegex + '$';
            return false;
        }		
    });
    var zipFieldRedStar;
    if (zipField.parent('.form-group').length > 0) {
        zipFieldRedStar = zipField.parent('.form-group').find('.red_star_span');
    }
    else {
        zipFieldRedStar = zipField.parents('.form-group').find('.red_star_span');
    }

    if ((zipValidationRegex !== '') && (zipValidationRegex !== null)) {
        zipFieldRedStar.removeClass('hide');
        var errorMsg = 'Please enter a valid zip.';
		var errorMsg1 = "Invalid US Zipcode format";
		var errorMsg2 = "Please enter valid US zipcode";
		if (selectedCountryId === '233' && cityField !== '' && stateField !== '') {
			zipField.rules('remove', 'required regex');
			zipField.rules('add', {
				required: true,
				zipcodeUS: true,
				usZipCode: true,
				messages: {
					required: errorMsg,
					zipcodeUS: errorMsg1,
					usZipCode: errorMsg2
				}
			});
		}
		else {
			zipField.rules('remove', 'zipcodeUS usZipCode');
			zipField.rules('add', {
				required: true,
				regex: zipValidationRegex,
				messages: {
					required: errorMsg,
					regex: errorMsg
				}
			});
		}
//		zipField.valid();
    }
    else {
        zipFieldRedStar.addClass('hide');
        zipField.rules('remove', 'zipcodeUS usZipCode required regex');
        zipField.valid();
    }
}

/*
 * Function to invite friends to an event or a community
 * 
 * @param int id id of the event or community.
 * @param int invited_by id of inviting user.
 * @param int invite_type To determine invite to what event or community
 */
function inviteFriends(id, invited_by, invite_type) {
    $("#close_invite_button").attr("disabled", "disabled");
    var l = Ladda.create(document.querySelector('#invite_button'));
    l.start();
    var form = document.getElementById('invite_friends');
    var inputTags = form.getElementsByTagName('input');
    var checkboxCount = 0;
    var url = '';
    if (invite_type == '1') {
        url = "/api/eventInvites";
    } else if (invite_type == '2') {
        url = "/api/communityInvites";
    }
    for (var i = 0, length = inputTags.length; i < length; i++) {
        if (inputTags[i].type == 'checkbox') {
            checkboxCount++;
        }
    }
    users = [];
    for (var i = 0; i < checkboxCount; i++) {
        if ($("#friend" + i).is(':checked')) {
            users.push($("#friend" + i).val());
            $("#friend" + i).remove();
        }
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            'id': id,
            'users': users,
            'invited_by': invited_by
        },
        success: function(result) {
            result = JSON.parse(result);
            $('.select_all_check_box').prop("checked", false);
            $('#selected_count').html(0);
            $('#inviteFriends').modal('hide');
            showInnerAlert(result['message'], result['message_type']);
            $("#invite_button").prop("disabled", true);
            l.stop();
            var event_id = $("#event_id_hidden").val();
            if (!(typeof event_id === 'undefined')) {
                updateList(event_id);
            }
            $("#close_invite_button").removeAttr("disabled");
            location.reload();
        }
    });

}


$(document).on('keyup', '.search_widget_txt', function() {
    search_div = $(this).data("searchbox");
    if (search_div == 'invite_friends') {
        searchInList(friendList, this);
    } else if (search_div == 'friends_list') {
        searchInList(friendList, this);
    } else if (search_div == 'mutual-friends') {
        searchInList(mutualFriendsList, this);
    } else if (search_div == 'invite_patient_friend') {
        searchFriendsInList(patientFriends, this);
    } else if (search_div == 'invite_my_friends') {
        searchFriendsInList(myFriendList, this);
    } else if (search_div == 'invite_team_member') {
        searchFriendsInList(friendsToInvite, this);
    } else {
        searchInList(membersList, this);
    }
});

//Function for searching friends in invite friends list
function searchInList(list, search_key) {
    var search_text = "";
    var search_div = "";
    if (typeof search_key == "string") {
        search_text = search_key;
    } else {
        search_text = $(search_key).val();
        search_div = $(search_key).data("searchbox");
        search_div = "#" + search_div + " ";
    }

    $(search_div + "#none_found").addClass('hidden');
    var friendsShown = 0;
    var friendsTotal = 0; //Total number of friends
    var json = list;
    if (!$.isEmptyObject(json.friends.friend)) {
        $.each(json.friends.friend, function(i, v) {
            $(search_div + " #" + v.friend_id).addClass("hidden");
            friendsTotal++;
        });
        var search = (search_text).toLowerCase();
        $.each(json.friends.friend, function(i, v) {
            var name = (v.friend_name).toLowerCase();
            if (name.search(search) !== -1) {
                $(search_div + "#" + v.friend_id).removeClass("hidden");
                friendsShown++;
            } else {
                friendsShown--;
            }
        });
    }
    if ((friendsShown == -friendsTotal) || (friendsTotal == 0)) {
        $(search_div + "#none_found").removeClass('hidden');
    }
}

/*
 * Function to send invites to friends
 */
$(document).on('click', '.invite_frnds', function() {
    var id = $(this).attr("id");
    if ($('#' + id + ' input[type=checkbox]').is(":checked")) {
        $('#' + id + ' input[type=checkbox]').prop("checked", false);
        $("#" + id).removeClass('active');
    } else {
        $("#" + id + " input[type=checkbox]").prop("checked", true);
        $("#" + id).addClass('active');
    }
    inviteButtonStatus();
    handleSelectedBoxCountChange();
});

$(document).on('click', '.invite_community', function() {
    var id = $(this).attr("id");
    if ($('#' + id + ' input[type=checkbox]').is(":checked")) {
        $('#' + id + ' input[type=checkbox]').prop("checked", false);
        $("#" + id).removeClass('active');
    } else {
        $("#" + id + " input[type=checkbox]").prop("checked", true);
        $("#" + id).addClass('active');
    }
    inviteCommunityButtonStatus();
    handleCommunitySelectedBoxCountChange();
});

/*
 * Function to change invite friend status button
 */
function inviteButtonStatus() {
    var form = document.getElementById('invite_friends');
    var inputTags = form.getElementsByTagName('input');
    var checkbox = 0;
    if (!(inputTags.length === 0)) {
        for (var i = 0, length = inputTags.length; i < length; i++) {
            if (inputTags[i].type == 'checkbox') {
                if (inputTags[i].checked) {
                    checkbox++;
                }
                if (checkbox == 0) {
                    $("#invite_button").attr("disabled", "disabled");
                } else {
                    $("#invite_button").prop("disabled", false);
                }
            }
        }
    } else {
        $("#invite_button").attr("disabled", "disabled");
    }

}

function inviteCommunityButtonStatus() {
    var form = document.getElementById('invite_community_friends');
    var inputTags = form.getElementsByTagName('input');
    var checkbox = 0;
    if (!(inputTags.length === 0)) {
        for (var i = 0, length = inputTags.length; i < length; i++) {
            if (inputTags[i].type == 'checkbox') {
                if (inputTags[i].checked) {
                    checkbox++;
                }
                if (checkbox == 0) {
                    $("#invite_button").attr("disabled", "disabled");
                } else {
                    $("#invite_button").prop("disabled", false);
                }
            }
        }
    } else {
        $("#invite_button").attr("disabled", "disabled");
    }

}

function showInnerAlert(alert_msg, alert_type) {
    var alert_types = new Array("warning", "success", "danger");
    if ($.inArray(alert_type, alert_types)) {
        $.each(alert_types, function(index, value) {
            $("#header-alert").removeClass("alert-" + value);
        });
        $("#header-alert").addClass("alert-" + alert_type);
        $("#header-alert .alert-content").html(alert_msg);
        $("#header-alert").show();
        setTimeout(function() {
            $("#header-alert").hide();
        }, 5000);
    }

}

function showMemberAlert(alert_msg, alert_type) {
    var alert_types = new Array("warning", "success", "danger");
    if ($.inArray(alert_type, alert_types)) {
        $.each(alert_types, function(index, value) {
            $("#group-section-alert").removeClass("alert-" + value);
        });
        $("#group-section-alert").addClass("alert-" + alert_type);
        $("#group-section-alert .alert-content").append(alert_msg + "<br/>");
        $("#group-section-alert").show();
    }

}


// Function for advanced search
$('#advance_search').click(function(e) {

    if ($(this).hasClass("advance_search_closed")) {
        $("#advanced_form").slideDown();
        $(this).removeClass("advance_search_closed");
        $(this).addClass("advance_search_open");
    } else {
        $("#advanced_form").slideUp();
        $(this).removeClass("advance_search_open");
        $(this).addClass("advance_search_closed");
		$('#advanced_flash_error').hide();
    }

});

// Function to perform advanced serach
$("#keywords_find").click(function(event) {
    $('.event_list #load-more').parent().remove();
    if ($('.advance_search_header #advanced_flash_error').length == 0) {
        var error_div = '<div class="alert alert-error" style="color: red;" id="advanced_flash_error">'
                + '<button aria-hidden="true" type="button" class="close" data-dismiss="alert">×</button>'
                + '<div class="message">Please enter any one keyword</div></div>';
        $('.advance_search_header').prepend(error_div)
    }
	$('#advanced_flash_error').hide();

	var searchData = getAdvancedSearchData();
	if (!$.isEmptyObject(searchData)) {
        $loadingImg = '<img src="/img/loading.gif" /> ';
        $("#advanced_form").slideUp();
        $('#advance_search').removeClass("advance_search_open");
        $('#advance_search').addClass("advance_search_closed");
        $('#searchList').html($loadingImg);
		searchData['ajax'] = true;
		searchData['type'] = 'people';
		$.ajax({
			url: '/search/search/index/page:' + 1,
			data: searchData,
            dataType: 'json',
            success: function(result) {
                $('.event_wraper').removeClass("no-border");
                if (result.paginator.count === 1)
                    $('.advance_search_header p.pull-left').html(result.paginator.count + " result");
                else
                    $('.advance_search_header p.pull-left').html(result.paginator.count + " results");
                $('#searchList').html(result.htm_content);
                if ($('#searchList .friends_list').length === 0) {
                    $('#searchList').html('<div class="friends_list"><div class="text-center friends_noresult_padding">Sorry, no results containing all your search terms were found.</div></div>');
                }

                if (result.paginator.nextPage == true) {
                    $('#searchPageList').append('<div id="more_button' + (result.paginator.page + 1) + '" class="block">' +
                            '<a href="javascript:load_more_items_advanced(' + (result.paginator.page + 1) + ')" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a>' +
                            '</div>');
                }
                window.scrollTo(0, 0);
            }
        });
    } else {
        $('.event_list #load-more').parent().remove();
        showAlert('advanced_flash_error', 'Please enter any one keyword');
        $('#searchList').html('');
        $('.advance_search_header p.pull-left').html('');
        $('.event_wraper').addClass("no-border");
        window.scrollTo(0, 0);
    }
});

/**
 * Add more diagnosis
 */
var add_diagnosis_clicked = false;
$(document).on('click', '#add_diagnosis', function() {
    if (!add_diagnosis_clicked) {
        add_diagnosis_clicked = true;
        setTimeout(function() {
            add_diagnosis_clicked = false;
        }, 1000);
        if (!$(this).hasClass('plus_disabled')) {
            var diagnosisLastIndex = $('#diagnosis_index').val();
            var diagnosisNewIndex = parseInt(diagnosisLastIndex) + 1;
            $.ajax({
                type: 'POST',
                url: '/search/search/getDiagnosisForm',
                data: {'index': diagnosisNewIndex},
                beforeSend: function() {
                },
                success: function(result) {
                    $('#advanced_diagnosis_container').append(result);
                    $('#diagnosis_index').val(diagnosisNewIndex);
                    $('#add_diagnosis').addClass('plus_disabled');
                }
            });
        }
    } else {
        return false;
    }
});

/**
 * Add more symptoms
 */
var add_symptoms_clicked = false;
$(document).on('click', '#add_symptoms', function() {
    if (!add_symptoms_clicked) {
        add_symptoms_clicked = true;
        setTimeout(function() {
            add_symptoms_clicked = false;
        }, 1000);
        if (!$(this).hasClass('plus_disabled')) {
            var symptomsLastIndex = $('#symptoms_index').val();
            var symptomsNewIndex = parseInt(symptomsLastIndex) + 1;
            $.ajax({
                type: 'POST',
                url: '/search/search/getSymptomsForm',
                data: {'index': symptomsNewIndex},
                beforeSend: function() {
                },
                success: function(result) {
                    $('#advanced_symptoms_container').append(result);
                    $('#symptoms_index').val(symptomsNewIndex);
                    $('#add_symptoms').addClass('plus_disabled');
                }
            });
        }
    } else {
        return false;
    }
});


$('#keyword_male, #keyword_female').change(function() {
    if ($("#keyword_male").is(':checked') && $("#keyword_female").is(':checked'))
        $("#keyword_gender").val("B");
    else if ($("#keyword_male").is(':checked'))
        $("#keyword_gender").val("M");
    else if ($("#keyword_female").is(':checked'))
        $("#keyword_gender").val("F");
    else
        $("#keyword_gender").val("");
});


// Function to clear advanced search form
$('#keywords_clear').click(function(e) {
    $('#advanced_location_container li.token').remove();
    $('#advanced_diagnosis_container li.token').remove();
    $('#advanced_treatment_container li.token').remove();
    $('#advanced_symptoms_container li.token').remove();
    $('.advance_search :input').val('');
    $('.advance_search input:checkbox').each(function() {
        if ($(this).is(':checked'))
            $(this).attr("checked", false);
    });
});

// Function for select the search category
$('.event_search .dropdown-menu li a').click(function(e) {

    $("#search_icons").removeClass($("#search_icons").attr("class"));
    $("#search_icons").addClass("search_icons " + $(this).data("classname"));

    $('.event_search .dropdown-menu li a').removeClass('search_list_active');
    $(this).addClass('search_list_active');

});

/*
 * Show hovercard for the user profile
 */

var el;
var showOneCard = false;
var showCard;

$(document).ready(function() {

    function showHoverCard(el)
    {
        $("input, textarea").focusout();
        $("input, textarea").blur();
        if (el.data('hovercard') != "")
        {
			if($('textarea#PostQuestion').length > 0){
				$('textarea#PostQuestion').blur();
			}
            var hovercard_url = "/profile/" + encodeURIComponent(el.data('hovercard')) + "/hovercard";
            $.ajax({
                url: hovercard_url,
                cache: false,
                type: 'GET',
                success: function(response) {
                    el.attr("data-content", response);
                    el.popover({
                        content: response,
                        template: '<div class="' + el.data('hovercard') + ' popover profile_hovercard"><div class="arrow">' +
                                '</div><div class="popover-inner">' +
                                '<div class="popover-content"><p></p></div></div></div>',
                        html: true,
                placement: function(tip, element) {
					showOneCard = true;
                    var $element, above, actualHeight, actualWidth, below, boundBottom, boundLeft, boundRight, boundTop, elementAbove, elementBelow, elementLeft, elementRight, isWithinBounds, left, pos, right;
                    isWithinBounds = function(elementPosition) {
                      return boundTop < elementPosition.top && boundLeft < elementPosition.left && boundRight > (elementPosition.left + actualWidth) && boundBottom > (elementPosition.top + actualHeight);
                    };
                    $element = $(element);
                    pos = $.extend({}, $element.offset(), {
                      width: element.offsetWidth,
                      height: element.offsetHeight
                    });
                    actualWidth = 100;
                    actualHeight = 200;
                    boundTop = $(document).scrollTop()+$('.top_header .navbar').height();
                    boundLeft = $(document).scrollLeft();
                    boundRight = boundLeft + $('.main_container .container').width();;
                    boundBottom = boundTop + $(window).height()-$('.top_header .navbar').height();
                    elementAbove = {
                      top: pos.top - actualHeight,
                      left: pos.left + pos.width / 2 - actualWidth / 2
                    };
                    elementBelow = {
                      top: pos.top + pos.height,
                      left: pos.left + pos.width / 2 - actualWidth / 2
                    };
                    elementLeft = {
                      top: pos.top + pos.height / 2 - actualHeight / 2,
                      left: pos.left - actualWidth
                    };
                    elementRight = {
                      top: pos.top + pos.height / 2 - actualHeight / 2,
                      left: pos.left + pos.width
                    };
                    above = isWithinBounds(elementAbove);
                    below = isWithinBounds(elementBelow);
                    left = isWithinBounds(elementLeft);
                    right = isWithinBounds(elementRight);
                    if (above) {
                      return "top";
                    } else {
                      if (below) {
                        return "bottom";
                      } else {
                        if (left) {
                          return "left";
                        } else {
                          if (right) {
                            return "right";
                          } else {
                            return "right";
                          }
                        }
                      }
                    }
                  },
                    container: 'body'
                    }).popover("show")
                            .on('mouseleave', function() {
                                var _this = this;
                                removeShowCard = setTimeout(function() {
                                    if ($(".popover:hover").length == 0) {
                                        $(_this).popover("destroy");
                }
                                }, 500);
                                if ($(".popover:hover").length == 1) {

                                    clearTimeout(removeShowCard);
                                }
                            }).on("mouseenter", function() {
                        var _this = this;
                        $(".popover").on("mouseleave", function() {
                            setTimeout(function() {
                                $(_this).popover('hide');
                            }, 500);
            });
                    });
                    $(".popover").each(function(i, obj) {
                        if (!($(obj).hasClass(el.data('hovercard')))) {
                            $(obj).remove();
                        }
                    });
                }
            });




        }
    }
	
	/*
     * Show the hovercard
     */
	$(document).hoverIntent({
		over: function(){ 
			if(showOneCard == false){
				showCard = showHoverCard($(this));
			}
		},
		out: function() {
			if ($(".popover:hover").length == 0) {
				$('.popover').remove();
				showOneCard = false;
			}
		},
		selector: '[data-hovercard]',
		interval: 50
	});
    
    /*
     * Hide the hovercard
     */
    var removeShowCard;
    $(document).on("mouseleave", ".popover", function() {
        removeShowCard = setTimeout(function() {
            $('.profile_hovercard').remove();
        }, 300);
        $(document).on("mouseenter", ".profile_hovercard", function() {
            clearTimeout(removeShowCard);
        });
    });

    /*
     * Hide the hovercard on clicking anywhere other than hovercard
     */
    $("body").on('click', function(event) {
        if ($(event.target).closest(".popover").length == 0)
            $('.profile_hovercard').remove();

        // hide bootbox dialogs on clicking outside
        $('.video_modal').modal('hide');
});

    $(window).scroll(function() {
        clearTimeout(showCard);
        $('.profile_hovercard').remove();
    });

    $('.slimScrollDiv').scroll(function() {
        clearTimeout(showCard);
        $('.popover').remove();
    });

});

function removeBgColor() {
    $("#profile_icon_container").css("background-color", "#2c589e;");//unselected blue
}
function addBgColor() {

    if ($("#profile_icon_container").children('div.open').length > 0) {
        $("#profile_icon_container").css("background-color", "#2c589e;");
    } else {
        $("#profile_icon_container").css("background-color", "#1e4687");//selected blue
    }
}

// function to autocomplete location in advanced search
var locationCache = {};
function initLocationAutoComplete(element, minLength) {
    $(element).autocomplete({
        minLength: minLength,
        source: function(request, response) {
            if (typeof locationJSON === 'undefined') {
                var searchTerm = request.term;
                var responseData;
                if (searchTerm in locationCache) {
                    var cacheData = locationCache[searchTerm];
                    responseData = getFilteredLocationSearchResult(cacheData);
                    response(responseData);
                }
                else {
                    $.ajax({
                        url: '/api/searchLocation',
                        dataType: 'json',
                        data: {
                            term: searchTerm
                        },
                        success: function(data) {
                            locationCache[searchTerm] = data;
                            responseData = getFilteredLocationSearchResult(data);
                            response(responseData);
                        }
                    });
                }
            }
            else {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                data = $.grep(locationJSON, function(item) {
                    if (matcher.test(item.label)) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id
                        };
                    }
                });
                responseData = getFilteredLocationSearchResult(data);
                response(responseData);
            }
        },
        select: function(event, ui) {
            if (ui.item) {
                var locationId = ui.item.id;
                var locationIdField = $(element).parent().find('.location_id_hidden');
                locationIdField.val(locationId);
                $('#add_location').removeClass('plus_disabled');
                $('.location_id_hidden').each(function() {
                    if ($(this).val() === '')
                        $('#add_location').addClass('plus_disabled');
                });

            }
        },
        search: function(event) {
            if (event.ctrlKey === true) {
                return false;
            }
            var locationIdField = $(element).parent().find('.location_id_hidden');
            locationIdField.val('');
            $('#add_location').addClass('plus_disabled');
        },
        change: function() {
            var locationIdField = $(element).parent().find('.location_id_hidden');
            var locationId = locationIdField.val();
            if (!(locationId > 0)) {
                $(element).val('');
            }
        },
        response: function(event, ui) {
            var noResultmsg = $(element).parent().find('.no_result_msg');
            if (ui.content.length === 0) {
                noResultmsg.show();
                var locationIdField = $(element).parent().find('.location_id_hidden');
                locationIdField.val('');
                $(element).val('');
            } else {
                noResultmsg.hide();
            }
        }
    });
}

/**
 * Function to get the final Location search result after filtering out already 
 * selected Locations
 */
function getFilteredLocationSearchResult(locationData) {
    var selectedLocations = $('input.location_id_hidden').map(function() {
        if (this.value !== "") {
            return this.value;
        }
    }).get();

    return $.map(locationData, function(item) {
        var itemId = item.id.toString();
        if ($.inArray(itemId, selectedLocations) == -1) {
            return {
                label: item.label,
                value: item.value,
                id: item.id
            };
        }
    });
}

/**
 * Add more location for advanced search
 */
var add_location_clicked = false;
$(document).on('click', '#add_location', function() {
    if (!add_location_clicked) {
        add_location_clicked = true;
        setTimeout(function() {
            add_location_clicked = false;
        }, 1000);
        if (!$(this).hasClass('plus_disabled')) {
            var locationLastIndex = $('#location_index').val();
            var locationNewIndex = parseInt(locationLastIndex) + 1;
            $.ajax({
                type: 'POST',
                url: '/search/search/getLocationForm',
                data: {'index': locationNewIndex},
                beforeSend: function() {
                },
                success: function(result) {
                    $('#advanced_location_container').append(result);
                    $('#location_index').val(locationNewIndex);
                    $('#add_location').addClass('plus_disabled');
                }
            });
        }
    } else {
        return false;
    }
});

// function to autocomplete symptom in advanced search
var symptomCache = {};
function initSymptomAutoComplete(element, minLength) {
    $(element).autocomplete({
        minLength: minLength,
        source: function(request, response) {
            if (typeof symptomJSON === 'undefined') {
                var searchTerm = request.term;
                var responseData;
                if (searchTerm in symptomCache) {
                    var cacheData = symptomCache[searchTerm];
                    responseData = getFilteredSymptomSearchResult(cacheData);
                    response(responseData);
                }
                else {
                    $.ajax({
                        url: '/api/searchSymptom',
                        dataType: 'json',
                        data: {
                            term: searchTerm
                        },
                        success: function(data) {
                            symptomCache[searchTerm] = data;
                            responseData = getFilteredSymptomSearchResult(data);
                            response(responseData);
                        }
                    });
                }
            }
            else {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                data = $.grep(symptomJSON, function(item) {
                    if (matcher.test(item.label)) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id
                        };
                    }
                });
                responseData = getFilteredSymptomSearchResult(data);
                response(responseData);
            }
        },
        select: function(event, ui) {
            if (ui.item) {
                var symptomId = ui.item.id;
                var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
                symptomIdField.val(symptomId);
                $('#add_symptoms').removeClass('plus_disabled');
                $('.symptoms_id_hidden').each(function() {
                    if ($(this).val() === '')
                        $('#add_symptoms').addClass('plus_disabled');
                });
            }
        },
        search: function(event) {
            if (event.ctrlKey === true) {
                return false;
            }
            var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
            symptomIdField.val('');
            $('#add_symptoms').addClass('plus_disabled');
        },
        change: function() {
            var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
            var symptomId = symptomIdField.val();
            if (!(symptomId > 0)) {
                $(element).val('');
            }
        },
        response: function(event, ui) {
            var noResultmsg = $(element).parent().find('.no_result_msg');
            if (ui.content.length === 0) {
                noResultmsg.show();
                var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
                symptomIdField.val('');
                $(element).val('');
            } else {
                noResultmsg.hide();
            }
        }
    });
}

/**
 * Function to get the final symptom search result after filtering out already 
 * selected symptoms
 */
function getFilteredSymptomSearchResult(symptomData) {
    var selectedSymptoms = $('input.symptoms_id_hidden').map(function() {
        if (this.value !== "") {
            return this.value;
        }
    }).get();

    return $.map(symptomData, function(item) {
        var itemId = item.id.toString();
        if ($.inArray(itemId, selectedSymptoms) == -1) {
            return {
                label: item.label,
                value: item.value,
                id: item.id
            };
        }
    });
}

// function to autocomplete symptom in user symptom add
var symptomAddCache = {};
function initSymptomAddAutoComplete(element, minLength) {
    $(element).autocomplete({
        minLength: minLength,
        source: function(request, response) {
            if (typeof symptomJSON === 'undefined') {
                var searchTerm = $.trim(request.term);
                var responseData;
                var noResultmsg = $(element).parent().find('.no_result_msg');
                noResultmsg.hide();
                if (searchTerm in symptomAddCache) {
                    var cacheData = symptomAddCache[searchTerm];
                    responseData = getFilteredAddSymptomSearchResult(cacheData);
                    response(responseData);
                }
                else {
                    $('#symptom_error_message').html('');
                    $.ajax({
                        url: '/user/api/searchNewSymptom',
                        dataType: 'json',
                        data: {
                            term: searchTerm
                        },
                        success: function(data) {
                            symptomCache[searchTerm] = data;
                            responseData = getFilteredAddSymptomSearchResult(data);
                            response(responseData);
                        }
                    });
                }
            }
            else {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                data = $.grep(symptomJSON, function(item) {
                    if (matcher.test(item.label)) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id
                        };
                    }
                });
                responseData = getFilteredAddSymptomSearchResult(data);
                response(responseData);
            }
        },
        open: function() {
            $('.ui-menu').width(300)
        },
        select: function(event, ui) {
            if (ui.item) {
                var symptomId = ui.item.id;
                var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
                symptomIdField.val(symptomId);
                $('#add_symptoms').removeClass('plus_disabled');
                $('.symptoms_id_hidden').each(function() {
                    if ($(this).val() === '')
                        $('#add_symptoms').addClass('plus_disabled');
                });
            }
        },
        search: function(event) {
            if (event.ctrlKey === true) {
                return false;
            }
            var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
            symptomIdField.val('');
            $('#add_symptoms').addClass('plus_disabled');
        },
        change: function() {
            var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
            var symptomId = symptomIdField.val();
            if (!(symptomId)) {
                $(element).val('');
            }
        },
        response: function(event, ui) {
            var noResultmsg = $(element).parent().find('.no_result_msg');
            if (ui.content.length === 0) {
                noResultmsg.show();
                var symptomIdField = $(element).parent().find('.symptoms_id_hidden');
                symptomIdField.val('');
//                $(element).val('');
            } else {
                noResultmsg.hide();
            }
        }
    });
}

/**
 * Function to get the final symptom search result after filtering out already 
 * selected symptoms
 */
function getFilteredAddSymptomSearchResult(symptomData) {
    var selectedSymptoms = $('input.symptoms_id_hidden').map(function() {
        if (this.value !== "") {
            return this.value;
        }
    }).get();

    return $.map(symptomData, function(item) {
        var itemId = item.id.toString();
        if ($.inArray(itemId, selectedSymptoms) == -1) {
            return {
                label: item.label,
                value: item.value,
                id: item.id
            };
        }
    });
}
/**
 * Function to get the final Location search result after filtering out already 
 * selected Locations
 */
function getFilteredLocationSearchResult(locationData) {
    var selectedLocations = $('input.location_id_hidden').map(function() {
        if (this.value !== "") {
            return this.value;
        }
    }).get();

    return $.map(locationData, function(item) {
        var itemId = item.id.toString();
        if ($.inArray(itemId, selectedLocations) == -1) {
            return {
                label: item.label,
                value: item.value,
                id: item.id
            };
        }
    });
}

/**
 * Add more treatment for advanced search
 */
var add_treatment_clicked = false;
$(document).on('click', '#add_treatment', function() {
    if (!add_treatment_clicked) {
        add_treatment_clicked = true;
        setTimeout(function() {
            add_treatment_clicked = false;
        }, 1000);
        if (!$(this).hasClass('plus_disabled')) {
            var treatmentLastIndex = $('#treatment_index').val();
            var treatmentNewIndex = parseInt(treatmentLastIndex) + 1;
            $.ajax({
                type: 'POST',
                url: '/search/search/getTreatmentForm',
                data: {'index': treatmentNewIndex},
                beforeSend: function() {
                },
                success: function(result) {
                    $('#advanced_treatment_container').append(result);
                    $('#treatment_index').val(treatmentNewIndex);
                    $('#add_treatment').addClass('plus_disabled');
                }
            });
        }
    } else {
        return false;
    }
});

// function to autocomplete treatment in advanced search
var treatmentCache = {};
function initTreatmentAutoComplete(element, minLength) {
    $(element).autocomplete({
        minLength: minLength,
        source: function(request, response) {
            if (typeof treatmentJSON === 'undefined') {
                var searchTerm = request.term;
                var responseData;
                if (searchTerm in treatmentCache) {
                    var cacheData = treatmentCache[searchTerm];
                    responseData = getFilteredTreatmentSearchResult(cacheData);
                    response(responseData);
                }
                else {
                    $.ajax({
                        url: '/api/searchTreatment',
                        dataType: 'json',
                        data: {
                            term: searchTerm
                        },
                        success: function(data) {
                            treatmentCache[searchTerm] = data;
                            responseData = getFilteredTreatmentSearchResult(data);
                            response(responseData);
                        }
                    });
                }
            }
            else {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                data = $.grep(treatmentJSON, function(item) {
                    if (matcher.test(item.label)) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id
                        };
                    }
                });
                responseData = getFilteredTreatmentSearchResult(data);
                response(responseData);
            }
        },
        select: function(event, ui) {
            if (ui.item) {
                var treatmentId = ui.item.id;
                var treatmentIdField = $(element).parent().find('.treatment_id_hidden');
                treatmentIdField.val(treatmentId);
                $('#add_treatment').removeClass('plus_disabled');
                $('.treatment_id_hidden').each(function() {
                    if ($(this).val() === '')
                        $('#add_treatment').addClass('plus_disabled');
                });
            }
        },
        search: function(event) {
            if (event.ctrlKey === true) {
                return false;
            }
            var treatmentIdField = $(element).parent().find('.treatment_id_hidden');
            treatmentIdField.val('');
            $('#add_treatment').addClass('plus_disabled');
        },
        change: function() {
            var treatmentIdField = $(element).parent().find('.treatment_id_hidden');
            var treatmentId = treatmentIdField.val();
            if (!(treatmentId > 0)) {
                $(element).val('');
            }
        },
        response: function(event, ui) {
            var noResultmsg = $(element).parent().find('.no_result_msg');
            if (ui.content.length === 0) {
                noResultmsg.show();
                var treatmentIdField = $(element).parent().find('.treatment_id_hidden');
                treatmentIdField.val('');
                $(element).val('');
            } else {
                noResultmsg.hide();
            }
        }
    });
}

/**
 * Function to get the final treatment search result after filtering out already 
 * selected treatments
 */
function getFilteredTreatmentSearchResult(treatmentData) {
    var selectedTreatments = $('input.treatment_id_hidden').map(function() {
        if (this.value !== "") {
            return this.value;
        }
    }).get();

    return $.map(treatmentData, function(item) {
        var itemId = item.id.toString();
        if ($.inArray(itemId, selectedTreatments) == -1) {
            return {
                label: item.label,
                value: item.value,
                id: item.id
            };
        }
    });
}

/*
 * Pending friends requests Notificatoin icon click. 
 */
$(document).on('click', '#frineds_notification_icon', function() {
    getFriendNotificationUpdates();
    loadPeopleMayKnowNotificationsAjax();
});

/*
 * mesage Notificatoin icon click. 
 */
$(document).on('click', '#message_notification_icon', function() {
    getMessageNotificationUpdates();
});

/*
 * load notifications on clicking alarm icon
 */
var totalNotificationCount = 0;
$(document).on('click', '#alarm_notification_icon', function() {
    var countHolder = $("#unread_notification_count");
    var notificationCount = countHolder.html();
    if (notificationCount === '' || notificationCount === null) {
        notificationCount = 0;
    }
    else {
        notificationCount = parseInt(notificationCount);
    }
    countHolder.html('').removeClass("visible").addClass("hidden");
    var appendNotifications = (totalNotificationCount > 0) ? true : false;
    if ((appendNotifications === false) || (notificationCount > 0)) {
        totalNotificationCount = totalNotificationCount + notificationCount;
        if (appendNotifications === true) {
            var notificationLoader = '<li id="notification_loader_item"><center><img class="notification_loader" width="30" height="30" src="/img/loader.gif" alt="Loading..."></center></li>';
            $('ul#notification_list_container ul.notification_scroll').prepend(notificationLoader);
        }
        $.ajax({
            data: {
                'limit': notificationCount,
                'append': appendNotifications
            },
            url: '/notification/notification/getNotifications',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                clearNotificationCount('unread_notification_count');
                $('#notification_loader_item').remove();
                if (appendNotifications === true) {
                    $('ul#notification_list_container ul.notification_scroll').prepend(data.html_content);
                }
                else {
                    $("#notification_container ul#notification_list_container").html(data.html_content);
                }
                applySlimScrollToNotification();
                $("#notification_container ul").addClass("open");
            }
        });
    }

    var moreLink = $('#notification_list_container a.more');
    if (totalNotificationCount > 0) {
        moreLink.removeClass("hidden");
    } else {
        moreLink.addClass("hidden");
    }
});

/**
 * Function to clear notification count in all tabs
 * 
 * @param {String} notificationName
 * @returns {void}
 */
function clearNotificationCount(notificationName) {
    socket.emit('notify_user', {
        'user_id': app.loggedInUserId,
        'notification_name': notificationName,
        'notification_count': 0
    });
}

function applySlimScrollToNotification() {
    if ($(".notification_scroll li").length > 4) {
        $(".notification_scroll").slimScroll({
            color: '#BBDAEC',
            railColor: '#EBF5F7',
            size: '12px',
            height: '310px',
            railVisible: true
        });
        $(".notification_scroll").parent().css({'margin-top': '-20px'});
    } else {
        $("#notification_container ul ul").css({'margin-top': '-20px'});
    }
}

/**
 * Redirect to notification detail page on clicking a notification item
 */
$(document).on('click', '#notification_list_container .notfctn_item', function() {
    if ($(this).attr('data-href') !== '') {
        var url = $(this).attr('data-href');
        if ($(this).hasClass('disabled') || $(this).hasClass('popup')) {
            if ($(this).hasClass('unread')) {
                var selectedItem = $(this);
                $.post(url, function() {
                    selectedItem.removeClass('unread').addClass('read');
                    var notificationCountHolder = $("#unread_notification_count");
                    if (notificationCountHolder.length > 0) {
                        var notificationCount = parseInt(notificationCountHolder.html());
                        notificationCount = notificationCount - 1;
                        if (notificationCount > 0) {
                            notificationCountHolder.html(notificationCount);
                        } else {
                            notificationCountHolder.removeClass('visible').addClass('hidden');
                        }
                    }
                });
            }
        }
        else {
            window.location.href = url;
        }
    }
    if ($(this).find('form.additional_info_frm').length > 0) {
        var form = $(this).find('form.additional_info_frm');
        var activityType = form.find('.activity_type').val();
        if (activityType === 'medication_reminder') {
            $('.notifications_container').removeClass('open');
            showMedicationNotificationInfoPopUp(form);
        }
    }
});

/**
 * Function to show medication info in a pop up
 */
function showMedicationNotificationInfoPopUp($form) {
    $modal = $('#medication_info_dialog');
    var fields = ['name', 'dose', 'form', 'amount', 'route'];
    $.each(fields, function(index, field) {
        $field = $form.find('input[name="' + field + '"]');
        $modalFieldRow = $modal.find('tr.' + field);
        $modalField = $modalFieldRow.find('td.value');
        if ($field.length > 0) {
            var fieldValue = $field.val();
            $modalField.html(fieldValue);
            $modalFieldRow.show();
        }
        else {
            $modalField.html('');
            $modalFieldRow.hide();
        }
    });
    $modal.modal('show');
}

$(document).on('click', '#message_notification_container li.message_ntfcn_content', function() {
    var other_user_id = $(this).children("#other_user_id_hidden").val();
    window.location.href = "/message/details/index/" + other_user_id;
});

function loadFriendRequestsNotificationsAjax(callback) {
    var result = null;
    $.ajax({
        url: '/notification/notification/getFriendRequestsNotifications',
        type: 'POST',
        dataType: 'json',
        success: function(data) {

            result = data.notification_counts;
            $("#frineds_notification_container ul .friends_notification_loader").hide();
            $("#frineds_notification_container ul#frineds_notification_list_container li.pending_rquests_notification, .pending_rquests_notification_header").remove();
            $("#frineds_notification_container ul#frineds_notification_list_container").prepend(data.html_content);
            $("#frineds_notification_container ul").addClass("open");
            callback(result);
        }
    });

}
function loadMessageNotificationAjax(callback) {
    var result = null;
    $.ajax({
        url: '/notification/notification/getMessageNotifications',
        type: 'POST',
        dataType: 'json',
        success: function(data) {

            result = data.notification_counts;
            $("#message_notification_container ul .message_notification_loader").hide();
//            $("#message_notification_container ul#message_notification_list_container li.pending_rquests_notification, .pending_rquests_notification_header").remove();
            $("#message_notification_container ul#message_notification_list_container").html(data.html_content);
            applySlimScrollToMessageNotification();
            $("#message_notification_container ul").addClass("open");
            callback(result);
        }
    });

}
function loadPeopleMayKnowNotificationsAjax() {
    $.ajax({
        url: '/notification/notification/getPeopleMayKnowNotifications',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            $("#frineds_notification_container ul#frineds_notification_list_container li.people_mayknow_notification, .people_mayknow_notification_header").remove();
            $("#frineds_notification_container ul#frineds_notification_list_container").append(data.html_content);
            $("#frineds_notification_container ul").addClass("open");
        }
    });
}
$(document).on('click', '.dropdown-menu', function(e) {
    $(this).hasClass('keep_open') && e.stopPropagation();
});

function getMessageNotificationUpdates() {
    loadMessageNotificationAjax(function(notification_counts) {
        var element = $("#unread_message_count");
        element.html(notification_counts);
        if (notification_counts > 0) {
            element.removeClass("hidden").addClass("visible");
        } else {
            element.removeClass("visible").addClass("hidden");
        }
    });
}
function getFriendNotificationUpdates() {
    loadFriendRequestsNotificationsAjax(function(notification_counts) {
        var element = $("#pending_friend_requests_count");
        element.html(notification_counts);
        if (notification_counts > 0) {
            element.removeClass("hidden").addClass("visible");
        } else {
            element.removeClass("visible").addClass("hidden");
        }
    });
}
function updateAllNotificationCounts(result) {
    $.each(result.data, function(key, value) {
        updateNotificationCount(value);
    });
}
function updateNotificationCount(value) {
    var element = $("#" + value.notification_name);
    if (element.length > 0 && parseInt(element.html()) != value.notification_count) {
        element.html(value.notification_count);
        updateNotificationsMouseHoverTitle(value);
        if (value.notification_count > 0) {
            element.removeClass("hidden").addClass("visible");
            if (element.closest('.notifications_container').find('ul').hasClass('open')) {
                updateAllNotificationsLilst(value.notification_name);
            }
        } else {
            element.closest('ul').removeClass('open');
            element.removeClass("visible").addClass("hidden");
        }
    }
}
function updateAllNotificationsLilst(list_id) {
    if (list_id != null) {
        switch (list_id) {
            case 'pending_friend_requests_count':
                getFriendNotificationUpdates();
                break;
        }
    }
}
function updateNotificationsMouseHoverTitle(value) {
    if (value.notification_name !== null) {
        switch (value.notification_name) {
            case 'unread_message_count':
                if (value.notification_count > 0) {
                    $('.message_icon_dashboard').attr("title", "Messages");
                }
                else {
                    $('.message_icon_dashboard').attr("title", "No new messages");
                }
                break;
            case 'unread_notification_count':
                if (value.notification_count > 0) {
                    $('#alarm_notification_icon').attr("title", "Reminders");
                }
                else {
                    $('#alarm_notification_icon').attr("title", "No active reminders");
                }
                break;

        }
    }
}
function updateFriendNotificationListRow(notification_list_class) {
    var replace_element = null;
    var hidden_lists = null;
    var responded_lists = $("#frineds_notification_list_container ." + notification_list_class + " :disabled");

    responded_lists.each(function() {
        hidden_lists = $('#frineds_notification_list_container .'
                + notification_list_class + ':hidden');
        if (hidden_lists.length > 0) {
            replace_element = hidden_lists.first();
            replace_element.removeClass('hidden');
            $(this).closest('li').replaceWith(replace_element);
        } else {
            loadPeopleMayKnowNotificationsAjax();
        }
    });

}

function applySlimScrollToMessageNotification() {
    if ($(".message_notification_scroll li").length > 4) {
        $(".message_notification_scroll").slimScroll({
            color: '#BBDAEC',
            railColor: '#EBF5F7',
            size: '12px',
            height: 'auto',
            railVisible: true
        });
        $(".message_notification_scroll").parent().css({'margin-top': '-20px'});
    } else {
        $("#message_notification_container ul ul").css({'margin-top': '-20px'});
    }

}
function updateNotificationCounts() {
    $.ajax({
        url: '/notification/notification/getNotificationCounts',
        type: 'POST',
        dataType: 'json',
        success: function(result) {
            updateAllNotificationCounts(result);
        }
    });
}

function getVimeoEmbedCode(videoId, width, height) {
    var $playerUrl = 'https://player.vimeo.com/video/%VIDEO_ID%?autoplay=1';
    var $embedCode = '<iframe src="' + $playerUrl + '" width="%WIDTH%" height="%HEIGHT%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    var $output = $embedCode.replace('%VIDEO_ID%', videoId);
    $output = $output.replace('%WIDTH%', width);
    $output = $output.replace('%HEIGHT%', height);
    return $output;
}

function playNotificationMusic() {
	var notification_audio = document.getElementById("notification_audio");
    var notification_audio_ie8 = document.getElementById("notification_audio_ie8");
    if (Modernizr.audio) {
		notification_audio.play();
    } else {
        notification_audio_ie8.controls.play();
    }
}

$(document).on('click', '.home_video_container [data-video]', function() {
    showVideoPopup(this);
});

$(document).on('click', '.profile_video', function() {
    if ($('#profile_video_container').length) {
        $('#profile_video_container_wrapper').show();
        $(this).addClass('hide');
        $('.profile_tile .profile_cover_gear').addClass('hide');
        if (isIE () == 9 || isIE11()) {
            $('#profile_video_container_wrapper').show();
            jwplayer('profile_video_container').seek(0);
        } else {
            
        }
        $('#profile_video_container_wrapper').css('z-index', 999);
        $('#profile_video_minimize_icon').removeClass('hide');
        jwplayer('profile_video_container').play();
    }
});


function hideCoverVideo() {
    if ($('#profile_video_container').length) {
        if (isIE () == 9 || isIE11()) {
			    if (jwplayer('profile_video_container').getState() !== 'PAUSED') {
					jwplayer('profile_video_container').pause();
				}
                $('#profile_video_container_wrapper').hide();
        } else {
				jwplayer('profile_video_container').stop();
                setTimeout(function() {
                    if (jwplayer('profile_video_container').getState() !== 'IDLE') {
                        jwplayer('profile_video_container').stop();
                    }
                }, 100);
                
        }
        $('#profile_video_container_wrapper').hide();        
        $('#profile_video_container_wrapper').css('z-index', -1);
        $('#profile_video_minimize_icon').addClass('hide');
        $('.profile_video').removeClass('hide');
        $('.dashboard_video_play').removeClass('hide');
        $('.profile_tile .profile_cover_gear').removeClass('hide');
    }
}

$(document).on('click', '#profile_video_minimize_icon', function() {

    hideCoverVideo();
});

function showVideoPopup(el) {
    var $height = $(window).height();
    $height = $height - ((20 * $height) / 100) + 'px';
    var $videoUrl = $(el).attr('data-video');
    if ($videoUrl) {
        var $embedCode = $(el).embedPlayer($videoUrl, '100%', $height, true);
        //var $embedCode = getVimeoEmbedCode($videoId, '100%', $height);
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
}
/**
 * Function to update online friends tile 
 */
function updateOnlineFriendsList() {
    $.ajax({
        url: '/user/dashboard/getOnlineFriendsHTML',
        success: function(data) {
            if ($("#online_friends_list").length) {
                $('#online_friends_list').html(data);
                //applay slim scroll to online friends

            }
        }
    });


    $.ajax({
        url: '/user/dashboard/getOnlineFriendsJSON',
        dataType: 'json',
        success: function(data) {
            if (typeof my_friends_json !== 'undefined') {
                my_friends_json = data;
            }
        }
    });
}
/**
 * Function to update online friends tile 
 */
function updateProfileOnlineFriendsList() {
    $.ajax({
        url: '/user/profile/getOnlineFriendsHTML',
        success: function(data) {
            
            var el = $("#profile_friends_online .details_container");
            if (el.length) {
                    
                    el.html(data);
                    
                    /*
                     * applay slim scroll 
                     * if height > 400 and slimscroll is not applied yet
                     */
                    if ( el.height() > 400 && ( !el.parent().hasClass('slimScrollDiv') ) ) {
                            applySlimScroll( el, '450px' );  
                    }
            }
        }
    });

    $.ajax({
        url: '/user/profile/getOnlineUserCountAjax',
        success: function(data) {
            if ($("#profile_friends_online").length) {
                $('#profile_friends_online h4').html('Online Friends (' + data + ')');
                //applay slim scroll to online friends

            }
        }
    });
}

/**
 * Function to update online friends tile 
 */
function updateDiseaseOnlineFriendsList() {
    $.ajax({
        url: '/user/profile/getOnlineFriendsHTML',
        success: function(data) {
           
            var el = $("#disease_friends_online .details_container");
            if (el.length) {
                    
                    el.html(data);
                    
                    /*
                     * applay slim scroll 
                     * if height > 400 and slimscroll is not applied yet
                     */
                    if ( el.height() > 400 && ( !el.parent().hasClass('slimScrollDiv') ) ) {
                            applySlimScroll( el, '450px' );  
                    }
            }
        }
    });

    $.ajax({
        url: '/user/profile/getOnlineUserCountAjax',
        success: function(data) {
            if ($("#disease_friends_online").length) {
                $('#disease_friends_online h4').html('Online Friends (' + data + ')');
                //applay slim scroll to online friends

            }
        }
    });
}

function updateVideoProfileOnlineFriendsList() {
    $.ajax({
        url: '/user/profile/getOnlineVideoFriendsHTML',
        success: function(data) {
            if ($("#online_video_friends_list").length) {
                $('#online_video_friends_list').html(data);
                //applay slim scroll to online friends

            }
        }
    });

}

/**
 * Function to update disease member list in disease page
 * @returns {boolean}
 */
function updateDiseaseMemberList() {
    var diseaseId = $("#disease_members_online").data('disease-id');
    $.ajax({
        url: '/disease/diseases/getDiseaseMembersHTML',
        data: {'diseaseId':diseaseId },
        dataType: 'html',
        type: 'POST',
        success: function(data) {
            
            var el = $("#disease_members_online .details_container");
            if (el.length) {
                    
                    el.html(data);
                    
                    /*
                     * applay slim scroll 
                     * if height > 400 and slimscroll is not applied yet
                     */
                    if ( el.height() > 400 && ( !el.parent().hasClass('slimScrollDiv') ) ) {
                            applySlimScroll( el, '450px' );  
                    }
            }
            return true;
        }
    });
    
    $.ajax({
        url: '/disease/diseases/getDiseaseMembersCountAjax',
        data: {'diseaseId':diseaseId },
        dataType: 'html',
        type: 'POST',
        success: function(data) {
            if ($("#disease_members_online").length) {
                $('#disease_members_online h4').html('Online Members (' + data + ')');
                //applay slim scroll to online friends

            }
        }
    });
}
/**
 * Function to update community member list in community detail page
 * @returns {boolean}
 */
function updateCommunityMembersList() {
    var communityId = $("#community_members_online").data('community-id');
    $.ajax({
        url: '/community/details/getCommunityMembersHTML',
        data: {'communityId':communityId },
        dataType: 'html',
        type: 'POST',
        success: function(data) {
            var el = $("#community_members_online .details_container");
            if (el.length) {
                    
                    el.html(data);
                    
                    /*
                     * applay slim scroll to online friends
                     * if height > 400 and slimscroll is not applied yet
                     */
                    if ( el.height() > 400 && ( !el.parent().hasClass('slimScrollDiv') ) ) {
                            applySlimScroll( el, '450px' );  
                    }
            }
            return true;
        }
    });
    
    $.ajax({
        url: '/community/details/getCommunityMembersCountAjax',
        data: {'communityId':communityId },
        dataType: 'html',
        type: 'POST',
        success: function(data) {
            if ($("#community_members_online").length) {
                $('#community_members_online h4').html('Online Members (' + data + ')');
                //applay slim scroll to online friends

            }
        }
    });
}

/**
 * Function to apply slim scroll to an element
 * @param {DOM elemnt} el
 * @param {string} height
 * @returns {boolean}
 */
function applySlimScroll(el, height){
    
    if ( el.length ) {
            el.slimScroll({
                    color: '#e2e2e2',
                    railColor:'#f1f1f1',
                    size: '8px',
                    height: height,
                    distance: '6px',
                    disableFadeOut: false,
                    railVisible: true
            });
            return true;
    }
    
    return false;
}

$(document).ready(function() {
    if ($("#online_friends_list .friends_list_dashboard").length > 8) {
        $("#online_friends_list").slimScroll({
            color: 'rgba(225, 225, 225, 0.2)',
            size: '8px',
            height: '432px',
            distance: '6px',
            disableFadeOut: false
        });
    }

    // Apply slim scroll to mycondition tile
    if ($('.my_condition_tile').length) {
        $(".my_condition_tile").slimScroll({
            color: 'rgba(225, 225, 225, 0.2)',
            size: '8px',
            height: '214px',
            distance: '6px',
            disableFadeOut: false
        });
    }

    
     // applay slim scroll to online disease members tile
    if ($('#disease_members_online .details_container').length) {       
        var el =  $("#disease_members_online .details_container");
//        applySlimScroll(el);
    }

    // applay slim scroll to diease online friends tile
    if ($('#disease_friends_online .details_container').length) {       
        var el = $("#disease_friends_online .details_container");
//        applySlimScroll(el);
    }
    
    // applay slim scroll to diease online friends tile
    if ($('#profile_friends_online .details_container').length) {
        var el = $("#profile_friends_online .details_container");
//        applySlimScroll(el);
    }


    if(!$('.favorite_icon').parents('.disease').length){
        $('.favorite_icon').attr('title','');
    }
    
    if($('.report_abuse').parents('.disease').length){
         $('.report_abuse').attr('title','Report Abuse');
    }

});

/**
 * Get timezone data (offset and dst)
 *
 *  Inspired by: http://goo.gl/E41sTi
 *
 * @returns {{offset: number, dst: number}}
 */
function getTimeZoneData() {
    var today = new Date();
    var jan = new Date(today.getFullYear(), 0, 1);
    var jul = new Date(today.getFullYear(), 6, 1);
    var dst = today.getTimezoneOffset() < Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());

    return {
        offset: -today.getTimezoneOffset() / 60,
        dst: +dst
    };
}

function getSymGraphTiles() {
    $.ajax({
        url: '/user/mysymptom/getGraphBlock',
        cache: false,
        type: 'POST',
        success: function(result) {
            $('#symptom_tiles').html(result);
            renderWeeklyGraphs();
        }
    });
}

function renderWeeklyGraphs() {
	//get div elements for symptom graph
    var graphElements = $('.mysymptoms .symptom_graph_rep .graph_div div.symptom_graph');
    var userId = $.trim($("#symptomUserId").val());
    var notify_temp = app.show_site_notfications;
    // get Id s of each div
    $.each(graphElements, function(i, item) {
        var itemId = $(item).attr('id');
        if ((graphElements.length - 1) == i) {
            app.show_site_notfications = notify_temp;
        }
        getSymptomWeeklySeverityGraph(itemId, userId);
        
    });

}

function getSymLists(symptom_date) {
    $.ajax({
        url: '/user/mysymptom/getSymptomList',
        cache: false,
        type: 'POST',
        data: {
            'symptom_date': symptom_date
        },
        success: function(result) {
            $('#symptom_user_list').html(result);
        }
    });
}

function getHealthWidget() {
    $.ajax({
        url: '/user/myhealth/getDailyHealthWidget',
        cache: false,
        type: 'POST',
        success: function(result) {
            $('#health_indicator').html(result);
            renderGaugeChart();
        }
    });
}
$(document).on('mouseover', '#my_library_container .posting_area', function() {
    $(this).find('.remove_from_favorite_btn').removeClass('hide');
});
$(document).on('mouseout', '#my_library_container .posting_area', function() {
    $(this).find('.remove_from_favorite_btn').addClass('hide');
});
$(document).on('click', '.remove_from_favorite_btn', function() {
    var _this = this;
    var postId = $(this).data('post_id');
    var status = 0;
    var confirmMessage = "Are you sure you want to remove this post from Library?";
    bootbox.confirm(confirmMessage, function(isConfirmed) {
        if (isConfirmed) {
            addToFavoritePost(postId, status);
            $(_this).closest('.posting_area').hide();
        }
    });
//    $(this).removeClass('not_favorite').addClass('favorite');
//    $(this).attr('title', 'Remove from my library.')
//    $(this).html('Remove from my library');
});
$(document).on('click', '.not_favorite', function() {
    var postId = $(this).data('post_id');
    var status = 1;
    $(this).removeClass('not_favorite').addClass('favorite');
    if($(this).parents('.disease').length){
        $(this).attr('title', 'Remove from my library.');
    }
    $(this).children('span').html('Remove from my library');
    addToFavoritePost(postId, status);
});
$(document).on('click', '.favorite', function() {
    var postId = $(this).data('post_id');
    $(this).removeClass('favorite').addClass('not_favorite');
    if($(this).parents('.disease').length){
       $(this).attr('title', 'Add to my library.');
    }
    $(this).children('span').html('Add to my library');
    var status = 0;
    addToFavoritePost(postId, status);
});

$(document).on('click', '#symptom_submit_button', function() {
    symptom_id = $.trim($('#symptoms_id_hidden').val());
    severity = 0;
    $('#symptom_error_message').html('');
    if ($('input[name=symptomHistoryRadio]:radio:checked').length > 0) {
        severity = $('input[name=symptomHistoryRadio]:radio:checked').val();
    }
    rotate = Ladda.create(this);
    if (rotate != null) {
        rotate.start();
    }

    if (!$.isNumeric(symptom_id)) {
        $('#symptom_error_message').text('Please enter valid symptom.').show();
        rotate.stop();
        return false;
    }
    else if (symptom_id == 0) {
        new_symptom = $.trim($('#symptom-search').val());
        $("#symptom_submit_button").attr('disabled', 'disabled');
        $.ajax({
            type: 'POST',
            url: '/user/api/addNewUserSymptom',
            data: {
                'new_symptom': new_symptom,
                'severity': severity
            },
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(result) {
                rotate.stop();
                if (result.status == true) {
                    socket.emit('my_health_update', {
                        room: $('#graphUpdatedInRoom').val(),
                        type: 'symptom'
                    });
                    if ($('#symptomTilePage').length > 0) { //mysymptom page
                        getSymGraphTiles();
                    } else if ($('#symptomListPage').length > 0) { //symptom list page						
                        symptom_date = $.trim($('#symptomDatepicker').val());
                        getSymLists(symptom_date);
                    } else { //health page 
                        getHealthWidget();
                    }
                    $("#addSymptom").modal('hide');
                    $('#symptom_error_message').html('');
                    $('#symptom-search').val('');
                    $('#symptoms_id_hidden').val('');
                    $('#add_new_score_button').show();
                } else {
                    $('#symptom_search_error_message').text('Unable to save').show();
                }

            }
        });
    }
    else {
        $.ajax({
            type: 'POST',
            url: '/user/api/addUserSymptom',
            data: {
                'id': symptom_id,
                'severity': severity
            },
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(result) {
                rotate.stop();
                if (result == true) {
                    socket.emit('my_health_update', {
                        room: $('#graphUpdatedInRoom').val(),
                        type: 'symptom'
                    });
                    if ($('#symptomTilePage').length > 0) { //mysymptom page
                        getSymGraphTiles();
                    } else if ($('#symptomListPage').length > 0) { //symptom list page						
                        symptom_date = $.trim($('#symptomDatepicker').val());
                        getSymLists(symptom_date);
                    } else { //health page 
                        getHealthWidget();
                    }
                    $("#addSymptom").modal('hide');
                    $('#symptom_error_message').html('');
                    $('#symptom-search').val('');
                    $('#symptoms_id_hidden').val('');
                    $('#add_new_score_button').show();
                } else {
                    $('#symptom_search_error_message').text('Unable to save').show();
                }

            }
        });
    }
});

$(document).on('click', '#close_symptom_submit_button', function() {
    $('#symptom_error_message').html('');
});

$(document).on('click', '#delete_user_symptom', function() {

    if (!is_symptom_newscore_active) {
        symptomId = $(this).data('symptom-id');

        var confirmMessage = "Are you sure you want to delete this symptom?";
        bootbox.confirm(confirmMessage, function(isConfirmed) {
            $('#symptom_conditions').modal('hide');
            if (isConfirmed) {
                $.ajax({
                    url: '/symptom/delete',
                    cache: false,
                    data: {
                        'id': symptomId
                    },
                    type: 'POST',
                    success: function(result) {
                        if (result) {
                            socket.emit('my_health_update', {
                                room: $('#graphUpdatedInRoom').val(),
                                type: 'symptom'
                            });
                            $('#add_user_symptom').show();
                            $('#delete_user_symptom').hide();
                            $('#symptom_history_row').html('');
                            $('#add_new_history_button').hide();
                            $('.symptom_history_filter_option').hide();
                            load_symptom_history();
                            getSymptomSeverityGraph('symptomSeverityDetailGraph');
                            window.location.href = '/profile/mysymptom';
                        }
                    }
                });
            }
        });
    }
});

$(document).on('click', '#add_user_symptom', function() {

    symptomId = $(this).data('symptom-id');

    var confirmMessage = "Are you sure you want to add this symptom?";
    bootbox.confirm(confirmMessage, function(isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '/user/api/addUserSymptom',
                data: {
                    'id': symptomId
                },
                dataType: 'json',
                success: function(result) {
                    if (result == true) {
                        socket.emit('my_health_update', {
                            room: $('#graphUpdatedInRoom').val(),
                            type: 'symptom'
                        });
                        $('#add_user_symptom').hide();
                        $('#delete_user_symptom').show();
                        $('#add_new_history_button').show();
                        $('#symptom_history_row').html('');
                        load_symptom_history();
                        getSymptomSeverityGraph('symptomSeverityDetailGraph');
                    }

                }
            });
        }
    });
});

$(document).on('click', '.symptom-history-delete', function() {
    elem = $(this).closest('.symptom-history-div');
    divId = elem.data('id');
    symptomId = elem.data('symptom-id');
    var confirmMessage = "Are you sure you want to delete this symptom history?";
    bootbox.confirm(confirmMessage, function(isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                url: '/mysymptom/delete/' + symptomId + '/' + divId,
                cache: false,
                success: function(result) {
                    if (result) {
                        socket.emit('my_health_update', {
                            room: $('#graphUpdatedInRoom').val(),
                            type: 'symptom'
                        });
                        elem.remove();
                        getSymptomSeverityGraph('symptomSeverityDetailGraph');
                    }
                }
            });
        }
    });

});
$(document).on('click', '#symptom_history_cancel', function() {
    $('#symptom_history_error_message').html('');
});
$(document).on('click', '#symptom_history_save', function() {

    symptom_id = $.trim($('#symptom_id').val());
    symptom_date = $.trim($('#selectedSymptomDate').val());

    if ($('input[name=symptomHistoryRadio]:radio:checked').length > 0) {
        severity = $('input[name=symptomHistoryRadio]:radio:checked').val();
        $('#symptom_history_save').attr('disabled', 'disabled');
        $.ajax({
            url: '/user/api/addUserSeverity',
            data: {
                'id': symptom_id,
                'date': symptom_date,
                'severity': severity
            },
            type: 'POST',
//            dataType: 'json',
            success: function(result) {
                socket.emit('my_health_update', {
                    room: $('#graphUpdatedInRoom').val(),
                    type: 'symptom'
                });
                $('#symptom_conditions').modal('hide');
                $('#symptom_history_row').html('');
                load_symptom_history();
                $('#symptom_history_save').removeAttr('disabled');
                getSymptomSeverityGraph('symptomSeverityDetailGraph');
            }
        });
    }
    else {
        $('#symptom_history_error_message').text('Please select valid severity.').show();
        return false;
    }


});

$(document).on('click', '.condition_indicator label input', function() {

    $(this).closest('.condition_popup_container').find('label').removeClass('on');
    $(this).parent().addClass('on');
});

$(document).on('click', '#add_new_history_button', function() {
    $('#selectedSymptomDate').val('');
    $('.condition_popup_container').find('label').removeClass('on');
    $("#symptomHistoryDatepicker").datepicker('show');
    bootbox.hideAll();
});

$(document).on('click', '.symptom-history-edit', function() {
    $('.condition_popup_container').find('label').removeClass('on');
    elem = $(this).closest('.symptom-history-div');
    divId = elem.data('id');
    displayDate = elem.data('date');
    severity = elem.data('severity');
    severity_name = elem.data('severity-name');
    symptom_id = $.trim($('#symptom_id').val());
    $('#symptom_conditions').modal('show');
    bootbox.hideAll();
    myDate = new Date(Date.parse(displayDate));

    symptom_date = myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
    $('#date-selected').html(symptom_date); //date to display in popup
    divIdDate = new Date(1000 * divId);
    symptom_gmt_date = (divIdDate.getMonth() + 1) + '/' + divIdDate.getDate() + '/' + divIdDate.getFullYear();

    $('#selectedSymptomDate').val(displayDate);//date to submit
    $('.condition_' + severity_name).addClass('on');
    $('.condition_' + severity_name).find("input").attr('checked', true);

});
/**
 * Function to filter symptom history
 */
$(document).on('click', 'ul#symptom_history_filter li a', function() {
    var filterValue = $(this).data('filter_value');//symptom_filter_year
    $('#symptom_filter_year').val(filterValue);
    var sym_id = $('#symptom_id').val();
    var username = $("#symptomSeverityDetailGraph").data('username');
    $.ajax({
        url: '/symptom/history/list',
        cache: false,
        type: 'POST',
        data: {
            'filterValue': filterValue,
            'symId': sym_id,
            'username': username
        },
        success: function(result) {
            $('#symptom_history_row').html(result);

        }
    });
});

function load_symptom_history() {
    sym_id = $('#symptom_id').val();
    var username = $("#symptomSeverityDetailGraph").data('username');
    $.ajax({
        url: '/symptom/history/list',
        cache: false,
        type: 'POST',
        data: {
            'symId': sym_id,
            'username': username
        },
        success: function(result) {
            $('#symptom_history_row').append(result);

        }
    });
}

/*
 * 
 */
function addToFavoritePost(postId, status) {
    $.ajax({
        url: '/post/api/addToFavorite',
        cache: false,
        type: 'POST',
        data: {'post_id': postId, 'status': status},
        success: function(data) {
            
        }
    });
}

/*
 * Function to get current user's now time object
 */
function getUserNow() {
    var d = new Date();
    var localTime = d.getTime();
    var localOffset = d.getTimezoneOffset() * 60000;
    var utc = localTime + localOffset;
    var offset = parseFloat(app.user_timezone);
    var userTime = utc + (3600000 * offset);
    var nd = new Date(userTime);
    return nd;
}

var chatMessageAJAXRequest = null;
function appReceiveArrowchatMessage(from, message, sent) {
    if ($('#chat_notification_container').length > 0) {
        if (chatMessageAJAXRequest !== null) {
            chatMessageAJAXRequest.abort();
        }
        chatMessageAJAXRequest = $.ajax({
            method: 'POST',
            url: '/user/dashboard/getChatMessageData',
            data: {
                from: from,
                message: message,
                sent: sent
            },
            success: function(chatHTML) {
                showBlurDashboardImages();
                $('#chat_notification_container').html(chatHTML).show();
            }
        });
    }
}

/**
 * Function to show the blurred dashboard images
 */
function showBlurDashboardImages() {
    loadBlurImages($('#dashboard_image_container img'));
    loadBlurImages($('#dashboard_slideshow_container img'));
}

/**
 * Function to load blur images instead of normal images
 */
function loadBlurImages(images) {
    images.each(function() {
        var src = $(this).attr('src');
        if (src.indexOf('_blur') === -1) {
            var ext;
            if (src.indexOf('.jpg') > -1) {
                ext = '.jpg';
            }
            else if (src.indexOf('.png') > -1) {
                ext = '.png';
            }
            else if (src.indexOf('.gif') > -1) {
                ext = '.gif';
            }
            var blurSrc = src.replace(ext, '_blur' + ext);
            $(this).attr('src', blurSrc);
        }
    });
}

/*
 * Scroll back to top functionality
 */
$(document).ready(function() {
    //hide back-to-top button
    $('.back-to-top').hide();

    $(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });

        // scroll body to 0px on click
        $('.back-to-top').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 400);
            return false;
        });
    });
});

/*
 * Creating a volunteer
 */
$(document).on('click', '#create_volunteer', function() {
    $.ajax({
        url: '/myteam/api/createVolunteer',
        cache: false,
        type: 'POST',
        dataType: 'json',
        success: function(result) {
            if (result.success == true) {
                window.location.reload(true);
            }
        }
    });
});

/*
 * Deleting a volunteer
 */
$(document).on('click', '#remove_volunteer', function() {
    $.ajax({
        url: '/myteam/api/deleteVolunteer',
        cache: false,
        type: 'POST',
        dataType: 'json',
        success: function(result) {
            if (result.success == true) {
                window.location.reload(true);
            }
        }
    });
});

//Function for searching friends in invite friends list(team)
function searchFriendsInList(list, search_key) {
    var search_text = "";
    if (typeof search_key == "string") {
        search_text = search_key;
    } else {
        search_text = $(search_key).val();
    }
    $(".none_found").addClass('hidden');
    $("#team_error_message").hide();
    $(".invite_submit").attr("disabled", false);
    var friendsShown = 0;
    var friendsTotal = 0; //Total number of friends
    var json = list;
    if (!$.isEmptyObject(json.friends.friend)) {
        $.each(json.friends.friend, function(i, v) {
            var userDiv = ".invite_team_members" + v.friend_id;
            $(userDiv).addClass("hidden");
            friendsTotal++;
        });
        var search = (search_text).toLowerCase();
        $.each(json.friends.friend, function(i, v) {
            var name = (v.friend_name).toLowerCase();
            if (name.search(search) !== -1) {
                var userDiv = ".invite_team_members" + v.friend_id;
                $(userDiv).removeClass("hidden");
                friendsShown++;
            } else {
                friendsShown--;
            }
        });
    }
    if ((friendsShown == -friendsTotal) || (friendsTotal == 0)) {
        $(".none_found").removeClass('hidden');
        $("#team_error_message").hide();
        $(".invite_submit").attr("disabled", "disabled");
    }
}

/*
 * Cancel button click in all invite friend pop up in Event & Community
 */
$(document).on('click', '.invite_close_button', function() {
    $("#search_friends").val('');
    $('#none_found').addClass('hidden');
    $('#search_friends').keyup();
    $('input[type="checkbox"]').parent('.invite_frnds').removeClass('active');
    $('input[type="checkbox"]').prop("checked", false);
    $('.select_all_check_box').prop("checked", false);
    $('#selected_count').html(0);
    $("#invite_button").prop('disabled', true);
});

/**
 * Select/Deselect all friends in invite pop up for events & communities
 */
$(document).on('change', '.select_all_check_box', function() {
    var selectedCols = $('#invite_friends .not_invited');
    if ($(this).is(':checked')) {
        selectedCols.addClass('active');
        $("#invite_button").prop("disabled", false);
        $('.invite_box').prop("checked", true);
    }
    else {
        selectedCols.removeClass('active');
        $("#invite_button").attr("disabled", "disabled");
        $('.invite_box').prop("checked", false);
    }
    handleSelectedBoxCountChange();
});

$(document).on('change', '.select_all_community_check_box', function() {
    var selectedCols = $('#invite_community_friends .community_not_invited');
    if ($(this).is(':checked')) {
        selectedCols.addClass('active');
        $("#invite_button").prop("disabled", false);
        $('.invite_box').prop("checked", true);
    }
    else {
        selectedCols.removeClass('active');
        $("#invite_button").attr("disabled", "disabled");
        $('.invite_box').prop("checked", false);
    }
    handleCommunitySelectedBoxCountChange();
});

/**
 * Function to show total selected friend's count
 */
function handleSelectedBoxCountChange() {
    var count = 0;
    $('.not_invited input[type=checkbox]').each(function() {
        if ($(this).is(':checked')) {
            count = count + 1;
        }
    });
    $('#selected_count').html(count);
}

function handleCommunitySelectedBoxCountChange() {
    var count = 0;
    $('.community_not_invited input[type=checkbox]').each(function() {
        if ($(this).is(':checked')) {
            count = count + 1;
        }
    });
    $('#selected_community_count').html(count);
}

$(document).on('click', '.disease_follow_btn', function() {
    diseaseId = $(this).data('disease-id');
    followButton = $(this);
    $.ajax({
        url: '/user/api/followDiseasePage',
        data: {
            'diseaseId': diseaseId
        },
        type: 'POST',
        cache: false,
        success: function(result) {
            if (result) {
                followButton.hide();
                followButton.next(".disease_unfollow_btn").show();
                
                /*
                 * Update online member list
                 */
                if ( $("#disease_friends_online").length ) {
                    updateDiseaseMemberList();
                }
            }
        }
    });
});

$(document).on('click', '.disease_unfollow_btn', function() {
    diseaseId = $(this).data('disease-id');
    unfollowButton = $(this);

    bootbox.dialog({
        message: "Are you sure you want to unfollow this disease?",
        title: "Unfollow",
        closeButton: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                            url: '/user/api/unFollowDiseasePage',
                            data: {
                                'diseaseId': diseaseId
                            },
                            type: 'POST',
                            cache: false,
                            success: function(result) {
                                if (result) {
                                    unfollowButton.hide();
                                    unfollowButton.prev(".disease_follow_btn").show();
                                    
                                    /*
                                     * Update online member list
                                     */
                                    if ( $("#disease_friends_online").length ) {
                                        updateDiseaseMemberList();
                                    }
                                }
                            }
                        });
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

$(document).on('click', '.profile_follow_btn', function() {
    profileId = $(this).data('profile-id');
    followButton = $(this);
    $.ajax({
        url: '/user/api/followProfilePage',
        data: {
            'profileId': profileId
        },
        type: 'POST',
        cache: false,
        success: function(result) {
            if (result) {
                followButton.hide();
                followButton.next(".profile_unfollow_btn").show();
            }
        }
    });
});

$(document).on('click', '.profile_unfollow_btn', function() {
    profileId = $(this).data('profile-id');
    unfollowButton = $(this);
    var confirmMessage = "Are you sure you want to unfollow this user?";

    bootbox.dialog({
        message: confirmMessage,
        title: "Unfollow",
        closeButton: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Yes",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                            url: '/user/api/unFollowProfilePage',
                            data: {
                                'profileId': profileId
                            },
                            type: 'POST',
                            cache: false,
                            success: function(result) {
                                if (result) {
                                    unfollowButton.hide();
                                    unfollowButton.prev(".profile_follow_btn").show();
                                }
                            }
                        });
                    }
                }
            },
            danger: {
                label: "No",
                className: "btn btn_clear"
            }
        }
    });
});

$(document).on('click', '.notification_switch', function() {
    var followId = $(this).parent().data('follow-id');
    var noti_type = $(this).parent().data('notification-type');
    var status = $(this).data("status");
    element = $(this);

    $.ajax({
        url: '/user/api/switchNotification',
        data: {
            'Id': followId,
            'type': noti_type,
            'status': status
        },
        dataType: 'json',
        type: 'POST',
        cache: false,
        success: function(result) {
            if (result.notification_type == 1) {
                element.next(".notification_off").show();
                element.hide();
            } else {
                element.prev(".notification_on").show();
                element.hide();
            }
			window.location.reload();
        }
    });
});

/**
 * Deleting tracker histroy record
 */
$(document).on('click', '.tracker-history-delete', function() {
    dataDiv = $(this).closest('.tracker-history-div');
    divId = dataDiv.data('id');
    recordType = dataDiv.data('record-type');
    bootbox.dialog({
        message: "Are you sure you want to delete this tracker history?",
        title: "Delete History",
        closeButton: true,
        onEscape: function() {
        },
        buttons: {
            main: {
                label: "Ok",
                className: "btn btn_active",
                callback: function(confirmed) {
                    if (confirmed) {
                        $.ajax({
                            url: '/healthTracker/delete/' + recordType + '/' + divId,
                            cache: false,
                            success: function(result) {
                                if (result) {
                                    dataDiv.remove();
                                    load_tracker_graph('tracker_graph_container');
                                }
                            }
                        });
                    }
                }
            },
            danger: {
                label: "Cancel",
                className: "btn btn_clear"
            }
        }
    });
});

$(document).on('click', '#stop_medication_reminder_dialog #yes_btn', function() {
    var loading = Ladda.create(this);
    var data = $(this).closest('form').serialize();
    $.ajax({
        url: '/user/scheduler/stopMedicationReminder',
        data: data,
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            loading.start();
            $('#stop_medication_reminder_dialog button').prop('disabled', true);
        },
        success: function(result) {
            $('#stop_medication_reminder_dialog #confirm_message').addClass('hide');
            $('#stop_medication_reminder_dialog .modal-footer').remove();
            if (result.success) {
                $('#stop_medication_reminder_dialog #success_message').removeClass('hide');
            }
            else if (result.error) {
                $('#stop_medication_reminder_dialog #error_message').removeClass('hide');
            }
        }
    });
});
$(document).on('click', '#stop_medication_reminder_dialog #no_btn', function() {
    $('#stop_medication_reminder_dialog #confirm_message').addClass('hide');
    $('#stop_medication_reminder_dialog .modal-footer').remove();
    $('#stop_medication_reminder_dialog #no_message').removeClass('hide');
});

/*
 * Printing Friends list
 */
$(document).on('click', '.friends_print', function() {
    window.open('/healthinfo/print?graphIds=18', '_blank');
});

/*
 * Printing My team details
 */
$(document).on('click', '.team_print', function() {
    window.open('/healthinfo/print?graphIds=19', '_blank');
});

/*
 * Printing My Health summary
 */
$(document).on('click', '#print_summary', function() {
    var selectedOptions = [];
    var startDate = '';
    var endDate = '';
    var dateRange = '';
    if ($("#dateSelectType1").prop("checked")) {
        dateRange = $(".periodSelectionValue").val();
    } else if ($("#dateSelectType2").prop("checked")) {
        dateRange = 1;
        startDate = $("#PrintFrom").val();
        endDate = $("#PrintTo").val();
    }
    if (dateRange == '') {
        $('#print_error_message').text('Please choose a date option.').show();
    } else if(startDate > endDate) {
		$('#print_error_message').text('From-date should be less than To-date.').show();
		if(startDate.length == 0 || endDate.length == 0) {
			$('#print_error_message').text('Please enter Both dates.').show();
		}
	} else if(startDate < endDate && dateRange == 1 && startDate.length == 0) {
		$('#print_error_message').text('Please enter Both dates.').show();
	} else if(startDate == endDate && dateRange == 1 && startDate.length == 0) {
		$('#print_error_message').text('Please enter Both dates.').show();
	} else {
			$('input[name="graph_options_list[]"]:checked').each(function() {
				selectedOptions.push($(this).attr('value'));
			});
			if (selectedOptions == '') {
				$('#print_error_message').text('Please choose the options to print.').show();
			} else {
				url = '/healthinfo/print?graphIds=' + selectedOptions + '&customDates=' + dateRange + '&mindate=' + startDate + '&maxdate=' + endDate;
				if ($.inArray('16', selectedOptions) != -1) { // in case of printing symptoms
					selectedOptions = removeDuplicates(selectedOptions);
					symptomIds = [];
					$('input[class="symptom_options"]:checked').each(function() {
						symptomIds.push($(this).data('symptom'));
					});
					url = '/healthinfo/print?graphIds=' + selectedOptions + '&customDates=' + dateRange + '&mindate=' + startDate + '&maxdate=' + endDate + '&symptoms=' + symptomIds;
				}
				window.open(url, '_blank');
				$("#medical_summary_modal").modal('hide');
				$('input[type="radio"]').prop('checked', false);
				$('input[type="checkbox"]').prop('checked', false);
				$('input[type="text"]').val('');
				$('.periodSelection').addClass('hidden');
				$('.dateSelection').addClass('hidden');
			}
    }
});

function removeDuplicates(list) {
    var result = [];
    $.each(list, function(i, e) {
        if ($.inArray(e, result) == -1)
            result.push(e);
    });
    return result;
}

/*
 * On changing the search type
 */
$('.search_type').click(function() {
    var keyword = $("#header_search").val();
    if (!keyword == "") {
        var searchCategory = $('#search_icons').attr('class').replace("search_icons ", "");
        var type;
        switch (searchCategory) {
            case 'all' :
                type = "all";
                break;
            case 'people_search' :
                type = "people";
                break;
            case 'community_search' :
                type = "community";
                break;
            case 'disease_search' :
                type = "disease";
                break;
            case 'hash_search' :
                type = "hashtag";
                break;
            default:
                type = "all";
                break;
        }
        window.location.replace("/search?type=" + type + "&keyword=" + encodeURIComponent(keyword));
    }
});

/*
 * On clicking search icon in header search
 */
$(".search_submit").click(function() {
    var keyword = $("#header_search").val();
    if (!keyword == "") {
        var searchCategory = $('#search_icons').attr('class').replace("search_icons ", "");
        var type;
        switch (searchCategory) {
            case 'all'				:
                type = "all";
                break;
            case 'people_search' 	:
                type = "people";
                break;
            case 'community_search'	:
                type = "community";
                break;
            case 'disease_search'	:
                type = "disease";
                break;
            case 'hash_search'	:
                type = "hashtag";
                break;
            default:
                type = "all";
                break;
        }

        window.location.replace("/search?type=" + type + "&keyword=" + encodeURIComponent(keyword));
    }
});

function showSiteNotification(message, alert_type) {
    var urlArray = window.location.pathname.split('/');
    var msg = urlArray[2] + ' just updated their details!';
    var notifyObj = {};
    notifyObj.options = {};
    var alert_types = new Array("success", "info", "warn", "error");
    notifyObj.options.className = 'success';
    notifyObj.options.autoHide = true;
    notifyObj.options.position = 'bottom left';
    notifyObj.options.arrowSize = 0;

//    notifyObj.container = $("#selection_bar");
    if ($.inArray(alert_type, alert_types)) {
        notifyObj.options.className = alert_type;
    }
//    $.notify(message, alert_class );
//    notifyObj.container.notify(message, notifyObj.options);
    $.notify(msg, notifyObj.options);
    app.show_site_notfications = false;

}


function getMyHealthGraphData(type) {
    app.show_site_notfications = true;
    switch (type) {
        case 'pain_tracker':
            refreshPainDataGraph();
            break;
        case 'current_health':
            load_stock_graph(false, true);
            break;
        case 'symptom':
            renderWeeklyGraphs(true);
            getSymptomSeverityGraph('symptomSeverityDetailGraph', true);
            break;
        case 'tracker':
            load_stock_graph(false, true);
            break;
        case 'health_indicators':
            if ($('#status_graph').length) {
                $('#status_graph').click();
            } else {
                load_stock_graph(false, true);
            }
            break;
    }
}


    /**
    *  Functino to get IE version
    * @returns {version|boolean}
    */
    function isIE() {
       var myNav = navigator.userAgent.toLowerCase();
       return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
    }
    
    function isIE11(){
        return !!navigator.userAgent.match(/Trident.*rv\:11\./);
    }
	
/*
 * Playing video in dashboard
 */
$(document).on('click', '.dashboard_video_play', function() {
    if ($('#profile_video_container').length) {
        $('#profile_video_container_wrapper').show();
		$(this).addClass('hide');
        $('.profile_tile .profile_cover_gear').addClass('hide');
        if (isIE () == 9 || isIE11()) {
            $('#profile_video_container_wrapper').show();
            jwplayer('profile_video_container').seek(0);
        } 
        $('#profile_video_container_wrapper').css('z-index', 999);
        $('#profile_video_minimize_icon').removeClass('hide');
		$('.dashboard_video_play').addClass('hide');
        jwplayer('profile_video_container').play();
    }
});

/**
 * Function to embed Vimeo video player
 */
(function($) {
	$.fn.embedVimeoPlayer = function(videoId, width, height) {
		var $output = getVimeoEmbedCode(videoId, width, height);
		return this.html($output);
	};
})(jQuery);

/*
 * Function to search the hashtags from dashboard
 */
$(document).on('input', '#db_htag', function() {
    $.ajax({
       url: '/search/search/getHeaderSearch',
       dataType: 'json',
       data: {
           term: $(this).val(),
           category: 6
       },
       success: function(data) {
		   $('.hashtag_container').empty();
		   $.each(data, function(i, item) {
				if (item.Name != 'More' && item.Name != 'empty') {
					var inner_html = '<p class="rating_4 tags">';
					inner_html += '<a href="' + item.Url + '">' + item.Name + '</a></p>';
				}
				$('.hashtag_container').append(inner_html);
		   });
       }
    });
});
	
 /*
 * Displaying hashtags in dashboard when there is no search strings.
 */
 $("#db_htag").keyup(function() {
	searchStr = $('#db_htag').val();
	if(searchStr == '') {
		$.ajax({
			 url: '/search/search/getAllHashtags',
             dataType: 'json'
        }).done(function(data) {
			$('.hashtag_container').empty();
			$.each(data, function(i, item) {
				var hash_url = "/hashtag?tag=" + i ;
				var inner_html = '<p class="rating_4 tags">';
				inner_html += '<a href="' + hash_url + '">' + "#" +i + '</a></p>';
				$('.hashtag_container').append(inner_html);
			});
		});
	}
});

/*
 * Disabling current tab when clicking a menu item in profile,disease & community pages.
 */
$(document).on('click', '.subtabs_list li', function() {
	$('.subtabs_list li').removeClass('current');
});
