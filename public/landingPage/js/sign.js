$('.form').find('input, textarea').on('keyup blur focus', function (e) {

  var $this = $(this),
      label = $this.prev('label');

	  if (e.type === 'keyup') {
			if ($this.val() === '') {
          label.removeClass('active highlight');
        } else {
          label.addClass('active highlight');
        }
    } else if (e.type === 'blur') {
    	if( $this.val() === '' ) {
    		label.removeClass('active highlight'); 
			} else {
		    label.removeClass('highlight');   
			}   
    } else if (e.type === 'focus') {
      
      if( $this.val() === '' ) {
    		label.removeClass('highlight'); 
			} 
      else if( $this.val() !== '' ) {
		    label.addClass('highlight');
			}
    }

});

$('.tab a').on('click', function (e) {

    e.preventDefault($('.tab a'));

    resetForm(this);

    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');
  
    target = $(this).attr('href');

    $('.tab-content > div').not(target).hide();
    $(target).fadeIn(600);
    $('.sig').show();
    $('.forg_wrap').hide();
});

$('.forgot').on('click', function(event){
  event.preventDefault();

  resetForm(this);

  if ($('.forg_wrap').attr('style') === 'display: none;')
  {
    $('.sig').hide();
    $('.forg_wrap').show();
  }
  else
  {
    $('.forg_wrap').hide();
    $('.sig').show();
  }
});

$('.gender').on('click', function(event){
    var f = $('#f_gender');
    var t = $('#t_gender');

    if (this === f[0])
        addMitmash(f, t);
    else
        addMitmash(t, f);

    function addMitmash(a, b)
    {
        a.addClass('mitmash_v2');
        a.removeClass('mitmash_v1');
        b.removeClass('mitmash_v2');
        b.addClass('mitmash_v1');

        $('#gender')[0].value = a[0].innerText
    }
});

$('.form').on('submit', function(event){
    event.preventDefault();

    var obj = {};
    for (let input of event.target){
      if (input.localName === 'input')
        obj[input.name] = input.value;
    }

    if (obj.remember !== undefined &&
        obj.remember === "")
        obj.remember = 1;

    sender.form('/' + event.target.id, obj, function(request){
        processRequest(request, obj, event.target.id);
    });

    return false;
});

$('#leb_check').click(function(){
    let $checkbox = $('#checkbox_remember');
    if ($checkbox.attr('data') === '1')
    {
        $checkbox.attr('data', '2');
        $checkbox.val(0);
    }
    else
    {
        $checkbox.attr('data', '1');
        $checkbox.val(1);
    }
});

function processRequest(request, obj, formId)
{
    if (request.susses_registr)
    {
        resetForm(formId);
        swalCreater(request.susses_registr, "", "success");
    }
    else if (request.url)
        location.href = request.url;
    else if (request.email_send)
    {
        let title = (!request.email_send.status) ?
                    "Something was wrong" :
                    "Please check your email for a reset password link";
        let msg = (!request.email_send.status) ?
                    "Error" : "";
        let icon = (!request.email_send.status) ?
                    "error" : "success";
        
        swalCreater(title, msg, icon);
    }
    else if (request.verified_email)
    {
        var compl = {
            'icon' : "warning",
            'title' : "Verify Your Email Address",
            'content' : creatIframe(obj),
            'danger' : true,
            'button' : "Cancel",
        };

        swalCreater(compl.title, "", compl.icon, compl);
    }
    else
        swalCreater(request, "", "error");

}

function swalCreater(title, msg, icon, compl = null)
{
    if (!compl)
        swal(title, msg, icon);
    else
    {
        swal({
            icon: compl.icon,
            title: compl.title,
            content: compl.content,
            dangerMode: compl.danger,
            buttons: compl.button,
        });
    }
}

function creatIframe(obj)
{
    var iframe = document.createElement("iframe");
    iframe.style.width = '400px';
    iframe.style.height = '280px';
    iframe.setAttribute('src', 'landingPage/view/verify.php');
    iframe.setAttribute('obj', obj.login);

    return (iframe);
}

function resetForm(target)
{
   var form;

   if (target.innerText && target.innerText === 'Sign Up')
        form = $('#register')[0];
    else if (target.innerText && target.innerText === 'Sign In')
        form = $('#login')[0];
    else if (target.innerText && target.innerText === 'Forgot Password?')
        form = $('#email')[0];
    else
    {
        let id = '#' + target;
        form = $(id)[0];
    }

    if ($('.mitmash_v2'))
    {
        $('.mitmash_v2').addClass('mitmash_v1');
        $('.mitmash_v2').removeClass('mitmash_v2');
    }

    for (let input of form){
        input.value = '';
        if (input.previousElementSibling &&
            input.previousElementSibling.localName === 'label')
            input.previousElementSibling.removeAttribute('class');
    }
}