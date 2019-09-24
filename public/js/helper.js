sender = {
    form: function(url, obj, call){
        var callback = call || function() {};

        $.ajax({
            url: url,
            type: 'POST',
            headers:{
                'X-CSRF-TOKEN':
                $('meta[name="csrf_token"]').attr('content'),
            },
            data: JSON.stringify(obj),
            contentType: "application/json",
            dataType:'json',
            success: function(request){
                callback(request);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                if (xhr.status === 422)
                    console.dir(xhr.responseText);
                else if (xhr.status === 401)
                    location.href = '/landing';
            }
        });
    },

    file: function(url, $file, $appendName,call){
        var callback = call || function() {};

        let formData = new FormData;
        formData.append($appendName, $file);

        $.ajax({
            url: url,
            type: 'POST',
            headers:{
                'X-CSRF-TOKEN':
                $('meta[name="csrf_token"]').attr('content'),
            },
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
            success: function(request){
                callback(request);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                if (xhr.status === 422)
                    console.dir(xhr.responseText);
            }
        });
    },
}

function ImgWorker($inp)
{
    this.file = $inp.prop('files')[0];

    this.iconSend = function($url, $putResultInto, $appendName, $putErrorInto){
        if (!checkSize(this.file))
            return;

        var $errorCall = $putErrorInto || function() {};

        sender.file($url, this.file, $appendName,function(request){
            if (request.src && $putResultInto !== undefined)
                $putResultInto.attr('src', request.src);
            else if (request.error && $putErrorInto)
                $errorCall(request.error);
        });   
    }

    this.imgSend = function($url, $appendName, $putResultInto, $putErrorInto){

        if (!checkSize(this.file))
            return;

        var $resultCall = $putResultInto || function() {};
        var $errorCall = $putErrorInto || function() {};

        sender.file($url, this.file, $appendName, function(request){
            if (request.src)
                $resultCall(request.src);
            else if (request.error)
                $errorCall(request.error)
        });
    }

    var checkSize = function(file){
        if (file === undefined)
            return;
        return (file.size <= 10000000);
    }
}

/*
* Get user location
*/
function getUserLocation(call){
    var callback = call || function() {};

    navigator.geolocation.getCurrentPosition(function(position){
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    // var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+ latitude +','+ longitude +'&sensor=false&key=AIzaSyAFlQz9H-L0209Sq94idC1aY9wKOhiH0gs';
    var url = 'http://api.geonames.org/findNearbyPlaceNameJSON?lat='+latitude+'&lng='+longitude+'&username=localdev'
    // var url =  'https://restcountries.eu/rest/v2/all';
        $.getJSON(url, function(data, textStatus){
            var geonames = data.geonames[0];
            if (geonames)
            {
                var country = geonames.countryName;
                var city = geonames.adminName1.split(' ')[0];

                var location = {
                    'latitude' : latitude,
                    'longitude' : longitude,
                    'country' : country,
                    'city' : city,
                }

                callback(location);
            }
        });
    });
};

function getRange(min = 0, max = 100, min_val = 0, max_val = 100){
    return ('\
    <div class="multi-range" data-lbound='+ min +' data-ubound='+ max +'>\
        <div class="multi_range_1"></div>\
        <hr>\
        <input type="range" name="min" min='+ min +' max='+ max +' value='+ min_val +' oninput="setRangeVal(this);">\
        <input type="range" name="max" min='+ min +' max='+ max +' value='+ max_val +' oninput="setRangeVal(this);">\
        <div class="multi_range_2"></div>\
    </div>\
    ');
}

function setRangeVal(elem){
    if (elem.getAttribute('name') === 'max')
        elem.parentNode.dataset.ubound = elem.value;
    else if (elem.getAttribute('name') === 'min')
        elem.parentNode.dataset.lbound=elem.value;
}

/*** ADD NEW TAG AND SEE TAG HELPER ***/
function tagHelper($value){
    $hide = 0;
    $hash = $value.split('#');
    $hash[0] = null;

    $tag = $hash[$hash.length - 1];
    
    if ($hash.length - 1 > 1)
    {
        if ($('.resultTags') !== undefined)
            $('.resultTags').remove();

        sendTag($hash[1].replace(/<\/?[^>]+(>|$)/g, ""));
        $('.helperAbs').hide();
        $hide = 1;
        $('#interestsHelp').val('#');
    }
    
    $('.btn_inter').click(function(){
        $hash = $('#interestsHelp').val().split('#');
        $hash[0] = null;
    
        if ($hash[1])
        {
            $hide = 1;
            changeTag($hash[1].replace(/<\/?[^>]+(>|$)/g, ""));
        }
    });

    if ($tag && $tag.length > 2)
    {
        sender.form('/searchTag', {'tag' : $tag}, function(request){
            if ($('.resultTags') !== undefined)
                    $('.resultTags').remove();
            if ($hide)
                $('.helperAbs').hide();
            else if (request.similar.length)
            {
                $('.helperProfInt').show();
                for (let value of request.similar){
                    $('.helperProfInt').append('\
                        <p class="resultTags" onclick="changeTag(this.innerText)">'
                        + value.tag + '</p>')
                }
            }
            else
                $('.helperAbs').hide();
        });
    }
}

function changeTag(tag){
    sendTag(tag);

    $('.resultTags').remove();
    $('.helperAbs').hide();
    $('#interestsHelp').val('#');
}
/*** /ADD NEW TAG AND SEE TAG HELPER ***/
