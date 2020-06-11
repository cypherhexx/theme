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