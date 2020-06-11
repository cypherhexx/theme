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