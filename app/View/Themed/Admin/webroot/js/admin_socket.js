/**
 * JS file to handle the socket connection events in admin side
 */
$(function() {
	socket = io.connect(app.socket_url);

	var userRoom = 'user_room_' + parseInt(app.loggedInUserId);
	socket.emit('create', userRoom);

	socket.on('user_blocked', function(data) {
		window.location.href = '/admin/users/logout?blocked=true';
	});
});