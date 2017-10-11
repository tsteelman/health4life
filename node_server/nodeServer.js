var socket = require( 'socket.io' );
var express = require( 'express' );
var fs = require( 'fs' );
var app = express();

var secureMode = '0';

//If secure mode is enabled in the env, use https
if(secureMode === '1')
{
    var serverOptions = {
        key: fs.readFileSync('/home/health4life/ssl/health4lifeprivatekey.key'),
        cert: fs.readFileSync('/home/health4life/ssl/www_health4life_com.crt'),
        ca: [fs.readFileSync('/home/health4life/ssl/DigiCertCA.crt')]
    };
    var server = require('https').createServer(serverOptions, app);
} 
else {
     var server = require('http').createServer(serverOptions, app);
}


var io = socket.listen( server, { log: false } );

/*
 * 
 * Configuration to allow transport 
 * protocols to be used in WebSockets
 * Example shown is for the ajax long polling
 */
/*
io.configure(function () {
  io.set("transports", ["xhr-polling"]);
  io.set("polling duration", 10);
});
*/

var clients = [];

io.sockets.on( 'connection', function( client ) {
    
        /*
         * On taking the posting page, join the user socket to the room
         */
       client.on( 'create', function( room ) {
		//console.log( client.id + ' Joined page ' + room  );
		client.join(room);
	});
        
        /*
         * New post update
         */
        client.on( 'new_post', function( data ) {
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('post_added', {postId: data.postId});
        });
		
        /*
         * New question update
         */
        client.on( 'new_question', function( data ) {
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('question_added', {postId: data.postId});
        });
        
        /*
         * New comment update
         */
        client.on( 'new_comment', function( data ) {
              
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('comment_added', data);
        });      
        
        /*
         * New answer update
         */
        client.on( 'new_answer', function( data ) {
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('answer_added', data);
        });      
        
        /*
         * Like update
         */
        client.on( 'new_like', function( data ) {
              
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('post_liked', data);
        });
        
        /*
         * unLike update
         */
        client.on( 'new_unlike', function( data ) {
              
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('post_unliked', data);
        });
		/*
		 * Block user
		 */
		client.on('block_user', function(data) {
			emitEventToUserRoom('user_blocked', data);
		});

		/*
		 * Post delete
		 */
		client.on('post_delete', function(data) {
			// sending to all clients in the room(channel) except sender
			client.broadcast.to(data.room).emit('post_deleted', {postId: data.postId});
		});
		
		/*
		 * Question delete
		 */
		client.on('question_delete', function(data) {
			// sending to all clients in the room(channel) except sender
			client.broadcast.to(data.room).emit('question_deleted', {postId: data.postId});
		});

		/*
         * Post comment delete
		 */
        client.on( 'comment_delete', function( data ) {
              
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('comment_deleted', data);
		});
		
		/*
         * Question answer delete
		 */
        client.on( 'delete_answer', function( data ) {
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('answer_deleted', data);
		});
		
		/*
         * Poll vote
		 */
        client.on( 'poll_update', function( data ) {
              
            // sending to all clients in the room(channel) except sender
            client.broadcast.to(data.room).emit('poll_voted', data);
		});
		
		/*
		 * Notify user
		 */
		client.on('notify_user', function(data) {
			emitEventToUserRoom('user_notified', data);
		});
		/*
		 * 
		 */
		client.on('my_health_update', function(data) {
			client.broadcast.to(data.room).emit('my_health_updated', data);
		});

		/**
		 * Function to emit an event to a user's room
		 * 
		 * @param {string} event
		 * @param {json} data
		 */
		function emitEventToUserRoom(event, data) {
			var userId = 0;
			if (data.user_id > 0) {
				userId = data.user_id;
			}
			else if (data.userId > 0) {
				userId = data.userId;
			}
			
			if (userId > 0) {
				var userRoom = 'user_room_' + parseInt(userId);
				client.broadcast.to(userRoom).emit(event, data);
			}
		}
        
//	client.on( 'message', function( data ) {
//		console.log( 'Message received ' + data.name + ":" + data.message );
//		
//		//client.broadcast.emit( 'message', { name: data.name, message: data.message } );
//		io.sockets.emit( 'message', { name: data.name, message: data.message } );
//	});
});

io.sockets.on( 'disconnect', function ( data ) {
    console.log( "Client disconnect!"+data.id );
});




server.listen( 7790 );