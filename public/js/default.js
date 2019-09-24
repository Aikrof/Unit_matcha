
var socket = new WebSocket("ws://"+ location.hostname +":6322");

socket.onopen = function(open){
	socket.send(JSON.stringify({'type' : 'notification'}))
};

function sendMsg($data){
	socket.send(JSON.stringify($data));
}

socket.onmessage = function(event){
	let $path = location.pathname.split('/');

	let data = JSON.parse(event.data);

	switch (data.type){

		case 'message': {

			if ($path[$path.length - 1] === 'chat')
				getNewMessage(data);
			else{
				pushNewAlert(data);
			}

			break;
		}
		case 'alert': {
			pushNewAlert(data);
			break;
		}
		case 'notification': {
			let notifi_data = data.notifi_data;

			if (notifi_data.length == 0)
				return;

			for (let n of notifi_data){
				let result = {};
				
				switch (n.type){
					case 'message': {
						result = {
							'type' : 'message',
							'str' : "You have new Message from: ",
							'login' : n.login,
						}

						break;
					}
					case 'like':{
						result = {
							'type' : 'like',
							'str' : "Your image was liked by: ",
							'login' : n.login,
						}
						break;
					}
					case 'dislike':{
						result = {
							'type' : 'dislike',
							'str' : "Your image was disliked by: ",
							'login' : n.login,
						}
						break;
					}
					case 'comment': {
						result = {
							'type' : 'comment',
							'str' : "Your img was comented by: ",
							'login' : n.login,
						}
						break;
					}
					case 'connect': {
						result = {
							'type' : 'connect',
							'str' : "You have new connect, now you can chat with: ",
							'login' : n.login,
						}
						break;
					}
					case 'remove_connect': {
						result = {
							'type' : 'connect',
							'str' : "You have lost connect, with: ",
							'login' : n.login,
						}
						break;
					}
				}

				let $repetitive = $('.notification_login:contains('+ result.login +')')

				if ($repetitive.hasClass('notification_login') &&
					$repetitive.parent().attr('data-type') === result.type){

					let $small = $repetitive.parent().find('.small_n_count');
					
					if ($small.text() === ""){
						$small.text("2");
					}
					else
						$small.text(parseInt($small.text()) + 1);
				}
				else{
					$('.notification_ul').append(
						'<p class="dropdown-item notification_p" data-type="'+ result.type +'">\
						<span class="n_count"><small class="small_n_count"></small></span>'
						+ result.str +
						'<strong class="notification_login c-e74">'
						+ result.login +
						'</strong><input type="hidden" value="'+ result.str +'"></p>'
					);
				}
			}

			$('.notification_count').show();
			$('.notification_count').text(notifi_data.length);
		}
	}
}

function pushNewAlert(data){
	const Notifi = Swal.mixin({
  		toast: true,
  		position: 'bottom-end',
  		showConfirmButton: false,
	})
	Notifi.fire({
  		type: 'success',
  		html: getHtmlNotifiView(),
	});

	function getHtmlNotifiView(){
		switch (data.action){
			case 'message': {
				return ('\
					<p class="notify_p">\
						You have new message from:</p>\
  						<p class="notify_p notify_username c-e74">'
  						+ data.from +
  					'</p>');
			}
			case 'like': {
				return ('\
					<p class="notify_p">\
						Your image was liked by:</p>\
  						<p class="notify_p notify_username c-e74">'
  						+ data.from +
  					'</p>');
			}
			case 'dislike': {
				return ('\
					<p class="notify_p">\
						Your image was disliked by:</p>\
  						<p class="notify_p notify_username c-e74">'
  						+ data.from +
  					'</p>');
			}
			case 'comment': {
				return ('\
					<p class="notify_p">\
						Your img was comented by:</p>\
  						<p class="notify_p notify_username c-e74">'
  						+ data.from +
  					'</p>');
			}
			case 'connect': {
				return ('\
					<p class="notify_p">\
						You have new connect, now you can chat with:</p>\
  						<p class="notify_p notify_username c-e74">'
  						+ data.from +
  					'</p>');
			}
			case 'remove_connect':{
				return ('\
					<p class="notify_p" style="display:inline-flex">\
						You have lost connect, with:</p>\
  						<p style="display:inline-flex" class="notify_p notify_username c-e74">'
  						+ data.from +
  					'</p>\
  					<p class="notify_p" style="display:inline-flex">\
  						now chat is unavailable.\
  					</p>');
			}
		}
	}
}

$('.logout').click(function(){
	sender.form('/logout');
});