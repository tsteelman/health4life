/* 
 * JS file to handle the socket connection events in the Posting page
 */
$(function() {
    try {
	socket = io.connect( app.socket_url );
	
	/**
	 * Create a room for the logged in user
	 */
	var userRoom = 'user_room_' + parseInt(app.loggedInUserId);
	socket.emit('create', userRoom);

	/**
	 * Logout user when user is blocked by admin
	 */
	socket.on('user_blocked', function(data) {
		window.location.href = '/logout?blocked=true';
	});
	
	/**
	 * Notify user about new notification
	 */
	socket.on('user_notified', function(data) {
		updateNotificationCount(data);
		if((parseInt(app.play_notification_sound) == 1) && data.notification_count > 0) {
			playNotificationMusic();
		}
	});
	
    if($('#PostPostedIn').length){        

		if ($('#PostNewsFeedUrl').length > 0) {
			socket.emit('create', $('#PostNewsFeedUrl').val());
		}
		else {
			socket.emit('create', $('#PostPostedInRoom').val());
		}

		socket.on('post_added', function(data) {
			incNewPostCount(data.postId);
		});

		socket.on('post_deleted', function(data) {
			decNewPostCount(data.postId);
		});

		socket.on('question_added', function(data) {
			incNewQuestionCount(data.postId);
		});

		socket.on('question_deleted', function(data) {
			decNewQuestionCount(data.postId);
		});
        
        socket.on( 'comment_added', function( data ) {
			/*
			 * Functionality to update comment details
			 */             
			updateNewComment(data);
        });     
        
		socket.on( 'comment_deleted', function( data ) {
                /*
                 * Functionality to delete comment
                 */             
                
                $('#comment_' + data.commentId).remove();
                setTimeout(function() {
                    applyTimeAgo();
                }, 60000);
				$('#comment_count_' + data.postId).html(data.commentCount);
        });  
		 
		socket.on('answer_added', function(data) {
			/*
			 * Functionality to update new answer details
			 */
			updateNewAnswer(data);
		});
		
		socket.on('answer_deleted', function(data) {
			/*
			 * Functionality to delete an answer
			 */
			deleteAnswer(data.answerId);
		});  
		
		socket.on( 'poll_voted', function( data ) {
                /*
                 * Functionality to update poll details
                 */   
				userId = app.loggedInUserId;
				attendedUsers = data.attendedUsers;
				attendedUserCount = attendedUsers.length;
				if($.inArray(userId, attendedUsers) !==-1) {
					$('.poll_'+ data.pollId).replaceWith(data.content);
					setTimeout(function() {
						applyTimeAgo();
					}, 60000);
				} else {
					if(attendedUserCount == 1) {
						$('#user_count_'+data.pollId).html(attendedUserCount+' person voted');
					} else {
						$('#user_count_'+data.pollId).html(attendedUserCount+' people voted');
					}
				}
        });     
        
        socket.on( 'post_liked', function( data ) {            

                /*
                 * Functionality to update like details
                 */             
				
				// change like to unlike if same user
				if (data.username === app.loggedInUserName) {
					var postId = data.postId;
					var postDiv = $('#post_' + postId);
					var likeBtn = postDiv.find('a.like_btn');
					likeBtn.removeClass('like_btn').addClass('unlike_btn');
					likeBtn.html('Unlike');
				}
				
				// update liked users list
				updateLikedUsersList(data);
        }); 
        
        socket.on( 'post_unliked', function( data ) {            
				
                /*
                 * Functionality to update like details
                 */             
				
				// change unlike to like if same user
				if (data.username === app.loggedInUserName) {
					var postId = data.postId;
					var postDiv = $('#post_' + postId);
					var unlikeBtn = postDiv.find('a.unlike_btn');

					unlikeBtn.removeClass('unlike_btn').addClass('like_btn');
					unlikeBtn.html('Like');
				}
				
				// update liked users list
				updateLikedUsersList(data);
        });  
    }
        
        var myHealthRoom = $('#graphUpdatedInRoom').val();
        
        if (myHealthRoom) {

        socket.emit('create', myHealthRoom);
            /**
             * Update graph.
             */
            socket.on('my_health_updated', function(data) {
                getMyHealthGraphData(data.type);
            });
           
        }
    }
catch (e) {
   alert(e);
   console.error('Sorry, the web socket at "%s" is un-available', url);
}    

});

/**
 * Function to update liked users list
 * 
 * @param {Object} data
 */
function updateLikedUsersList(data) {
	var postId = data.postId;
	var likeCount = data.likeCount;
	var likedUsersDiv = $('#liked_users_list_' + postId);
	if (likeCount > 0) {
		var lastLikedUsers = data.lastLikedUsers;
		var lastLikedUsersCount = lastLikedUsers.length;
		var othersLikeCount = likeCount - lastLikedUsersCount;
		var Html = '';
		var currentUserName = app.loggedInUserName;
		var currentUserIndex = $.inArray(currentUserName, lastLikedUsers);
		var postDiv = $('#post_' + postId);
		var isCurrentUserLiked = (postDiv.find('a.unlike_btn').length > 0);
		if (isCurrentUserLiked && currentUserIndex === -1) {
			// if current user has liked the post, but not in last liked users list,
			// add current user as the first user and remove last user from the list
			lastLikedUsers.unshift(currentUserName);
			lastLikedUsers.pop();
		}
		else {
			// add current user as the first user, if not already first user
			if (currentUserIndex > 0) {
				lastLikedUsers.splice(currentUserIndex, 1);
				lastLikedUsers.unshift(currentUserName);
			}
		}

		$.each(lastLikedUsers, function(key, likedUserName) {
			if (likedUserName === currentUserName) {
				Html += '<span class="liker">You</span>';
			} else {
				Html += getUserElement(likedUserName);
			}
			if (key === 0) {
				if (lastLikedUsersCount > 1) {
					Html += (othersLikeCount > 0) ? '<span class="comma">, </span>' : '<span class="and"> and </span>';
				}
			}
		});
		if (othersLikeCount > 0) {
			Html += '<span class="and"> and </span>';
			var pluralText = (othersLikeCount > 1) ? 's' : '';
			var othersText = othersLikeCount + ' ' + 'other' + pluralText;
			Html += '<a data-post_id="' + postId + '" class="owner view_likes">' + othersText + '</a>';
		}
		Html += '<span> like this</span>';
		likedUsersDiv.removeClass('hide');
		likedUsersDiv.find('span').html(Html);
	}
	else {
		likedUsersDiv.addClass('hide');
	}
}

/*
 * Function to get profile link from usrename
 */
function getUserElement( username ) {
    return '<span class="liker"><a href="/profile/' + encodeURIComponent(username) 
            +'" class="owner" data-hovercard="'+ username +'">' 
            + username +'</a></span>'
}

/**
 * 
 * Functiont to update the like text using Array of usernames
 */
//function getLikeString(data) {
//    var likedUsersList = data.likedUsersList.split(',');
//    var HTML = '';
//    
//    /*
//     * Check if the logged in user liked the post
//     */
//    var arrayIndex = $.inArray(app.loggedInUserName, likedUsersList);
//    if( arrayIndex !== -1 ) {
//        /*
//         * Remove logged in username from array
//         */
//        likedUsersList.splice(arrayIndex, 1);
//        HTML += '<span class="liker">You</span>' 
//    }
//    
//    HTML += '<span>like this</span>'
//}
