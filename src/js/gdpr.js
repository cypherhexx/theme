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
