/*
 * Declare global variables
 */
var POST_TYPE_TEXT = 'text';
var POST_TYPE_LINK = 'link';
var POST_TYPE_VIDEO = 'video';
var POST_TYPE_IMAGE = 'image';
var POST_TYPE_POLL = 'poll';
var POST_TYPE_ECARD = 'ecard';
var POST_TYPE_BLOG = 'blog';
var isTextPresent = false;
var isLinkDataPresent = false;
var isPhotoPresent = false;
var isVideoPresent = false;
var isCrawling = false;
var isEcard = false;
var isBlog = false;

var urlRegex = /(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i;
var previewImagesCount = 0;
var currentPreviewImgPos = 1;
var nextImg = null;
var prevImg = null;
var isPollDataPresent = false;
var defaultPlaceHolderText = 'Add more text';
var NEW_POST_COUNT = 0;
var NEW_QUESTION_COUNT = 0;
var NEW_POST_IDS = [];
var NEW_QUESTION_IDS = [];
$(document).on('click', '.start_discussion', function() {
	showPostingOption();
	$('#share_post_btn').attr('disabled', 'disabled');
});

$(document).on('click', '#cancel_post_btn', function() {
	cancelPosting();
});

function cancelPosting()
{
	$(".discussion_matter").hide();
	$(".start_discussion").show();
	resetForm();
}

function showPostingOption()
{
	$(".discussion_matter").show();
	$(".start_discussion").hide();

	// show the default form
	$('#posting_text_form').show();
	$('#PostDescription').focus();
}

/*
 * Function to show or hide the posting options
 */
$(document).on('click', '.posting_items ul.post_options li', function() {
	$(".posting_options_form").hide();
	$(this).parent().find("li").removeClass("active");
	$(this).addClass("active")
	$("#" + $(this).data("elem") + "_form").show();
	var postType = $(this).data("posttype");
	$("#PostPostType").val(postType);
	$("#posting_more_action").show();
	/*
	 * Add class to the textarea based on the posting option
	 */
	if ($(this).data("elem") == "posting_text")
	{
		$("#PostDescription").addClass("post_no_border_bottom");
	}
	else
	{
		$("#PostDescription").removeClass("post_no_border_bottom");
	}

	// change placeholder text
	var placeHolderText;
	switch (postType) {
		case POST_TYPE_TEXT:
			placeHolderText = defaultPlaceHolderText;
			break;
		case POST_TYPE_LINK:
		case POST_TYPE_VIDEO:
		case POST_TYPE_IMAGE:
                case POST_TYPE_POLL:
			placeHolderText = 'Add a note';
			break;
                case POST_TYPE_ECARD:
			placeHolderText = 'Type message here';
			break;
                case POST_TYPE_BLOG:
			placeHolderText = 'Add title';
			break;                      
	}
	$("#PostDescription").attr('placeholder', placeHolderText);

	// hide the validation error messages
	$('div#posting_errors').html('').hide();
	$('div#video_posting_error').html('').hide();

	// change the share button disabled status
	if (postType === POST_TYPE_TEXT) {
		handlePostTextChange();
	}
	else {
		changeShareBtnStatus();
	}
});

function setPostFormRules() {
	if ($.trim($('#PostLinkUrl').val()) !== '' && $.trim($('#PostLinkUrl').val()) !== null) {
		if ($('#PostTitle').length > 0) {
			$('#PostTitle').rules('remove', 'required');
		}
		$('#PostDescription').rules('remove', 'required');
	}
	else if ($.trim($('#PollForm_title').val()) !== '' && $.trim($('#PollForm_title').val()) !== null) {
		if ($('#PostTitle').length > 0) {
			$('#PostTitle').rules('remove', 'required');
		}
		$('#PostDescription').rules('remove', 'required');
	}
	else if ($('#PostPostType').val() === POST_TYPE_IMAGE) {
		if ($('#PostTitle').length > 0) {
			$('#PostTitle').rules('remove', 'required');
		}
		$('#PostDescription').rules('remove', 'required');
	}
	else if ($('#PostPostType').val() === POST_TYPE_VIDEO) {
		if ($('#PostTitle').length > 0) {
			$('#PostTitle').rules('remove', 'required');
		}
		$('#PostDescription').rules('remove', 'required');
	}
	else {
		if ($('#PostTitle').length > 0) {
			$('#PostTitle').rules('add', {required: true});
		}
		$('#PostDescription').rules('add', {required: true});
	}
}
//function setPostFormRules() {
//    if ($.trim($('#PostLinkUrl').val()) === '') {
//        if ($('#PostTitle').length > 0) {
//            $('#PostTitle').rules('add', {required: true});
//        }
//        $('#PostDescription').rules('add', {required: true});
//    }
//    else {
//        if ($('#PostTitle').length > 0) {
//            $('#PostTitle').rules('remove', 'required');
//        }
//        $('#PostDescription').rules('remove', 'required');
//    }
//}

function isPostFormValid() {
	setPostFormRules();
	return $('#postForm').valid();
}

/**
 * Function to handle anonymous post checkbox
 */
$(document).on('click', '#PostIsAnonymous', function() {
	if ($(this).is(':checked')) {
		$('.normal_post_thumb').addClass('hidden');
		$('.anonymous_post_thumb').removeClass('hidden');
	}
	else {
		$('.normal_post_thumb').removeClass('hidden');
		$('.anonymous_post_thumb').addClass('hidden');
	}
});

/**
 * Function to create post
 */
$(document).on('click', '#share_post_btn', function() {
	var submitBtn = this;
	var leaveCommunityBtn = $('.btn_leave');
	if (isPostFormValid()) {
		if ($('#PostPostType').val() != POST_TYPE_POLL || validatePollForm()) {
			var loading = Ladda.create(submitBtn);
			loading.start();
			$.ajax({
				type: 'POST',
				url: '/post/api/createPost',
				data: $(form).serialize(),
				dataType: 'json',
				beforeSend: function() {
					$(submitBtn).attr('disabled', 'disabled');
					leaveCommunityBtn.attr('disabled', 'disabled');
				},
				success: function(result) {
					cancelPosting();
					$(submitBtn).removeAttr('disabled');
					leaveCommunityBtn.removeAttr('disabled');
					loading.stop();
					if (result.success === true) {
						$(submitBtn).attr('disabled', 'disabled');
						$('#PostPostType').val(POST_TYPE_TEXT);
						
						if ($('#new_posts_notification').is(':visible'))  {
							$('#new_posts_notification').click();
						} else {
							$('#post_list').prepend(result.content);
						}
						
						setTimeout(function() {
							applyTimeAgo();
						}, 60000);
						if ($('#post_container').hasClass('hide')) {
							$('#no_posts_msg').addClass('hide');
							$('#post_container').removeClass('hide');
						}
						/*
						 * Send the new post details to the socket
						 */
						emitEventToRooms('new_post', result);
					}
					else if (result.error === true) {
						bootbox.alert(result.message, function() {
							if (result.errorType && (result.errorType === 'fatal')) {
								window.location.reload();
								window.scrollTo(0, 0);
							}
						});
					}
				}
			});
		}
	}
});

/**
 * Function to reset the form to enter links
 */
function resetLinkForm() {
	clearPreview();
}

/**
 * Function to reset post form
 */
function resetForm() {
	$(form)[0].reset();
	resetPollForm();
	resetLinkForm();
	resetPostVideoForm();
	resetPostingOptions();
        resetPostPhotoForm();
	isTextPresent = false;
	$('#PostDescription').attr('placeholder', defaultPlaceHolderText);
	$('#posting_errors').html('').hide();
	$('#video_posting_error').html('').hide();
	$('.normal_post_thumb').removeClass('hidden');
	$('.anonymous_post_thumb').addClass('hidden');
}

function resetPostingOptions() {
	$('.posting_options_form').hide();
	$('ul.post_options').find('li').removeClass('active');
	$('ul.post_options').find('li.posting_post').addClass('active');
}

/**
 * Function to apply timeago update plugin
 */
function applyTimeAgo() {
	$(".timeago").timeago();
}

/**
 * Function to paginate posts
 */
function paginatePostData() {
	if ($.autopager) {
		$.autopager({
			// a selector that matches a element of next page link
			link: 'span.next a',
			// a selector that matches page contents
			content: '.posting_area',
			// where contents would be appended.
			appendTo: '#post_list',
			// a callback function to be triggered when loading start 
			start: function(current, next) {
				// show loading status
				$("#post_loading").removeClass('hide');
			},
			// a function to be executed when next page was loaded. 
			load: function(current, next) {
				// hide loading status
				$("#post_loading").addClass('hide');

				// apply timeago plugin
				applyTimeAgo();
			}
		});
	}
}

$(document).ready(function() {
	// apply timeago plugin
	applyTimeAgo();

	// paginate posts
	paginatePostData();

	// post photo uploader
	//initPostVideoUploader();    
});

// The post button will be inactive till the user enters text in the posting area
$(document).on('keyup', '#PostTitle, #PostDescription', function() {
	handlePostTextChange();
});
$(document).on('drop', '#PostTitle, #PostDescription', function() {
	// short pause to wait for drop to complete
	setTimeout(function() {
		handlePostTextChange();
	}, 100);
});
$(document).on('paste', '#PostTitle, #PostDescription', function() {
	// short pause to wait for paste to complete
	setTimeout(function() {
		handlePostTextChange();
	}, 100);
});

/**
 * Function to handle change in text in the posting area
 */
function handlePostTextChange() {
	if ($('#PostTitle').length > 0) {
		$('#PostTitle').rules('remove', 'required');
	}
	if ($('#PostDescription').length > 0) {
		$('#PostDescription').rules('remove', 'required');
	}

	var isDescriptionPresent = ($.trim($("#PostDescription").val()) !== '') ? true : false;
	if ($('#PostTitle').is(':visible')) {
		var isTitlePresent = ($.trim($("#PostTitle").val()) !== '') ? true : false;
		isTextPresent = (isTitlePresent && isDescriptionPresent) ? true : false;
	}
	else {
		isTextPresent = isDescriptionPresent;
	}
	changeShareBtnStatus();
}

function changeShareBtnStatus() {
	var postType = $('#PostPostType').val();
	var allowPosting = false;
	switch (postType) {
		case POST_TYPE_TEXT:
			allowPosting = isTextPresent;
			break;
		case POST_TYPE_LINK:
			allowPosting = isLinkDataPresent;
			break;
		case POST_TYPE_VIDEO:
			allowPosting = isVideoPresent;
			break;
		case POST_TYPE_IMAGE:
			allowPosting = isPhotoPresent;
			break;
		case POST_TYPE_POLL:
			allowPosting = isPollDataPresent;
			break;
		case POST_TYPE_ECARD:
			allowPosting = isEcard;
			break;                        
	}
	if (allowPosting === true) {
		$('#share_post_btn').removeAttr('disabled');
	}
	else {
		$('#share_post_btn').attr('disabled', 'disabled');
	}
}

/**
 * Function to like a post
 */
$(document).on('click', '.like_btn', function() {
	var likeBtn = this;
	if (!$(this).attr('disabled')) {
		var postId = $(this).data('post_id');
		$.ajax({
			type: 'POST',
			url: '/post/api/likePost',
			data: {'postId': postId},
			dataType: 'json',
			beforeSend: function() {
				$(likeBtn).attr('disabled', 'disabled');
			},
			success: function(result) {
				$(likeBtn).removeAttr('disabled');
				if (result.success === true) {
					$(likeBtn).removeClass('like_btn').addClass('unlike_btn');
					$(likeBtn).html('Unlike');
					var likedUsersList = $('#liked_users_list_' + postId);
					if (result.likeCount > 0) {
						likedUsersList.removeClass('hide');
						likedUsersList.find('span').html(result.content);
					}
					else {
						likedUsersList.addClass('hide');
					}

					emitEventToRooms('new_like', {
						likeBtn: this, postId: postId,
						likeCount: result.likeCount,
						username: app.loggedInUserName,
						postInfo: result.postInfo,
						lastLikedUsers: result.lastLikedUsers
					});
				}
				else if (result.error === true) {
					bootbox.alert(result.message, function() {
						$('#post_' + postId).remove();
						hidePostListingIfNoPosts();
					});
				}
			}
		});
	}
});

/**
 * Function to unlike a post
 */
$(document).on('click', '.unlike_btn', function() {
	var unlikeBtn = this;
	if (!$(this).attr('disabled')) {
		var postId = $(this).data('post_id');
		$.ajax({
			type: 'POST',
			url: '/post/api/unlikePost',
			data: {'postId': postId},
			dataType: 'json',
			beforeSend: function() {
				$(unlikeBtn).attr('disabled', 'disabled');
			},
			success: function(result) {
				$(unlikeBtn).removeAttr('disabled');
				if (result.success === true) {
					$(unlikeBtn).removeClass('unlike_btn').addClass('like_btn');
					$(unlikeBtn).html('Like');
					var likedUsersList = $('#liked_users_list_' + postId);
					if (result.likeCount > 0) {
						likedUsersList.removeClass('hide');
						likedUsersList.find('span').html(result.content);
					}
					else {
						likedUsersList.addClass('hide');
					}

					emitEventToRooms('new_unlike', {
						likeBtn: this, postId: postId,
						likeCount: result.likeCount,
						username: app.loggedInUserName,
						lastLikedUsers: result.lastLikedUsers,
						postInfo: result.postInfo
					});
				}
				else if (result.error === true) {
					bootbox.alert(result.message, function() {
						$('#post_' + postId).remove();
						hidePostListingIfNoPosts();
					});
				}
			}
		});
	}
});

/**
 * Function to list the likes on clicking like count
 */
$(document).on('click', '.view_likes', function() {
	var postId = $(this).data('post_id');
	var modalTitle = 'People Who Like This';
	var modalClassName = 'likes_modal';
	loadingDialog = bootbox.dialog({
		className: modalClassName,
		message: $('#loading_dialog').html(),
		title: modalTitle
	});
	$.ajax({
		type: 'POST',
		url: '/post/api/listLikes',
		data: {'postId': postId},
		dataType: 'json',
		success: function(result) {
			bootbox.dialog({
				className: modalClassName,
				message: result.message,
				title: modalTitle
			});
			loadingDialog.modal('hide');
		}
	});
});

/**
 * Function to handle anonymous comment checkbox
 */
$(document).on('click', '.comment_form input[type="checkbox"]', function() {
	var commentForm = $(this).closest('.post_comment_section_form');
	if ($(this).is(':checked')) {
		commentForm.find('.normal_thumb').addClass('hidden');
		commentForm.find('.anonymous_thumb').removeClass('hidden');
	}
	else {
		commentForm.find('.normal_thumb').removeClass('hidden');
		commentForm.find('.anonymous_thumb').addClass('hidden');
	}
});

/**
 * Function to submit post comment
 */
$(document).on('keyup', '.comment_form textarea, .comment_anonymous_box input[type="checkbox"]', function(event) {
	var enterKeyCode = 13;
	var commentXHR = null;
	// if enter key is pressed, without holding the shift key, add the comment
	if (($(this).prop('disabled') === false) && (commentXHR === null) && (event.keyCode === enterKeyCode) && (event.shiftKey === false)) {
		var commentForm = $(this).closest('form.comment_form');
		if (isCommentFormValid(commentForm)) {
			var commentData = commentForm.serialize();
			commentForm.find('textarea').prop('disabled', true);
			commentForm.find('checkbox').prop('disabled', true);
			commentXHR = $.ajax({
				type: 'POST',
				url: '/post/api/addComment',
				data: commentData,
				dataType: 'json',
				success: function(result) {
					commentXHR = null;
					commentForm.find('textarea').prop('disabled', false);
					commentForm.find('checkbox').prop('disabled', false);
					if (result.success === true) {
						updateNewComment(result);
						commentForm.find('textarea').html('').val('');
						emitEventToRooms('new_comment', result);
					}
					else if (result.error === true) {
						bootbox.alert(result.message, function() {
							if (result.errorType && result.errorType === 'postDeleted') {
								var postId = result.postId;
								$('#post_' + postId).remove();
								hidePostListingIfNoPosts();
							}
							else if (result.errorType && (result.errorType === 'fatal')) {
								window.location.reload();
								window.scrollTo(0, 0);
							}
						});
					}
				}
			});
		}
	}
	else {
		return false;
	}
});

/**
 * Checks if any posts are present, if no posts are present, hides the listing
 * and displays 'no posts' message 
 */
function hidePostListingIfNoPosts() {
	if ($('#post_container .posting_area').length === 0) {
		$('#post_container').addClass('hide');
		$('#no_posts_msg').removeClass('hide');
	}
}

/**
 * Function to update a new comment to the comments list of a post
 * 
 * @param {Object} result
 */
function updateNewComment(result) {
	var postId = result.postId;
	$('#comment_list_' + postId).prepend(result.content);
	setTimeout(function() {
		applyTimeAgo();
	}, 60000);
	$('#comment_count_' + postId).html(result.commentCount);

	// based on user, hide/show buttons for realtime updated comment
	var loggedInUserId = parseInt(app.loggedInUserId);
	var postedUserId = parseInt(result.postedUserId);
	var commentedUserId = parseInt(result.commentedUserId);
	var isCommentedUser = (loggedInUserId === commentedUserId);
	var isPostedUser = (loggedInUserId === postedUserId);
	var canDelete = (isCommentedUser || isPostedUser) ? true : false;
	var commentId = result.commentId;
	var commentRow = $('#comment_' + commentId);
	if (!canDelete) {
		commentRow.find('.delete_comment_btn').parent('li').remove();
	}
	if (!isCommentedUser) {
		var reportAbuseLI = '<li><a class="report_abuse report_abuse_comment" data-comment_id="' + commentId + '">Report Abuse</a></li>';
		commentRow.find('.edit_field .dropdown-menu').append(reportAbuseLI);
	}
}

function emitEventToRooms(eventName, params) {
	$.ajax({
		type: 'POST',
		url: '/post/api/getPostFollowingRooms',
		data: {'post': params.postInfo},
		dataType: 'json',
		success: function(rooms) {
			rooms.forEach(function(room) {
				var data = {'room': room};
				$.extend(data, params);
				socket.emit(eventName, data);
			});
		}
	});
}

/**
 * Function to validate the comment form
 * 
 * @param {element} commentForm
 * @returns {boolean}
 */
function isCommentFormValid(commentForm) {
	var maxLength = 5000;
	commentForm.validate({
		errorPlacement: function(error, element) {
			error.appendTo(element.parent());
		},
		errorLabelContainer: false,
		errorElement: 'span',
		errorClass: 'help-block',
		highlight: function(element) {
			$(element).closest('.form-group').addClass('error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('error');
		},
		rules: {
			'data[Comment][comment_text]': {
				'required': true,
				'maxlength': maxLength
			}
		},
		messages: {
			'data[Comment][comment_text]': {
				'required': 'Please enter your comment before submission',
				'maxlength': 'Cannot be more than ' + maxLength + ' characters long'
			}
		}
	});
	return commentForm.valid();
}

/**
 * Function to list all the comments for a post
 */
$(document).on('click', '.load_more_comments', function() {
	var loadMore = $(this);
	var loadMoreLink = loadMore.find('a');
	var postId = loadMoreLink.data('post_id');
	var postedInType = $('#PostPostedInType').val();
	$.ajax({
		type: 'POST',
		url: '/post/api/listComments',
		data: {
			'postId': postId,
			'postedInType': postedInType
		},
		dataType: 'json',
		beforeSend: function() {
			var loadingImg = '<div class="text-center"><img alt="Loading..." src="/img/loading.gif"></div>';
			loadMore.html(loadingImg);
		},
		success: function(result) {
			loadMore.remove();
			if (result.success === true) {
				$('#comment_list_' + postId).html(result.message);
			}
		}
	});
});

/**
 * Show/hide delete comment icon on mouseover/mouseout on comment
 */
$(document).on('mouseover', '.comment_section', function() {
	$(this).find('.edit_field').removeClass('hide');
});
$(document).on('mouseout', '.comment_section', function() {
	$(this).find('.edit_field').addClass('hide');
});

/**
 * Function to delete a comment
 */
$(document).on('click', '.delete_comment_btn', function() {
	var commentId = $(this).data('comment_id');
	if (commentId > 0) {
		var commentCountElement = $(this).closest('.posting_area').find('.comment_count');
		var commentCount = commentCountElement.html();
		var confirmMessage = "Are you sure you want to delete this comment?";
		bootbox.confirm(confirmMessage, function(isConfirmed) {
			if (isConfirmed) {
				$('#comment_' + commentId).remove();
				commentCount = commentCount - 1;
				commentCountElement.html(commentCount);
				$.ajax({
					type: 'POST',
					url: '/post/api/deleteComment',
					data: {'commentId': commentId},
					dataType: 'json',
					success: function(result) {
						if (result.success === true) {
							postId = result.postId;
							emitEventToRooms('comment_delete', {
								commentId: commentId,
								postId: postId,
								commentCount: commentCount,
								postInfo: result.postInfo
							});
						}
					}
				});
			}
		});
	}
});

/**
 * Functin to filter posts
 */
$(document).on('click', 'ul#post_filter li a', function() {
	var filterValue = $(this).data('filter_value');
	var postedIn = $('#PostPostedIn').val();
	var postedInType = $('#PostPostedInType').val();
	var isLibray = ($('#isLibray').val()) ? $('#isLibray').val() : false;
	$("#filter_value_hidden").val(filterValue);
	$('#new_posts_notification').hide();
	resetNewPostCount();
	$.ajax({
		type: 'POST',
		url: '/post/list/filterPosts',
		data: {
			'filterValue': filterValue,
			'postedIn': postedIn,
			'postedInType': postedInType,
			'isLibray': isLibray
		},
		dataType: 'json',
		beforeSend: function() {
			var loadingImg = '<div class="text-center"><img alt="Loading..." src="/img/loader.gif"></div>';
			var loadingHTML = '<div class="posting_area">' + loadingImg + '</div>';
			$('#post_list').html(loadingHTML);
			disableAutoPagination();
		},
		success: function(result) {
			if (result.success === true) {
				$('#post_list').html(result.content);
				paginatePostData();
			}
			else if (result.error === true) {
				var errorHTML = '<div class="posting_area">' + result.content + '</div>';
				$('#post_list').html(errorHTML);
			}
		}
	});
});

/**
 * Function to disable auto paginatination
 */
function disableAutoPagination() {
	if ($.autopager) {
		$.autopager({
			autoLoad: false
		});
	}
}

/**
 * Show/hide delete post icon on mouseover/mouseout on post
 */
$(document).on('mouseover', '.posting_area, .questionnaire_div', function() {
	$(this).find('.delete_post_btn').removeClass('hide');
});
$(document).on('mouseout', '.posting_area, .questionnaire_div', function() {
	$(this).find('.delete_post_btn').addClass('hide');
});

/**
 * Function to delete a post
 */
$(document).on('click', '.delete_post_btn', function() {
	var postId = $(this).data('post_id');
	if (postId > 0) {
		var postDiv = $('#post_' + postId);
		var postType = 'post';
		if (postDiv.hasClass('questionnaire_div')) {
			postType = 'question';
		}
		var confirmMessage = 'Are you sure you want to delete this ' + postType + '?';
		bootbox.confirm(confirmMessage, function(isConfirmed) {
			if (isConfirmed) {
				postDiv.remove();
				var eventName = 'post_delete';
				if (postType === 'post') {
					hidePostListingIfNoPosts();
				}
				else if (postType === 'question') {
					eventName = 'question_delete';
					if ($('#questions_list .questionnaire_div').length === 0) {
						$('#no_questions_msg').removeClass('hide');
					}
				}
				$.ajax({
					type: 'POST',
					url: '/post/api/deletePost',
					data: {'postId': postId},
					dataType: 'json',
					success: function(result) {
						emitEventToRooms(eventName, result);
					}
				});
			}
		});
	}
});


$(document).on('click', 'li.posting_url', function() {
	$('#posting_url_form').removeClass('hide');
	$('#link_input_section').removeClass('hide');
	$('#PostLink').val('').removeAttr('disabled');
});

function grabUrl() {
	var link = $('#PostLink').val();
	$.ajax({
		type: 'POST',
		url: '/post/api/textCrawler',
		data: {
			'link': link
		},
		dataType: 'json',
		beforeSend: function() {
			$('#grab_url_btn').attr('disabled', 'disabled');
			$('#PostLink').addClass("textbox_loader").attr('disabled', 'disabled');
			$('#preview').hide();

			isCrawling = true;
		},
		success: function(response) {
			$('#grab_url_btn').removeAttr('disabled');
			if (response.url && ((response.title !== '') || (response.video !== 'no') || (response.images !== '' && response.images !== false && response.images !== null))) {
				$('#PostLink').removeClass('textbox_loader');
				$('#link_input_section').addClass('hide');
				$('#preview').show();
				$('#previewTitle').html(response.title);
				$('#previewUrl').html(response.cannonicalUrl);
				$('#previewDescription').html(response.description);
				try {
					if (response.images !== '' && response.images !== false && response.images !== null) {
						images = (response.images).split("|");
						response.images = images;
						$('#previewImages').show();
						images.length = parseInt(images.length);
						previewImagesCount = images.length;
						if (previewImagesCount > 1) {
							$('#previewImagesNav').show();
							currentPreviewImgPos = 1;
						}
						else {
							$('#previewImagesNav').hide();
						}
						var appendImage = "";
						for (i = 0; i < images.length; i++) {
							if (i === 0)
								appendImage += "<img id='imagePreview" + i + "' src='" + images[i] + "' style='width: 130px; height: auto' ></img>";
							else
								appendImage += "<img id='imagePreview" + i + "' src='" + images[i] + "' style='width: 130px; height: auto; display: none' ></img>";
						}
						$('#previewImage').html("<a href='" + response.pageUrl + "' target='_blank'>" + appendImage + "</a><div id='whiteImage' style='width: 130px; color: transparent; display:none;'>...</div>");
					}
					else {
						$('#previewImages').hide();
					}
				} catch (err) {
					$('#previewImages').hide();
				}
				setLinkData(response);
				isLinkDataPresent = true;
				isCrawling = false;
				changeShareBtnStatus();
				isPostFormValid();
			}
			else {
				isCrawling = false;
				setPostFormRules();
				showPostLinkError('Please post a valid URL.');
				$('#PostLink').removeClass('textbox_loader').removeAttr('disabled');
			}
		}
	});
}

$(document).on('click', '#previewImagesNav a.prev', function() {
	var currentImg = $('#previewImage img:visible');
	currentImg.hide();
	if (currentPreviewImgPos === 1) {
		currentPreviewImgPos = previewImagesCount;
		prevImg = $('#previewImage img:last');
	}
	else {
		currentPreviewImgPos--;
		prevImg = currentImg.prev('img');
	}
	prevImg.show();
	$('#PostLinkImage').val(prevImg.attr('src'));
});

$(document).on('click', '#previewImagesNav a.next', function() {
	var currentImg = $('#previewImage img:visible');
	currentImg.hide();
	if (currentPreviewImgPos === previewImagesCount) {
		currentPreviewImgPos = 1;
		nextImg = $('#previewImage img:first');
	}
	else {
		currentPreviewImgPos++;
		nextImg = currentImg.next('img');
	}
	nextImg.show();
	$('#PostLinkImage').val(nextImg.attr('src'));
});

$(document).on('click', '#closePreview', function() {
	$('#preview').fadeOut("fast", function() {
		clearPreview();
		$('#PostLink').val('').removeClass('textbox_loader').removeAttr('disabled');
		$('#link_input_section').removeClass('hide');

		//resetPostingOptions();
	});
});

function clearPreview() {
	$('#preview').hide();
	$('#previewImage').html("");
	$('#previewTitle').html("");
	$('#previewUrl').html("");
	$('#previewDescription').html("");
	resetLinkData();
	changeShareBtnStatus();
	$('#grab_url_btn').attr('disabled', 'disabled');
}

function setLinkData(response) {
	$('#PostLinkTitle').val(response.title);
	$('#PostLinkUrl').val(response.url);
	$('#PostLinkPageUrl').val(response.pageUrl);
	$('#PostLinkCannonicalUrl').val(response.cannonicalUrl);
	$('#PostLinkDescription').val(response.description);
	var selectedImg = '';
	if (response.images !== false) {
		if (response.images instanceof Array) {
			selectedImg = response.images[0];
		}
		else {
			selectedImg = response.images;
		}
	}
	$('#PostLinkImage').val(selectedImg);
	$('#PostLinkVideo').val(response.video);

	var videoIframe = '';
	if (response.video === 'yes') {
		videoIframe = response.videoIframe;
	}
	$('#PostLinkVideoIframe').val(videoIframe);
}

function resetLinkData() {
	$('#PostLinkTitle').val('');
	$('#PostLinkUrl').val('');
	$('#PostLinkPageUrl').val('');
	$('#PostLinkCannonicalUrl').val('');
	$('#PostLinkDescription').val('');
	$('#PostLinkImage').val('');
	$('#PostLinkVideo').val('');
	$('#PostLinkVideoIframe').val('');
	isLinkDataPresent = false;
}

function showPostLinkError(message) {
	if ($("#posting_errors span[for='PostLink']").length > 0) {
		$("#posting_errors span[for='PostLink']").html(message).show();
	}
	else {
		var PostLinkErrorHTML = '<span for="PostLink" class="help-block">' + message + '</span>';
		$("#posting_errors").append(PostLinkErrorHTML);
	}
	$("#posting_errors").show();
}

var postLink;
$(document).on('keyup', '#PostLink', function(e) {
	postLink = this;
	checkPostLink();
});

$(document).on('paste', '#PostLink', function(e) {
	postLink = this;
	// Short pause to wait for paste to complete
	setTimeout(function() {
		if ($('#PostLink').valid()) {
			grabUrl();
		}
	}, 100);
});

$(document).on('drop', '#PostLink', function(e) {
	postLink = this;
	// Short pause to wait for drop to complete
	setTimeout(function() {
		if ($('#PostLink').valid()) {
			grabUrl();
		}
	}, 100);
});

function checkPostLink() {
	isLinkDataPresent = false;
	if ($.trim($(postLink).val()) !== "") {
		var text = $(postLink).val();

		// add http if not present
		if (text.substr(0, 7) !== 'http://') {
			if (text.substr(0, 8) != 'https://') {
				text = 'http://' + text;
			}
		}
		if (text.substr(text.length - 1, 1) !== '/') {
			text = text + '/';
		}

		if (urlRegex.test(text)) {
			$('#grab_url_btn').removeAttr('disabled');
		}
		else {
			$('#grab_url_btn').attr('disabled', 'disabled');
			$('#preview').fadeOut("fast", function() {
				clearPreview();
			});
		}
	}
	else {
		$('#grab_url_btn').attr('disabled', 'disabled');
	}
}

/**
 * Grab url on clicking 'Add' button
 */
$(document).on('click', '#grab_url_btn', function() {
	grabUrl();
});

/**
 * Show friend request respond buttons in like pop up, on clicking respond btn
 */
$(document).on('click', '.frnd_request_respond_btn', function() {
	$(this).next('.frnd_request_respond_box').removeClass('hide');
	$(this).hide();
});
/**
 * Load new post on clicking the new posts alert.
 */

$(document).on('click', '#new_posts_notification', function() {
	//var filterValue = $("#filter_value_hidden").val();
	var filterValue = 9;
	var postedIn = $('#PostPostedIn').val();
	var postedInType = $('#PostPostedInType').val();
	var isLibray = ($('#isLibray').val()) ? $('#isLibray').val() : false;
	$.ajax({
		type: 'POST',
		url: '/post/list/filterPosts',
		data: {
			'filterValue': filterValue,
			'postedIn': postedIn,
			'postedInType': postedInType,
			'isLibray': isLibray
		},
		dataType: 'json',
		beforeSend: function() {
			var loadingImg = '<div class="text-center"><img alt="Loading..." src="/img/loading.gif"></div>';
			$('#new_posts_notification').html(loadingImg);
			disableAutoPagination();
		},
		success: function(result) {
			if (result.success === true) {
				if ($('#post_container').hasClass('hide')) {
					$('#no_posts_msg').addClass('hide');
					$('#post_container').removeClass('hide');
				}
				$('#post_list').html(result.content);
				paginatePostData();
				$('#new_posts_notification').html("");
				$('#new_posts_notification').hide();
				resetNewPostCount();
			}
			else if (result.error === true) {
				var errorHTML = '<div class="posting_area">' + result.content + '</div>';
				$('#post_list').html(errorHTML);
			}
		}
	});
});


function getNewPostCount() {
	return NEW_POST_COUNT;
}

/**
 * Show/hide the new post update in the posting page
 */
function updateNewPostCount() {
	if (parseInt(NEW_POST_COUNT) > 0) {
		var message = 'Show ' + NEW_POST_COUNT + ' new update(s) with default filter option';
		$('#new_posts_notification').html(message);
		$('#new_posts_notification').show();
	} else {
		$('#new_posts_notification').hide();
	}
}

/**
 * Increment the new post count and add the postid to new post ids list
 * 
 * @param {Integer} postId
 */
function incNewPostCount(postId) {
	NEW_POST_COUNT = NEW_POST_COUNT + 1;
	NEW_POST_IDS.push(postId);
	updateNewPostCount();
}

/*
 * Decrement the new post count and remove the postid from new post ids list
 * 
 * @param {Integer} postId
 */
function decNewPostCount(postId) {
	var deletedPostIndex = $.inArray(postId, NEW_POST_IDS);
	if (deletedPostIndex > -1) {
		NEW_POST_IDS.splice(deletedPostIndex, 1);
		NEW_POST_COUNT = NEW_POST_COUNT - 1;
		updateNewPostCount();
	}
}

/*
 * Resets the new post count and resets new post ids list
 */
function resetNewPostCount() {
	NEW_POST_COUNT = 0;
	NEW_POST_IDS = [];
}

/**
 * Load new question(s) on clicking the new updates alert.
 */
$(document).on('click', '#new_questions_notification', function() {
	diseaseId = $('#question_form #PostPostedIn').val();
	$.ajax({
		type: 'POST',
		url: '/listForumQuestions/' + diseaseId,
		beforeSend: function() {
			var loadingImg = '<div class="text-center"><img alt="Loading..." src="/img/loading.gif"></div>';
			$('#new_questions_notification').html(loadingImg);
		},
		success: function(result) {
			$('#questions_list').html(result);
			$('#new_questions_notification').html('');
			$('#new_questions_notification').addClass('hide');
			resetNewQuestionCount();
		}
	});
});

/**
 * Function to show/hide the new question count
 */
function updateNewQuestionCount() {
	if (parseInt(NEW_QUESTION_COUNT) > 0) {
		var message = 'Show ' + NEW_QUESTION_COUNT + ' new update(s)';
		$('#new_questions_notification').html(message);
		$('#new_questions_notification').removeClass('hide');
	} else {
		$('#new_questions_notification').addClass('hide');
	}
}

/**
 * Increment the new question count and add the questionId to new question ids list
 * 
 * @param {Integer} questionId
 */
function incNewQuestionCount(questionId) {
	NEW_QUESTION_COUNT = NEW_QUESTION_COUNT + 1;
	NEW_QUESTION_IDS.push(questionId);
	updateNewQuestionCount();
}

/**
 * Decrement the new question count and remove the questionId from new question ids list
 * 
 * @param {Integer} questionId
 */
function decNewQuestionCount(questionId) {
	var deletedQuestionIndex = $.inArray(questionId, NEW_QUESTION_IDS);
	if (deletedQuestionIndex > -1) {
		NEW_QUESTION_IDS.splice(deletedQuestionIndex, 1);
		NEW_QUESTION_COUNT = NEW_QUESTION_COUNT - 1;
		updateNewQuestionCount();
	}
}

/**
 * Function to reset the new question count and new question ids list
 */
function resetNewQuestionCount() {
	NEW_QUESTION_COUNT = 0;
	NEW_QUESTION_IDS = [];
}

/**
 * Function to report abuse a comment
 */
var commentCountElement;
var commentCount;
var commentId;
$(document).on('click', '.report_abuse_comment', function() {
	commentId = $(this).data('comment_id');
	if (commentId > 0) {
		commentCountElement = $(this).closest('.posting_area').find('.comment_count');
		commentCount = commentCountElement.html();
		$('#report_abuse_comment_dialog form input[type="hidden"]').val(commentId);
		$('#report_abuse_comment_dialog form textarea').val('');
		$('#report_abuse_comment_dialog form input[type="checkbox"]').prop('checked', false);
		$('#report_abuse_comment_dialog').modal('show');
	}
});
$(document).on('click', '#confirm_report_abuse_comment', function() {
	$('#comment_' + commentId).remove();
	commentCount = commentCount - 1;
	commentCountElement.html(commentCount);
	var data = $('#report_abuse_comment_dialog form').serialize();
	$('#report_abuse_comment_dialog form input[type="hidden"]').val('');
	$('#report_abuse_comment_dialog').modal('hide');
	$.ajax({
		type: 'POST',
		url: '/post/api/reportAbuseComment',
		data: data,
		dataType: 'json',
		success: function(result) {
			if (result.error === true) {
				bootbox.alert(result.message);
			}
		}
	});
});

/**
 * Function to report abuse a post
 */
var postId;
$(document).on('click', '.report_abuse_post', function() {
	postId = $(this).data('post_id');
	if (postId > 0) {
		$('#report_abuse_post_dialog form input[type="hidden"]').val(postId);
		$('#report_abuse_post_dialog form textarea').val('');
		$('#report_abuse_post_dialog form input[type="checkbox"]').prop('checked', false);
		$('#report_abuse_post_dialog').modal('show');
	}
});
$(document).on('click', '#confirm_report_abuse_post', function() {
	$('#post_' + postId).remove();
	hidePostListingIfNoPosts();
	var data = $('#report_abuse_post_dialog form').serialize();
	$('#report_abuse_post_dialog form input[type="hidden"]').val('');
	$('#report_abuse_post_dialog').modal('hide');
	$.ajax({
		type: 'POST',
		url: '/post/api/reportAbusePost',
		data: data,
		dataType: 'json',
		success: function(result) {
			if (result.error === true) {
				bootbox.alert(result.message);
			}
		}
	});
});

/**
 * Function to play post video
 */
$(document).on('click', '.play_video', function() {
	var $img = $(this).find('img.img-responsive');
	var $width = $img.width();
	var $height = $img.height();
	var $videoId = $(this).attr('data-video_id');
	$(this).embedVimeoPlayer($videoId, $width, $height);
});

/**
 * Function to add a question
 */
$(document).on('click', '#add_question_btn', function() {
	var submitBtn = this;
	if (isQuestionFormValid()) {
		var loading = Ladda.create(submitBtn);
		loading.start();
		$.ajax({
			type: 'POST',
			url: '/post/api/addQuestion',
			data: $('#question_form').serialize(),
			dataType: 'json',
			beforeSend: function() {
				$(submitBtn).prop('disabled', true);
			},
			success: function(result) {
				$('#question_form')[0].reset();
				$(submitBtn).prop('disabled', false);
				loading.stop();
				if (result.success === true) {
					$('#no_questions_msg').addClass('hide');
					$('#questions_list').prepend(result.content);
					if($('.show_more_questions').length>0){
						var nextPageOffset =$('.show_more_questions a').attr('data-offset');
						nextPageOffset=parseInt(nextPageOffset)+1;
						$('.show_more_questions a').attr('data-offset',nextPageOffset);
					}
					setTimeout(function() {
						applyTimeAgo();
					}, 60000);

					/**
					 * Send the new question details to the socket
					 */
					var data = {
						room: $('#PostPostedInRoom').val(),
						postId: result.postId
					};
					socket.emit('new_question', data);
				}
				else if (result.error === true) {
					bootbox.alert(result.message);
				}
			}
		});
	}
});

/*
 * Function to validate the question form
 * 
 * @returns {boolean}
 */
function isQuestionFormValid() {
	var maxLength = 150;
	var questionForm = $('#question_form');
	questionForm.validate({
		errorLabelContainer: 'div#question_form_error',
		errorClass: 'error-block',
		highlight: function(element) {
			$(element).addClass('error');
		},
		unhighlight: function(element) {
			$(element).removeClass('error');
		},
		rules: {
			'data[Post][question]': {
				'required': true,
				'maxlength': maxLength
			}
		},
		messages: {
			'data[Post][question]': {
				'required': 'Please enter the question.',
				'maxlength': 'Question cannot be more than ' + maxLength + ' characters long.'
			}
		}
	});
	return questionForm.valid();
}

var diseaseId;
$(document).ready(function() {
	if ($('#questions_list').length > 0) {
		diseaseId = $('#question_form #PostPostedIn').val();
		$.ajax({
			type: 'POST',
			url: '/listForumQuestions/' + diseaseId,
			success: function(result) {
				$("#questions_loading").addClass('hide');
				$('#questions_list').html(result);
			}
		});
	}
});

$(document).on('click', '.show_more_questions', function() {
	var nextPageOffset = $(this).find('a').attr('data-offset');
	var nextPageUrl='/listForumQuestions/'+diseaseId+'/offset:'+nextPageOffset;
	$(this).remove();
	$("#questions_loading").removeClass('hide');
	$.ajax({
		type: 'POST',
		url: nextPageUrl,
		success: function(result) {
			$("#questions_loading").addClass('hide');
			$('#questions_list').append(result);
			setTimeout(function() {
				applyTimeAgo();
			}, 60000);
		}
	});
});

/**
 * Function to handle anonymous answer checkbox
 */
$(document).on('click', '.answer_form input[type="checkbox"]', function() {
	var answerFormContainer = $(this).closest('.answer_form_container');
	if ($(this).is(':checked')) {
		answerFormContainer.find('.normal_thumb, .original_username').addClass('hidden');
		answerFormContainer.find('.anonymous_thumb, .anonymous_username').removeClass('hidden');
	}
	else {
		answerFormContainer.find('.normal_thumb, .original_username').removeClass('hidden');
		answerFormContainer.find('.anonymous_thumb, .anonymous_username').addClass('hidden');
	}
});

/**
 * Function to submit question answer
 */
$(document).on('click', '.add_answer_btn', function(event) {
	if (($(this).prop('disabled') === false)) {
		var submitBtn = this;
		var answerForm = $(this).closest('form.answer_form');
		if (isAnswerFormValid(answerForm)) {
			var answerData = answerForm.serialize();
			answerForm.find('textarea, checkbox, button').prop('disabled', true);
			var loading = Ladda.create(submitBtn);
			loading.start();
			$.ajax({
				type: 'POST',
				url: '/post/api/addAnswer',
				data: answerData,
				dataType: 'json',
				success: function(result) {
					answerForm.find('textarea, checkbox, button').prop('disabled', false);
					loading.stop();
					if (result.success === true) {
						updateNewAnswer(result);
						answerForm.find('textarea').html('').val('');

						/**
						 * Send the new answer details to the socket
						 */
						var socketData = {room: $('#PostPostedInRoom').val()};
						$.extend(socketData, result);
						socket.emit('new_answer', socketData);
					}
					else if (result.error === true) {
						bootbox.alert(result.message, function() {
							if (result.errorType && result.errorType === 'postDeleted') {
								var postId = result.postId;
								$('#post_' + postId).remove();
							}
							else if (result.errorType && (result.errorType === 'fatal')) {
								window.location.reload();
								window.scrollTo(0, 0);
							}
						});
					}
				}
			});
		}
	}
	else {
		return false;
	}
});

/**
 * Updates new answer to the answers list of a question
 * 
 * @param {object} result
 */
function updateNewAnswer(result) {
	var postId = result.postId;
	var questionDiv = $('#post_' + postId);
	var answersDiv = questionDiv.find('.question_answers_div');
	var countSpan = questionDiv.find('.answer_count');
	answersDiv.find('.no_answers_msg').addClass('hide');
	answersDiv.prepend(result.content);
	setTimeout(function() {
		applyTimeAgo();
	}, 60000);
	var answerCount = countSpan.text();
	answerCount = parseInt(answerCount) + 1;
	countSpan.html(answerCount);

	// based on user, remove delete answer button for realtime updated answer
	if (!canCurrentUserDeleteAnswer(result)) {
		var answerId = result.answerId;
		$('#answer_' + answerId).find('.delete_answer_btn').remove();
	}
}

/**
 * Checks if current user can delete an answer
 * 
 * @param {Object} result
 * @returns {Boolean}
 */
function canCurrentUserDeleteAnswer(result) {
	var loggedInUserId = parseInt(app.loggedInUserId);
	var questionAskedUserId = parseInt(result.questionAskedUserId);
	var answeredUserId = parseInt(result.answeredUserId);
	var isAnsweredUser = (loggedInUserId === answeredUserId);
	var isQuestionAskedUser = (loggedInUserId === questionAskedUserId);
	var canDelete = (isAnsweredUser || isQuestionAskedUser) ? true : false;
	return canDelete;
}

/**
 * Function to validate the answer form
 * 
 * @param {element} answerForm
 * @returns {boolean}
 */
function isAnswerFormValid(answerForm) {
	var maxLength = 5000;
	answerForm.validate({
		errorPlacement: function(error, element) {
			error.appendTo(element.parent().find('.error_container'));
		},
		errorLabelContainer: false,
		errorElement: 'span',
		errorClass: 'error-block',
		highlight: function(element) {
			$(element).addClass('error');
		},
		unhighlight: function(element) {
			$(element).removeClass('error');
		},
		rules: {
			'data[Answer][answer]': {
				'required': true,
				'maxlength': maxLength
			}
		},
		messages: {
			'data[Answer][answer]': {
				'required': 'Please enter your answer before submission',
				'maxlength': 'Cannot be more than ' + maxLength + ' characters long'
			}
		}
	});
	return answerForm.valid();
}

$(document).on('click', '.more_text', function() {
	var truncatedText = $(this).parent('.truncated_text');
	truncatedText.addClass('hide');
	truncatedText.next('.full_text').removeClass('hide').children('.less_text').removeClass('hide');
	$(this).addClass('hide');
});

$(document).on('click', '.less_text', function() {
	var fullText = $(this).parent('.full_text');
	fullText.addClass('hide');
	fullText.prev('.truncated_text').removeClass('hide').children('.more_text').removeClass('hide');
	$(this).addClass('hide');
});

$(document).on('click', '.add_answer_link', function() {
	var answerFormContainer = $(this).closest('.questionnaire_div').find('.answer_form_container');
	if (answerFormContainer.hasClass('hide')) {
		answerFormContainer.removeClass('hide');
	}
	else {
		answerFormContainer.find('textarea').focus();
	}
});

/**
 * Function to list all the answers for a question
 */
$(document).on('click', '.load_more_answer', function() {
	var loadMore = $(this);
	var loadMoreLink = loadMore.find('a');
	var postId = loadMoreLink.attr('data-postid');
	var answersDiv=$('#post_' + postId).find('.question_answers_div');
	$.ajax({
		type: 'POST',
		url: '/disease/diseases/listQuestionAnswers',
		data: {'postId': postId},
		beforeSend: function() {
			var loadingImg = '<div class="text-center"><img alt="Loading..." src="/img/loading.gif"></div>';
			loadMore.html(loadingImg);
		},
		success: function(result) {
			loadMore.remove();
			answersDiv.html(result);
			setTimeout(function() {
				applyTimeAgo();
			}, 60000);
		}
	});
});

/**
 * Show/hide delete answer icon on mouseover/mouseout on answer
 */
$(document).on('mouseover', '.disease_answer_div', function() {
	$(this).find('.delete_answer_btn').removeClass('hide');
});
$(document).on('mouseout', '.disease_answer_div', function() {
	$(this).find('.delete_answer_btn').addClass('hide');
});

/**
 * Function to delete an answer
 */
$(document).on('click', '.delete_answer_btn', function() {
	var answerId = $(this).data('answer_id');
	if (answerId > 0) {
		var confirmMessage = "Are you sure you want to delete this answer?";
		bootbox.confirm(confirmMessage, function(isConfirmed) {
			if (isConfirmed) {
				deleteAnswer(answerId);
				$.ajax({
					type: 'POST',
					url: '/post/api/deleteAnswer',
					data: {'answerId': answerId},
					dataType: 'json'
				});

				/**
				 * Send the deleted answer details to the socket
				 */
				var socketData = {
					room: $('#PostPostedInRoom').val(),
					answerId: answerId
				};
				socket.emit('delete_answer', socketData);
			}
		});
	}
});

/**
 * Function to delete an answer from a question
 * 
 * @param {Integer} answerId
 */
function deleteAnswer(answerId) {
	var answerDiv = $('#answer_' + answerId);
	var questionDiv = answerDiv.closest('.questionnaire_div');
	var answerCountElement = questionDiv.find('.answer_count');
	var answerCount = answerCountElement.html();
	answerDiv.remove();
	answerCount = parseInt(answerCount) - 1;
	answerCountElement.html(answerCount);
	if (answerCount === 0) {
		questionDiv.find('.no_answers_msg').removeClass('hide');
	}
}