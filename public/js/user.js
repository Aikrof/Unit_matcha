$('.tab a').click(function(){
    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');

    $target = $(this).attr('href').toLowerCase();

    $('#profile-content > .profile_choice').not($target).hide();
    $($target).fadeIn(600);
});


birthday = {};

$('.edit_select').click(function(){
	let $val;
	let $change = $(this).attr('change');
	
	if ($change)
		$('.' + $change).text($(this).text());
	
	let $name = $(this).attr('name');
	if ($name === 'month')
	{
		birthday[$name] = $(this).text();
		if (!checkElseBirthParam())
			return;
		$val = birthday;
		$name = 'birthday';
	}
	else
		$val = $(this).text();

	editProfile($name, $val);
});

$('.edit_inp').change(function(){
	let $val;
	let $name = $(this).attr('name');
	
	if ($name === 'day' ||
		$name === 'year')
	{
		birthday[$name] = $(this).val();
		if (!checkElseBirthParam())
			return;
		$val = birthday;
		$name = 'birthday';
	}
	else
		$val = $(this).val().replace(/<\/?[^>]+(>|$)/g, "");

	editProfile($name, $val);
});

$('.remove_birthday').click(function(){
	$('input[name="day"]').val("");
	$('input[name="year"]').val("");
	$('.birthday_buttn').text("Month");

	sender.form('/profile/removeBirthday', null);
});

$('.interest_cont').on('click', 'p.tag_se', function(){
	$value = $(this).text().replace('#', '');

	$(this).remove();
	sender.form('/profile/removeTag', {'tag' : $value});
});

/*
* Add icon
*/
$('#profile_avatar').change(function(){

    let $file = new ImgWorker($(this));
    $file.iconSend('/saveUserIcon', $('.avatar'), $(this).attr('name'));    
});

/*** USER IMG ***/

/**
* Add user img
**/
$('#inp_img').change(function(){

    let $file = new ImgWorker($(this));
    
    $(this).val("");
    
    $file.imgSend('/saveUserImg', $(this).attr('name'), function(request){
        
        if ($('.pr_img_21').length == 4)
            $('.taget_img').last().remove();

    	$('.user_img_area').prepend(addNewImageContent(request));
    }, function(error){
    	console.log(error);
    });
});

/*
* Show img comments and likes and
add img likes count 
*/
$('.user_img_area').on('mouseenter', 'div.img_cont', function(){

    $(this).children('.user_func').show();
    
    $putResult = $(this).find('small.like_count');

    $src = $(this).children('.pr_img_21').attr('src');
    $img = $src.split('/');
    $img = $img[$img.length - 1];

    sender.form('/user/getCountLikes', {'img': $img}, function(request){
        $putResult.text("");
        if (request)
            $putResult.text(request);
    });
});

/*
* Hide img likes and comments
*/
$('.user_img_area').on('mouseleave', 'div.img_cont', function(){
    $(this).children('.user_func').hide();
});

/*
* Show who likes and add like
*/
$('.user_img_area').on('click', '.see_img_likes', function(){
    let $likes_area = $(this).parent().parent().children('.box_likes_hidden').removeClass("none");
    let $targetArrea = $(this).parents('.img_cont').children('.box_likes_hidden').children('.like_box').children('.users_like');

    let $img = getImg($likes_area.parent().children('.pr_img_21').attr('src'));

    $targetArrea.empty();

    sender.form('/user/getLikes', {'img': $img}, function(request){

        if (request.data)
        {
            for (let user of request.data){
                addNewLikeToArea($targetArrea, user.icon, user.login);
            }

            $('.users_like')[0].scrollTop = $('.users_like')[0].scrollHeight;
        }
        else if (request.empty)
        {
            $targetArrea.append('\
                <div class="empty_likes ecl">\
                    <p>'+ request.empty +'</p>\
                </div>\
            ');
        }
    })
});


/*
* Show comments
*/
$('.user_img_area').on('click', '.hov_comments_fa', function(e){
    let $area = $(this).parent().parent().children('.box_commnets_hidden').removeClass("none");
    let $targetArrea = $(this).parents('.img_cont').children('.box_commnets_hidden').children('.comment_box').children('.users_coments');

    let $img = getImg($area.parent().children('.pr_img_21').attr('src'));

    $targetArrea.empty();

    sender.form('/user/getComments', {'commentImg' : $img}, function(request){
        if (request.data)
        {
            for (let $data of request.data){
                addCommentsToArea($targetArrea, $data.icon, $data.login, $data.comment);
            }

            $('.users_coments')[0].scrollTop = $('.users_coments')[0].scrollHeight;
        }
        else if (request.empty)
        {
            $targetArrea.append('\
                <div class="empty_comment ecl">\
                    <p>'+ request.empty +'</p>\
                </div>\
                ');
        }

    });
});

/*
* Close comments and likes
*/
$('.user_img_area').on('click', '.comment_close', function(){
    $(this).parent().parent().addClass("none");
});

/*
* Send new comment
*/
$('.user_img_area').on('click', '.snd_new_comment', function(){
    let $text = $(this).parent().children('textarea');
    let $targetArrea = $(this).parents('.comment_box').children('.users_coments');

    $obj = {
        'img' : getImg($(this).parents('.img_cont').children('.pr_img_21').attr('src')),
        'comment' : $text.val().replace(/<\/?[^>]+(>|$)/g, ""),
        'id' : $(this).parents('.img_cont').children('.pr_img_21').attr('data')
    };
   
    sender.form('/user/addComment', {'comment' : $obj}, function(request){
        if (request.data){
            if ($('.empty_comment') !== undefined)
                $('.empty_comment').remove();
            addCommentsToArea($targetArrea, request.data.icon, request.data.login, request.data.comment);
        }
        if (request.notification)
                sendMsg(request.notification);
    });

    $text.val("");
});

/*
* New like
*/
$('.user_img_area').on('click', '.snd_new_like', function(){
    let $targetArrea = $(this).parents('.img_cont').children('.box_likes_hidden').children('.like_box').children('.users_like');

    let $obj = {
        'img' : getImg($(this).parents('.img_cont').children('.pr_img_21').attr('src')),
        'id' : $(this).parents('.img_cont').children('.pr_img_21').attr('data'),
    };

    sender.form('/user/addLike', {'like':$obj}, function(request){

        if (request.add)
        {
            addNewLikeToArea($targetArrea, request.add.icon, request.add.login);

            sendMsg(request.add.notification);
        }
        else if (request.remove)
        {
            let $find = $targetArrea.find('p.likes_area_login');

            for (let $p of $find){

                if ($p.innerText.toLowerCase() === request.remove.login.toLowerCase())
                {
                    $p.setAttribute('id', 'remove_like');
                    break;
                }
            }
            $('#remove_like').parents('.likes_area').remove();
            
            sendMsg(request.remove.notification);
        }
    });
});

/*
* Hover effect
*/
$('.user_img_area').on('mouseenter','.h-u-login, .h-u-img', function(){
    
    $(this).parents('.h-u-cont').find('.h-u-login').css({"font-weight": "bold",
    "color": "#E74C3C"});
    $(this).parents('.h-u-cont').find('.h-u-img').css({"border": "2px solid #E74C3C"});
});
$('.user_img_area').on('mouseleave','.h-u-login, .h-u-img', function(){
    
    $(this).parents('.h-u-cont').find('.h-u-login').removeAttr('style');
    $(this).parents('.h-u-cont').find('.h-u-img').removeAttr('style');
});

/*
* Redirect to another user page when click
in 'comment' or 'like' area
*/
$('.user_img_area').on('click','.h-u-login, .h-u-img', function(){
    let $login = $(this).parents('.h-u-cont').find('.h-u-login').text();
    let $char = $login.charAt(0).toUpperCase();
    $login = $char + $login.substr(1, $login.length-1)

   location.href = '/' + $login;
});

$('.remove_img').click(function(){
    sender.form('/user/getImgs', null, function(request){

        Swal.fire({
            title: 'Select image what you want to delete',
            html: '\
                <div class="dell_cont">\
                </div>',
            onBeforeOpen: () => {
                const content = Swal.getContent()
                const $ = content.querySelector.bind(content)

                for (let src of request){
                    img = document.createElement('img');
                    img.className = 'dell_img';
                    img.setAttribute('title', 'Delete image?');
                    img.onclick = function(){
                        $src = this.getAttribute('src').split('/');
                        
                        sender.form('/user/dellImg', {'img' : $src[$src.length - 1]});
                        
                        $user_imgs = document.getElementsByClassName('pr_img_21');
                        
                        for (let img of $user_imgs){
                            if (this.getAttribute('src') === img.getAttribute('src'))
                            {
                                document.getElementsByClassName('user_img_area')[0].
                                removeChild(img.parentNode.parentNode);
                                break;
                            }
                        }
                        
                        let parent = this.parentNode;
                        parent.removeChild(this);
                        
                        if (parent.childNodes.length == 1)
                            swal.close();
                    }
                    img.setAttribute('src', src);

                    $('.dell_cont').appendChild(img);
                }
            },
            customClass: 'swal-dell-wide',
            showCloseButton: true,
            showConfirmButton: false,
        });
    });
});
/*** /USER IMG ***/

$('.user_location_add').click(function(){
	getUserLocation(function(location){
		$('.city_local').val(location.city);
		$('.country_local').val(location.country);

		sender.form('/profile/profileUpdate', {'location' : location});
	});
});
$('.user_location_remove').click(function(){
	$('.city_local').val("");
	$('.country_local').val("");

	sender.form('/profile/removeLocation');
});

/*
* Add new tag in to tags select
*/
function sendTag(tag)
{
    let $search = '#' + tag;
    for (let $value of $('.tag_se')){
        if ($value.innerHTML === $search)
            return;
    }

    if (tag !== '' && tag !== '#')
    {
    	$('.interest_cont').prepend(
    	 	'<p class="tag_se">#' + tag + '</p>');
    	
        let arr = [tag];
    	
        sender.form('/profile/profileUpdate', {'interests' : arr}, function(request){
			console.log(request);
        });
    }
}

/*
* Hide tag helper
*/
$(window).on('click', function(event){
    let $helper = $('.helperProfInt');
    if ($helper !== undefined &&
        $helper.attr('style') !== 'display: none;' &&
        event.target !== $helper[0])
        $helper.hide();
});

function checkElseBirthParam(){
	var count = 0;

	for (let property in birthday){
    	if (birthday.hasOwnProperty(property))
			count++;
    }
    return (count == 3);
}

function editProfile($name, $value){
	let $obj = {[$name] : $value};
	sender.form('/profile/profileUpdate', $obj);
}

var getImg = function($path){
    $img = $path.split('/');
    return ($img[$img.length - 1]);
}

function addCommentsToArea($parent, $icon, $login, $comment){
    $parent.append('\
        <div class="comment_area_cont form-group h-u-cont">\
                <img class="comment_area_img h-u-img" src='+ $icon +'>\
            <div class="comment_area">\
                <p class="comment_area_login h-u-login">'+ $login +'</p>\
                <div class="comment_area_comment">\
                    <p>'+ $comment +'</p>\
                </div>\
            </div>\
        </div>');
    
    $('.users_coments')[0].scrollTop = $('.users_coments')[0].scrollHeight;
}

function addNewLikeToArea($parent, $icon, $login){
    if ($parent.children('.empty_likes') !== undefined)
        $parent.children('.empty_likes').remove('.empty_likes'); 
    
    $parent.append('\
        <div class="likes_area form-group h-u-cont">\
             <img class="likes_area_img h-u-img" src='+ $icon +'>\
            <p class="likes_area_login h-u-login">'+ $login +'</p>\
        </div>\
    ');

    $('.users_like')[0].scrollTop = $('.users_like')[0].scrollHeight;
}

function addNewImageContent(request)
{
    return ('\
<div class="row fle_xeble taget_img pos_rel">\
    <div class="col-md-11 pos_rel img_cont">\
        <img class="form-group pr_img_21" src='+ request.img_src +' data='+ request.id +'>\
        <div class="user_func posr_abs hov_func" style="display: none">\
            <div class="hov_comments_fa hov_fa comments col-white"></div>\
                <div class="hov_img_fa_red hov_fa hov_img_fa see_img_likes col-white like pos_rel">\
                    <small class="like like_count posr_abs"></small>\
                </div>\
            </div>\
            <div class="box_commnets_hidden posr_abs none">\
                <div class="commment_exit_box">\
                    <div class="comment_close"></div>\
                </div>\
                <div class="row comment_box">\
                    <div class="col-md-12 users_coments"></div>\
                    <div class="col-md-12 add_new_comment">\
                        <label>Add your comment</label>\
                        <div class=" textarea_comment_cont">\
                            <textarea class="form-control new_comment" name="new_comment" placeholder="Add your comment"></textarea>\
                            <p class="btn snd_new_comment">Add</p>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            <div class="box_likes_hidden posr_abs none">\
                <div class="commment_exit_box">\
                    <div class="comment_close"></div>\
                </div>\
                <div class="row like_box">\
                    <div class="col-md-12 users_like"></div>\
                <div class="col-md-12 add_new_like">\
                    <label>Add your like</label>\
                    <p class="btn snd_new_like form-control">Add like</p>\
                </div>\
            </div>\
        </div>\
    </div>\
</div>');
}
