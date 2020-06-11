function GlobalSetCookie(cname, cvalue, exdays) {
	"use strict";
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}



// Hero Scroll Arrow
$(".scroll-arrow").click(function() {
	$("html, body").animate({
	scrollTop: $("#section-2").offset().top - 62
	}, 650);
});


// Give header blue backround if the first section is light (or if there are no sections at all)
$('.global-main > .section--1.theme--secondary').parent().prev().addClass('theme--primary');
$('.global-main > .section--1.theme--alternative').parent().prev().addClass('theme--primary');
if ( $('.global-main').children().length === 0 ) {
	$('.header').addClass('theme--primary');
}


// Give the first linked copy-block an active state to show it's clickable
$('.copy-block:not(.no-hover):first-child').addClass('active');
$('.copy-block').hover(function() {
	$(this).siblings().removeClass('active');
	$(this).removeClass('active');
});



// Give hero section a height that fills the full height of the viewport in px (vh doesn't play well with address bars that hide/show as you scroll on mobile)
if(window.innerWidth < 768) {
	$('.global-main>.section--1.section--copy-image>.container').css('height', (window.innerHeight - 30)+"px");
}

// Sticky Header
if($('.country-selector').length > 0) {
	var barHeight = $('.country-selector').outerHeight();
	$('.header').css('top', barHeight);
}

$(document).scroll(function () {
	if($('.country-selector').length > 0) {
		var barHeight = $('.country-selector').outerHeight();
		$('.header').css('top', barHeight);
			if ($(this).scrollTop() > barHeight) {
				$('.header').addClass('header--sticky-0').addClass('header--sticky');
			} else {
				$('.header').removeClass('header--sticky-0').removeClass('header--sticky');
			}
	} else {
		if ($(this).scrollTop() > 100) {
			$('.header').addClass('header--sticky');
		} else {
			$('.header').removeClass('header--sticky');
		}
	}
});
//https://css-tricks.com/snippets/jquery/simple-jquery-accordion/
(function ($) {

	var allPanels = $('.accordion > dd').hide();

	$('.accordion > dt > a').click(function () {
		allPanels.slideUp();
		$(this).parent().next().slideDown();
		return false;
	});

})(jQuery);

// --------------------------------------------------------------------------------------------------
// Trigger Animations when in viewport
// --------------------------------------------------------------------------------------------------

;(function($, win) {
	$.fn.inViewport = function(cb) {
		return this.each(function(i,el) {
			function visPx(){
			var elH = $(el).outerHeight(),
				H = $(win).height(),
				r = el.getBoundingClientRect(), t=r.top, b=r.bottom;
			return cb.call(el, Math.max(0, t>0? Math.min(elH, H-t) : Math.min(b, H)));  
			}
			visPx();
			$(win).on("resize scroll", visPx);
		});
	};
}(jQuery, window));


$("[data-animate]").inViewport(function(px) {
	//console.log( px ); // `px` represents the amount of visible height
    var anim = $(this).data('animate');
    
    var elHeight = $(this).height();

    var thirtyPercent = (elHeight/100)*30;

	if(px > thirtyPercent) {
        $(this).css('opacity','1');
		$(this).addClass(anim);
	}
});
// Category Filters
$('.blog-filters__input').change(function(){

	if ($(this).is(':checked')) {
		$(this).parent().addClass('active');
		$(this).parent().siblings().removeClass('active');
	}

	$('.blog-load-more').hide();

	var filter = $('.blog-filters');
	$.ajax({
		url:filter.attr('action'),
		data:filter.serialize(), // form data
		type:filter.attr('method'), // POST
		beforeSend:function(xhr){
			$('.blog-filters').addClass('active');
			$('.post-list').css('opacity','0');
			$('.post-list').css('transform','translateY(100px)');
		},
		success:function(data){
			$('.post-list').html(data);
			$('.post-list').css('opacity','1');
			$('.post-list').css('transform','translateY(0)');
			$('.blog-filters').removeClass('active');
		}
	});
	return false;
});


// Load more
$('.blog-load-more').click(function(){

	var button = $(this),
		data = {
		'action': 'loadmore',
		'query': posts_loadmore,
		'page' : current_page_loadmore
	};

	$.ajax({
		url : '/wp-admin/admin-ajax.php',
		data : data,
		type : 'POST',
		beforeSend : function ( xhr ) {
			button.text('Loading...');
		},
		success : function( data ){
			if( data ) { 
				button.text( 'View more' ).parent().prev().find('.post-list').append(data);
				current_page_loadmore++;

				if ( current_page_loadmore == max_page_loadmore ) 
					button.remove();
			} else {
				button.remove();
			}
		}
	});
});
// --------------------------------------------------------------------------------------------------
// Country Selector
// --------------------------------------------------------------------------------------------------

$('.country-selector .continue').click(function() {
  var dest = $(this).prev('select').val();
  window.location.assign(dest);
});

if($('.country-selector').length > 0) {
  var origin = window.location.origin;
  var path = window.location.pathname;
  var path = path.replace('/us', '');
  $('.country-selector .us').val(origin+"/us"+path+"?noredirect=true");
  $('.country-selector .uk').val(origin+path+"?noredirect=true");
}

$('.country-selector__close').click(function() {
  $('.country-selector').remove();
  $('.header').addClass('header--sticky-0');
  $(document).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('.header').addClass('header--sticky-0').addClass('header--sticky');
		}
  });
  setCookie('lang','yes',7);
});

function setCookie(name,value,days) {
  var expires = "";
  if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days*24*60*60*1000));
      expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
// --------------------------------------------------------------------------------------------------
// Drag to Scroll
// --------------------------------------------------------------------------------------------------

const slider = document.querySelector('.drag-scroll');
let isDown = false;
let startX;
let scrollLeft;

if(slider) {
  slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('drag-active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });
  slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('drag-active');
  });
  slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('drag-active');
  });
  slider.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1; //scroll-fast
    slider.scrollLeft = scrollLeft - walk;
    //console.log(walk);
  });
}
/*
	@author Rob Watson
	@name GDPR Setup

	GDPR
	----
	setting up the GDPR vars
*/
var $euCookieBar,
	$euCookieBarActive,
	$btnDeclineEuCookieBar,
	btnAcceptEuCookieBar,
	COOKIE_DECLINE,
	COOKIE_DECLINE_VALUE,
	COOKIE_ACCEPT,
	COOKIE_ACCEPT_VALUE,
	CLASS_ACTIVE,
	DEBUG_GDPR;

$euCookieBar = $('.section--gdpr');
$euCookieBarActive = $('.section--gdpr--active');
$btnDeclineEuCookieBar = $('.section--gdpr .btn--decline');
$btnAcceptEuCookieBar = $('.section--gdpr .btn--accept');
COOKIE_EXPIRE_DAYS = 1;
COOKIE_DECLINE = 'mf_eu_cookie_decline';
COOKIE_DECLINE_VALUE = 'declined';
COOKIE_ACCEPT = 'mf_eu_cookie_accept';
COOKIE_ACCEPT_VALUE = 'accepted';
CLASS_ACTIVE = 'section--gdpr--active';
DEBUG_GDPR = false;


/*
	- window.load()
	-- We need to check here if the user had previously accepted or declined the cookie.

	---	Check: Decline
			--------------
			If user has previoously declined the cookie we update it to expire in 24 hours

	---	Check: Accept
			--------------
			If user has previoously declined the cookie we update it to expire in 24 hours

	---	Default Action
			--------------
			If user has previoously neither declined or accepted, or the cookie has expired we add the acive class (CLASS_ACTIVE) to display the GDPR message

*/
$(window).load(function () {
	/* Check if decline cookie exists  */
	if (Cookies.get(COOKIE_DECLINE) === COOKIE_DECLINE_VALUE) {

		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD - USER PREVIOUSLY DECLINED');
		}
		/*  set decline cookie to declined */
		Cookies.set(COOKIE_DECLINE, COOKIE_DECLINE_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD (CHECK) - DECLINE COOKIE IS SET TO: DECLINED');
		}

		/* Set accept cookie to declined */
		Cookies.set(COOKIE_ACCEPT, COOKIE_DECLINE_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD (CHECK) - ACCEPT COOKIE IS SET TO: DECLINED');
		}

	} else if (Cookies.get(COOKIE_ACCEPT) === COOKIE_ACCEPT_VALUE) {

		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD - USER PREVIOUSLY ACCEPTED');
		}
		/* if accept cookie exists, set it to accepted */
		Cookies.set(COOKIE_ACCEPT, COOKIE_ACCEPT_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD (CHECK) - ACCEPT COOKIE IS SET TO: ACCEPTED');
		}
		/* set decline cookie to accepted */

		Cookies.set(COOKIE_DECLINE, COOKIE_ACCEPT_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD (CHECK) - DECLINE COOKIE IS SET TO: ACCEPTED');
		}

	} else {

		if (DEBUG_GDPR === true) {
			console.log('GDPR: PAGE LOAD (CHECK) - NEW USER OR NO COOKIES ARE SET');
		}
		/* Neither cookie exisit so show GDPR alert */
		$($euCookieBar).fadeIn('slow').addClass(CLASS_ACTIVE).attr('role', 'alert');

	}
});
/*
	- document.ready()
	-- Here we set the cookies when the user clicks either accept or decline

	---	Event: Click: Decline
			--------------------
			If user clicks declined we set the cookie COOKIE_DECLINE to COOKIE_DECLINE_VALUE with an expiry time of 24 hours

	---	Event: Click: Accept
			--------------------
			If user clicks accept we set the cookie COOKIE_ACTIVE to COOKIE_ACTIVE_VALUE with an expiry time of 24 hours

*/
$(document).ready(function () {

	$($btnDeclineEuCookieBar).on('click', function () {

		$($euCookieBar).fadeOut('slow').removeClass(CLASS_ACTIVE).removeAttr('role');

		Cookies.set(COOKIE_DECLINE, COOKIE_DECLINE_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: CLICK - DECLINE COOKIE IS SET TO DECLINED');
		}

		Cookies.set(COOKIE_ACCEPT, COOKIE_DECLINE_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: CLICK - ACCEPT COOKIE IS SET TO DECLINED');
		}

	});

	$($btnAcceptEuCookieBar).on('click', function () {

		$($euCookieBar).fadeOut('slow').removeClass(CLASS_ACTIVE).removeAttr('role');

		Cookies.set(COOKIE_ACCEPT, COOKIE_ACCEPT_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: CLICK - ACCEPT COOKIE IS SET TO ACCEPT');
		}

		Cookies.set(COOKIE_DECLINE, COOKIE_ACCEPT_VALUE, {
			expires: COOKIE_EXPIRE_DAYS
		});
		if (DEBUG_GDPR === true) {
			console.log('GDPR: CLICK - DECLINE COOKIE IS SET TO ACCEPT');
		}

	});

});

(function ($) {
	function new_map($el) {
		var $markers = $el.find('.marker');
		var args = {
			zoom: 16,
			center: new google.maps.LatLng(0, 0),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map($el[0], args);
		map.markers = [];
		$markers.each(function () {
			add_marker($(this), map);
		});
		center_map(map);
		return map;
	}

	function add_marker($marker, map) {
		var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));
		var marker = new google.maps.Marker({
			position: latlng,
			map: map
		});
		map.markers.push(marker);
		if ($marker.html()) {
			var infowindow = new google.maps.InfoWindow({
				content: $marker.html()
			});
			google.maps.event.addListener(marker, 'click', function () {
				infowindow.open(map, marker);
			});
		}
	}
	function center_map(map) {
		var bounds = new google.maps.LatLngBounds();
		$.each(map.markers, function (i, marker) {
			var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
			bounds.extend(latlng);
		});
		if (map.markers.length == 1) {
			map.setCenter(bounds.getCenter());
			map.setZoom(16);
		} else {
			map.fitBounds(bounds);
		}
	}
	var map = null;
	$(document).ready(function () {
		$('.container--map').each(function () {
			map = new_map($(this));
		});
	});
})(jQuery);

// --------------------------------------------------------------------------------------------------
// Carousel Horizontal Scroll Buttons (e.g. on childs module)
// --------------------------------------------------------------------------------------------------

var child = $(".carousel > *");
  var cont = $(".carousel"),
    x;

// Auto scroll
var scrollInt = setInterval(timer, 2000);

function timer() {
  x = ((child.width() + 40)) + cont.scrollLeft();
  cont.animate({
    scrollLeft: x,
  });
  if(x > 300) {
    $('.carousel__arrow--left').css('opacity', '1');
  } else {
    $('.carousel__arrow--left').css('opacity', '0.5');
  }
}


$(".carousel__arrow").click(function() {

  window.clearInterval(scrollInt);

  var child = $(".carousel > *");
  var cont = $(".carousel"),
    x;
  if ($(this).hasClass("carousel__arrow--right")) {
    x = ((child.width() + 40)) + cont.scrollLeft();
    cont.animate({
      scrollLeft: x,
    });
  } else {
    x = ((child.width() + 40)) - cont.scrollLeft();
    cont.animate({
      scrollLeft: -x,
    });
  }

  if(x > 300) {
    $('.carousel__arrow--left').css('opacity', '1');
  } else {
    $('.carousel__arrow--left').css('opacity', '0.5');
  }
});
// --------------------------------------------------------------------------------------------------
// Mobile Navigation
// @author Stephen Greig
// --------------------------------------------------------------------------------------------------
//SVG Icons
var iconClose = "<svg class='icon icon-times'><use xlink:href='"+themeURL+"/assets/img/icons-sprite.svg#icon-times'></use></svg>";
var iconAngleUp = "<svg class='icon icon-angle-up'><use xlink:href='"+themeURL+"/assets/img/icons-sprite.svg#icon-angle-up'></use></svg>";
var iconAngleDown = "<svg class='icon icon-angle-down'><use xlink:href='"+themeURL+"/assets/img/icons-sprite.svg#icon-angle-down'></use></svg>";
        
// Copy primary and secondary menus to .mob-nav element
var mobNav = document.querySelector('.mob-nav .scroll-container');

if($('.desktop-nav .main-menu').length > 0) {
    var copyPrimaryMenu = document.querySelector('.desktop-nav .main-menu').cloneNode(true);
    mobNav.appendChild(copyPrimaryMenu);
}

// Add Close Icon element
$( "<div class='mob-nav-close'>"+iconClose+"</div>" ).insertAfter( ".mob-nav .scroll-container" );

// Add dropdown arrow to links with sub-menus
$( "<span class='sub-arrow'>"+iconAngleDown+iconAngleUp+"</span>" ).insertBefore(".mob-nav .sub-menu");

// Show sub-menu when dropdown arrow is clicked
$('.sub-arrow').click(function() {
    $(this).toggleClass('active');
    $(this).prev('a').toggleClass('active');
    $(this).next('.sub-menu').slideToggle();
    $(this).children().toggle();
});

// Add underlay element after mobile nav
$( "<div class='mob-nav-underlay'></div>" ).insertAfter( ".mob-nav" );

// Show underlay and fix the body scroll when menu button is clicked
$('.mob-nav-toggle').click(function() {
    $('.mob-nav,.mob-nav-underlay').addClass('active');
    $('body').addClass('fixed');
});

// Hide menu when close icon or underlay is clicked
$('.mob-nav-underlay,.mob-nav-close').click(function() {
    $('.mob-nav,.mob-nav-underlay').removeClass('active');
    $('body').removeClass('fixed');
});
// --------------------------------------------------------------------------------------------------
// Scroll to Section Menu
// --------------------------------------------------------------------------------------------------

var navItemsCount = $('.scroll-menu__list-item').length;

for (var i=0; i<=navItemsCount; i++) {
    (function(i){
        $('.scroll-menu__list-item:nth-child('+i+') a').click(function() {

            event.preventDefault();

            var link = $(this).attr('href');

            $("html, body").animate({
                scrollTop: $(link).offset().top - 62
            }, 650);

        });
    })(i);
}
// --------------------------------------------------------------------------------------------------
// Tabs
// --------------------------------------------------------------------------------------------------

$('.tabs-nav li:first-child, .tabs-panel:first-child').addClass('active');

$('.tabs .active ~ .active').prevAll('.active').removeClass('active');

var tabsCount = $('.tabs-nav li').length;

for (var i=0; i<=tabsCount; i++) {
    (function(i){
        $('.tabs-nav li:nth-child('+i+')').click(function() {
            $(this).parents('.tabs').find('.tabs-panel').removeClass('active');
            $(this).parents('.tabs').find('.tabs-nav li').removeClass('active');
            $(this).addClass('active');
            $(this).parents('.tabs').find('.tabs-nav li:nth-child('+i+')').addClass('active');
            $(this).parents('.tabs').find('.tabs-panel:nth-child('+i+')').addClass('active');
            clearInterval(interval);
            //timer();
        });
    })(i);
}

// Auto rotate functionality
var interval;
var timer = function(){
    interval = setInterval(function(){
        var active = $(".tabs-auto-rotate .active").removeClass('active');
        if(active.next() && active.next().length){ active .next().addClass('active'); } else{ active.siblings(":first-child").addClass('active'); }
    },6000);
};

timer();