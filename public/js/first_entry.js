/*** First Entry questions***/
entry = {
    icon: function(){
        return ('\
<div class="row fle_xeble">\
<div class="col-md-6 pr-1">\
    <div class="f_icon_con border_custom">\
        <label class="pattaya_style c-330">Add icon:</label>\
        <div class="custom-file">\
            <label>\
                <input type="file" id="f_icon" name="icon">\
                <img id="f_img_icon" src="img/icons/spy.png">\
            </label>\
        </div>\
    </div>\
</div>\
</div>\
        ')
    },

    sexualOrient: function(){
        return ('\
<div class="row h-180">\
<div class="col-md-12 pr-1 fle_xeble">\
    <form class="dropdown open f_sexual" onsubmit="return false">\
        <label class="pattaya_style c-330">Sexual orientations:</label>\
        <p class="btn btn-secondary dropdown-toggle orient_dropdown-item" data-toggle="dropdown">Sexual orientations</p>\
        <input type="hidden" name="orientation">'
        + ((new orient).getOrientation()) +
    '</form>\
</div>\
</div>\
        ');
    },
    age: function(){
        return ('\
<div class="row h-180">\
<div class="col-md-12 pr-1 fle_xeble">\
    <form class="form-group f_age_cont f_age" onsubmit="return false">\
        <label class="pattaya_style c-330">Age:</label>\
        <input type="text" class="form-control" maxlength="2" placeholder="Age" name="age" autocomplete="off">\
    </form>\
</div>\
</div>\
        ');
    },

    birthday: function(){
        return ('\
<div class="row h-180">\
<div class="col-md-12 pr-1 fle_xeble">\
    <div class="form-group">\
        <label class="pattaya_style c-330">Birthday:</label>\
        <form class="f_birthday" onsubmit="return false">\
            <input type="text" name="day" class="form-control" maxlength="2" placeholder="Day" autocomplete="off">\
            <div class="dropdown open m_nth">\
                <p class="btn dropdown-toggle f_birthday_buttn" data-toggle="dropdown"><span class="month_dropdown-item">Month</span></p>\
                <input type="hidden" name="month">'
                + (new month()).getMonth() +
            '</div>\
            <input type="text" name="year" class="form-control" maxlength="4" placeholder="Year" autocomplete="off">\
        </form>\
    </div>\
</div>\
</div>\
        ');
    },

    interests: function(){
        return ('\
<div class="row pr-1 fle_xeble_col">\
<label class="pattaya_style c-330">List of interests:</label>\
<div class="row col-md-4 pr-1">\
    <p class="form-control dropdown-toggle nav-link cursor no-indent" data-toggle="dropdown">\
        <span>Your Tags</span>\
    </p>\
    <input type="hidden" class="form-control">\
    <ul class="dropdown-menu your_tags"></ul>\
</div>\
<p class="help_small"><small>Add your interests with tag #</small></p>\
<p class="help_small"><small>To save / write new tag just press #</small></p>\
<form class="row col-md-8 pr-1" onsubmit="return false">\
    <input type="interests" class="form-control"\
        name="interests" autocomplete="off"\
        oninput="firstEntryTagHelper(this.value)"\
        id="f_interestsHelp" value="#">\
</form>\
</div>\
<div class="row pr-1 h-20 fle_xeble_col helperRel">\
    <div class="col-md-4 helperAbs" style="display: none;"></div>\
</div>\
        ');
    },

    about: function(){
        return ('\
<div class="row h-180">\
<div class="col-md-12 pr-1 fle_xeble">\
    <form class="form-group f_about_cont f_about" onsubmit="return false">\
        <label class="pattaya_style c-330">About:</label>\
        <textarea name="about" placeholder="Add some info about yourself" class="form-control"></textarea>\
    </form>\
</div>\
</div>\
        ')
    },

    location: function(){
        return ('\
<div class="row h-180">\
<div class="col-md-12 pr-1 fle_xeble">\
<div class="form-group">\
    <label class="f_location" onclick="takeCoords()">\
        <i class="fa fa-map-marker"></i>\
        <p class="pattaya_style c-330">Add your location:</p>\
        <form class="get_f_local" onsubmit="return false">\
            <input type="hidden" name="latitude">\
            <input type="hidden" name="longitude">\
            <input type="hidden" name="country">\
            <input type="hidden" name="city">\
        </form>\
    </label>\
    <div class="location_result" style="display:none">\
        <span class="country f-size-32 c-330"></span>\
        <span class="city f-size-32 c-330"></span>\
    </div>\
</div>\
</div>\
</div>\
        ')
    }
}
/*** /First Entry questions***/

var orient = function(){
    this.div = '<div class="dropdown-menu f_orient" aria-labelledby="dropdownMenuLink" style="z-index: 4000;">';
    this.orientations = [
        "Heterosexual", "Bisexual", "Homosexual"
    ];

    this.creatOrientationSelect = function(){
        var val = '';
        for (let pref of this.orientations){
            val += '<label>\
                        <span class="dropdown-item" onclick="orientCh(event)">' + pref + '</span>\
                    </label>';
        }

        return (val);
    }

    this.getOrientation = function(){
        return (this.div + this.creatOrientationSelect() + '</div>');
    }
}

var month = function(){
    this.div = '<div class="dropdown-menu month_cont" aria-labelledby="dropdownMenuLink">';
    this.year = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December',
    ];

    this.creatMonthSelect = function(){
        var val = '';

        for (let month of this.year){
            val += '<label>\
                    <span class="dropdown-item" onclick="birthMonthCh(event)">' + month + '</span>\
                </label>';
        }

        return (val);
    }

    this.getMonth = function(){
        return (this.div + this.creatMonthSelect() + '</div>');
    }
}


/*** Modal window***/
Swal.mixin({
    title: 'Add info about yourself',
    showCancelButton: true,
    confirmButtonText: 'Add and Next &rarr;',
    reverseButtons: true,
    progressSteps: ['Ic', 'Ag', 'Bi', 'Or', 'In' , 'Ab' , 'Lo']
}).queue([
    {
        html: entry.icon(),
        preConfirm: function(){
            let $url = '/saveUserIcon';

            let $file = new ImgWorker($('#f_icon'));
            $file.iconSend($url, $('#f_img_icon'), $('#f_icon').attr('name'), function(request){
                console.log(request);
            });
        }
    },

    {
        html: entry.age(),
        preConfirm: function(){
            firstEntrySender.send($('.f_age'));
        }
    },

    {
        html: entry.birthday(),
        preConfirm: function(value){
            firstEntrySender.send($('.f_birthday'));
        }
    },

    {
        html: entry.sexualOrient(),
        preConfirm: function(value){
           firstEntrySender.send($('.f_sexual'));
        }
    },

    {
        html: entry.interests(),
        preConfirm: function(value){
            if ($('#f_interestsHelp').val() !== '#')
            {
                $('#f_interestsHelp').val(
                    $('#f_interestsHelp').val().split('#').join(''));
                addTag($('#f_interestsHelp').val());
            }
            firstEntrySender.sendInterests($('a[name="tag"]'));
        }
    },
    
    {
        html: entry.about(),
        preConfirm: function(value){
           firstEntrySender.send($('.f_about'));
        }
    },

    {
        html: entry.location(),
        preConfirm: function(value){
            firstEntrySender.send($('.get_f_local'));
        }
    },

]).then((result) => {
    let done;

    if (result.dismiss)
        done = false;
    else
        done = true;

    sender.form('/SuccessfulUserFirstEntry', {'done' : done});
});
/*** /Modal window***/

/*** Send Request ***/
firstEntrySender = {

    send: function($form){
        let $data = {};
        let $obj = (function(){
            var $data = {};

            $form.find ('input, textarea').each(function(){

                if (this.name)
                    $data[this.name] = $(this).val();
            });

            return ($data);
        }());

        if ($form[0] && $form[0] === $('.f_birthday')[0])
            $data.birthday = $obj;
        else if ($form[0] && $form[0] === $('.get_f_local')[0])
            $data.location = $obj;
        else
            $data = $obj;

        sender.form('/firstEntry', $data, function(request){
            console.log(request);
        });
    },

    sendInterests: function($tags){
        let $obj = {
            'interests' : (function(){
                var $data = [];

                for (let $tag of $tags){
                    $data.push($tag.innerHTML.replace('#', ""));
                }

                return ($data);
            }())
        };

        sender.form('/firstEntry', $obj, function(request){
            console.log(request);
        });
    }
}
/*** /Send Request ***/


/*** Tag helper section ***/
function firstEntryTagHelper($value){
    $hide = 0;
    $hash = $value.split('#');
    $hash[0] = null;

    $tag = $hash[$hash.length - 1];
    
    if ($hash.length - 1 > 1)
    {
        if ($('.resultTags') !== undefined)
            $('.resultTags').remove();

        addTag($hash[1]);
        $('.helperAbs').hide();
        $hide = 1;
        $('#f_interestsHelp').val('#');
    }
    
    if ($tag && $tag.length > 2)
    {
        sender.form('/searchTag', {'tag' : $tag}, function(request){
            if ($('.resultTags') !== undefined)
                    $('.resultTags').remove();
            if ($hide)
                $('.helperAbs').hide();
            else if (request.similar.length)
            {
                $('.helperAbs').show();
                for (let value of request.similar){
                    $('.helperAbs').append('\
                        <p class="resultTags" onclick="changeTag(this.innerText)">'
                        + value.tag + '</p>')
                }
            }
            else
                $('.helperAbs').hide();
        });
    }
}

/*
* Change tag
*/
function changeTag(tag){
    addTag(tag);

    $('.resultTags').remove();
    $('.helperAbs').hide();
    $('#f_interestsHelp').val('#');
}


/*
* Add new tag in to your tags select
*/
function addTag(tag)
{
    if (is_similar() && tag !== '')
    {
        $('.your_tags')
            .append('\
                <a class="dropdown-item cursor" name="tag">#'
                + tag +
                '</a>'
        );
    }

    function is_similar()
    {
        let my_tags = [];

        for (let spl of $('a[name=tag]')){
            my_tags.push(spl.innerHTML);
        }

        return ((my_tags.indexOf("#" + tag) == -1));
    }
}

/*** /Tag helper section ***/

/*
* Add icon
*/
$('#f_icon').change(function(){
    
    var reader = new FileReader();
    
    reader.onload = function (e) {
        $('#f_img_icon').attr('src', e.target.result);
    };
    reader.readAsDataURL($(this).prop('files')[0]);
});

/*
* Changes Birth month in select block
*/
function birthMonthCh(event){
    $('input[name="month"]').val(event.target.innerText);
    $('.month_dropdown-item')[0].innerHTML = event.target.innerText;
};


/*
* Changes  orientation in select block
*/
function orientCh(event){
    $('input[name="orientation"]').val(event.target.innerText);
    $('.orient_dropdown-item')[0].innerHTML = event.target.innerText;
};


$(window).click(function(){
  if ($('.helperAbs') !== undefined &&
      $('.helperAbs').attr('style') !== 'display: none;' &&
      $(this) !== $('.helperAbs'))
        $('.helperAbs').hide();
});

/** Take User Coords **/
function takeCoords(){
    getUserLocation(function(location){
        $('input[name="latitude"]').val(location.latitude);
        $('input[name="longitude"]').val(location.longitude);

        $('.country').text(location.country + ',');
        $('.city').text(location.city);
        $('.location_result').show();
        $('.f_location').hide();

        $('input[name="country"]').val(location.country);
        $('input[name="city"]').val(location.city);
    });
}
