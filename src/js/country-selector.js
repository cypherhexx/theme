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