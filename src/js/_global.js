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