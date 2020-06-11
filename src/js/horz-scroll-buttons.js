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