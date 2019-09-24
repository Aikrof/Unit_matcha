$('meta').attr('name', 'csrf_token');
$('meta').attr('content', window.parent.frames.document.all.csrf_token.content);

$('#resend').on('click', function(event){
	var obj = {
		'login' : window.parent.frames.document.activeElement.attributes.obj.value,
	};
	sender.form('/' + event.target.id, obj, function(request){
		swal({
                icon: "success",
                title: 'Please check your email for a verification link.',
                buttons: false,
            })
			setTimeout(function(){
				var parent = window.parent.frames.document.body;
				var swal = window.parent.frames.document.body.children;
				parent.removeChild(swal[swal.length - 1]);
			}, 1600);
	});
});