$('.block_user_btn').click(function(){
	let $user = {
		login : $('#block_user_login').val().replace(/<\/?[^>]+(>|$)/g, ""),
	};

	sender.form('/blockUser', {'user' : $user}, function(request){
		if (request.user){
			location.href = location.href;
		}
		else
			creatErrSwal(request.err);
	});
});
$('.remove_block_user_btn, .block_user_div_btn').click(function(){
	let $user = {
		login: ($(this).attr('class') === 'btn remove_block_user_btn') ?
			$('#remove_block_user_login').val().replace(/<\/?[^>]+(>|$)/g, "") :
			$(this).parent().children('.blocked_user_login').text().replace(/<\/?[^>]+(>|$)/g, "")
	}

	sender.form('/removeFromBlock', {'user' : $user}, function(request){
		if (request.user){
			location.href = location.href;
		}
		else
			creatErrSwal(request.err);
	});
});


/*
* Hover effect on user login and icon
*/
$('.blocked_user_div').on('mouseenter','.blocked_user_img, .blocked_user_login', function(){

    $(this).parents('.blocked_user_div').find('.blocked_user_login').css({"font-weight": "bold",
    "color": "#E74C3C"});
    $(this).parents('.blocked_user_div').find('.blocked_user_img').css({"border": "2px solid #E74C3C"});
});
$('.blocked_user_div').on('mouseleave','.blocked_user_img, .blocked_user_login', function(){
    
    $(this).parents('.blocked_user_div').find('.blocked_user_login').removeAttr('style');
    $(this).parents('.blocked_user_div').find('.blocked_user_img').removeAttr('style');
});
$('.blocked_user_img, .blocked_user_login').click(function(){
	$login = $(this).parent().children('.blocked_user_login').text();
	let $char = $login.charAt(0).toUpperCase();
    $login = $char + $login.substr(1, $login.length-1)

   location.href = '/' + $login;
});

$('#set_change_login').click(function(){
	let $html = '\
<div class="form-group">\
	<div class="row">\
		<div class="col-md-12">\
			<label>New Login:</label>\
			<input class="form-control" id="login-change_login" type="text" name="login" autocomplete="off">\
			<label>Password:</label>\
			<input class="form-control" id="login-change_password" type="password" name="password" autocomplete="off">\
		</div>\
	</div>\
</div>\
	';

	creatSwal($html, "Change Login",function(){
		let $data = {
			login : $('#login-change_login').val(),
			password : $('#login-change_password').val(),
		};

		sender.form('/settings/changeLogin', {'new_login' : $data}, function(result){
			if (result.changed)
			{
				let $login = $data.login.toLowerCase();
				let $char = $login.charAt(0).toUpperCase();
    			$login = $char + $login.substr(1, $login.length-1)
				
				$('.sign_login').text($login);
				creatSuccessSwal('Your login was succefuly changed');
			}
			else
				creatErrSwal(result);
		});
	});
});

$('#set_change_passwd').click(function(){
	let $html = '\
<div class="form-group">\
	<div class="row">\
		<div class="col-md-12">\
			<label>Password:</label>\
			<input class="form-control" id="password-change_password" type="password" name="password" autocomplete="off">\
			<label>New Password:</label>\
			<input class="form-control" id="password-change_new_password" type="password" name="new_password" autocomplete="off">\
			<label>Confirm:</label>\
			<input class="form-control" id="password-change_confirm" type="password" name="confirm" autocomplete="off">\
		</div>\
	</div>\
</div>\
	';

	creatSwal($html, "Change Password",function(){
		let $data = {
			password : $('#password-change_password').val(),
			new_password: $('#password-change_new_password').val(),
			confirm: $('#password-change_confirm').val(),
		};

		sender.form('/settings/changePassword', {'new_password' : $data}, function(result){
			if (result.changed)
				creatSuccessSwal('Your password was succefuly changed');
			else
				creatErrSwal(result);
		});
	});
});

$('#set_change_email').click(function(){
	let $html = '\
<div class="form-group">\
	<div class="row">\
		<div class="col-md-12">\
			<label>Email:</label>\
			<input class="form-control" id="email-change_email" type="email" name="email" autocomplete="off">\
			<label>Password:</label>\
			<input class="form-control" id="email-change_password" type="password" name="password" autocomplete="off">\
		</div>\
	</div>\
</div>\
	';

	creatSwal($html, "Change Email",function(){
		let $data = {
			email: $('#email-change_email').val(),
			password: $('#email-change_password').val(),
		};

		sender.form('/settings/changeEmail', {'new_email' : $data}, function(result){
			if (result.changed)
				creatSuccessSwal('Your email was succefuly changed');
			else
				creatErrSwal(result);
		});
	});
});

function creatSwal($html, $title, call){
	callback = call || {};

	Swal.fire({
		title: '<h2 class="swal-change_title">'+ $title +'</h2>',
		html: $html,
		showCloseButton: true,
  		showCancelButton: true,
  		reverseButtons: true,
	}).then((result) => {
		if (result.value)
			callback();
	});
}

function creatSuccessSwal(msg){
	Swal.fire({
		type: 'success',
		title: '<h3 class="swal-ress_suc">' + msg + '</h3>',
		showCloseButton: true,
  		showCancelButton: true,
  		reverseButtons: true,
  		timer: 3500,
	});
}

function creatErrSwal(msg){
	Swal.fire({
		type: 'error',
		title: '<h3 class="swal-ress_err">' + msg + '</h3>',
		showCloseButton: true,
  		showCancelButton: true,
  		reverseButtons: true,
  		timer: 3500,
	});
}