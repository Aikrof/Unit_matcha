(function ($) {
  "use strict";
  
  // Preloader
  $(window).on('load', function () {
    if ($('#preloader').length) {
      $('#preloader').delay(100).fadeOut('slow', function () {
        $(this).remove();
      });
    }
  });

  // Back to top button
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });
  $('.back-to-top').click(function(){
    $('html, body').animate({scrollTop : 0},1500, 'easeInOutExpo');
    return false;
  });
  
	var nav = $('nav');
	var navHeight = nav.outerHeight();

	/*--/ ScrollReveal /Easy scroll animations for web and mobile browsers /--*/
	window.sr = ScrollReveal();
	sr.reveal('.foo', { duration: 1000, delay: 15 });

	/*--/ Carousel owl /--*/
	$('#carousel').owlCarousel({
		loop: true,
		margin: -1,
		items: 1,
		nav: true,
		navText: ['<i class="ion-ios-arrow-back" aria-hidden="true"></i>', '<i class="ion-ios-arrow-forward" aria-hidden="true"></i>'],
		autoplay: true,
		autoplayTimeout: 3000,
		autoplayHoverPause: true
	});

	/*--/ Animate Carousel /--*/
	$('.intro-carousel').on('translate.owl.carousel', function () {
		$('.intro-content .intro-title').removeClass('zoomIn animated').hide();
		$('.intro-content .intro-price').removeClass('fadeInUp animated').hide();
		$('.intro-content .intro-title-top, .intro-content .spacial').removeClass('fadeIn animated').hide();
	});

	$('.intro-carousel').on('translated.owl.carousel', function () {
		$('.intro-content .intro-title').addClass('zoomIn animated').show();
		$('.intro-content .intro-price').addClass('fadeInUp animated').show();
		$('.intro-content .intro-title-top, .intro-content .spacial').addClass('fadeIn animated').show();
	});
	
	/*--/ Navbar Collapse /--*/
	$('.search-box-collapse').on('click', function () {
		$('body').removeClass('box-collapse-closed').addClass('box-collapse-open');
	});
	$('.close-box-collapse, .click-closed').on('click', function () {
		$('body').removeClass('box-collapse-open').addClass('box-collapse-closed');
		$('.menu-list ul').slideUp(700);
	});

	/*--/ Navbar Menu Reduce /--*/
	$(window).trigger('scroll');
	$(window).bind('scroll', function () {
		var pixels = 50;
		var top = 1200;
		if ($(window).scrollTop() > pixels) {
			$('.navbar-default').addClass('navbar-reduce');
			$('.navbar-default').removeClass('navbar-trans');
		} else {
			$('.navbar-default').addClass('navbar-trans');
			$('.navbar-default').removeClass('navbar-reduce');
		}
		if ($(window).scrollTop() > top) {
			$('.scrolltop-mf').fadeIn(1000, "easeInOutExpo");
		} else {
			$('.scrolltop-mf').fadeOut(1000, "easeInOutExpo");
		}
	});

	/*--/ Property owl /--*/
	$('#property-carousel').owlCarousel({
		loop: true,
		margin: 30,
		responsive: {
			0: {
				items: 1,
			},
			769: {
				items: 2,
			},
			992: {
				items: 3,
			}
		}
	});

	/*--/ Property owl owl /--*/
	$('#property-single-carousel').owlCarousel({
		loop: true,
		margin: 0,  
		nav: true,
		navText: ['<i class="ion-ios-arrow-back" aria-hidden="true"></i>', '<i class="ion-ios-arrow-forward" aria-hidden="true"></i>'],
		responsive: {
			0: {
				items: 1,
			}
		}
	});

	/*--/ News owl /--*/
	$('#new-carousel').owlCarousel({
		loop: true,
		margin: 30,
		responsive: {
			0: {  
				items: 1,
			},
			769: {
				items: 2,
			},
			992: {
				items: 3,
			}
		}
	});

	/*--/ Testimonials owl /--*/
	$('#testimonial-carousel').owlCarousel({
		margin: 0,
		autoplay: true,
		nav: true,
		animateOut: 'fadeOut',
		animateIn: 'fadeInUp',
		navText: ['<i class="ion-ios-arrow-back" aria-hidden="true"></i>', '<i class="ion-ios-arrow-forward" aria-hidden="true"></i>'],
		autoplayTimeout: 4000,
		autoplayHoverPause: true,
		responsive: {
			0: {
				items: 1,
			}
		}
	});

})(jQuery);


var europeCountry= [
	'Austria','Albania','Andorra','Belarus','Belgium',
	'Bulgaria','Bosnia and Herzegovina','Vatican','Hungary',
	'Germany','Guernsey','Gibraltar','Greece','Denmark',
	'Jersey','Ireland','Iceland','Spain','Italy','Kosovo',
	'Latvia','Lithuania','Liechtenstein','Luxembourg',
	'Macedonia','Malta','Moldova','Monaco','Netherlands',
	'Norway','Isle of Man','Holy See (Vatican City)','Poland',
	'Portugal','Russia','Romania','San Marino','Serbia',
	'Slovakia','Slovenia','United Kingdom','Ukraine',
	'Faroe Islands','Finland','France','Croatia','Montenegro',
	'Czech Republic','Switzerland','Sweden',
	'Svalbard and Jan Mayen','Estonia'
];

(function(){
	for (let i in europeCountry){
		$('#country_search').append('\
			<option class="country_option">'
			+ europeCountry[i] +
			'</option>\
		');
	}
}());

$('#country_search').change(function(){
	$val = $(this).val();

	if ($val !== "Select Country")
		getCity($val, null);
});

function getCity($country){

	var url = "http://api.geonames.org/searchJSON?q="+ $country +"&username=aikrof";
	$('.city_option').remove()
	$.getJSON(url, function(data, status){
    	if (data.geonames)
    	{
    		for (let i = 1; i < data.geonames.length; ++i){
    			$('#city_search').append('\
					<option class="city_option">'
					+ data.geonames[i].toponymName +
					'</option>\
				');
    		}
    	}
	});
}

/*
* Checker for Search by same tags
*/
$('.search_by_same_tags_label').click(function(){
	let $check = $('#search_by_same_tags');

	if ($(this).attr('data') === '0')
	{
		$(this).children('.search_sort_checker').addClass('check_ok_content');
		$(this).attr('data', '1');
		$('#search_by_same_tags').val(true);
	}
	else
	{
		$(this).children('.search_sort_checker').removeClass('check_ok_content');
		$(this).attr('data', '0');
		$('#search_by_same_tags').val(false);
	}
});

/*
* Checker for Age, Distance, Rating, Same tags
*/
$('.search_lab_check').click(function(){
	$check = $(this).parent().children('.search_imp_check');
	
	if ($(this).attr('data') === '0')
	{
		$(this).children('.search_sort_checker').addClass('check_ok_content');
		$(this).attr('data', '1');;
		$check.val(true);

		for (let label of $('.search_lab_check')){
			if ($(this)[0] !== label)
			{
				$(label).children('.search_sort_checker').removeClass('check_ok_content');
				$(label).attr('data', '0');
				$(label).parent().children('.search_imp_check').val(false);
			}
		}
	}
	else
	{
		$(this).children('.search_sort_checker').removeClass('check_ok_content');
		$(this).attr('data', '0');
		$check.val(false);
	}
});

/*
* Toggle Asc Desc
*/
$('.search_toggle').click(function(){
	$('input[name="search_sorted_by"]').val($(this).val());
});


/*
* Send Sort / Filter request
*/
$('.search_f_btn').click(function(){

	$property = checkSearchProperty();

	$search = {
		login: ($property !== 'login') ? null : getSearchProperty(),
		tags: ($property !== 'tags') ? null : getSearchProperty(),
		same_tags: ($('#search_by_same_tags').val() === 'false') ? null : true,
		country: ($('#country_search').val() === "Select Country") ? null : $('#country_search').val(),
		city: ($('#city_search').val() === "All City") ? null : $('#city_search').val(),
	}
	$sort = {
		priority: getSearchSortPriority(),
		
		interests: getSearchInterests($('.search_sort_interests').text()),

		sorted_by: getSearchSortedBy($('input[name="search_sorted_by"]').val()),
	};
	
	$filter = {
		age: getSearchAge($('.search_filter_range_age').attr('data-lbound'), $('.search_filter_range_age').attr('data-ubound')),

		distance: getSearchDistance($('.search_distance_inp').attr('data-lbound')),

		rating: getSearchRating($('.search_filter_rating').attr('data-lbound')),

		interests: getSearchInterests($('.search_filter_interests').text()),
	};
	$local = '/search'
	$str = "?";

	$search_str = "";
	for (let key in $search){
		if ($search[key] !== null)
			$search_str += 'search[' + key + ']=' + $search[key] + '&';
	}

	$sort_str = "";
	for (let key in $sort){
		if ($sort[key] !== null && $sort[key] !== false)
			$sort_str += 'sort['+ key + ']=' + $sort[key] + '&';
	}

	$filter_str = "";
	for (let key in $filter){
		if ($filter[key] !== null)
			$filter_str += 'filter['+ key + ']=' + $filter[key] + '&';
	}

	$str += $search_str + $sort_str + $filter_str;

	location.href = $local + encodeURI($str);
});


/*
* Delete tag
*/
$('.seacrch_interest_cont, .search_interest_cont_filter, .search_interest_cont_sort').on('click', 'p.tag_sear_sort, p.tag_sear_fil', function(){
	$value = $(this).text().replace('#', '');

	$(this).remove();
});

/*
* Set distance and add + if distance equal 100
*/
function setDistance(inp){
	$parent = $(inp).parent();
	$parent.attr('data-lbound', inp.value);

	if (inp.value === '100' && $parent.hasClass('less_100'))
	{
		$parent.removeClass('less_100');
		$parent.addClass('more_100');
	}
	else if (inp.value !== '100' && $parent.hasClass('more_100'))
	{
		$parent.removeClass('more_100');
		$parent.addClass('less_100');
	}
}


/** ADD TAG IN SORT **/
function search_TagHelperSort($value){
	$hide = 0;
    $hash = $value.split('#');
    $hash[0] = null;

    $tag = $hash[$hash.length - 1];

    if ($hash.length - 1 > 1)
    {
        if ($('.search_resultTags') !== undefined)
            $('.search_resultTags').remove();

        search_sendTag($hash[1].replace(/<\/?[^>]+(>|$)/g, ""), $('.search_interest_cont_sort'), 'tag_sear_sort');
        $('.helperAbs').hide();
        $hide = 1;
        $('#search_interestsHelpSort').val('#');
    }
    
    $('.search_btn_inter_sort').click(function(){
    	$hash = $('#search_interestsHelpSort').val().split('#');
    	$hash[0] = null;
    	
    	if ($hash[1].replace(/<\/?[^>]+(>|$)/g, ""))
    	{
    		$hide = 1;
       		search_changeTagSort($hash[1].replace(/<\/?[^>]+(>|$)/g, ""));
    	}
	});

    if ($tag && $tag.length > 2)
    {
        sender.form('/searchTag', {'tag' : $tag}, function(request){
            if ($('.search_resultTags') !== undefined)
                    $('.search_resultTags').remove();

            if ($hide)
            	$('.helperAbs').hide();
            else if (request.similar.length)
            {
                $('.search_helperProfIntSort').show();
                for (let value of request.similar){
                    $('.search_helperProfIntSort').append('\
                        <p class="search_resultTags" onclick="search_changeTagSort(this.innerText)">'
                        + value.tag + '</p>')
                }
            }
            else
                $('.helperAbs').hide();
        });
    }
}

/** /ADD TAG IN SORT **/

/** ADD TAG IN FILTER **/

function search_tagHelperFilter($value){
	$hide = 0;
    $hash = $value.split('#');
    $hash[0] = null;

    $tag = $hash[$hash.length - 1];

    if ($hash.length - 1 > 1)
    {
        if ($('.search_resultTags') !== undefined)
            $('.search_resultTags').remove();

        search_sendTag($hash[1].replace(/<\/?[^>]+(>|$)/g, ""), $('.search_interest_cont_filter'), 'tag_sear_fil');
        $('.helperAbs').hide();
        $hide = 1;
        $('#search_interestsHelpFilter').val('#');
    }
    
    $('.search_btn_inter_filter').click(function(){
    	$hash = $('#search_interestsHelpFilter').val().split('#');
    	$hash[0] = null;
    	
    	if ($hash[1].replace(/<\/?[^>]+(>|$)/g, ""))
    	{
    		$hide = 1;
       		search_changeTagFilter($hash[1].replace(/<\/?[^>]+(>|$)/g, ""));
    	}
	});

    if ($tag && $tag.length > 2)
    {
        sender.form('/searchTag', {'tag' : $tag}, function(request){
            if ($('.search_resultTags') !== undefined)
                    $('.search_resultTags').remove();

            if ($hide)
            	$('.helperAbs').hide();
            else if (request.similar.length)
            {
                $('.search_helperProfIntFilter').show();
                for (let value of request.similar){
                    $('.search_helperProfIntFilter').append('\
                        <p class="search_resultTags" onclick="search_changeTagFilter(this.innerText)">'
                        + value.tag + '</p>')
                }
            }
            else
                $('.helperAbs').hide();
        });
    }
}

/** /ADD TAG IN FILTER **/
function search_changeTagSort(tag){
    search_sendTag(tag, $('.search_interest_cont_sort'), 'tag_sear_sort');

    $('.search_resultTags').remove();
    $('.helperAbs').hide();
    $('#search_interestsHelpSort').val('#');
}
function search_changeTagFilter(tag){
    search_sendTag(tag, $('.search_interest_cont_filter'), 'tag_sear_fil');

    $('.search_resultTags').remove();
    $('.helperAbs').hide();
    $('#search_interestsHelpFilter').val('#');
}
function search_sendTag(tag, $parent, tag_sear)
{
    let $search = '#' + tag;
    for (let $value of $('.' + tag_sear)){
        if ($value.innerHTML === $search)
            return;
    }

    if (tag !== '' && tag !== '#')
    {
    	$parent.prepend(
    	 	'<p class="'+ tag_sear +'">#' + tag + '</p>');
    }
}

/*
* Hide tag helper
*/
$(window).on('click', function(event){
    let $helper = $('.helperProfInt');
	let $helper1 = $('.helperProfIntFilter');
    if ($helper !== undefined &&
        $helper.attr('style') !== 'display: none;' &&
        event.target !== $helper[0])
        $helper.hide();
    else if ($helper1 !== undefined &&
        $helper1.attr('style') !== 'display: none;' &&
        event.target !== $helper1[0])
        $helper1.hide();
});

function checkSearchProperty(){
	$input_val = $('#search_input').val();

	return (($input_val.indexOf('#') == -1) ? 'login' : 'tags');
}
function getSearchProperty(){
	$input_val = $('#search_input').val().replace(/<\/?[^>]+(>|$)/g, "");

	if ($input_val.indexOf('#') != -1)
	{
		$val = $input_val.split('#');
		delete $val[0];

		$str = "";
		for (let i in $val){
			if ($val[i] !== undefined && $val[i] != "")
				$str += $val[i] + ',';
		}
		return (($str === "") ? null : $str);
	}

	return (($input_val === "") ? null : $input_val);
}


function getSearchSortPriority(){
	$priority = {
		age: ($('.search_sort_age').val() === 'false') ? false : true,
		distance: ($('.search_sort_location').val() === 'false') ? false : true,
		rating: ($('.search_sort_rating').val() === 'false') ? false : true,
		same_tags: ($('.search_sort_same_tags').val() === 'false') ? false : true,
	}

	for (let i in $priority){
		if ($priority[i])
			return (i);
	}

	return (null);
}

function getSearchInterests($interestsText){
	if ($interestsText === "")
		return (null);

	return ($interestsText.split('#').splice(1));
}

function getSearchAge($min, $max){
	if ($min === '10' &&
		$max === '60')
		return (null);
	else
		return ($min + '-' + $max);
}

function getSearchDistance($distance){
	return ($distance === '0' ? null : $distance);
}

function getSearchRating($rating){
	return ($rating === '0' ? null : $rating);
}

function getSearchSortedBy($name){
	if ($name === "")
		return (null);

	return ($name);
}
