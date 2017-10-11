var socket = io.connect( 'http://10.4.0.74:8080' );

$( "#messageForm" ).submit( function() {
	var nameVal = $( "#nameInput" ).val();
	var msg = $( "#messageInput" ).val();
	var room = $( "#room" ).val();
	
	
	// Ajax call for saving datas
	$.ajax({
		url: "./ajax/insertNewMessage.php",
		type: "POST",
		data: { name: nameVal, message: msg, room: room },
		success: function(data) {
			// Commenting out the functionality to emit from JS and moving the functionality to the PHP
                        //socket.emit( 'message', { name: nameVal, message: msg } );
                        
                        $( "#messageInput" ).val('');
		}
	});
	
	return false;
});
socket.on( 'message', function( data ) {
    console.log(data);
	var actualContent = $( "#messages" ).html();
	var newMsgContent = '<li> <strong>' + data.name + '</strong> : ' + data.message + '</li>';
	var content = newMsgContent + actualContent;
	
	$( "#messages" ).html( content );
});
