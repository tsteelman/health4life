var messageLoadingHTML;
var selectedMessageType;

$(document).ready(function() {

	if ($('#message_page').length > 0) {
		startRealTimeMessageSystem();
	}

	if ($('#message_loading_container').length > 0) {
		messageLoadingHTML = $('#message_loading_container').html();
	}
	else {
		messageLoadingHTML = $('.message_list_container').html();
	}

	if ($('#default_message_section').length > 0) {
		loadInbox();
	}
	else {
		showMessageDetailScrollBar();
		ajaxifyNextPageLink();
	}

	$('#compose-message-button').bind('click', function() {
		$('#composeMessageResponse').html('');
	});
});

$('#composeMessage').on('hidden.bs.modal', function(e) {
	clearComposeMessage();
});

$('#composeMessage').on('show.bs.modal', function(e) {
	if ($('.popover').length > 0) {
		$('.popover').remove(); //remove the hovercard  
	}
});

$('#SendMessageByEmail').keypress(function(event) {
	if (event.keyCode == 13) {
		event.preventDefault();
	}
});

$(document).on('click', '.message_button', function() {
	$(".arrowchat_closebox_bottom").click();
	$('#UserId').val($(this).data("user-id"));
	$('#SendMessageByEmail').val($(this).data("username"));
	$('#SendMessageByEmail').attr("disabled", true);
});

$(document).on('click', 'a#inbox_link', function() {
	clearSearch();
	loadInbox();
});

$(document).on('click', 'a#sent_link', function() {
	clearSearch();
	loadSentMessages();
});

$(document).on('click', 'a#saved_link', function() {
	clearSearch();
	loadSavedMessages();
});

$(document).on('click', '#select_all_messages', function() {
	var selectedRows = $('.message_list_container').find('.message_list');
	selectMessages(selectedRows);
	changeMessageActionButtonsState();
});

$(document).on('click', '#select_read_messages', function() {
	unSelectMessages();
	var selectedRows = $('.message_list_container').find('.message_list.read');
	selectMessages(selectedRows);
	changeMessageActionButtonsState();
});

$(document).on('click', '#select_unread_messages', function() {
	unSelectMessages();
	var selectedRows = $('.message_list_container').find('.message_list.unread');
	selectMessages(selectedRows);
	changeMessageActionButtonsState();
});

$(document).on('click', '#unselect_messages', function() {
	unSelectMessages();
	changeMessageActionButtonsState();
});

$(document).on('change', '.message_list_container input[type="checkbox"]', function() {
	var selectedRow = $(this).closest('.message_list');
	if ($(this).is(':checked')) {
		selectedRow.addClass('active');
	}
	else {
		selectedRow.removeClass('active');
	}
	changeMessageActionButtonsState();
});

$(document).on('submit', '#message_search_form', function() {
	var searchTerm = $('#message_search_input').val();
	loadMessages(selectedMessageType, searchTerm);
	return false;
});

$(document).on('click', '#delete_msg_btn', function() {
	var btn = $(this);
	var confirmMessage = 'Are you sure you want to delete the selected conversation(s)?';
	bootbox.confirm(confirmMessage, function(isConfirmed) {
		if (isConfirmed) {
			var selectedMessages = $('.message_list_container input[type="checkbox"]');
			var data = selectedMessages.serialize();
			data = data + '&type=' + selectedMessageType;
			$.ajax({
				url: '/message/messages/deleteConversations',
				data: data,
				method: 'POST',
				beforeSend: function() {
					btn.prop('disabled', true);
				},
				success: function(result) {
					var deletedMessages = $('.message_list_container input[type="checkbox"]:checked').closest('.message_list');
					deletedMessages.remove();
					if (selectedMessageType === 'inbox') {
						updateInboxCount();
					}
					if ($('.message_list_container .message_list').length === 0) {
						var noMessagesHTML = '<br><div class="alert alert-warning"><div class="message">No messages!</div></div>';
						$('.message_list_container').html(noMessagesHTML);
					}
				}
			});
		}
	});
});

$(document).on('click', '#save_msg_btn', function() {
	var btn = $(this);
	var selectedMessages = $('.message_list_container input[type="checkbox"]');
	var data = selectedMessages.serialize();
	$.ajax({
		url: '/message/messages/saveConversations',
		data: data,
		method: 'POST',
		beforeSend: function() {
			btn.prop('disabled', true);
			hideSuccessMessage();
		},
		success: function() {
			btn.prop('disabled', false);
			var msg = 'Successfully saved the selected conversation(s).';
			$('#message_success_element .message').html(msg);
			$('#message_success_element').removeClass('hide');
			window.scrollTo(0, 0);
		}
	});
});

$(document).on('click', '#message_success_element button.close', function(event) {
	hideSuccessMessage();
});

function hideSuccessMessage() {
	$('#message_success_element .message').html('');
	$('#message_success_element').addClass('hide');
}

$(document).on('click', '.message_list', function(event) {
	var checkboxElement = 'input[type="checkbox"]';
	var target = $(event.target);
	if (!target.is(checkboxElement)) {
		var other_user_id = $(this).find(checkboxElement).val();
		$(this).removeClass('unread').addClass('read');
		updateInboxCount();
		var detailPageUrl;
		if (selectedMessageType === 'saved') {
			detailPageUrl = '/message/details/saved/' + other_user_id;
			type = 'saved_messages';
		}
		else {
			detailPageUrl = '/message/details/index/' + other_user_id;
			type = 'messages';
		}
		$.ajax({
			type: 'POST',
			url: detailPageUrl,
			data: {
				first_load: true
			},
			beforeSend: function() {
				$('.message_list_container').html(messageLoadingHTML);
			},
			success: function(result) {
				$('.message_list_container').html(result);
				$('#message_select_box').addClass('hide');
				ajaxifyNextPageLink();
				showMessageDetailScrollBar();
				if (type == "messages") {
					startRealTimeMessageSystem();
				}
			}
		});
	}
});

$(document).on('click', '#next_conversations_link', function(event) {
	var nextPageUrl = $(this).attr('data-href');
	current_page = $.trim($('#message_page').val());
	$('#message_page').val(parseInt(current_page) + 1);
	$(this).parents('center').remove();
	$.ajax({
		type: 'POST',
		url: nextPageUrl,
		beforeSend: function() {
			$('.message_details_list').prepend(messageLoadingHTML);
		},
		success: function(result) {
			$('.message_details_list #message_loader').remove();
			$('.message_details_list').prepend(result);
			ajaxifyNextPageLink();
		}
	});
});

function updateInboxCount() {
	var inboxCount = $('.message_list.unread').length;
	inboxCountBox = $('#inbox_link span');
	if (inboxCount > 0) {
		inboxCountBox.html('(' + inboxCount + ')');
	}
	else {
		inboxCountBox.remove();
	}
}

function clearSearch() {
	$('.message_search_field input').val('');
}

function clearComposeMessage() {
	$('#UserId').val('');
	$('#compose_message').val('');
	$('#SendMessageByEmail').val('');
	$('#composeMessageResponse').html('');
	$('.token').remove();
	$('#SendMessageByEmail').attr('placeholder', $('#SendMessageByEmail').data('placeholder'));
}

function selectMessages(selectedRows) {
	selectedRows.addClass('active');
	var checkBoxList = selectedRows.find('input[type="checkbox"]');
	checkBoxList.prop('checked', true);
}

function unSelectMessages() {
	var selectedRows = $('.message_list_container').find('.message_list');
	selectedRows.removeClass('active');
	var checkBoxList = selectedRows.find('input[type="checkbox"]');
	checkBoxList.prop('checked', false);
}

function loadInbox() {
	loadMessages('inbox');
	$('#select_read_messages').show();
	$('#select_unread_messages').show();
}

function loadSentMessages() {
	loadMessages('sent');
	$('#select_read_messages').hide();
	$('#select_unread_messages').hide();
}

function loadSavedMessages() {
	loadMessages('saved');
	$('#select_read_messages').hide();
	$('#select_unread_messages').hide();
}

function loadMessages(type, searchTerm) {
	var data = {};
	if (searchTerm !== undefined) {
		data = {search_term: searchTerm};
	}
	$('ul#message_tabs li a').removeClass('active');
	$('a#' + type + '_link').addClass('active');
	$.ajax({
		type: 'POST',
		url: '/message/messages/' + type,
		data: data,
		beforeSend: function() {
			$('#message_select_box').addClass('hide');
			$('.message_list_container').html(messageLoadingHTML);
			changeMessageActionButtonsState();
		},
		success: function(result) {
			selectedMessageType = type;
			$('.message_list_container').html(result);
			$('#message_select_box').removeClass('hide');
			setPageHeader(selectedMessageType);
		}
	});
}

function ajaxifyNextPageLink() {
	var nextPageLink = $('#next_conversations_link');
	var nextPage = nextPageLink.attr('href');
	nextPageLink.removeAttr('href').attr('data-href', nextPage);
}

function startRealTimeMessageSystem() {
	setInterval(function() {
		if ($('#other_user_id').length) {
			user_id = $.trim($('#other_user_id').val());
			current_page = parseInt($.trim($('#message_page').val()));

			$('.next').empty();
			$.ajax({
				type: 'POST',
				data: {
					current_page: current_page
				},
				url: '/message/details/index/' + user_id,
				success: function(result) {
					$('.message_details_list').html('');
					$('.message_details_list').append(result);

					$('#message_select_box').addClass('hide');
					ajaxifyNextPageLink();
					autoupdate = "true";
					showMessageDetailScrollBar(autoupdate);
				}
			});

		}
		setInterval(function() {
			$('.next').slice(1).empty();
			var nextPageLink = $('#next_conversations_link');
			p = current_page + 1;
			var nextPage = '/message/details/index/' + user_id + '/page:' + p;
			nextPageLink.attr('data-href', nextPage);
		}, 1000);
	}, 20000);
}
function setPageHeader(selectedMessageType) {
	var heading;
	switch (selectedMessageType) {
		case 'inbox':
			heading = 'Inbox';
			break;
		case 'sent':
			heading = 'Sent';
			break;
		case 'saved':
			heading = 'Saved';
			break;
	}
	$('.page-header h3').html(heading);
	$('title').html(heading + ' - Messages - '+ app.appName);
}

function changeMessageActionButtonsState() {
	var selectedCount = $('.message_list_container input[type="checkbox"]:checked').length;
	if (selectedCount > 0) {
		if (selectedMessageType !== 'saved') {
			$('#save_msg_btn').prop('disabled', false);
		}
		$('#delete_msg_btn').prop('disabled', false);
	}
	else {
		$('#save_msg_btn').prop('disabled', true);
		$('#delete_msg_btn').prop('disabled', true);
	}
}

function composeMessage() {
	user_ids = $.trim($('#UserId').val());
	message_txt = $.trim($('#compose_message').val());
	$('#composeMessageResponse').html('');

	if (user_ids === "")
	{
		$('#composeMessageResponse').html("<div class ='alert alert-error'>Please enter valid usernames.</div>");
	}
	else if (message_txt === "")
	{
		$('#composeMessageResponse').html("<div class ='alert alert-error'>Please enter message.</div>");
	}
	else
	{
		$.ajax({
			type: 'POST',
			url: '/message/messages/createUserMessage',
			data: {
				'user_ids': user_ids,
				'message': message_txt
			},
			dataType: 'json',
			beforeSend: function() {
			},
			success: function(result) {
				if (result.success === true) {
					$('#compose_message').val('');
					$('#composeMessageResponse').html('');
					$('#composeMessage').modal('hide');
					var notifyObj = {};
					notifyObj.options = {};
					notifyObj.options.className = 'success';
					notifyObj.options.autoHide = true;
					notifyObj.options.position = 'bottom left';
					notifyObj.options.arrowSize = 0;
					$.notify("Your messages have been successfully sent", notifyObj.options);
					// not clearing other fields since we require them to send again.                                        
					$('#composeMessageResponse').html("<div class ='alert alert-success'>" + result.message + "</div>");
				} else if (result.error === true) {
					$('#composeMessageResponse').html("<div class ='alert alert-error'>" + result.message + "</div>");
				}

			}
		});
	}
}

function replyMessage() {
	user_id = $.trim($('#other_user_id').val());
	message_txt = $.trim($('#reply_message').val());
	current_page = $.trim($('#message_page').val());
	$('.reply_message_response').html('');

	if (user_id === "")
	{
		$('.reply_message_response').html("<div class ='alert alert-error'>Please enter valid usernames.</div>");
	}
	else if (message_txt === "")
	{
		$('.reply_message_response').html("<div class ='alert alert-error'>Please enter message.</div>");
	}
	else
	{
		$.ajax({
			type: 'POST',
			url: '/message/messages/createUserMessage',
			data: {
				'user_ids': user_id,
				'message': message_txt
			},
			dataType: 'json',
			beforeSend: function() {
			},
			success: function(result) {
				if (result.success === true) {
					$('#reply_message').val('');
					$('.reply_message_response').html("<div class ='alert alert-success'>" + result.message + "</div>");
				} else if (result.error === true) {
					$('.reply_message_response').html("<div class ='alert alert-error'>" + result.message + "</div>");
				}
				/**
				 * calling the index again instead of one record to reset the pagination.
				 */
				$.ajax({
					type: 'POST',
					data: {
						reply_success: true,
						current_page: current_page
					},
					url: '/message/details/index/' + user_id,
					success: function(result) {
//                        $('.message_list_container').html(result);
						$('.message_details_list').html('');
						$('.message_details_list').append(result);
						$('#message_select_box').addClass('hide');
						ajaxifyNextPageLink();
						$('.message_details_list').slimScroll({destroy: true});
						showMessageDetailScrollBar();
					}
				});
				setTimeout("$('.reply_message_response').html('');", 5000);

			}
		});
	}
}

/**
 * Display the message detail content scroll bar
 */
function showMessageDetailScrollBar(autoupdate) {
	autoupdate = autoupdate || 0; //default value set

	if ($('.message_details_list').length > 0) {
		var scrollStart = $('#last_msg_top');
		$('.message_details_list').slimScroll({
			color: '#BBDAEC',
			railColor: '#EBF5F7',
			size: '12px',
			height: 'auto',
			railVisible: true,
			start: scrollStart,
			disableFadeOut: true
		});

		if (!autoupdate) {
			$('#reply_message').focus();
		}

		scrollToReplyBox();
	}
}

function scrollToReplyBox() {
	if ($(window).height() > 600) {
		$('html, body').animate({
			scrollTop: $('.messages').offset().top
		}, 100);
	}
	else {
		window.scrollTo(0, 190);
	}
}